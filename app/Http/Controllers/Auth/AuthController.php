<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendVerificationRequest;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\ResetRequest;
use App\Models\Auth;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function register()
    {
        $this->authorize('register', Auth::class);

        return view('auth.register', [
            'title' => 'Buat Akun'
        ]);
    }

    public function registerStore(RegisterRequest $request)
    {
        $this->authorize('register', Auth::class);

        $this->authService->register(
            $request->validated()
        );

        return redirect()->route('login')->with('status', 'Pendaftaran telah berhasil');
    }

    public function verify(Request $request, string $id, string $hash)
    {
        $this->authorize('verify', Auth::class);

        if (! $request->hasValidSignature())
        {
            abort(403, 'Link verifikasi tidak valid atau sudah kedaluwarsa.');
        }

        $this->authService->verify($id, $hash);

        return redirect()->route('verify.success');
    }

    public function verifySuccess()
    {
        $this->authorize('verify', Auth::class);

        return view('auth.verifySuccess', [
            'title' => 'Verifikasi Berhasil',
            'status' => 'Email telah terverifikasi. Mohon tunggu verifikasi lanjutan dari admin.',
        ]);
    }

    public function resendVerification()
    {
        $this->authorize('resend', Auth::class);

        return view('auth.resend', [
            'title' => 'Verifikasi Email'
        ]);
    }

    public function resendVerificationStore(ResendVerificationRequest $request)
    {
        $this->authorize('resend', Auth::class);

        $this->authService->resendVerification(
            $request->validated()
        );

        return redirect()->route('login')->with('status', 'Jika akun tersedia dan belum terverifikasi, email verifikasi akan dikirim');
    }

    public function forgot()
    {
        $this->authorize('forgot', Auth::class);

        return view('auth.forgot', [
            'title' => 'Reset Password'
        ]);
    }

    public function forgotStore(ForgotRequest $request)
    {
        $this->authorize('forgot', Auth::class);

        $this->authService->forgot(
            $request->validated()
        );

        return redirect()->route('login')->with('status', 'Link reset password telah terkirim');
    }

    public function reset(string $token, string $email)
    {
        $this->authorize('reset', Auth::class);

        return view('auth.reset', [
            'title' => 'Reset Password',
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetStore(ResetRequest $request)
    {
        $this->authorize('reset', Auth::class);

        $this->authService->reset(
            $request->validated()
        );

        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login kembali.');
    }

    public function login()
    {
        $this->authorize('login', Auth::class);

        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function loginStore(LoginRequest $request)
    {
        $this->authorize('login', Auth::class);

        $user = $this->authService->login(
            $request->validated(),
        );

        if (! $user) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->withInput($request->only('email'));
        }

        auth()->login($user);

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return redirect()->route('login');
    }
}
