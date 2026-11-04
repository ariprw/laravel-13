<?php

namespace App\Services\Branches;

use App\Models\Branches;
use App\Enums\Branches\Status;
use App\Enums\Export\Category;
use App\Services\Audit\ActivityLogService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class BranchesService
{
    public string $title = 'Cabang';

    public function __construct(
        private ActivityLogService $activityLogService,
    ) {}

    public function paginate(): LengthAwarePaginator
    {
        return Branches::query()
        ->filter(request(['search', 'status']))
        ->withCount('users')
        ->latest()
        ->paginate(20)
        ->withQueryString();
    }

    public function create(array $data): Branches
    {
        $fields = ['name', 'phone', 'email', 'address', 'maps', 'status', 'logo'];

        $branches = Branches::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
            'maps' => $data['maps'],
            'status' => $data['status'],
            'logo' => $data['logo'],
        ]);

        $this->activityLogService->record(
            description: 'Created branch',
            subject: $branches,
            properties: [
                'new' => $branches->only($fields),
            ],
        );

        return $branches;
    }

    public function update(Branches $branches, array $data): Branches
    {
        $fields = ['name', 'phone', 'email', 'address', 'maps', 'status', 'logo'];

        $old = $branches->only($fields);

        $branches->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
            'maps' => $data['maps'],
            'status' => $data['status'],
            'logo' => $data['logo'],
        ]);

        $branches->refresh();

        $this->activityLogService->record(
            description: 'Updated branch',
            subject: $branches,
            properties: [
                'old' => $old,
                'new' => $branches->only($fields),
            ],
        );

        return $branches;
    }

    public function delete(Branches $branches): void
    {
        $fields = ['name', 'phone', 'email', 'address', 'maps', 'status', 'logo'];

        $old = $branches->only($fields);
        
        $this->activityLogService->record(
            description: 'Deleted branch',
            subject: $branches,
            properties: [
                'old' => $old,
            ],
        );

        $branches->delete();
    }

    public function exportQuery(array $filters = [], $user = null): Builder
    {
        $query = Branches::query()
        ->withTrashed()
        ->with(['users']);

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

    public function mapExport(Branches $data): array
    {
        return [
            'ID' => $data->id,
            'Nama Cabang' => $data->name,
            'Ponsel' => $data->phone,
            'Email' => $data->email,
            'Alamat' => $data->address,
            'Maps' => $data->maps,
            'Logo' => asset($data->logo),
            'Status' => $data->status,
            'Tanggal Dibuat' => $data->created_at?->format('Y-m-d H:i:s'),
            'Diperbarui' => $data->updated_at?->format('Y-m-d H:i:s'),
            'Dihapus' => $data->deleted_at?->format('Y-m-d H:i:s'),

            'Users' => $data->users->map(function ($user) {
                return [
                    'ID User' => $user->id,
                    'Nama' => $user->name,
                    'Email' => $user->email,
                ];
            })->values()->all(),
        ];
    }
}