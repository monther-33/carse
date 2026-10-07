<?php

namespace App\Actions\Ownership;

use App\Enums\EarningMode;
use App\Enums\OwnershipKind;
use App\Enums\OwnershipStatus;
use App\Enums\PayoutTiming;
use App\Enums\SequenceType;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Vehicle;
use App\Models\VehicleOwnership;
use App\Services\Numbering\SequenceService;
use App\Services\Vehicles\DraftVehicleResolver;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Takes a consignment vehicle into the showroom: the car belongs to one or more owners
 * (parties) and the showroom only sells it. No journal entry: the car is not the showroom's
 * asset, so it enters stock at zero cost; owners are owed money only when it is sold.
 * The intake gets a CI number and a printable receipt.
 */
class ReceiveConsignment
{
    public function __construct(
        private readonly DraftVehicleResolver $vehicles,
        private readonly VehicleStateMachine $states,
        private readonly SequenceService $sequences,
    ) {}

    /**
     * @param  array<string, mixed>  $data  vin + Vehicle::EDITABLE, entry_status, received_at, earning_mode,
     *                                      earning_amount|earning_percent, payout, notes, owners: list<{party_id, share}>
     */
    public function handle(array $data): VehicleOwnership
    {
        $owners = OwnerShares::validate($data['owners'] ?? []);
        $mode = EarningMode::from((string) $data['earning_mode']);
        [$amount, $percent] = $this->terms($mode, $data);
        $status = VehicleStatus::from((string) $data['entry_status']);
        $date = Carbon::parse((string) $data['received_at']);

        if (! in_array($status, VehicleStatus::entryStatuses(), true)) {
            throw BusinessRuleException::make('ownership.errors.entry_status');
        }

        return DB::transaction(function () use ($data, $owners, $mode, $amount, $percent, $status, $date) {
            $branchId = (int) Auth::user()?->branch_id;
            $vehicle = $this->vehicles->resolve($data, $branchId, fn (Vehicle $v) => false);

            $ownership = VehicleOwnership::query()->create([
                'branch_id' => $branchId,
                'number' => $this->sequences->next(SequenceType::ConsignmentIntake, $date),
                'vehicle_id' => $vehicle->id,
                'kind' => OwnershipKind::Consignment,
                'showroom_share' => '0',
                'earning_mode' => $mode,
                'earning_amount' => $amount,
                'earning_percent' => $percent,
                'payout' => PayoutTiming::from((string) $data['payout']),
                'status' => OwnershipStatus::Active,
                'received_at' => $date,
                'notes' => $data['notes'] ?? null,
            ]);
            foreach ($owners as $owner) {
                $ownership->owners()->create($owner);
            }

            $vehicle->forceFill([
                'purchase_cost' => '0',
                'extra_cost' => '0',
                'total_cost' => '0',
                'purchase_invoice_id' => null,
                'sale_invoice_id' => null,
                'ownership_id' => $ownership->id,
                'received_at' => $date,
                'sold_at' => null,
            ])->save();

            $this->states->transition($vehicle, $status, $ownership);

            return $ownership->load('owners.party', 'vehicle');
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{0: string|null, 1: string|null} [earning_amount, earning_percent]
     */
    private function terms(EarningMode $mode, array $data): array
    {
        if ($mode === EarningMode::Percent) {
            $percent = Money::rate((string) ($data['earning_percent'] ?? '0'));
            if (! $percent->isPositive() || ! $percent->isLessThan(100)) {
                throw BusinessRuleException::make('ownership.errors.percent');
            }

            return [null, (string) $percent->toScale(4)];
        }

        $amount = Money::of((string) ($data['earning_amount'] ?? '0'));
        if (! $amount->isPositive()) {
            throw BusinessRuleException::make('ownership.errors.amount');
        }

        return [(string) $amount, null];
    }
}
