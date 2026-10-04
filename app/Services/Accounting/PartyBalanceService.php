<?php

namespace App\Services\Accounting;

use App\Support\Money;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Support\Facades\DB;

/**
 * Party balances on a control account, computed from journal lines (never stored).
 * Signed as debit − credit: receivables are positive, payables negative.
 */
class PartyBalanceService
{
    /**
     * @return array{amount: BigDecimal, base: BigDecimal} amount in $currencyId, base in LYD
     */
    public function balance(int $partyId, int $accountId, int $currencyId): array
    {
        $row = DB::table('journal_lines')
            ->where('party_id', $partyId)
            ->where('account_id', $accountId)
            ->where('currency_id', $currencyId)
            ->selectRaw('COALESCE(SUM(debit - credit), 0) AS amount, COALESCE(SUM(debit_base - credit_base), 0) AS base')
            ->first();

        return ['amount' => Money::of((string) $row->amount), 'base' => Money::of((string) $row->base)];
    }

    /**
     * Base balance across all currencies, per account.
     *
     * @param  list<int>  $accountIds
     */
    public function baseBalance(int $partyId, array $accountIds): BigDecimal
    {
        $value = DB::table('journal_lines')
            ->where('party_id', $partyId)
            ->whereIn('account_id', $accountIds)
            ->selectRaw('COALESCE(SUM(debit_base - credit_base), 0) AS base')
            ->value('base');

        return Money::of((string) $value);
    }

    /**
     * The average rate at which the party's open foreign balance is carried (base / foreign),
     * or null when nothing is open in that currency.
     */
    public function carryingRate(int $partyId, int $accountId, int $currencyId): ?BigDecimal
    {
        ['amount' => $amount, 'base' => $base] = $this->balance($partyId, $accountId, $currencyId);

        if ($amount->isZero()) {
            return null;
        }

        return $base->dividedBy($amount, Money::RATE_SCALE, RoundingMode::HalfUp)->abs();
    }
}
