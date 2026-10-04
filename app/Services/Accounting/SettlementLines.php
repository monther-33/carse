<?php

namespace App\Services\Accounting;

use App\Enums\AccountRole;
use App\Services\Currency\ExchangeRateService;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * Builds the party side of a cash settlement (receipt or payment) on a control account.
 *
 * In the base currency this is a single line. In a foreign currency the open balance is
 * settled at the rate it is carried at (the invoice rate when the voucher references an
 * invoice, otherwise the party's average carrying rate) and any excess at the voucher
 * rate; the base-currency difference against the cash line goes to "currency differences".
 */
class SettlementLines
{
    public function __construct(
        private readonly PartyBalanceService $balances,
        private readonly AccountResolver $accounts,
        private readonly ExchangeRateService $rates,
    ) {}

    /**
     * @param  bool  $partyIsDebit  true for payments (Dr payables), false for receipts (Cr receivables)
     * @param  BigDecimal|null  $settleRate  force the rate of the open part (e.g. the invoice rate)
     */
    public function add(
        JournalBuilder $builder,
        bool $partyIsDebit,
        int $accountId,
        int $partyId,
        int $currencyId,
        BigDecimal $amount,
        BigDecimal $voucherRate,
        ?BigDecimal $settleRate = null,
        ?string $memo = null,
    ): void {
        $side = $partyIsDebit ? 'debit' : 'credit';

        if ($this->rates->isBase($currencyId)) {
            $builder->{$side}($accountId, $amount, $currencyId, '1', partyId: $partyId, memo: $memo);

            return;
        }

        // Open balance in the direction this settlement reduces it.
        $open = $this->balances->balance($partyId, $accountId, $currencyId)['amount'];
        $open = $partyIsDebit ? $open->negated() : $open; // payables are credit balances
        $open = $open->isPositive() ? $open : Money::zero();

        $carried = $settleRate ?? $this->balances->carryingRate($partyId, $accountId, $currencyId) ?? $voucherRate;
        $settled = $amount->isGreaterThan($open) ? $open : $amount;
        $excess = $amount->minus($settled);

        if ($settled->isPositive()) {
            $builder->{$side}($accountId, $settled, $currencyId, $carried, partyId: $partyId, memo: $memo);
        }
        if ($excess->isPositive()) {
            $builder->{$side}($accountId, $excess, $currencyId, $voucherRate, partyId: $partyId, memo: $memo);
        }

        // Cash side is booked at the voucher rate; balance the base difference.
        $cashBase = Money::toBase($amount, $voucherRate);
        $partyBase = Money::toBase($settled, $carried)->plus(Money::toBase($excess, $voucherRate));
        $difference = $cashBase->minus($partyBase);

        if (! $difference->isZero()) {
            $fx = $this->accounts->idFor(AccountRole::FxDifferences);
            // Payment: paying more base than carried is a loss (debit). Receipt: receiving more is a gain (credit).
            $debitFx = $partyIsDebit ? $difference->isPositive() : $difference->isNegative();
            $builder->{$debitFx ? 'debit' : 'credit'}($fx, $difference->abs(), memo: __('accounting.fx_difference'));
        }
    }
}
