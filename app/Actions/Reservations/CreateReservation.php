<?php

namespace App\Actions\Reservations;

use App\Enums\ReservationStatus;
use App\Enums\SequenceType;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Cashbox;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Services\Numbering\SequenceService;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Reserves an available car for a customer against a deposit:
 *  - the car moves available → reserved immediately;
 *  - the deposit is a receipt voucher on "customer deposits" (see DepositVouchers for
 *    when it is posted at once or waits for approval).
 */
class CreateReservation
{
    public function __construct(
        private readonly SequenceService $sequences,
        private readonly VehicleStateMachine $vehicles,
        private readonly DepositVouchers $vouchers,
    ) {}

    /**
     * @param  array{vehicle_id: int, party_id: int, cashbox_id: int, deposit: mixed, expires_at: string, date?: string, rate?: mixed, notes?: string|null}  $data
     */
    public function handle(array $data): Reservation
    {
        return DB::transaction(function () use ($data) {
            $vehicle = Vehicle::query()->lockForUpdate()->findOrFail($data['vehicle_id']);
            $cashbox = Cashbox::query()->findOrFail($data['cashbox_id']);
            $date = CarbonImmutable::parse($data['date'] ?? now());
            $deposit = Money::of((string) $data['deposit']);

            if ($vehicle->status !== VehicleStatus::Available) {
                throw BusinessRuleException::make('reservations.errors.not_available', ['vin' => $vehicle->vin]);
            }
            if (! $deposit->isPositive()) {
                throw BusinessRuleException::make('reservations.errors.deposit');
            }
            if (CarbonImmutable::parse($data['expires_at'])->startOfDay()->isBefore($date->startOfDay())) {
                throw BusinessRuleException::make('reservations.errors.expiry');
            }

            $reservation = Reservation::query()->create([
                'branch_id' => Auth::user()->branch_id ?? $vehicle->branch_id,
                'number' => $this->sequences->next(SequenceType::Reservation, $date),
                'date' => $date->toDateString(),
                'vehicle_id' => $vehicle->id,
                'party_id' => $data['party_id'],
                'salesperson_id' => Auth::id(),
                'deposit' => (string) $deposit,
                'currency_id' => $cashbox->currency_id,
                'expires_at' => $data['expires_at'],
                'status' => ReservationStatus::Active,
                'notes' => $data['notes'] ?? null,
            ]);

            $this->vehicles->transition($vehicle, VehicleStatus::Reserved, $reservation);

            $voucher = $this->vouchers->receive($reservation, $cashbox->id, $deposit, $data['rate'] ?? null,
                __('reservations.deposit_description', ['number' => $reservation->number, 'vin' => $vehicle->vin]));
            $reservation->update(['voucher_id' => $voucher->id]);

            return $reservation->refresh();
        });
    }
}
