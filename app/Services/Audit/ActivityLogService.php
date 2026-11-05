<?php

namespace App\Services\Audit;

use App\Models\ActivityLog;
use App\Enums\User\Role;
use App\Enums\Export\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public string $title = 'Log Aktivitas';

    public function paginate(): LengthAwarePaginator
    {
        $auth = auth()->user();
        $query = ActivityLog::query();

        if ($auth->role !== Role::ADMIN->value) {
            $query->where(function ($query) use ($auth) {
                $query->where('user_id', $auth->id);
            });
        }

        return $query->filter(request(['search']))
        ->with(ActivityLog::getWith())
        ->latest()
        ->paginate(20)
        ->withQueryString();
    }

    public function record(
        string $description,
        ?string $logName = null,
        ?Model $subject = null,
        array $properties = [],
    ): ActivityLog {
        $properties['activity'] = array_merge(
            $properties['activity'] ?? [],
            [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
        );
        
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'log_name' => $logName ?? $subject?->getTable(),
            'description' => $description,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
        ]);
    }

    public function exportQuery(array $filters = [], $user = null): Builder
    {
        $query = ActivityLog::query()->with(ActivityLog::getWith());

        if ($user->role !== Role::ADMIN->value) {
            $query->where('user_id', $user->id);
        }

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

    public function mapExport(ActivityLog $data): array
    {
        return [
            'ID' => $data->id,
            'ID User' => $data->user_id,
            'Nama' => $data->user?->name ?? $data->user_id,
            'Cabang' => $data->user?->branches?->name ?? '',
            'Nama Log' => $data->log_name,
            'Deskripsi' => $data->description,
            'ID Objek' => $data->subject_id,
            'Tipe Objek' => class_basename($data->subject_type),
            'Tanggal Dibuat' => $data->created_at?->format('Y-m-d H:i:s'),
            'Diperbarui' => $data->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}