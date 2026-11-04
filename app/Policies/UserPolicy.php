<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'user'], true);
    }

    public function view(User $user, User $model): bool
    {
        return in_array($user->role, ['admin', 'user'], true);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    public function profile(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin' && $model->role !== 'admin';
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
    
    public function export(User $user): bool
    {
        return $user->role === 'admin';
    }
}
