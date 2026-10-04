<?php

namespace App\Actions\Expenses;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\SequenceType;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Cashbox;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vehicle;
use App\Models\VehicleCost;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Numbering\SequenceService;
use App\Support\Money;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Posts a draft expense:
 *  - operating:            Dr category account          / Cr cashbox
 *  - on a car in stock:    Dr the car's stock account    / Cr cashbox  (capitalised: extra_cost, total_cost)
 *  - on a car already sold: Dr cost of vehicles sold     / Cr cashbox  (the sale's cost_snapshot stays frozen)
 */
class PostExpense
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly PostingService $posting,
        private readonly AccountResolver $accounts,
        private readonly SequenceService $sequences,
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

            $number = $this->sequences->next(SequenceType::Expense, $expense->date);
            $entry = $this->posting->post(
                JournalBuilder::make($expense->date, $number.' — '.$expense->description)
                    ->source($expense)
                    ->branch($expense->branch_id)
                    ->debit($debitAccount, $base, vehicleId: $vehicle?->id, memo: $vehicle?->vin)
                    ->credit($cashbox->account_id, $expense->amount, $expense->currency_id, $expense->rate)
            );
            $this->markPosted($expense, $number, $entry);

            if ($expense->recurs_every_months) {
                $expense->update(['next_due_date' => $expense->date->copy()->addMonthsNoOverflow($expense->recurs_every_months)]);
            }

            if ($vehicle !== null) {
                VehicleCost::query()->create([
                    'vehicle_id' => $vehicle->id,
                    'expense_id' => $expense->id,
                    'amount' => (string) $base,
                    'description' => $expense->description,
                    'to_cost_of_sales' => $toCostOfSales,
                    'created_by' => Auth::id(),
                ]);

                if (! $toCostOfSales) {
                    $vehicle->forceFill([
                        'extra_cost' => (string) Money::of($vehicle->extra_cost)->plus($base),
                        'total_cost' => (string) Money::of($vehicle->total_cost)->plus($base),
                    ])->save();
                }
            }

            return $expense;
        });
    }
}
