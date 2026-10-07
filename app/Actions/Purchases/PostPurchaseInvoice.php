<?php

namespace App\Actions\Purchases;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Actions\Vouchers\PostVoucher;
use App\Actions\Vouchers\SaveVoucher;
use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\SequenceType;
use App\Enums\VoucherType;
use App\Exceptions\BusinessRuleException;
use App\Models\PurchaseInvoice;
use App\Models\Vehicle;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Numbering\SequenceService;
use App\Services\Ownership\PartnershipPurchase;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Approves a draft purchase invoice:
 *  - one entry: per vehicle Dr stock account (inventory, or "in transit" for cars still on
 *    the way) / Cr supplier payables in the invoice currency;
 *  - vehicles enter stock with their purchase cost;
 *  - a car bought with partners: each partner's contribution is moved off the car onto their
 *    account (PartnershipPurchase), so the car carries the showroom's part only;
 *  - the paid part becomes an automatic, posted payment voucher settled at the invoice rate.
 */
class PostPurchaseInvoice
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly PostingService $posting,
        private readonly AccountResolver $accounts,
        private readonly SequenceService $sequences,
        private readonly VehicleStateMachine $vehicles,
        private readonly SaveVoucher $saveVoucher,
        private readonly PostVoucher $postVoucher,
        private readonly PartnershipPurchase $partnership,
    ) {}

    public function handle(PurchaseInvoice $invoice): PurchaseInvoice
    {
        return DB::transaction(function () use ($invoice) {
            $invoice = $this->lockInStatus($invoice, DocumentStatus::Draft);
            $invoice->load('items');

            if ($invoice->items->isEmpty()) {
                throw BusinessRuleException::make('purchases.errors.no_items');
            }

            $payables = $this->accounts->idFor(AccountRole::Payables);
            $number = $this->sequences->next(SequenceType::PurchaseInvoice, $invoice->date);
            $builder = JournalBuilder::make($invoice->date, __('purchases.entry', ['number' => $number]))
                ->source($invoice)
                ->branch($invoice->branch_id);

            $vehicles = Vehicle::query()->lockForUpdate()->findMany($invoice->items->pluck('vehicle_id'))->keyBy('id');
            $parts = $invoice->items->mapWithKeys(fn ($item) => [$item->id => $this->partnership->parts($item)])->all();

            foreach ($invoice->items as $item) {
                $vehicle = $vehicles[$item->vehicle_id];
                if ($vehicle->status->isInStock()) {
                    throw BusinessRuleException::make('purchases.errors.vin_in_stock', ['vin' => $vehicle->vin]);
                }

                $builder
                    ->debit($this->accounts->idFor($item->entry_status->stockRole()), $item->cost_base, vehicleId: $vehicle->id, memo: $vehicle->vin)
                    ->credit($payables, $item->net, $invoice->currency_id, $invoice->rate, partyId: $invoice->party_id, vehicleId: $vehicle->id, memo: $vehicle->vin);

                if ($parts[$item->id] !== null) {
                    $this->partnership->addLines($builder, $this->accounts->idFor($item->entry_status->stockRole()), $vehicle, $parts[$item->id]);
                }
            }

            $entry = $this->posting->post($builder);
            $this->markPosted($invoice, $number, $entry);

            foreach ($invoice->items as $item) {
                $vehicle = $vehicles[$item->vehicle_id];
                $cost = $parts[$item->id]['showroom'] ?? Money::of($item->cost_base);
                $vehicle->forceFill([
                    'purchase_cost' => (string) $cost,
                    'extra_cost' => '0',
                    'total_cost' => (string) $cost,
                    'purchase_invoice_id' => $invoice->id,
                    'sale_invoice_id' => null,
                    'ownership_id' => null,
                    'received_at' => $invoice->date,
                    'sold_at' => null,
                ])->save();

                if ($parts[$item->id] !== null) {
                    $this->partnership->open($invoice, $item, $vehicle, $parts[$item->id]);
                }
                $this->vehicles->transition($vehicle, $item->entry_status, $invoice);
            }

            if (Money::of($invoice->paid)->isPositive()) {
                $this->payOnPosting($invoice, $payables);
            }

            return $invoice->refresh()->load('items.vehicle');
        });
    }

    private function payOnPosting(PurchaseInvoice $invoice, int $payables): void
    {
        $voucher = $this->saveVoucher->handle([
            'type' => VoucherType::Payment->value,
            'date' => $invoice->date->toDateString(),
            'party_id' => $invoice->party_id,
            'cashbox_id' => $invoice->cashbox_id,
            'account_id' => $payables,
            'amount' => $invoice->paid,
            'rate' => $invoice->rate,
            'description' => __('purchases.auto_payment', ['number' => $invoice->number]),
            'reference_type' => $invoice->getMorphClass(),
            'reference_id' => $invoice->id,
        ], branchId: $invoice->branch_id);

        $this->postVoucher->handle($voucher);
    }
}
