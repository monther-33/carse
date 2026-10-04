<?php

namespace App\Actions\Reservations;

use App\Enums\ReservationStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

/**
 * Changes to an active reservation: extend it, move it (and its deposit) to another car,
 * or add to the deposit.
 */
class ManageReservation
{
    public function __construct(
        private readonly VehicleStateMachine $vehicles,
        private readonly DepositVouchers $vouchers,
    ) {}

    public function extend(Reservation $reservation, string $expiresAt): Reservation
    {
        return DB::transaction(function () use ($reservation, $expiresAt) {
            $reservation = $this->lockActive($reservation);

            if (CarbonImmutable::parse($expiresAt)->startOfDay()->isBefore(today())) {
                throw BusinessRuleException::make('reservations.errors.expiry');
            }

            $reservation->update(['expires_at' => $expiresAt]);

            return $reservation;
        });
    }

    public function changeVehicle(Reservation $reservation, int $vehicleId, ?string $note = null): Reservation
    {
        return DB::transaction(function () use ($reservation, $vehicleId, $note) {
            $reservation = $this->lockActive($reservation);
            $old = Vehicle::query()->lockForUpdate()->findOrFail($reservation->vehicle_id);
            $new = Vehicle::query()->lockForUpdate()->findOrFail($vehicleId);

            if ($new->id === $old->id) {
                return $reservation;
            }
            if ($new->status !== VehicleStatus::Available) {
                throw BusinessRuleException::make('reservations.errors.not_available', ['vin' => $new->vin]);
            }

            $note ??= __('reservations.moved_note', ['from' => $old->vin, 'to' => $new->vin]);
            $this->vehicles->transition($old, VehicleStatus::Available, $reservation, $note);
            $this->vehicles->transition($new, VehicleStatus::Reserved, $reservation, $note);
            $reservation->update(['vehicle_id' => $new->id]);

            return $reservation;
        });
    }

    public function topUp(Reservation $reservation, int $cashboxId, mixed $amount, mixed $rate = null): Reservation
    {
        return DB::transaction(function () use ($reservation, $cashboxId, $amount, $rate) {
            $reservation = $this->lockActive($reservation);
            $amount = Money::of((string) $amount);

            if (! $amount->isPositive()) {
                throw BusinessRuleException::make('reservations.errors.deposit');
            }

            $this->vouchers->receive($reservation, $cashboxId, $amount, $rate,
                __('reservations.top_up_description', ['number' => $reservation->number]));
            $reservation->update(['deposit' => (string) Money::of($reservation->deposit)->plus($amount)]);

            return $reservation;
        });
    }

    private function lockActive(Reservation $reservation): Reservation
    {
        $reservation = Reservation::query()->lockForUpdate()->findOrFail($reservation->id);

        if ($reservation->status !== ReservationStatus::Active) {
            throw BusinessRuleException::make('reservations.errors.not_active');
        }

        return $reservation;
    }
}
