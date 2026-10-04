<?php

namespace App\Services\Currency;

use App\Exceptions\MissingExchangeRateException;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Carbon\CarbonInterface;
use InvalidArgumentException;

/**
 * Rates are expressed as base-currency (LYD) units per one unit of the foreign currency.
 * A document uses the latest rate on or before its date unless the user overrides it.
 */
class ExchangeRateService
{
    private ?Currency $base = null;

    public function baseCurrency(): Currency
    {
        return $this->base ??= Currency::query()->where('is_base', true)->firstOrFail();
    }

    public function isBase(Currency|int $currency): bool
    {
        return $this->currencyId($currency) === $this->baseCurrency()->getKey();
    }

    public function rateFor(Currency|int $currency, CarbonInterface $date): BigDecimal
    {
        if ($this->isBase($currency)) {
            return Money::rate(1);
        }

        $rate = ExchangeRate::query()
            ->where('currency_id', $this->currencyId($currency))
            ->whereDate('date', '<=', $date->toDateString())
            ->orderByDesc('date')
            ->value('rate');

        if ($rate === null) {
            $code = $currency instanceof Currency ? $currency->code : (string) Currency::query()->whereKey($currency)->value('code');

            throw new MissingExchangeRateException($code, $date->toDateString());
        }

        return Money::rate((string) $rate);
    }

    public function toBase(BigDecimal|string|int $amount, BigDecimal|string|int $rate): BigDecimal
    {
        return Money::toBase($amount, $rate);
    }

    public function setRate(Currency $currency, CarbonInterface $date, BigDecimal|string $rate): ExchangeRate
    {
        if ($currency->is_base) {
            throw new InvalidArgumentException('The base currency rate is fixed at 1.');
        }

        $rate = Money::rate($rate);

        if (! $rate->isPositive()) {
            throw new InvalidArgumentException('Exchange rate must be positive.');
        }

        return ExchangeRate::query()->updateOrCreate(
            ['currency_id' => $currency->getKey(), 'date' => $date->toDateString()],
            ['rate' => (string) $rate],
        );
    }

    private function currencyId(Currency|int $currency): int
    {
        return $currency instanceof Currency ? (int) $currency->getKey() : $currency;
    }
}
