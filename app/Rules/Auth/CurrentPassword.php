<?php

namespace App\Rules\Auth;

use App\Services\Auth\PasswordService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CurrentPassword implements ValidationRule
{
    public function __construct(
        private PasswordService $passwordService
    ) {}
    
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        $user = auth()->user();

        if (
            ! $user ||
            ! $this->passwordService->check(
                (string) $value,
                $user->password
            )
        ) {
            $fail('Password saat ini tidak sesuai');
        }
    }
}
