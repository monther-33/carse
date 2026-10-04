<?php

namespace App\Services\Accounting;

use Brick\Math\BigDecimal;

/**
 * One requested journal line, before PostingService validates and resolves it.
 */
final class JournalLineData
{
    public function __construct(
        public readonly int $accountId,
        public readonly bool $isDebit,
        public readonly BigDecimal $amount,
        public readonly ?int $currencyId = null,
        public readonly ?BigDecimal $rate = null,
        public readonly ?int $partyId = null,
        public readonly ?int $vehicleId = null,
        public readonly ?string $memo = null,
    ) {}
}
