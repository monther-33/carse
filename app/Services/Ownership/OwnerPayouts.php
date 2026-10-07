<?php

namespace App\Services\Ownership;

use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\PayoutTiming;
use App\Enums\VoucherType;
use App\Models\SalesInvoice;
use App\Models\VehicleOwnerDue;
use App\Models\Voucher;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\PartyBalanceService;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

/**
 * What the showroom owes a vehicle owner or partner (LYD), and how much of it may be paid now.
 *
 * The owner's balance on the owners account already nets every sale, expense charged to them
 * and payment. For sales agreed "as the customer pays", the part the customer has not paid
 * yet is held back: due × (1 − collected / invoice total).
 */
class OwnerPayouts
{
    public function __construct(
        private readonly PartyBalanceService $balances,
        private readonly AccountResolver $accounts,
    ) {}

    /** The owner's balance: positive = the showroom owes them, negative = they owe the showroom. */
    public function balance(int $partyId): BigDecimal
    {
        return $this->balances->baseBalance($partyId, [$this->accounts->idFor(AccountRole::OwnersPayable)])->negated();
    }

    /** Held back until customers pay: the unpaid part of "as collected" sales. */
    public function pending(int $partyId): BigDecimal
    {
        $dues = VehicleOwnerDue::query()->open()->where('party_id', $partyId)
            ->where('payout', PayoutTiming::OnCollection)
            ->with('item.invoice')
            ->get();

        return Money::sum($dues->map(function (VehicleOwnerDue $due) {
            $ratio = $this->collectedRatio($due->item->invoice);

            return Money::of($due->amount)->minus(Money::of($due->amount)->multipliedBy($ratio)->toScale(Money::SCALE, RoundingMode::HalfUp));
        })->all());
    }

    /** What may be paid to the owner now (never negative). */
    public function available(int $partyId): BigDecimal
    {
        $available = $this->balance($partyId)->minus($this->pending($partyId));

        return $available->isNegative() ? Money::zero() : $available;
    }

    /**
     * Share of the invoice the customer has settled: trade-in, applied deposit and posted
     * receipts on the invoice or its installment plan, less refunds; between 0 and 1.
     */
    public function collectedRatio(SalesInvoice $invoice): BigDecimal
    {
        $total = Money::of($invoice->total);
        if (! $total->isPositive()) {
            return BigDecimal::one();
        }

        $references = [[$invoice->getMorphClass(), $invoice->id]];
        if ($plan = $invoice->installmentPlan()->first()) {
            $references[] = [$plan->getMorphClass(), $plan->id];
        }

        $vouchers = Voucher::query()->where('status', DocumentStatus::Posted)
            ->where(function ($q) use ($references) {
                foreach ($references as [$type, $id]) {
                    $q->orWhere(fn ($r) => $r->where('reference_type', $type)->where('reference_id', $id));
                }
            })
            ->get(['type', 'amount']);

        $paid = Money::sum($vouchers->where('type', VoucherType::Receipt)->pluck('amount')->all())
            ->minus(Money::sum($vouchers->where('type', VoucherType::Payment)->pluck('amount')->all()));
        $collected = $paid->plus(Money::of($invoice->trade_in_value))->plus(Money::of($invoice->deposit_applied));

        $ratio = $collected->dividedBy($total, 6, RoundingMode::Down);

        return match (true) {
            $ratio->isNegative() => BigDecimal::zero(),
            $ratio->isGreaterThan(1) => BigDecimal::one(),
            default => $ratio,
        };
    }
}
