<?php

namespace App\Actions\OpeningStock;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\SequenceType;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\JournalEntry;
use App\Models\OpeningStock;
use App\Models\Vehicle;
use App\Models\VehicleStatusLog;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Accounting\ReversalService;
use App\Services\Numbering\SequenceService;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Approves a draft opening stock: per vehicle Dr inventory (or "in transit" for cars still on
 * the way) / Cr opening balances, and the vehicles enter stock at their imported cost.
 *
 * Cancelling reverses the entry and takes the vehicles out of stock, only while none of them
 * has changed since (same conditions as cancelling a purchase invoice).
 */
class PostOpeningStock
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly PostingService $posting,
        private readonly ReversalService $reversal,
        private readonly AccountResolver $accounts,
        private readonly SequenceService $sequences,
        private readonly VehicleStateMachine $vehicles,
    ) {}

    public function handle(OpeningStock $stock): OpeningStock
    {
        return DB::transaction(function () use ($stock) {
            $stock = $this->lockInStatus($stock, DocumentStatus::Draft);
            $stock->load('items');

            if ($stock->items->isEmpty()) {
                throw BusinessRuleException::make('purchases.errors.no_items');
            }

            $opening = $this->accounts->idFor(AccountRole::OpeningBalances);
            $number = $this->sequences->next(SequenceType::OpeningStock, $stock->date);
            $builder = JournalBuilder::make($stock->date, $number.' — '.$stock->description)
                ->source($stock)
                ->branch($stock->branch_id);

            $vehicles = Vehicle::query()->lockForUpdate()->findMany($stock->items->pluck('vehicle_id'))->keyBy('id');

            foreach ($stock->items as $item) {
                $vehicle = $vehicles[$item->vehicle_id];
                if ($vehicle->status->isInStock()) {
                    throw BusinessRuleException::make('purchases.errors.vin_in_stock', ['vin' => $vehicle->vin]);
                }

                $builder
                    ->debit($this->accounts->idFor($item->entry_status->stockRole()), $item->cost, vehicleId: $vehicle->id, memo: $vehicle->vin)
                    ->credit($opening, $item->cost, vehicleId: $vehicle->id, memo: $vehicle->vin);
            }

            $entry = $this->posting->post($builder);
            $this->markPosted($stock, $number, $entry);

            foreach ($stock->items as $item) {
                $vehicle = $vehicles[$item->vehicle_id];
                $vehicle->forceFill([
                    'purchase_cost' => $item->cost,
                    'extra_cost' => '0',
                    'total_cost' => $item->cost,
                    'purchase_invoice_id' => null,
                    'sale_invoice_id' => null,
                    'ownership_id' => null,
                    'received_at' => $item->received_at,
                    'sold_at' => null,
                ])->save();

                $this->vehicles->transition($vehicle, $item->entry_status, $stock);
            }

            return $stock->refresh();
        });
    }

    public function cancel(OpeningStock $stock, string $reason): OpeningStock
    {
        return DB::transaction(function () use ($stock, $reason) {
            $stock = $this->lockInStatus($stock, DocumentStatus::Posted);
            $stock->load('items');
            $vehicles = Vehicle::query()->lockForUpdate()->findMany($stock->items->pluck('vehicle_id'))->keyBy('id');

            foreach ($stock->items as $item) {
                $vehicle = $vehicles[$item->vehicle_id];
                $untouched = $vehicle->status->isInStock()
                    && $vehicle->status !== VehicleStatus::Reserved
                    && Money::of($vehicle->extra_cost)->isZero()
                    && $vehicle->status->stockRole() === $item->entry_status->stockRole()
                    && $this->onlyManualMovesSince($vehicle, $stock);

                if (! $untouched) {
                    throw BusinessRuleException::make('imports.errors.cannot_cancel_stock', ['vin' => $vehicle->vin]);
                }
            }

            $this->reversal->reverse(
                JournalEntry::query()->findOrFail($stock->journal_entry_id),
                description: __('documents.cancellation_of', ['number' => $stock->number]),
            );
            $this->markCancelled($stock, $reason);

            foreach ($stock->items as $item) {
                $this->vehicles->transition($vehicles[$item->vehicle_id], VehicleStatus::ReturnedToSupplier, $stock, $reason);
            }

            return $stock;
        });
    }

    /** True when every status change after this document brought the car in was a manual one. */
    private function onlyManualMovesSince(Vehicle $vehicle, OpeningStock $stock): bool
    {
        $entry = VehicleStatusLog::query()->where('vehicle_id', $vehicle->id)
            ->where('source_type', $stock->getMorphClass())->where('source_id', $stock->id)
            ->max('id');

        return $entry !== null && ! VehicleStatusLog::query()->where('vehicle_id', $vehicle->id)
            ->where('id', '>', $entry)->whereNotNull('source_type')->exists();
    }
}
