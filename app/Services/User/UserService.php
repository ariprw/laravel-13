<?php

namespace App\Services\User;

use App\Models\User;
use App\Enums\Export\Category;
use App\Services\Audit\ActivityLogService;
use App\Services\Auth\PasswordService;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class UserService
{
    public string $title = 'User';

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
        $fields = ['name', 'gender', 'status', 'role', 'branch_id'];

        $old = $user->only($fields);

        $user->update([
            'name' => $data['name'],
            'gender' => $data['gender'],
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
        $fields = ['name', 'gender'];

        $old = $user->only($fields);

        $user->update([
            'name' => $data['name'],
            'gender' => $data['gender'],
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

    public function exportQuery(array $filters = [], $user = null): Builder
    {
        $query = User::query()->with(User::getWith());

        if (
            ($filters['category'] ?? null) === Category::CREATED_AT->value &&
            !empty($filters['startDate']) &&
            !empty($filters['endDate'])
        ) {
            $query->whereDate('created_at', '>=', $filters['startDate'])
            ->whereDate('created_at', '<=', $filters['endDate']);
        }

        return $query->orderBy('created_at', 'ASC')
        ->orderBy('id', 'ASC');
    }

    public function mapExport(User $data): array
    {
        return [
            'ID' => $data->id,
            'Nama' => $data->name,
            'Email' => $data->email,
            'Email Diverifikasi' => ($data->email_verified_at) ? $data->email_verified_at->format('d F Y H:i:s') : 'Belum Terverifikasi',
            'Jenis Kelamin' => $data->gender,
            'Status' => $data->status,
            'Role' => $data->role,
            'Cabang' => $data->branches?->name ?? $data->branch_id,
            'Tanggal Dibuat' => $data->created_at?->format('Y-m-d H:i:s'),
            'Diperbarui' => $data->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}