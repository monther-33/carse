<?php

namespace App\Actions\Expenses;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\AccountRole;
use App\Enums\CostBearer;
use App\Enums\DocumentStatus;
use App\Enums\SequenceType;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Cashbox;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vehicle;
use App\Models\VehicleCost;
use App\Models\VehicleOwnership;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Numbering\SequenceService;
use App\Services\Ownership\OwnershipSplit;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Posts a draft expense:
 *  - operating:            Dr category account          / Cr cashbox
 *  - on a car in stock:    Dr the car's stock account    / Cr cashbox  (capitalised: extra_cost, total_cost)
 *  - on a car already sold: Dr cost of vehicles sold     / Cr cashbox  (the sale's cost_snapshot stays frozen)
 *
 * On a car with owners, borne by the showroom: as above. Borne by the owners: their part is
 * Dr owners payable per owner (consignment: all of it among the owners; partnership: the
 * partners' shares, the showroom's share as above), and it is not capitalised.
 */
class PostExpense
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly PostingService $posting,
        private readonly AccountResolver $accounts,
        private readonly SequenceService $sequences,
        private readonly OwnershipSplit $split,
    ) {}

    public function handle(Expense $expense): Expense
    {
        return DB::transaction(function () use ($expense) {
            $expense = $this->lockInStatus($expense, DocumentStatus::Draft);
            $cashbox = Cashbox::query()->findOrFail($expense->cashbox_id);
            $base = Money::of($expense->amount_base);

            $vehicle = $expense->vehicle_id ? Vehicle::query()->lockForUpdate()->findOrFail($expense->vehicle_id) : null;
            $toCostOfSales = $vehicle?->status === VehicleStatus::Sold;

            if ($vehicle !== null && ! $vehicle->status->isInStock() && ! $toCostOfSales) {
                throw BusinessRuleException::make('expenses.errors.vehicle_not_ours', ['vin' => $vehicle->vin]);
            }

            $debitAccount = match (true) {
                $vehicle === null => ExpenseCategory::withTrashed()->findOrFail($expense->category_id)->account_id,
                $toCostOfSales => $this->accounts->idFor(AccountRole::CostOfSales),
                default => $this->accounts->idFor($vehicle->status->stockRole()),
            };

            [$showroomPart, $ownersParts] = $this->bearers($expense, $vehicle, $base);

            $number = $this->sequences->next(SequenceType::Expense, $expense->date);
            $builder = JournalBuilder::make($expense->date, $number.' — '.$expense->description)
                ->source($expense)
                ->branch($expense->branch_id);
            if ($showroomPart->isPositive()) {
                $builder->debit($debitAccount, $showroomPart, vehicleId: $vehicle?->id, memo: $vehicle?->vin);
            }
            foreach ($ownersParts as $partyId => $part) {
                if ($part->isPositive()) {
                    $builder->debit($this->accounts->idFor(AccountRole::OwnersPayable), $part, partyId: $partyId, vehicleId: $vehicle?->id, memo: $vehicle?->vin);
                }
            }
            $entry = $this->posting->post($builder->credit($cashbox->account_id, $expense->amount, $expense->currency_id, $expense->rate));
            $this->markPosted($expense, $number, $entry);

            if ($expense->recurs_every_months) {
                $expense->update(['next_due_date' => $expense->date->copy()->addMonthsNoOverflow($expense->recurs_every_months)]);
            }

            if ($vehicle !== null) {
                VehicleCost::query()->create([
                    'vehicle_id' => $vehicle->id,
                    'expense_id' => $expense->id,
                    'amount' => (string) $showroomPart,
                    'owners_amount' => (string) $base->minus($showroomPart),
                    'description' => $expense->description,
                    'to_cost_of_sales' => $toCostOfSales,
                    'created_by' => Auth::id(),
                ]);

                if (! $toCostOfSales) {
                    $vehicle->forceFill([
                        'extra_cost' => (string) Money::of($vehicle->extra_cost)->plus($showroomPart),
                        'total_cost' => (string) Money::of($vehicle->total_cost)->plus($showroomPart),
                    ])->save();
                }
            }

            return $expense;
        });
    }

    /**
     * The showroom's part (LYD) and each owner's part.
     *
     * @return array{0: BigDecimal, 1: array<int, BigDecimal>}
     */
    private function bearers(Expense $expense, ?Vehicle $vehicle, BigDecimal $base): array
    {
        if ($vehicle?->ownership_id === null || $expense->borne_by !== CostBearer::Owners) {
            return [$base, []];
        }

        $ownership = VehicleOwnership::query()->with('owners')->findOrFail($vehicle->ownership_id);
        if ($ownership->isConsignment()) {
            return [Money::zero(), $this->split->amongOwners($ownership, $base)];
        }

        $parts = $this->split->byShares($ownership, $base);

        return [$parts['showroom'], $parts['owners']];
    }
}
