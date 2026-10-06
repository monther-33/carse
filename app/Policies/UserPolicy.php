<?php

namespace App\Policies;

use App\Models\User;

/**
 * Users are never deleted, only deactivated (and never yourself). Developer accounts are
 * invisible to, and untouchable by, everyone but the developer.
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
        return $user->can('users.manage') && (! $target->isDeveloper() || $user->isDeveloper());
    }

    public function toggleActive(User $user, User $target): bool
    {
        return $this->update($user, $target) && ! $user->is($target);
    }

    public function delete(User $user, User $target): bool
    {
        return false;
    }
}
