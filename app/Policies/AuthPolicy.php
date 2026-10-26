<?php

namespace App\Policies;

use App\Models\User;

class AuthPolicy
{
    public function register(?User $user): bool
    {
        return $user === null;
    }

    public function verify(?User $user): bool
    {
        return $user === null;
    }

    public function resend(?User $user): bool
    {
        return $user === null;
    }

    public function forgot(?User $user): bool
    {
        return $user === null;
    }

    public function reset(?User $user): bool
    {
        return $user === null;
    }

    public function login(?User $user): bool
    {
        return $user === null;
    }

    public function logout(?User $user): bool
    {
        return $user !== null;
    }
}
