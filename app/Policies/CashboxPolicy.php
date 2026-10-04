<?php

namespace App\Policies;

use App\Models\Cashbox;
use App\Models\User;

/**
 * A treasurer (no cashboxes.view_all) only sees and uses the cashboxes assigned to them.
 */
class CashboxPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cashboxes.view');
    }

    public function view(User $user, Cashbox $cashbox): bool
    {
        if (! $user->can('cashboxes.view')) {
            return false;
        }

        return $user->can('cashboxes.view_all')
            || $cashbox->users()->whereKey($user->getKey())->exists();
    }

    public function create(User $user): bool
    {
        return $user->can('cashboxes.manage');
    }

    public function update(User $user, Cashbox $cashbox): bool
    {
        return $user->can('cashboxes.manage');
    }

    public function delete(User $user, Cashbox $cashbox): bool
    {
        return false;
    }
}
