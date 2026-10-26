<?php

namespace App\Policies;

use App\Models\Branches;
use App\Models\User;

class BranchesPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Branches $branches): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Branches $branches): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Branches $branches): bool
    {
        return $user->role === 'admin';
    }
}
