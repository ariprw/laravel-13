<?php

namespace App\Services\Export;

use App\Models\ActivityLog;
use App\Models\Branches;
use App\Models\User;
use App\Services\Audit\ActivityLogService;
use App\Services\Branches\BranchesService;
use App\Services\User\UserService;
use InvalidArgumentException;

class RegistryService
{
    private array $modules = [
        'activity-log' => [
            'model' => ActivityLog::class,
            'service' => ActivityLogService::class,
        ],
        'branches' => [
            'model' => Branches::class,
            'service' => BranchesService::class,
        ],
        'user' => [
            'model' => User::class,
            'service' => UserService::class,
        ],
    ];

    public function get(string $module): array
    {
        $config = $this->modules[$module] ?? throw new InvalidArgumentException("Module {$module} not found");

        return [
            ...$config,
            'title' => app($config['service'])->title,
            'ability' => 'export',
        ];
    }

    public function has(string $module): bool
    {
        return isset($this->modules[$module]);
    }

    public function all(): array
    {
        return $this->modules;
    }

    public function resolveToken(string $token): ?string
    {
        foreach ($this->all() as $key => $module) {
            if (hash_equals(idToken($key), $token)) {
                return $key;
            }
        }

        return null;
    }
}
