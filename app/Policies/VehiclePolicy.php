<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

/**
 * Vehicles enter stock through purchase invoices (vehicles.create is checked there).
 * Cost and profit are visible only with vehicles.view_cost — in screens, reports and exports.
 */
class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('vehicles.view');
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->can('vehicles.view');
    }

    public function viewCost(User $user): bool
    {
        return $user->can('vehicles.view_cost');
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->can('vehicles.update');
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return false;
    }
}
