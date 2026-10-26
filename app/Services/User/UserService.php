<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\Audit\ActivityLogService;
use App\Services\Auth\PasswordService;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        private ActivityLogService $activityLogService,
        private PasswordService $passwordService,
    ) {}
    
    public function paginate(): LengthAwarePaginator
    {
        return User::query()
        ->filter(request(['search', 'status', 'role']))
        ->with(User::getWith())
        ->latest()
        ->paginate(20)
        ->withQueryString();
    }

    public function update(User $user, array $data): User
    {
        $fields = ['name', 'status', 'role', 'branch_id'];

        $old = $user->only($fields);

        $user->update([
            'name' => $data['name'],
            'status' => $data['status'],
            'role' => $data['role'],
            'branch_id' => $data['branch'],
        ]);

        $user->refresh();

        $this->activityLogService->record(
            description: 'Updated user',
            subject: $user,
            properties: [
                'old' => $old,
                'new' => $user->only($fields),
            ],
        );

        return $user;
    }

    public function profileUpdate(User $user, array $data): User
    {
        $fields = ['name'];

        $old = $user->only($fields);

        $user->update([
            'name' => $data['name'],
        ]);

        $user->refresh();

        $this->activityLogService->record(
            description: 'Updated profile user',
            subject: $user,
            properties: [
                'old' => $old,
                'new' => $user->only($fields),
            ],
        );

        return $user;
    }

    public function passwordUpdate(User $user, array $data): User
    {
        $user->password = $this->passwordService->hash(
            $data['password']
        );

        $user->remember_token = Str::random(60);

        $user->save();
        $user->refresh();

        $this->activityLogService->record(
            description: 'Change password user',
            subject: $user,
        );

        return $user;
    }

    public function delete(User $user): void
    {
        $fields = ['name', 'email', 'email_verified_at', 'status', 'role', 'branch_id', 'created_at', 'updated_at'];

        $old = $user->only($fields);
        
        $this->activityLogService->record(
            description: 'Deleted user',
            subject: $user,
            properties: [
                'old' => $old,
            ],
        );

        $user->delete();
    }
}