<?php

namespace App\Services\Accounting;

use App\Models\Account;
use App\Models\Currency;
use App\Models\JournalEntry;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Fluent description of a journal entry. It writes nothing; hand it to PostingService::post().
 *
 * Currency defaults to the base currency. For a foreign currency without an explicit
 * rate, PostingService uses the rate in effect on the entry date.
 */
final class JournalBuilder
{
    public ?Model $source = null;

    public ?int $branchId = null;

    public ?JournalEntry $reverses = null;

    /** @var list<JournalLineData> */
    public array $lines = [];

    private function __construct(
        public readonly CarbonImmutable $date,
        public readonly string $description,
    ) {}

    public static function make(CarbonInterface|string $date, string $description): self
    {
        return new self(CarbonImmutable::parse($date)->startOfDay(), $description);
    }

    public function source(?Model $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function branch(int $branchId): self
    {
        $this->branchId = $branchId;

        return $this;
    }

    public function reversing(JournalEntry $original): self
    {
        $this->reverses = $original;

        return $this;
    }

    /**
     * @param  BigDecimal|string|int  $amount  in the line's currency
     * @param  BigDecimal|string|null  $rate  base units per 1 unit of the currency
     */
    public function debit(
        Account|int $account,
        mixed $amount,
        Currency|int|null $currency = null,
        mixed $rate = null,
        ?int $partyId = null,
        ?int $vehicleId = null,
        ?string $memo = null,
    ): self {
        return $this->line(true, $account, $amount, $currency, $rate, $partyId, $vehicleId, $memo);
    }

    /**
     * @param  BigDecimal|string|int  $amount  in the line's currency
     * @param  BigDecimal|string|null  $rate  base units per 1 unit of the currency
     */
    public function credit(
        Account|int $account,
        mixed $amount,
        Currency|int|null $currency = null,
        mixed $rate = null,
        ?int $partyId = null,
        ?int $vehicleId = null,
        ?string $memo = null,
    ): self {
        return $this->line(false, $account, $amount, $currency, $rate, $partyId, $vehicleId, $memo);
    }

    private function line(
        bool $isDebit,
        Account|int $account,
        mixed $amount,
        Currency|int|null $currency,
        mixed $rate,
        ?int $partyId,
        ?int $vehicleId,
        ?string $memo,
    ): self {
        $this->lines[] = new JournalLineData(
            accountId: $account instanceof Account ? (int) $account->getKey() : $account,
            isDebit: $isDebit,
            amount: Money::of($amount),
            currencyId: $currency instanceof Currency ? (int) $currency->getKey() : $currency,
            rate: $rate === null ? null : Money::rate($rate),
            partyId: $partyId,
            vehicleId: $vehicleId,
            memo: $memo,
        );

        return $this;
    }
}
