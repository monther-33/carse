<?php

use App\Exceptions\MissingExchangeRateException;
use App\Models\ExchangeRate;
use App\Services\Currency\ExchangeRateService;
use Carbon\CarbonImmutable;

beforeEach(function () {
    $this->rates = app(ExchangeRateService::class);
});

test('the base currency is LYD with a fixed rate of 1', function () {
    expect($this->rates->baseCurrency()->code)->toBe('LYD')
        ->and((string) $this->rates->rateFor(lyd(), CarbonImmutable::parse('2001-01-01')))->toBe('1.000000');
});

test('the latest rate on or before the date applies', function () {
    $this->rates->setRate(usd(), CarbonImmutable::parse('2026-01-01'), '4.800000');
    $this->rates->setRate(usd(), CarbonImmutable::parse('2026-02-01'), '4.900000');

    expect((string) $this->rates->rateFor(usd(), CarbonImmutable::parse('2026-01-31')))->toBe('4.800000')
        ->and((string) $this->rates->rateFor(usd(), CarbonImmutable::parse('2026-02-01')))->toBe('4.900000')
        ->and((string) $this->rates->rateFor(usd()->id, CarbonImmutable::parse('2026-06-30')))->toBe('4.900000');
});

test('a missing rate throws instead of guessing', function () {
    $this->rates->setRate(usd(), CarbonImmutable::parse('2026-02-01'), '4.900000');

    $this->rates->rateFor(usd(), CarbonImmutable::parse('2026-01-15'));
})->throws(MissingExchangeRateException::class);

test('setting a rate for the same day replaces it', function () {
    $day = CarbonImmutable::parse('2026-03-10');
    $this->rates->setRate(usd(), $day, '4.850000');
    $this->rates->setRate(usd(), $day, '4.860000');

    expect(ExchangeRate::query()->where('currency_id', usd()->id)->count())->toBe(1)
        ->and((string) $this->rates->rateFor(usd(), $day))->toBe('4.860000');
});

test('the base currency rate cannot be set and rates must be positive', function () {
    expect(fn () => $this->rates->setRate(lyd(), today(), '2'))->toThrow(InvalidArgumentException::class)
        ->and(fn () => $this->rates->setRate(usd(), today(), '0'))->toThrow(InvalidArgumentException::class);
});

test('conversion to base rounds half-up to 3 decimals', function () {
    expect((string) $this->rates->toBase('333.33', '4.855555'))->toBe('1618.502')
        ->and((string) $this->rates->toBase('1', '1.000500'))->toBe('1.001')
        ->and((string) $this->rates->toBase('1', '1.000499'))->toBe('1.000');
});
