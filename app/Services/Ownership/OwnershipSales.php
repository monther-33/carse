<?php

namespace App\Services\Ownership;

use App\Enums\AccountRole;
use App\Enums\OwnershipStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Vehicle;
use App\Models\VehicleOwnerDue;
use App\Models\VehicleOwnership;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Carbon\CarbonInterface;

/**
 * The revenue side of selling a vehicle, own or with owners.
 *
 * Own car: Cr vehicle sales with the item's net (invoice currency).
 * Car with owners: the item's net in LYD is split (OwnershipSplit) and credited as
 *   - the showroom's part: consignment commissions (consignment) or vehicle sales (partnership);
 *     a negative part (consignment sold below the net price) is debited instead;
 *   - each owner's part: owners payable, with the owner as party.
 * Each owner's part is also kept as a due (vehicle_owner_dues) for payout timing.
 */
class OwnershipSales
{
    public function __construct(
        private readonly OwnershipSplit $split,
        private readonly OwnerPayouts $payouts,
        private readonly AccountResolver $accounts,
    ) {}

    /** @return array{showroom: BigDecimal, owners: array<int, BigDecimal>}|null null for the showroom's own car */
    public function splitFor(SalesInvoiceItem $item, Vehicle $vehicle): ?array
    {
        $ownership = $vehicle->ownership_id ? VehicleOwnership::query()->with('owners')->findOrFail($vehicle->ownership_id) : null;

        return $ownership === null ? null : $this->split->sale($ownership, Money::of($item->net_base), $item->showroom_commission);
    }

    /**
     * @param  array{showroom: BigDecimal, owners: array<int, BigDecimal>}|null  $split
     */
    public function creditRevenue(JournalBuilder $builder, SalesInvoice $invoice, SalesInvoiceItem $item, Vehicle $vehicle, ?array $split): void
    {
        if ($split === null) {
            $builder->credit($this->accounts->idFor(AccountRole::VehicleSales), $item->net, $invoice->currency_id, $invoice->rate, vehicleId: $vehicle->id, memo: $vehicle->vin);

            return;
        }

        $this->lines($builder, credit: true, vehicle: $vehicle, showroomAccount: $this->showroomAccount($vehicle), showroom: $split['showroom'], owners: $split['owners']);
    }

    /** Mirror of creditRevenue for a returned item, from what was recorded at the sale. */
    public function debitRevenue(JournalBuilder $builder, SalesInvoice $invoice, SalesInvoiceItem $item, Vehicle $vehicle): void
    {
        if ($item->ownership_id === null) {
            $builder->debit($this->accounts->idFor(AccountRole::VehicleSales), $item->net, $invoice->currency_id, $invoice->rate, vehicleId: $vehicle->id);

            return;
        }

        $owners = [];
        foreach (VehicleOwnerDue::query()->open()->where('sales_invoice_item_id', $item->id)->get() as $due) {
            $owners[$due->party_id] = Money::of($due->amount);
        }
        $ownership = VehicleOwnership::query()->findOrFail($item->ownership_id);

        $this->lines($builder, credit: false, vehicle: $vehicle, showroomAccount: $this->revenueAccountFor($ownership), showroom: Money::of($item->showroom_revenue), owners: $owners);
    }

    /**
     * After posting: keep the split on the item, the dues per owner, and mark the ownership sold.
     *
     * @param  array{showroom: BigDecimal, owners: array<int, BigDecimal>}|null  $split
     */
    public function recordSale(SalesInvoiceItem $item, Vehicle $vehicle, ?array $split, CarbonInterface $date): void
    {
        if ($split === null) {
            $item->update(['ownership_id' => null, 'showroom_revenue' => null]);

            return;
        }

        $ownership = VehicleOwnership::query()->lockForUpdate()->findOrFail($vehicle->ownership_id);
        $item->update(['ownership_id' => $ownership->id, 'showroom_revenue' => (string) $split['showroom']]);

        foreach ($split['owners'] as $partyId => $amount) {
            VehicleOwnerDue::query()->create([
                'ownership_id' => $ownership->id,
                'sales_invoice_item_id' => $item->id,
                'party_id' => $partyId,
                'amount' => (string) $amount,
                'payout' => $ownership->payout,
            ]);
        }

        $ownership->update(['status' => OwnershipStatus::Sold, 'ended_at' => $date, 'end_reason' => null]);
    }

    /**
     * The sale of an item is undone (cancellation or return): its dues are reversed and the
     * car is the owners' again in the showroom. Refused when an owner has already been paid
     * more than would be left to them.
     */
    public function undoSale(SalesInvoiceItem $item): void
    {
        if ($item->ownership_id === null) {
            return;
        }

        $dues = VehicleOwnerDue::query()->open()->where('sales_invoice_item_id', $item->id)->with('party')->lockForUpdate()->get();
        foreach ($dues as $due) {
            if ($this->payouts->balance($due->party_id)->isLessThan(Money::of($due->amount))) {
                throw BusinessRuleException::make('ownership.errors.owner_already_paid', ['name' => $due->party->name]);
            }
        }

        VehicleOwnerDue::query()->whereKey($dues->modelKeys())->update(['reversed_at' => now()]);
        VehicleOwnership::query()->whereKey($item->ownership_id)->update(['status' => OwnershipStatus::Active, 'ended_at' => null]);
    }

    private function showroomAccount(Vehicle $vehicle): int
    {
        return $this->revenueAccountFor(VehicleOwnership::query()->findOrFail($vehicle->ownership_id));
    }

    private function revenueAccountFor(VehicleOwnership $ownership): int
    {
        return $this->accounts->idFor($ownership->isConsignment() ? AccountRole::ConsignmentRevenue : AccountRole::VehicleSales);
    }

    /**
     * @param  array<int, BigDecimal>  $owners
     */
    private function lines(JournalBuilder $builder, bool $credit, Vehicle $vehicle, int $showroomAccount, BigDecimal $showroom, array $owners): void
    {
        $this->signed($builder, $credit, $showroomAccount, $showroom, null, $vehicle);

        $payable = $this->accounts->idFor(AccountRole::OwnersPayable);
        foreach ($owners as $partyId => $amount) {
            $this->signed($builder, $credit, $payable, $amount, $partyId, $vehicle);
        }
    }

    /** A positive amount on the requested side; a negative one on the other side. */
    private function signed(JournalBuilder $builder, bool $credit, int $account, BigDecimal $amount, ?int $partyId, Vehicle $vehicle): void
    {
        if ($amount->isZero()) {
            return;
        }
        if ($amount->isNegative()) {
            $credit = ! $credit;
            $amount = $amount->negated();
        }

        $credit
            ? $builder->credit($account, $amount, partyId: $partyId, vehicleId: $vehicle->id, memo: $vehicle->vin)
            : $builder->debit($account, $amount, partyId: $partyId, vehicleId: $vehicle->id, memo: $vehicle->vin);
    }
}
