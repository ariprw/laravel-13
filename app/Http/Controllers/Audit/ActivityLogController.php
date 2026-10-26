<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\Audit\ActivityLogService;
use Illuminate\Support\Str;

class ActivityLogController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
        $this->title = 'Log Aktivitas';
    }

    public function index()
    {
        $this->authorize('viewAny', ActivityLog::class);

        $activityLog = $this->activityLogService->paginate();
        $title = $this->title;

        if (request()->hasAny(['search'])) {
            $title = 'Pencarian '.$this->title.' : '.Str::limit(request('search', ''), 50);
        }

        return view('activityLog.index', [
            'title' => $title,
            'activityLog' => $activityLog,
        ]);
    }

    public function show(ActivityLog $activityLog)
    {
        $this->authorize('view', $activityLog);

        $activityLog->load(ActivityLog::getWith());

        return view('activityLog.show', [
            'title' => 'Detail '.$this->title,
            'activityLog' => $activityLog,
        ]);
    }
}