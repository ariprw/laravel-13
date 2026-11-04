<?php

namespace App\Services\Export;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class ExportService
{
    private const DEFAULT_TAKE = 500;
    private const MAX_TAKE = 1000;
    private const TTL_MINUTES = 120;

    public function createJob(
        string $userId,
        string $module,
        int $total,
        array $filters = [],
    ): array {
        if ($total < 1) {
            throw ValidationException::withMessages([
                'export' => 'Tidak ada data untuk diekspor.',
            ]);
        }

        $requestId = Str::random(48);

        $job = [
            'user_id' => $userId,
            'module' => $module,
            'total' => $total,
            'filters' => $filters,
            'created_at' => now()->toIso8601String(),
        ];

        Cache::put(
            $this->jobKey($requestId),
            $job,
            now()->addMinutes(self::TTL_MINUTES)
        );

        return [
            'requestId' => $requestId,
            'start' => 0,
            'take' => self::DEFAULT_TAKE,
            'total' => $total,
        ];
    }

    public function getJob(
        string $requestId,
        string $userId
    ): array {
        $job = Cache::get($this->jobKey($requestId));

        if (! is_array($job) || (string) ($job['user_id'] ?? '') !== (string) $userId) {
            abort(404);
        }

        return $job;
    }

    public function normalizeTake(mixed $take): int
    {
        $take = filter_var($take, FILTER_VALIDATE_INT);

        if ($take === false || $take < 1) {
            return self::DEFAULT_TAKE;
        }

        return min($take, self::MAX_TAKE);
    }

    public function writeJson(
        string $requestId,
        int $start,
        array $rows
    ): string {
        $this->validateRequestId($requestId);

        if ($start < 0) {
            throw new RuntimeException('Posisi batch tidak valid.');
        }

        $json = json_encode(
            $rows,
            JSON_UNESCAPED_UNICODE
                | JSON_INVALID_UTF8_SUBSTITUTE
                | JSON_THROW_ON_ERROR
        );

        $path = "exports/{$requestId}/part_{$start}.json";

        Storage::disk('local')->put($path, $json);

        return $path;
    }

    public function getDownloadPath(
        string $requestId,
        int $start,
        string $userId
    ): string {
        $this->getJob($requestId, $userId);

        if ($start < 0) {
            abort(404);
        }

        $path = "exports/{$requestId}/part_{$start}.json";

        abort_unless(
            Storage::disk('local')->exists($path),
            404
        );

        return $path;
    }

    public function deleteJob(
        string $requestId,
        string $userId
    ): void {
        $this->getJob($requestId, $userId);

        Storage::disk('local')->deleteDirectory(
            "exports/{$requestId}"
        );

        Cache::forget($this->jobKey($requestId));
    }

    private function jobKey(string $requestId): string
    {
        $this->validateRequestId($requestId);

        return "exports:jobs:{$requestId}";
    }

    private function validateRequestId(string $requestId): void
    {
        if (! preg_match('/^[A-Za-z0-9]{48}$/', $requestId)) {
            abort(404);
        }
    }

    public function cleanupExpiredJobs(int $retentionHours = 3): array
    {
        $disk = Storage::disk('local');

        $directories = $disk->directories('exports');

        $deleted = [];
        $skipped = [];

        foreach ($directories as $directory) {
            $requestId = basename($directory);

            $files = $disk->allFiles($directory);

            if (!empty($files)) {
                $lastActivity = collect($files)
                ->map(fn ($file) => $disk->lastModified($file))
                ->max();
            } else {
                $lastActivity = $disk->lastModified($directory);
            }

            if (!$lastActivity) {
                $skipped[] = $requestId;
                continue;
            }

            $expired = Carbon::createFromTimestamp($lastActivity)
            ->addHours($retentionHours)
            ->isPast();

            if (!$expired) {
                $skipped[] = $requestId;
                continue;
            }

            $disk->deleteDirectory($directory);

            $deleted[] = $requestId;
        }

        return [
            'total_directories' => count($directories),
            'deleted' => count($deleted),
            'skipped' => count($skipped),
            'deleted_request_ids' => $deleted,
            'skipped_request_ids' => $skipped,
        ];
    }
}
