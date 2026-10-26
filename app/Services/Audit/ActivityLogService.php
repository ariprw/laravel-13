<?php

namespace App\Services\Audit;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityLogService
{
    public function paginate(): LengthAwarePaginator
    {
        return ActivityLog::query()
        ->filter(request(['search']))
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
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'log_name' => $logName ?? $subject?->getTable(),
            'description' => $description,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
        ]);
    }
}
