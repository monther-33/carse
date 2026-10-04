<?php

namespace App\Support;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use InvalidArgumentException;

/**
 * All money math goes through here. Floats are rejected at runtime: PHP's coercive
 * typing would otherwise silently turn 0.1 into "0.1" through a string parameter.
 */
final class Money
{
    public const SCALE = 3;

    public const RATE_SCALE = 6;

    /**
     * Parse an amount and normalise it to 3 decimals (HALF_UP).
     *
     * @param  BigDecimal|string|int|null  $value
     */
    public static function of(mixed $value): BigDecimal
    {
        if ($value === null || $value === '') {
            return self::zero();
        }

        return self::decimal($value)->toScale(self::SCALE, RoundingMode::HalfUp);
    }

    /**
     * @param  BigDecimal|string|int  $value
     */
    public static function rate(mixed $value): BigDecimal
    {
        return self::decimal($value)->toScale(self::RATE_SCALE, RoundingMode::HalfUp);
    }

    public static function zero(): BigDecimal
    {
        return BigDecimal::zero()->toScale(self::SCALE);
    }

    /**
     * Convert an amount in a foreign currency to the base currency.
     *
     * @param  BigDecimal|string|int  $amount
     * @param  BigDecimal|string|int  $rate
     */
    public static function toBase(mixed $amount, mixed $rate): BigDecimal
    {
        return self::decimal($amount)
            ->multipliedBy(self::decimal($rate))
            ->toScale(self::SCALE, RoundingMode::HalfUp);
    }

    /**
     * @param  iterable<BigDecimal|string|int>  $values
     */
    public static function sum(iterable $values): BigDecimal
    {
        $total = self::zero();

        foreach ($values as $value) {
            $total = $total->plus(self::decimal($value));
        }

        return $total->toScale(self::SCALE, RoundingMode::HalfUp);
    }

    /**
     * Split $total across $weights proportionally (3 decimals). The last share takes the
     * rounding remainder, so the shares always add up to $total exactly.
     *
     * @param  BigDecimal|string|int  $total
     * @param  list<BigDecimal|string|int>  $weights
     * @return list<BigDecimal>
     */
    public static function allocate(mixed $total, array $weights): array
    {
        $total = self::of($total);
        $weights = array_map(fn ($w) => self::decimal($w), $weights);
        $sum = array_reduce($weights, fn (BigDecimal $carry, BigDecimal $w) => $carry->plus($w), BigDecimal::zero());

        if ($weights === []) {
            return [];
        }

        $shares = [];
        $allocated = self::zero();
        $last = count($weights) - 1;

        foreach ($weights as $i => $weight) {
            if ($i === $last) {
                $shares[] = $total->minus($allocated);
                break;
            }

            $share = $sum->isZero()
                ? self::zero()
                : $total->multipliedBy($weight)->dividedBy($sum, self::SCALE, RoundingMode::HalfUp);
            $shares[] = $share;
            $allocated = $allocated->plus($share);
        }

        return $shares;
    }

    /**
     * Display format: thousands separator and fixed decimals, e.g. 12,500.000
     *
     * @param  BigDecimal|string|int|null  $value
     */
    public static function format(mixed $value, int $decimals = self::SCALE): string
    {
        $decimal = self::decimal($value ?? 0)->toScale($decimals, RoundingMode::HalfUp);
        $negative = $decimal->isNegative();
        [$int, $fraction] = array_pad(explode('.', (string) $decimal->abs()), 2, '');

        $formatted = strrev(implode(',', str_split(strrev($int), 3)));

        if ($decimals > 0) {
            $formatted .= '.'.$fraction;
        }

        return ($negative ? '-' : '').$formatted;
    }

    private static function decimal(mixed $value): BigDecimal
    {
        if ($value instanceof BigDecimal) {
            return $value;
        }

        if (! is_string($value) && ! is_int($value)) {
            throw new InvalidArgumentException('Money values must be BigDecimal, numeric string or int; got '.get_debug_type($value).'.');
        }

        return BigDecimal::of($value);
    }
}
