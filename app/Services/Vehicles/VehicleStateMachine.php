<?php

namespace App\Services\Vehicles;

use App\Enums\AccountRole;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Vehicle;
use App\Models\VehicleStatusLog;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * The only place that changes vehicles.status. Every change is validated against
 * VehicleStatus::allowedTargets() (or manualTargets() for user-triggered moves) and logged.
 *
 * When a vehicle leaves customs, its cost moves from "vehicles in transit" (15) to
 * "vehicle inventory" (14) with an automatic entry.
 */
class VehicleStateMachine
{
    public function __construct(
        private readonly PostingService $posting,
        private readonly AccountResolver $accounts,
    ) {}

    public function transition(
        Vehicle $vehicle,
        VehicleStatus $to,
        ?Model $source = null,
        ?string $note = null,
        bool $manual = false,
    ): VehicleStatusLog {
        return DB::transaction(function () use ($vehicle, $to, $source, $note, $manual) {
            $locked = Vehicle::query()->lockForUpdate()->findOrFail($vehicle->id);
            $from = $locked->status;

            $allowed = $manual ? $from->manualTargets() : $from->allowedTargets();
            if (! in_array($to, $allowed, true)) {
                throw BusinessRuleException::make('vehicles.errors.transition', [
                    'from' => $from->label(), 'to' => $to->label(),
                ]);
            }

            $entryId = null;
            if ($from->isInTransit() && $to->isInStock() && ! $to->isInTransit()) {
                $entryId = $this->moveCostOutOfTransit($locked);
            }

            $locked->status = $to;
            $locked->save();
            $vehicle->setRawAttributes($locked->getAttributes(), true);

            return VehicleStatusLog::query()->create([
                'vehicle_id' => $locked->id,
                'from_status' => $from,
                'to_status' => $to,
                'user_id' => Auth::id(),
                'note' => $note,
                'source_type' => $source?->getMorphClass(),
                'source_id' => $source?->getKey(),
                'journal_entry_id' => $entryId,
            ]);
        });
    }

    private function moveCostOutOfTransit(Vehicle $vehicle): ?int
    {
        $cost = Money::of($vehicle->total_cost);
        if (! $cost->isPositive()) {
            return null;
        }

        $entry = $this->posting->post(
            JournalBuilder::make(now(), __('vehicles.transit_transfer', ['vin' => $vehicle->vin]))
                ->source($vehicle)
                ->branch($vehicle->branch_id)
                ->debit($this->accounts->idFor(AccountRole::Inventory), $cost, vehicleId: $vehicle->id)
                ->credit($this->accounts->idFor(AccountRole::InTransit), $cost, vehicleId: $vehicle->id)
        );

        return $entry->id;
    }
}
