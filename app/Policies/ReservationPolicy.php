<?php

namespace App\Policies;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('reservations.view');
    }

    public function create(User $user): bool
    {
        return $user->can('reservations.create');
    }

    /** Extend, move to another car, or add to the deposit. */
    public function manage(User $user, Reservation $reservation): bool
    {
        return $reservation->status === ReservationStatus::Active && $user->can('reservations.create');
    }

    /** Cancelling a reservation releases a deposited car: a cancellation right (admin by default). */
    public function cancel(User $user, Reservation $reservation): bool
    {
        return $reservation->status === ReservationStatus::Active && $user->can('reservations.cancel');
    }

    /** Refunding or forfeiting deposit money is also a cancellation right. */
    public function settle(User $user, Reservation $reservation): bool
    {
        return $reservation->status !== ReservationStatus::Converted && $user->can('reservations.cancel');
    }
}
