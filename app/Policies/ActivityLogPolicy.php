<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'user'], true);
    }

    public function view(User $user, ActivityLog $activityLog): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'user' && $user->id === $activityLog->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }

    public function delete(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }
    
    public function export(User $user): bool
    {
        return in_array($user->role, ['admin', 'user'], true);
    }
}
