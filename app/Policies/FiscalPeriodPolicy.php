<?php

namespace App\Policies;

use App\Models\FiscalPeriod;
use App\Models\User;

class FiscalPeriodPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('periods.view');
    }

    public function create(User $user): bool
    {
        return $user->can('periods.manage');
    }

    public function close(User $user, FiscalPeriod $period): bool
    {
        return $user->can('periods.close') && ! $period->is_closed;
    }

    public function delete(User $user, FiscalPeriod $period): bool
    {
        return false;
    }
}
