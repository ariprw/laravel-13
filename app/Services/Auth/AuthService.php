<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Audit\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private ActivityLogService $activityLogService,
        private PasswordService $passwordService,
    ) {}

    public function register(array $data): User
    {
        $fields = ['name', 'email'];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->passwordService->hash($data['password']),
        ]);

        $user->sendEmailVerificationNotification();

        $this->activityLogService->record(
            description: 'Register user',
            subject: $user,
            properties: [
                'new' => $user->only($fields),
            ],
        );

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            report($e);
        }

        return $user;
    }

    public function resendVerification(array $data): void
    {
        $fields = ['name', 'email', 'email_verified_at', 'status'];

        $user = User::where('email', $data['email'])->first();

        if (!$user || $user->hasVerifiedEmail()) {
            return;
        }

        $user->sendEmailVerificationNotification();

        $this->activityLogService->record(
            description: 'Resend verification email',
            subject: $user,
            properties: [
                'old' => $user->only($fields),
            ],
        );
    }

    public function verify(string $id, string $hash): void
    {
        $fields = ['name', 'email', 'email_verified_at'];

        $user = User::find($id);

        if (! $user) {
            abort(404, 'User tidak ditemukan.');
        }

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Link verifikasi tidak valid.');
        }

        if (! $user->hasVerifiedEmail()) {
            $old = $user->only($fields);

            $user->markEmailAsVerified();

            $this->activityLogService->record(
                description: 'Verify email user',
                subject: $user,
                properties: [
                    'old' => $old,
                    'new' => $user->only($fields),
                ],
            );
        }
    }

    public function forgot(array $data): ?User
    {
        $fields = ['name', 'email', 'email_verified_at', 'status'];

        $user = User::where('email', $data['email'])
        ->whereNotNull('email_verified_at')
        ->where('status', 'aktif')
        ->first();

        if (! $user) {
            return null;
        }

        $token = Password::broker()->createToken($user);

        $user->sendForgotPasswordNotification($token);

        $this->activityLogService->record(
            description: 'Forgot password user',
            subject: $user,
            properties: [
                'old' => $user->only($fields),
            ],
        );

        return $user;
    }

    public function reset(array $data): ?User
    {
        $fields = ['name', 'email', 'email_verified_at', 'status'];

        $resetUser = null;

        $status = Password::broker()->reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $data['token'],
            ],
            function (User $user, string $password) use (&$resetUser) {
                $user->forceFill([
                    'password' => $this->passwordService->hash($password),
                    'remember_token' => Str::random(60),
                ])->save();

                $resetUser = $user;
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        $this->activityLogService->record(
            description: 'Password reset via forgot password',
            subject: $resetUser,
            properties: [
                'old' => $resetUser->only($fields),
            ],
        );

        return $resetUser;
    }

    public function login(array $data): ?User
    {
        $fields = ['name', 'email', 'status'];

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return null;
        }

        if ($user->email_verified_at === null) {
            return null;
        }

        if ($user->status !== 'aktif') {
            return null;
        }

        if (! $this->passwordService->check($data['password'], $user->password)) {
            return null;
        }

        $this->activityLogService->record(
            description: 'Login success',
            subject: $user,
            properties: [
                'old' => $user->only($fields),
            ],
        );

        return $user;
    }

    public function logout(Request $request): void
    {
        $user = Auth::user();

        if ($user) {
            $fields = ['name', 'email', 'status'];

            $this->activityLogService->record(
                description: 'Logout success',
                subject: $user,
                properties: [
                    'old' => $user->only($fields),
                ],
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
