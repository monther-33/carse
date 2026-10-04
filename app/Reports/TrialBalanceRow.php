<?php

namespace App\Reports;

use Brick\Math\BigDecimal;

final class TrialBalanceRow
{
    public function __construct(
        public readonly int $accountId,
        public readonly string $code,
        public readonly string $name,
        public readonly BigDecimal $debit,
        public readonly BigDecimal $credit,
    ) {}

    /** debit − credit */
    public function balance(): BigDecimal
    {
        return $this->debit->minus($this->credit);
    }
}
