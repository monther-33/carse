<?php

namespace App\Actions\Reservations;

use App\Actions\DeleteDraft;
use App\Enums\DocumentStatus;
use App\Enums\ReservationStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Services\Vehicles\VehicleStateMachine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Ends an active reservation (cancelled by a user, or expired): the car goes back to
 * "available". A posted deposit stays in customer deposits as the customer's credit,
 * to be refunded with a payment voucher or applied to a later sale; a deposit voucher
 * still in draft is simply deleted.
 */
class EndReservation
{
    public function __construct(
        private readonly VehicleStateMachine $vehicles,
        private readonly DeleteDraft $deleteDraft,
    ) {}

    public function cancel(Reservation $reservation, string $reason): Reservation
    {
        return $this->end($reservation, ReservationStatus::Cancelled, $reason);
    }

    public function expire(Reservation $reservation): Reservation
    {
        return $this->end($reservation, ReservationStatus::Expired, __('reservations.expired_note'));
    }

    /**
     * Expires every active reservation whose expiry date has passed.
     */
    public function expireOverdue(): int
    {
        $count = 0;

        Reservation::query()->active()->whereDate('expires_at', '<', today())->each(function (Reservation $reservation) use (&$count) {
            $this->expire($reservation);
            $count++;
        });

        return $count;
    }

    private function end(Reservation $reservation, ReservationStatus $status, string $reason): Reservation
    {
        return DB::transaction(function () use ($reservation, $status, $reason) {
            $reservation = Reservation::query()->lockForUpdate()->findOrFail($reservation->id);

            if ($reservation->status !== ReservationStatus::Active) {
                throw BusinessRuleException::make('reservations.errors.not_active');
            }

            $voucher = $reservation->voucher_id ? Voucher::query()->find($reservation->voucher_id) : null;
            if ($voucher?->status === DocumentStatus::Draft) {
                $reservation->update(['voucher_id' => null]);
                $this->deleteDraft->handle($voucher);
            }

            $reservation->update([
                'status' => $status,
                'cancelled_by' => Auth::id(),
                'cancelled_at' => now(),
                'cancel_reason' => $reason,
            ]);

            $vehicle = Vehicle::query()->lockForUpdate()->findOrFail($reservation->vehicle_id);
            if ($vehicle->status === VehicleStatus::Reserved) {
                $this->vehicles->transition($vehicle, VehicleStatus::Available, $reservation, $reason);
            }

            return $reservation;
        });
    }
}
