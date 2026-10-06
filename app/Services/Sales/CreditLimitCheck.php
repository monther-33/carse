<?php

namespace App\Services\Sales;

use App\Enums\AccountRole;
use App\Models\Party;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\PartyBalanceService;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * Credit limit: a WARNING only, never a block (owner's decision). A sale that leaves part of
 * its amount owed warns when the customer's receivable balance plus that unpaid part goes
 * over the customer's credit limit. A limit of zero means "no limit set".
 */
class CreditLimitCheck
{
    public function __construct(
        private readonly PartyBalanceService $balances,
        private readonly AccountResolver $accounts,
    ) {}

    /**
     * @param  BigDecimal  $unpaidBase  the part of this sale left owed, in the base currency
     * @return array{limit: BigDecimal, balance: BigDecimal, after: BigDecimal, over: BigDecimal}|null
     */
    public function warning(Party $party, BigDecimal $unpaidBase): ?array
    {
        $limit = Money::of($party->credit_limit);
        if (! $limit->isPositive() || ! $unpaidBase->isPositive()) {
            return null;
        }

        $balance = $this->balances->baseBalance($party->id, [$this->accounts->idFor(AccountRole::Receivables)]);
        $after = $balance->plus($unpaidBase);

        return $after->isGreaterThan($limit)
            ? ['limit' => $limit, 'balance' => $balance, 'after' => $after, 'over' => $after->minus($limit)]
            : null;
    }
}
