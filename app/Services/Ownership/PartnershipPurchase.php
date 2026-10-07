<?php

namespace App\Services\Ownership;

use App\Actions\Ownership\OwnerShares;
use App\Enums\AccountRole;
use App\Enums\OwnershipKind;
use App\Enums\OwnershipStatus;
use App\Enums\PayoutTiming;
use App\Exceptions\BusinessRuleException;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Vehicle;
use App\Models\VehicleOwnership;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * A car bought with partners: the showroom buys it from the supplier in full, and each partner
 * owns a share and owes their part of the cost (their contribution).
 *
 * On posting, per partner: Dr owners payable (partner) / Cr the car's stock account with their
 * contribution, so the car carries only the showroom's part of the cost. A partner then pays
 * in with a receipt voucher, or has it netted from their part of the sale.
 */
class PartnershipPurchase
{
    public function __construct(private readonly AccountResolver $accounts) {}

    /**
     * Checks a draft line's partners: their shares leave the showroom a share above zero.
     *
     * @param  array<int, mixed>|null  $partners  list<{party_id, share}>
     * @return array{partners: list<array{party_id: int, share: string}>|null, payout: PayoutTiming|null}
     */
    public function normalize(?array $partners, ?string $payout): array
    {
        $partners = array_values(array_filter($partners ?? [], fn ($p) => ! empty($p['party_id']) || ! empty($p['share'])));
        if ($partners === []) {
            return ['partners' => null, 'payout' => null];
        }

        $sum = $this->sumShares(array_map(fn ($p) => (string) ($p['share'] ?? '0'), $partners));
        if (! $sum->isLessThan(100)) {
            throw BusinessRuleException::make('ownership.errors.partners_total');
        }

        return [
            'partners' => OwnerShares::validate($partners, (string) $sum),
            'payout' => PayoutTiming::tryFrom((string) $payout) ?? PayoutTiming::OnSale,
        ];
    }

    /**
     * The line's cost split into the showroom's part and each partner's contribution (LYD).
     *
     * @return array{showroom_share: string, showroom: BigDecimal, owners: array<int, BigDecimal>}|null
     */
    public function parts(PurchaseInvoiceItem $item): ?array
    {
        if (empty($item->partners)) {
            return null;
        }

        $shares = array_map(fn ($p) => (string) $p['share'], $item->partners);
        $showroomShare = BigDecimal::of(100)->minus($this->sumShares($shares))->toScale(4);
        $amounts = Money::allocate($item->cost_base, [(string) $showroomShare, ...$shares]);

        return [
            'showroom_share' => (string) $showroomShare,
            'showroom' => array_shift($amounts),
            'owners' => array_combine(array_map(fn ($p) => (int) $p['party_id'], $item->partners), $amounts),
        ];
    }

    /**
     * @param  array{showroom_share: string, showroom: BigDecimal, owners: array<int, BigDecimal>}  $parts
     */
    public function addLines(JournalBuilder $builder, int $stockAccount, Vehicle $vehicle, array $parts): void
    {
        $payable = $this->accounts->idFor(AccountRole::OwnersPayable);
        foreach ($parts['owners'] as $partyId => $amount) {
            $builder
                ->debit($payable, $amount, partyId: $partyId, vehicleId: $vehicle->id, memo: __('ownership.contribution', ['vin' => $vehicle->vin]))
                ->credit($stockAccount, $amount, vehicleId: $vehicle->id, memo: __('ownership.contribution', ['vin' => $vehicle->vin]));
        }
    }

    /**
     * @param  array{showroom_share: string, showroom: BigDecimal, owners: array<int, BigDecimal>}  $parts
     */
    public function open(PurchaseInvoice $invoice, PurchaseInvoiceItem $item, Vehicle $vehicle, array $parts): VehicleOwnership
    {
        $ownership = VehicleOwnership::query()->create([
            'branch_id' => $invoice->branch_id,
            'vehicle_id' => $vehicle->id,
            'kind' => OwnershipKind::Partnership,
            'showroom_share' => $parts['showroom_share'],
            'payout' => $item->partner_payout ?? PayoutTiming::OnSale,
            'status' => OwnershipStatus::Active,
            'received_at' => $invoice->date,
            'source_type' => $invoice->getMorphClass(),
            'source_id' => $invoice->id,
        ]);

        foreach ($item->partners ?? [] as $partner) {
            $ownership->owners()->create([
                'party_id' => (int) $partner['party_id'],
                'share' => (string) $partner['share'],
                'contribution' => (string) $parts['owners'][(int) $partner['party_id']],
            ]);
        }

        $vehicle->forceFill(['ownership_id' => $ownership->id])->save();

        return $ownership;
    }

    /**
     * The purchase is undone (cancelled or the car returned to the supplier): Cr each partner's
     * contribution back on their account; the ownership ends.
     */
    public function creditContributions(JournalBuilder $builder, Vehicle $vehicle): void
    {
        $ownership = $vehicle->ownership_id ? VehicleOwnership::query()->with('owners')->findOrFail($vehicle->ownership_id) : null;
        if ($ownership === null || $ownership->isConsignment()) {
            return;
        }

        $payable = $this->accounts->idFor(AccountRole::OwnersPayable);
        foreach ($ownership->owners as $owner) {
            $builder->credit($payable, $owner->contribution, partyId: $owner->party_id, vehicleId: $vehicle->id, memo: __('ownership.contribution', ['vin' => $vehicle->vin]));
        }
    }

    /**
     * Shares are percents with four decimals (Money would round them to three).
     *
     * @param  list<string>  $shares
     */
    private function sumShares(array $shares): BigDecimal
    {
        return array_reduce($shares, fn (BigDecimal $sum, string $s) => $sum->plus(BigDecimal::of($s)), BigDecimal::zero())->toScale(4);
    }

    public function close(Vehicle $vehicle, string $reason): void
    {
        if ($vehicle->ownership_id === null) {
            return;
        }

        VehicleOwnership::query()->whereKey($vehicle->ownership_id)->update([
            'status' => OwnershipStatus::Closed,
            'ended_at' => now(),
            'end_reason' => $reason,
        ]);
        $vehicle->forceFill(['ownership_id' => null])->save();
    }
}
