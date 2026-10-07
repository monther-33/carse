<?php

namespace App\Actions\Expenses;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\CostBearer;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Cashbox;
use App\Models\Expense;
use App\Models\Vehicle;
use App\Services\Currency\ExchangeRateService;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Creates or updates a DRAFT expense, paid from one cashbox in that cashbox's currency.
 * On a car with owners (consignment or partnership) it says who bears it (showroom by default).
 */
class SaveExpense
{
    use ManagesDocumentLifecycle;

    public function __construct(private readonly ExchangeRateService $rates) {}

    /**
     * @param  array{date: string, category_id: int, cashbox_id: int, amount: mixed, rate?: mixed,
     *               vehicle_id?: int|null, borne_by?: string|null, description: string, recurs_every_months?: int|null, notes?: string|null}  $data
     */
    public function handle(array $data, ?Expense $expense = null): Expense
    {
        return DB::transaction(function () use ($data, $expense) {
            if ($expense !== null) {
                $expense = $this->lockInStatus($expense, DocumentStatus::Draft);
            }

            $cashbox = Cashbox::query()->findOrFail($data['cashbox_id']);
            $amount = Money::of((string) $data['amount']);
            $date = CarbonImmutable::parse($data['date']);

            if (! $amount->isPositive()) {
                throw BusinessRuleException::make('expenses.errors.amount');
            }

            $borneBy = null;
            if (! empty($data['vehicle_id'])) {
                $vehicle = Vehicle::query()->findOrFail($data['vehicle_id']);
                if (! $vehicle->status->isInStock() && $vehicle->status !== VehicleStatus::Sold) {
                    throw BusinessRuleException::make('expenses.errors.vehicle_not_ours', ['vin' => $vehicle->vin]);
                }
                if ($vehicle->ownership_id !== null) {
                    $borneBy = CostBearer::tryFrom((string) ($data['borne_by'] ?? '')) ?? CostBearer::Showroom;
                }
            }

            $rate = $this->rates->isBase($cashbox->currency_id)
                ? Money::rate(1)
                : (empty($data['rate']) ? $this->rates->rateFor($cashbox->currency_id, $date) : Money::rate((string) $data['rate']));

            $expense ??= new Expense(['status' => DocumentStatus::Draft, 'branch_id' => Auth::user()->branch_id ?? $cashbox->branch_id]);
            $expense->fill([
                'date' => $date->toDateString(),
                'category_id' => $data['category_id'],
                'cashbox_id' => $cashbox->id,
                'amount' => (string) $amount,
                'currency_id' => $cashbox->currency_id,
                'rate' => (string) $rate,
                'amount_base' => (string) Money::toBase($amount, $rate),
                'vehicle_id' => $data['vehicle_id'] ?: null,
                'borne_by' => $borneBy,
                'description' => $data['description'],
                'recurs_every_months' => $data['recurs_every_months'] ?: null,
                'notes' => $data['notes'] ?? null,
            ]);
            $expense->save();

            return $expense;
        });
    }
}
