<?php

use App\Support\Money;
use Brick\Math\BigDecimal;

test('amounts are normalised to 3 decimals with half-up rounding', function () {
    expect((string) Money::of('10'))->toBe('10.000')
        ->and((string) Money::of('10.0005'))->toBe('10.001')
        ->and((string) Money::of('10.0004'))->toBe('10.000')
        ->and((string) Money::of(null))->toBe('0.000')
        ->and((string) Money::of(''))->toBe('0.000');
});

test('floats are rejected', function (Closure $call) {
    expect($call)->toThrow(InvalidArgumentException::class);
})->with([
    'of' => fn () => Money::of(0.1),
    'toBase amount' => fn () => Money::toBase(0.1, '1'),
    'toBase rate' => fn () => Money::toBase('1', 4.85),
    'sum' => fn () => Money::sum(['1', 0.2]),
]);

test('foreign amounts convert to base with 3 decimals', function () {
    expect((string) Money::toBase('1000.00', '4.850000'))->toBe('4850.000')
        ->and((string) Money::toBase('0.10', '4.855555'))->toBe('0.486')
        ->and((string) Money::toBase('123.45', '1'))->toBe('123.450');
});

test('sums are exact where floats are not', function () {
    // 0.1 + 0.2 != 0.3 in floating point
    expect(Money::sum(['0.1', '0.2'])->isEqualTo('0.3'))->toBeTrue()
        ->and((string) Money::sum([]))->toBe('0.000')
        ->and((string) Money::sum([BigDecimal::of('1.111'), '2.222', 3]))->toBe('6.333');
});

test('format uses thousands separators and fixed decimals', function () {
    expect(Money::format('1234567.5'))->toBe('1,234,567.500')
        ->and(Money::format('-1500'))->toBe('-1,500.000')
        ->and(Money::format('0'))->toBe('0.000')
        ->and(Money::format('999.9999'))->toBe('1,000.000')
        ->and(Money::format('4.85', 6))->toBe('4.850000')
        ->and(Money::format('12', 0))->toBe('12');
});
