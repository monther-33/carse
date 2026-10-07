<?php

namespace App\Actions\Purchases;

use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\ReturnType;
use App\Enums\SequenceType;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\ReturnDocument;
use App\Models\Vehicle;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Numbering\SequenceService;
use App\Services\Ownership\PartnershipPurchase;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Returns one vehicle of a posted purchase invoice to its supplier:
 *   Dr supplier payables (item net, invoice currency and rate) / Cr stock account (item cost).
 * A car bought with partners: Cr stock the showroom's part and Cr each partner's contribution
 * back on their account.
 * Any refund then comes through a receipt voucher from the supplier.
 *
 * The vehicle must be in stock, not reserved, and carry no capitalised expenses
 * (cancel those first), so the credit equals exactly the cost that was debited.
 */
class ReturnPurchaseItem
{
    public function __construct(
        private readonly PostingService $posting,
        private readonly AccountResolver $accounts,
        private readonly SequenceService $sequences,
        private readonly VehicleStateMachine $vehicles,
        private readonly PartnershipPurchase $partnership,
    ) {}

    public function handle(PurchaseInvoiceItem $item, string $reason, ?string $date = null): ReturnDocument
    {
        return DB::transaction(function () use ($item, $reason, $date) {
            $item = PurchaseInvoiceItem::query()->lockForUpdate()->findOrFail($item->id);
            $invoice = PurchaseInvoice::query()->lockForUpdate()->findOrFail($item->invoice_id);
            $vehicle = Vehicle::query()->lockForUpdate()->findOrFail($item->vehicle_id);
            $date = CarbonImmutable::parse($date ?? now());

            if ($invoice->status !== DocumentStatus::Posted) {
                throw BusinessRuleException::make('documents.errors.not_posted');
            }
            if ($item->return_id !== null) {
                throw BusinessRuleException::make('purchases.errors.already_returned', ['vin' => $vehicle->vin]);
            }
            if (! $vehicle->status->isInStock() || $vehicle->status === VehicleStatus::Reserved || $vehicle->purchase_invoice_id !== $invoice->id) {
                throw BusinessRuleException::make('purchases.errors.not_returnable', ['vin' => $vehicle->vin]);
            }
            if (! Money::of($vehicle->extra_cost)->isZero()) {
                throw BusinessRuleException::make('purchases.errors.has_extra_cost', ['vin' => $vehicle->vin]);
            }

            $number = $this->sequences->next(SequenceType::PurchaseReturn, $date);

            $return = ReturnDocument::query()->create([
                'branch_id' => $invoice->branch_id,
                'type' => ReturnType::Purchase,
                'number' => $number,
                'date' => $date->toDateString(),
                'invoice_type' => $invoice->getMorphClass(),
                'invoice_id' => $invoice->id,
                'vehicle_id' => $vehicle->id,
                'amount' => $item->net,
                'amount_base' => $item->cost_base,
                'reason' => $reason,
                'status' => DocumentStatus::Posted,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            $builder = JournalBuilder::make($date, __('purchases.return_entry', ['number' => $number, 'invoice' => $invoice->number, 'vin' => $vehicle->vin]))
                ->source($return)
                ->branch($invoice->branch_id)
                ->debit($this->accounts->idFor(AccountRole::Payables), $item->net, $invoice->currency_id, $invoice->rate, partyId: $invoice->party_id, vehicleId: $vehicle->id)
                ->credit($this->accounts->idFor($vehicle->status->stockRole()), $vehicle->total_cost, vehicleId: $vehicle->id);
            $this->partnership->creditContributions($builder, $vehicle);
            $entry = $this->posting->post($builder);
            $return->update(['journal_entry_id' => $entry->id]);

            $item->update(['return_id' => $return->id]);
            $this->vehicles->transition($vehicle, VehicleStatus::ReturnedToSupplier, $return, $reason);
            $this->partnership->close($vehicle, $reason);

            return $return;
        });
    }
}
