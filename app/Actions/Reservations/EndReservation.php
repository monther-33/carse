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
use App\Support\Money;
use App\Support\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Ends an active reservation (cancelled by a user, or expired): the car goes back to
 * "available" and deposit receipts still in draft are deleted.
 *
 * The posted deposit is settled as chosen: refund part or all, forfeit part or all, and the
 * rest stays as the customer's credit (usable on any later sale, or settled later with
 * SettleDeposit). Expiry follows settings `reservations.expiry_action` (credit | forfeit).
 */
class EndReservation
{
    public function __construct(
        private readonly VehicleStateMachine $vehicles,
        private readonly DeleteDraft $deleteDraft,
        private readonly SettleDeposit $settle,
        private readonly Settings $settings,
    ) {}

    public function cancel(Reservation $reservation, string $reason, mixed $refund = '0', ?int $cashboxId = null, mixed $forfeit = '0'): Reservation
    {
        return DB::transaction(function () use ($reservation, $reason, $refund, $cashboxId, $forfeit) {
            $reservation = $this->end($reservation, ReservationStatus::Cancelled, $reason);

            $settles = Money::of((string) ($refund ?: '0'))->isPositive() || Money::of((string) ($forfeit ?: '0'))->isPositive();
            if ($settles) {
                $this->settle->handle($reservation, $refund, $cashboxId, $forfeit, $reason);
            }

            return $reservation->refresh();
        });
    }

    public function expire(Reservation $reservation): Reservation
    {
        return DB::transaction(function () use ($reservation) {
            $reservation = $this->end($reservation, ReservationStatus::Expired, __('reservations.expired_note'));

            if ($this->settings->get('reservations.expiry_action', 'credit') === 'forfeit') {
                $amount = $this->settle->limit($reservation);
                if ($amount->isPositive()) {
                    $this->settle->handle($reservation, '0', null, (string) $amount, __('reservations.expired_note'));
                }
            }

            return $reservation->refresh();
        });
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
        $reservation = Reservation::query()->lockForUpdate()->findOrFail($reservation->id);

        if ($reservation->status !== ReservationStatus::Active) {
            throw BusinessRuleException::make('reservations.errors.not_active');
        }

        $drafts = Voucher::query()->whereMorphedTo('reference', $reservation)->where('status', DocumentStatus::Draft)->get();
        foreach ($drafts as $draft) {
            if ($reservation->voucher_id === $draft->id) {
                $reservation->update(['voucher_id' => null]);
            }
            $this->deleteDraft->handle($draft);
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
    }
}
