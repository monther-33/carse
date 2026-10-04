<?php

namespace App\Actions\Sales;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Actions\Vouchers\CancelVoucher;
use App\Enums\CommissionStatus;
use App\Enums\DocumentStatus;
use App\Enums\InstallmentStatus;
use App\Enums\ReservationStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Commission;
use App\Models\Installment;
use App\Models\JournalEntry;
use App\Models\Reservation;
use App\Models\SalesInvoice;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Services\Accounting\ReversalService;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Cancels a posted sale as if it never happened (full reversing entry). Allowed only while
 * nothing has happened since: no installment collected, no vehicle returned, commissions
 * not paid, and the trade-in car still untouched. Otherwise use a sales return.
 *
 * The receipt vouchers taken at the sale are cancelled too, the cars go back to stock
 * (sold → returned → available), the trade-in car goes back to its owner, and a
 * reservation deposit returns to customer deposits (the customer's credit).
 */
class CancelSalesInvoice
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly ReversalService $reversal,
        private readonly CancelVoucher $cancelVoucher,
        private readonly VehicleStateMachine $vehicles,
    ) {}

    public function handle(SalesInvoice $invoice, string $reason): SalesInvoice
    {
        return DB::transaction(function () use ($invoice, $reason) {
            $invoice = $this->lockInStatus($invoice, DocumentStatus::Posted);
            $invoice->load(['items', 'tradeIn', 'installmentPlan', 'commissions']);

            $this->assertUntouched($invoice);

            foreach (Voucher::query()->whereMorphedTo('reference', $invoice)->posted()->get() as $voucher) {
                $this->cancelVoucher->handle($voucher, $reason);
            }

            $this->reversal->reverse(
                JournalEntry::query()->findOrFail($invoice->journal_entry_id),
                description: __('documents.cancellation_of', ['number' => $invoice->number]),
            );
            $this->markCancelled($invoice, $reason);

            foreach ($invoice->items as $item) {
                $vehicle = Vehicle::query()->lockForUpdate()->findOrFail($item->vehicle_id);
                $vehicle->forceFill(['sale_invoice_id' => null, 'sold_at' => null])->save();
                $this->vehicles->transition($vehicle, VehicleStatus::Returned, $invoice, $reason);
                $this->vehicles->transition($vehicle, VehicleStatus::Available, $invoice, $reason);
            }

            if ($invoice->tradeIn !== null) {
                $this->vehicles->transition(Vehicle::query()->findOrFail($invoice->tradeIn->vehicle_id), VehicleStatus::ReturnedToSupplier, $invoice, $reason);
            }

            if ($invoice->installmentPlan !== null) {
                Installment::query()->where('plan_id', $invoice->installmentPlan->id)->update(['status' => InstallmentStatus::Cancelled]);
            }
            Commission::query()->where('sales_invoice_id', $invoice->id)->update(['status' => CommissionStatus::Cancelled]);

            if ($invoice->reservation_id !== null) {
                Reservation::query()->whereKey($invoice->reservation_id)->update(['status' => ReservationStatus::Cancelled, 'cancel_reason' => $reason]);
            }

            return $invoice;
        });
    }

    private function assertUntouched(SalesInvoice $invoice): void
    {
        if ($invoice->items->contains(fn ($item) => $item->return_id !== null)) {
            throw BusinessRuleException::make('sales.errors.cannot_cancel_returned');
        }

        if ($invoice->installmentPlan !== null
            && Voucher::query()->whereMorphedTo('reference', $invoice->installmentPlan)->posted()->exists()) {
            throw BusinessRuleException::make('sales.errors.cannot_cancel_collected');
        }

        if ($invoice->commissions->contains(fn (Commission $c) => $c->status === CommissionStatus::Paid)) {
            throw BusinessRuleException::make('sales.errors.cannot_cancel_commission_paid');
        }

        foreach ($invoice->items as $item) {
            $vehicle = Vehicle::query()->lockForUpdate()->findOrFail($item->vehicle_id);
            if ($vehicle->status !== VehicleStatus::Sold || $vehicle->sale_invoice_id !== $invoice->id) {
                throw BusinessRuleException::make('sales.errors.cannot_cancel_vehicle', ['vin' => $vehicle->vin]);
            }
        }

        if ($invoice->tradeIn !== null) {
            $car = Vehicle::query()->lockForUpdate()->findOrFail($invoice->tradeIn->vehicle_id);
            $untouched = in_array($car->status, [VehicleStatus::InPreparation, VehicleStatus::Available], true)
                && Money::of($car->extra_cost)->isZero();

            if (! $untouched) {
                throw BusinessRuleException::make('sales.errors.cannot_cancel_trade_in', ['vin' => $car->vin]);
            }
        }
    }
}
