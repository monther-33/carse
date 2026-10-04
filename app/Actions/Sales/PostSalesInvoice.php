<?php

namespace App\Actions\Sales;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Actions\Vouchers\PostVoucher;
use App\Actions\Vouchers\SaveVoucher;
use App\Enums\AccountRole;
use App\Enums\CommissionStatus;
use App\Enums\DocumentStatus;
use App\Enums\ReservationStatus;
use App\Enums\SequenceType;
use App\Enums\VehicleStatus;
use App\Enums\VoucherType;
use App\Exceptions\BusinessRuleException;
use App\Models\Commission;
use App\Models\Reservation;
use App\Models\SalesInvoice;
use App\Models\Vehicle;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Numbering\SequenceService;
use App\Services\Sales\CommissionCalculator;
use App\Services\Sales\DepositService;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\DB;

/**
 * Approves a draft sale. One journal entry carries (spec 5.3):
 *  - revenue:    Dr receivables (party, invoice currency) / Cr vehicle sales     — per vehicle
 *  - cost:       Dr cost of vehicles sold / Cr inventory (vehicles.total_cost)  — per vehicle, frozen in cost_snapshot
 *  - deposit:    Dr customer deposits / Cr receivables                           — any part of the customer's posted deposit credit
 *  - trade-in:   Dr inventory (customer's car) / Cr receivables
 *  - commission: Dr commission expense / Cr accrued commissions
 * Payments taken at the sale become posted receipt vouchers (Dr cashbox / Cr receivables),
 * so the customer statement always shows the full sale and its settlement.
 *
 * Every vehicle is locked and must still be available (or reserved by this sale's own
 * reservation): the same car can never be sold twice.
 */
class PostSalesInvoice
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly PostingService $posting,
        private readonly AccountResolver $accounts,
        private readonly SequenceService $sequences,
        private readonly VehicleStateMachine $vehicles,
        private readonly CommissionCalculator $commissions,
        private readonly SaveSalesInvoice $saveSales,
        private readonly SaveVoucher $saveVoucher,
        private readonly PostVoucher $postVoucher,
        private readonly DepositService $deposits,
    ) {}

    public function handle(SalesInvoice $invoice): SalesInvoice
    {
        return DB::transaction(function () use ($invoice) {
            $invoice = $this->lockInStatus($invoice, DocumentStatus::Draft);
            $invoice->load(['items', 'payments', 'tradeIn', 'installmentPlan']);
            $reservation = $invoice->reservation_id ? Reservation::query()->lockForUpdate()->findOrFail($invoice->reservation_id) : null;

            $vehicles = Vehicle::query()->lockForUpdate()->findMany($invoice->items->pluck('vehicle_id'))->keyBy('id');
            $this->assertSellable($invoice, $vehicles->all(), $reservation);

            $tradeInVehicle = $invoice->tradeIn ? Vehicle::query()->lockForUpdate()->findOrFail($invoice->tradeIn->vehicle_id) : null;
            if ($tradeInVehicle?->status->isInStock()) {
                throw BusinessRuleException::make('purchases.errors.vin_in_stock', ['vin' => $tradeInVehicle->vin]);
            }

            // Re-check the deposit credit as it stands now (it may have been used or refunded since the draft).
            $deposit = $this->saveSales->depositToApply($invoice->deposit_applied, $invoice->party_id, $invoice->currency_id);
            $terms = new SalesTerms(
                $invoice->payment_type,
                Money::of($invoice->subtotal),
                Money::of($invoice->discount),
                Money::of($invoice->trade_in_value),
                $deposit,
                $invoice->payments->map(fn ($p) => Money::of($p->amount))->all(),
                $invoice->installmentPlan ? Money::of($invoice->installmentPlan->down_payment) : null,
                $invoice->installmentPlan?->months,
            );
            $terms->validate();
            if ($invoice->installmentPlan && ! $terms->financed()->isEqualTo($invoice->installmentPlan->financed_amount)) {
                throw BusinessRuleException::make('sales.errors.plan_outdated');
            }

            $number = $this->sequences->next(SequenceType::SalesInvoice, $invoice->date);
            $entry = $this->posting->post($this->entry($invoice, $number, $vehicles->all(), $deposit));
            $this->markPosted($invoice, $number, $entry);
            $invoice->update(['deposit_applied' => (string) $deposit]);

            foreach ($invoice->items as $item) {
                $vehicle = $vehicles[$item->vehicle_id];
                $commission = $this->commissions->forVehicle(Money::of($item->net_base));

                $item->update(['cost_snapshot' => $vehicle->total_cost, 'commission' => (string) $commission]);
                if ($commission->isPositive()) {
                    Commission::query()->create([
                        'sales_invoice_id' => $invoice->id,
                        'sales_invoice_item_id' => $item->id,
                        'user_id' => $invoice->salesperson_id,
                        'amount' => (string) $commission,
                        'status' => CommissionStatus::Accrued,
                    ]);
                }

                $vehicle->forceFill(['sale_invoice_id' => $invoice->id, 'sold_at' => $invoice->date])->save();
                $this->vehicles->transition($vehicle, VehicleStatus::Sold, $invoice);
            }

            if ($tradeInVehicle !== null) {
                $tradeInVehicle->forceFill([
                    'purchase_cost' => $invoice->tradeIn->value_base,
                    'extra_cost' => '0',
                    'total_cost' => $invoice->tradeIn->value_base,
                    'purchase_invoice_id' => null,
                    'sale_invoice_id' => null,
                    'received_at' => $invoice->date,
                    'sold_at' => null,
                ])->save();
                $this->vehicles->transition($tradeInVehicle, $invoice->tradeIn->entry_status, $invoice, __('sales.trade_in_note', ['number' => $number]));
            }

            $reservation?->update(['status' => ReservationStatus::Converted]);

            foreach ($invoice->payments as $payment) {
                $voucher = $this->saveVoucher->handle([
                    'type' => VoucherType::Receipt->value,
                    'date' => $invoice->date->toDateString(),
                    'party_id' => $invoice->party_id,
                    'cashbox_id' => $payment->cashbox_id,
                    'account_id' => $this->accounts->idFor(AccountRole::Receivables),
                    'amount' => $payment->amount,
                    'rate' => $invoice->rate,
                    'description' => __('sales.payment_description', ['number' => $number]),
                    'reference_type' => $invoice->getMorphClass(),
                    'reference_id' => $invoice->id,
                ], branchId: $invoice->branch_id);
                $this->postVoucher->handle($voucher);
                $payment->update(['voucher_id' => $voucher->id]);
            }

            return $invoice->refresh()->load(['items.vehicle', 'tradeIn.vehicle', 'installmentPlan.installments', 'payments.voucher']);
        });
    }

    /**
     * @param  array<int, Vehicle>  $vehicles
     */
    private function assertSellable(SalesInvoice $invoice, array $vehicles, ?Reservation $reservation): void
    {
        if ($invoice->items->isEmpty()) {
            throw BusinessRuleException::make('sales.errors.no_items');
        }
        if ($reservation !== null && ($reservation->status !== ReservationStatus::Active || $reservation->party_id !== $invoice->party_id)) {
            throw BusinessRuleException::make('sales.errors.reservation');
        }

        foreach ($invoice->items as $item) {
            $vehicle = $vehicles[$item->vehicle_id];
            $reservedForThisSale = $vehicle->status === VehicleStatus::Reserved && $reservation?->vehicle_id === $vehicle->id;

            if ($vehicle->status !== VehicleStatus::Available && ! $reservedForThisSale) {
                throw BusinessRuleException::make('sales.errors.not_sellable', ['vin' => $vehicle->vin, 'status' => $vehicle->status->label()]);
            }
        }
    }

    /**
     * @param  array<int, Vehicle>  $vehicles
     */
    private function entry(SalesInvoice $invoice, string $number, array $vehicles, BigDecimal $deposit): JournalBuilder
    {
        $receivables = $this->accounts->idFor(AccountRole::Receivables);
        $builder = JournalBuilder::make($invoice->date, __('sales.entry', ['number' => $number]))
            ->source($invoice)
            ->branch($invoice->branch_id);

        $commissionTotal = Money::zero();

        foreach ($invoice->items as $item) {
            $vehicle = $vehicles[$item->vehicle_id];

            $builder
                ->debit($receivables, $item->net, $invoice->currency_id, $invoice->rate, partyId: $invoice->party_id, vehicleId: $vehicle->id, memo: $vehicle->vin)
                ->credit($this->accounts->idFor(AccountRole::VehicleSales), $item->net, $invoice->currency_id, $invoice->rate, vehicleId: $vehicle->id, memo: $vehicle->vin);

            if (Money::of($vehicle->total_cost)->isPositive()) {
                $builder
                    ->debit($this->accounts->idFor(AccountRole::CostOfSales), $vehicle->total_cost, vehicleId: $vehicle->id, memo: $vehicle->vin)
                    ->credit($this->accounts->idFor($vehicle->status->stockRole()), $vehicle->total_cost, vehicleId: $vehicle->id, memo: $vehicle->vin);
            }

            $commissionTotal = $commissionTotal->plus($this->commissions->forVehicle(Money::of($item->net_base)));
        }

        // The customer's deposit credit, at the rate it is carried, moves to the receivable.
        if ($deposit->isPositive()) {
            $depositRate = $this->deposits->carryingRate($invoice->party_id, $invoice->currency_id);
            $builder
                ->debit($this->accounts->idFor(AccountRole::CustomerDeposits), $deposit, $invoice->currency_id, $depositRate, partyId: $invoice->party_id, memo: __('sales.deposit'))
                ->credit($receivables, $deposit, $invoice->currency_id, $depositRate, partyId: $invoice->party_id, memo: __('sales.deposit'));
        }

        if ($invoice->tradeIn !== null) {
            $builder
                ->debit($this->accounts->idFor(AccountRole::Inventory), $invoice->tradeIn->value_base, vehicleId: $invoice->tradeIn->vehicle_id, memo: __('sales.trade_in'))
                ->credit($receivables, $invoice->tradeIn->value, $invoice->currency_id, $invoice->rate, partyId: $invoice->party_id, vehicleId: $invoice->tradeIn->vehicle_id, memo: __('sales.trade_in'));
        }

        if ($commissionTotal->isPositive()) {
            $builder
                ->debit($this->accounts->idFor(AccountRole::CommissionExpense), $commissionTotal, memo: __('sales.commission'))
                ->credit($this->accounts->idFor(AccountRole::AccruedCommissions), $commissionTotal, memo: __('sales.commission'));
        }

        return $builder;
    }
}
