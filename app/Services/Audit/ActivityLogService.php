<?php

namespace App\Services\Audit;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
}
