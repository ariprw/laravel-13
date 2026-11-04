<?php

namespace App\Http\Controllers\Export;

use App\Enums\Export\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Export\CreateRequest;
use App\Http\Requests\Export\BatchRequest;
use App\Services\Export\ExportService;
use App\Services\Export\RegistryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function __construct(
        protected ExportService $exportService,
        protected RegistryService $registryService,
    ) {
    }

    public function index()
    {
        $fitur = request()->segment(1);

        abort_unless($this->registryService->has($fitur), 404);

        $module = $this->registryService->get($fitur);

        $this->authorize($module['ability'], $module['model']);

        return view('export.index', [
            'title' => 'Export '.$module['title'],
            'link' => url($fitur),
            'category' => Category::options(),
            'module' => idToken($fitur),
        ]);
    }

    public function store(CreateRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $key = $this->registryService->resolveToken($validated['module']);
        $module = $this->registryService->get($key);

        $this->authorize($module['ability'], $module['model']);

        $filters = collect($validated)
        ->only(['category', 'startDate', 'endDate'])
        ->toArray();

        $service = app($module['service']);

        $query = $service->exportQuery($filters, $user);
        $total = (clone $query)->count();

        $job = $this->exportService->createJob(
            $user->getAuthIdentifier(),
            $key,
            $total,
            $filters,
        );

        return response()->json([
            'message' => 'Export siap diproses.',
            'data' => $job,
        ]);
    }

    public function batch(BatchRequest $request, string $requestId)
    {
        $validated = $request->validated();
        $user = $request->user();

        $job = $this->exportService->getJob(
            $requestId,
            $user->getAuthIdentifier(),
        );

        $module = $this->registryService->get($job['module']);

        $this->authorize($module['ability'], $module['model']);

        $start = (int) $validated['start'];
        $take = $this->exportService->normalizeTake(
            $validated['take'] ?? 500
        );

        if ($start >= $job['total']) {
            return response()->json([
                'requestId' => $requestId,
                'start' => $start,
                'take' => 0,
                'total' => $job['total'],
                'done' => true,
                'nextStart' => null,
                'downloadUrl' => null,
            ]);
        }

        $service = app($module['service']);

        $rows = $service->exportQuery($job['filters'] ?? [], $user)
                ->offset($start)
                ->limit($take)
                ->get()
                ->map(fn ($item) => $service->mapExport($item))
                ->values()
                ->all();

        if ($rows === []) {
            abort(409, 'Data berubah selama proses export. Silakan mulai ulang.');
        }

        $this->exportService->writeJson(
            $requestId,
            $start,
            $rows,
        );

        $next = $start + count($rows);
        $done = $next >= $job['total'];

        return response()->json([
            'requestId' => $requestId,
            'start' => $start,
            'take' => count($rows),
            'total' => $job['total'],
            'done' => $done,
            'nextStart' => $done ? null : $next,
            'downloadUrl' => route('export.download', [
                'requestId' => $requestId,
                'start' => $start,
            ]),
        ]);
    }

    public function download(
        Request $request,
        string $requestId,
        int $start
    ) {
        $user = $request->user();

        $job = $this->exportService->getJob(
            $requestId,
            $user->getAuthIdentifier(),
        );

        $module = $this->registryService->get($job['module']);

        $this->authorize($module['ability'], $module['model']);

        $path = $this->exportService->getDownloadPath(
            $requestId,
            $start,
            $user->getAuthIdentifier(),
        );

        return Storage::disk('local')->download(
            $path,
            "export_{$requestId}_{$start}.json",
            ['Content-Type' => 'application/json'],
        );
    }
    
    public function cleanup(Request $request, string $requestId)
    {
        $user = $request->user();

        $job = $this->exportService->getJob(
            $requestId,
            $user->getAuthIdentifier(),
        );

        $module = $this->registryService->get($job['module']);

        $this->authorize($module['ability'], $module['model']);

        $this->exportService->deleteJob(
            $requestId,
            $user->getAuthIdentifier(),
        );

        return response()->json([
            'message' => 'File export berhasil dibersihkan.',
        ]);
    }

    public function cleanups()
    {
        $result = $this->exportService->cleanupExpiredJobs(3);

        return response()->json([
            'success' => true,
            'message' => 'Export cleanup completed',
            'summary' => $result,
        ]);
    }
}
