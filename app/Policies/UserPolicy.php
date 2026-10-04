<?php

namespace App\Policies;

use App\Models\User;

/**
 * Users are never deleted, only deactivated (and never yourself).
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('users.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('users.manage');
    }

    public function update(User $user, User $target): bool
    {
        return $user->can('users.manage');
    }

    public function toggleActive(User $user, User $target): bool
    {
        return $user->can('users.manage') && ! $user->is($target);
    }

    public function delete(User $user, User $target): bool
    {
        return false;
    }
}
