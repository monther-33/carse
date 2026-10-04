<?php

namespace App\Actions\Purchases;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Actions\Vouchers\CancelVoucher;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\JournalEntry;
use App\Models\PurchaseInvoice;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Services\Accounting\ReversalService;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Cancels a posted purchase invoice with a full reversing entry, cancels the payment
 * vouchers that reference it, and takes its vehicles out of stock.
 *
 * Only possible while every vehicle is untouched since the purchase: still in stock, not
 * reserved, no capitalised expenses, no return, and still in the stock account it was
 * posted to. Otherwise the reversal would not mirror reality; use a purchase return.
 */
class CancelPurchaseInvoice
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly ReversalService $reversal,
        private readonly CancelVoucher $cancelVoucher,
        private readonly VehicleStateMachine $vehicles,
    ) {}

    public function handle(PurchaseInvoice $invoice, string $reason): PurchaseInvoice
    {
        return DB::transaction(function () use ($invoice, $reason) {
            $invoice = $this->lockInStatus($invoice, DocumentStatus::Posted);
            $invoice->load('items');
            $vehicles = Vehicle::query()->lockForUpdate()->findMany($invoice->items->pluck('vehicle_id'))->keyBy('id');

            foreach ($invoice->items as $item) {
                $vehicle = $vehicles[$item->vehicle_id];
                $untouched = $item->return_id === null
                    && $vehicle->purchase_invoice_id === $invoice->id
                    && $vehicle->status->isInStock()
                    && $vehicle->status !== VehicleStatus::Reserved
                    && Money::of($vehicle->extra_cost)->isZero()
                    && $vehicle->status->stockRole() === $item->entry_status->stockRole();

                if (! $untouched) {
                    throw BusinessRuleException::make('purchases.errors.cannot_cancel', ['vin' => $vehicle->vin]);
                }
            }

            $vouchers = Voucher::query()->whereMorphedTo('reference', $invoice)->where('status', DocumentStatus::Posted)->get();
            foreach ($vouchers as $voucher) {
                $this->cancelVoucher->handle($voucher, $reason);
            }

            $this->reversal->reverse(
                JournalEntry::query()->findOrFail($invoice->journal_entry_id),
                description: __('documents.cancellation_of', ['number' => $invoice->number]),
            );
            $this->markCancelled($invoice, $reason);

            foreach ($invoice->items as $item) {
                $this->vehicles->transition($vehicles[$item->vehicle_id], VehicleStatus::ReturnedToSupplier, $invoice, $reason);
            }

            return $invoice;
        });
    }
}
