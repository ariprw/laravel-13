<?php

namespace App\Rules\Export;

use App\Services\Export\RegistryService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasRegistry implements ValidationRule
{
    public function __construct(
        private readonly RegistryService $registryService
    ) {}

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if ($this->registryService->resolveToken((string) $value) === null) {
            $fail('Tidak valid');
        }
    }
}
