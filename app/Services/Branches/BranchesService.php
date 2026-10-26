<?php

namespace App\Services\Branches;

use App\Models\Branches;
use App\Services\Audit\ActivityLogService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BranchesService
{
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
        $fields = ['name', 'is_active'];

        $branches = Branches::create([
            'name' => $data['name'],
            'is_active' => $data['status'],
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
        $fields = ['name', 'is_active'];

        $old = $branches->only($fields);

        $branches->update([
            'name' => $data['name'],
            'is_active' => $data['status'],
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
        $fields = ['name', 'is_active'];

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
}