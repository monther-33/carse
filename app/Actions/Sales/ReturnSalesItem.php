<?php

namespace App\Actions\Sales;

use App\Enums\AccountRole;
use App\Enums\CommissionStatus;
use App\Enums\DocumentStatus;
use App\Enums\InstallmentStatus;
use App\Enums\ReturnType;
use App\Enums\SequenceType;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Commission;
use App\Models\Installment;
use App\Models\ReturnDocument;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Vehicle;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Numbering\SequenceService;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * A customer returns one sold vehicle:
 *   Dr vehicle sales / Cr receivables          (the item's net price, invoice currency)
 *   Dr inventory / Cr cost of vehicles sold    (its cost_snapshot)
 *   Dr accrued commissions / Cr commission expense, if that commission is still unpaid
 * The car becomes "returned" (back in stock, then made available by hand after inspection).
 * Open installments of the invoice are cancelled: what the customer still owes or is owed
 * stays on their statement, to be collected or refunded with vouchers.
 */
class ReturnSalesItem
{
    public function __construct(
        private readonly PostingService $posting,
        private readonly AccountResolver $accounts,
        private readonly SequenceService $sequences,
        private readonly VehicleStateMachine $vehicles,
    ) {}

    public function handle(SalesInvoiceItem $item, string $reason, ?string $date = null): ReturnDocument
    {
        return DB::transaction(function () use ($item, $reason, $date) {
            $item = SalesInvoiceItem::query()->lockForUpdate()->findOrFail($item->id);
            $invoice = SalesInvoice::query()->with('installmentPlan')->lockForUpdate()->findOrFail($item->invoice_id);
            $vehicle = Vehicle::query()->lockForUpdate()->findOrFail($item->vehicle_id);
            $date = CarbonImmutable::parse($date ?? now());

            if ($invoice->status !== DocumentStatus::Posted) {
                throw BusinessRuleException::make('documents.errors.not_posted');
            }
            if ($item->return_id !== null) {
                throw BusinessRuleException::make('sales.errors.already_returned', ['vin' => $vehicle->vin]);
            }
            if ($vehicle->status !== VehicleStatus::Sold || $vehicle->sale_invoice_id !== $invoice->id) {
                throw BusinessRuleException::make('sales.errors.not_returnable', ['vin' => $vehicle->vin]);
            }

            $commission = Commission::query()->where('sales_invoice_item_id', $item->id)->lockForUpdate()->first();
            $number = $this->sequences->next(SequenceType::SalesReturn, $date);

            $return = ReturnDocument::query()->create([
                'branch_id' => $invoice->branch_id,
                'type' => ReturnType::Sale,
                'number' => $number,
                'date' => $date->toDateString(),
                'invoice_type' => $invoice->getMorphClass(),
                'invoice_id' => $invoice->id,
                'vehicle_id' => $vehicle->id,
                'amount' => $item->net,
                'amount_base' => $item->net_base,
                'reason' => $reason,
                'status' => DocumentStatus::Posted,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            $builder = JournalBuilder::make($date, __('sales.return_entry', ['number' => $number, 'invoice' => $invoice->number, 'vin' => $vehicle->vin]))
                ->source($return)
                ->branch($invoice->branch_id)
                ->debit($this->accounts->idFor(AccountRole::VehicleSales), $item->net, $invoice->currency_id, $invoice->rate, vehicleId: $vehicle->id)
                ->credit($this->accounts->idFor(AccountRole::Receivables), $item->net, $invoice->currency_id, $invoice->rate, partyId: $invoice->party_id, vehicleId: $vehicle->id);

            if (Money::of($item->cost_snapshot)->isPositive()) {
                $builder
                    ->debit($this->accounts->idFor(AccountRole::Inventory), $item->cost_snapshot, vehicleId: $vehicle->id)
                    ->credit($this->accounts->idFor(AccountRole::CostOfSales), $item->cost_snapshot, vehicleId: $vehicle->id);
            }

            if ($commission?->status === CommissionStatus::Accrued) {
                $builder
                    ->debit($this->accounts->idFor(AccountRole::AccruedCommissions), $commission->amount, memo: __('sales.commission'))
                    ->credit($this->accounts->idFor(AccountRole::CommissionExpense), $commission->amount, memo: __('sales.commission'));
                $commission->update(['status' => CommissionStatus::Cancelled]);
            }

            $entry = $this->posting->post($builder);
            $return->update(['journal_entry_id' => $entry->id]);
            $item->update(['return_id' => $return->id]);

            if ($invoice->installmentPlan !== null) {
                Installment::query()->where('plan_id', $invoice->installmentPlan->id)->open()->update(['status' => InstallmentStatus::Cancelled]);
            }

            $vehicle->forceFill(['sale_invoice_id' => null, 'sold_at' => null, 'total_cost' => $item->cost_snapshot])->save();
            $this->vehicles->transition($vehicle, VehicleStatus::Returned, $return, $reason);

            return $return;
        });
    }
}
