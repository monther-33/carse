<?php

namespace App\Actions\Expenses;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Expense;
use App\Models\JournalEntry;
use App\Models\Vehicle;
use App\Models\VehicleCost;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\ReversalService;
use App\Support\Money;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Cancels a posted expense with a full reversing entry.
 *
 * A cost capitalised on a car can only be undone while that cost is still where it was
 * posted (same stock account, car not sold since); the car's extra_cost is reduced and a
 * negative row is added to its cost log.
 */
class CancelExpense
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly ReversalService $reversal,
        private readonly AccountResolver $accounts,
    ) {}

    public function handle(Expense $expense, string $reason): Expense
    {
        return DB::transaction(function () use ($expense, $reason) {
            $expense = $this->lockInStatus($expense, DocumentStatus::Posted);
            $entry = JournalEntry::query()->with('lines')->findOrFail($expense->journal_entry_id);
            $cost = VehicleCost::query()->where('expense_id', $expense->id)->orderBy('id')->first();
            $capitalised = Money::of($cost?->amount);

            $vehicle = null;
            if ($cost !== null && ! $cost->to_cost_of_sales && $capitalised->isPositive()) {
                $vehicle = Vehicle::query()->lockForUpdate()->findOrFail($cost->vehicle_id);
                $stockAccounts = [$this->accounts->idFor(AccountRole::Inventory), $this->accounts->idFor(AccountRole::InTransit)];
                $postedTo = $entry->lines->where('vehicle_id', $vehicle->id)->whereIn('account_id', $stockAccounts)->first()?->account_id;

                if (! $vehicle->status->isInStock() || $postedTo !== $this->accounts->idFor($vehicle->status->stockRole())) {
                    throw BusinessRuleException::make('expenses.errors.cannot_cancel_capitalised', ['vin' => $vehicle->vin]);
                }
            }

            $this->reversal->reverse($entry, description: __('documents.cancellation_of', ['number' => $expense->number]));
            $this->markCancelled($expense, $reason);

            if ($cost !== null) {
                VehicleCost::query()->create([
                    'vehicle_id' => $cost->vehicle_id,
                    'expense_id' => $expense->id,
                    'amount' => (string) $capitalised->negated(),
                    'owners_amount' => (string) Money::of($cost->owners_amount)->negated(),
                    'description' => __('documents.cancellation_of', ['number' => $expense->number]),
                    'to_cost_of_sales' => $cost->to_cost_of_sales,
                    'created_by' => Auth::id(),
                ]);
            }

            if ($vehicle !== null) {
                $vehicle->forceFill([
                    'extra_cost' => (string) Money::of($vehicle->extra_cost)->minus($capitalised),
                    'total_cost' => (string) Money::of($vehicle->total_cost)->minus($capitalised),
                ])->save();
            }

            return $expense;
        });
    }
}
