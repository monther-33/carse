<?php

namespace App\Support;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use InvalidArgumentException;

/**
 * Arabic amount in words for printed documents ("التفقيط"), e.g.
 *   1250.500 LYD → "فقط ألف ومائتان وخمسون دينارًا وخمسمائة درهم لا غير"
 *
 * Counted-noun agreement (all units here are masculine):
 *   1 → "دينار واحد", 2 → "ديناران", 3–10 → "ثلاثة دنانير", 11–99 → "أحد عشر دينارًا",
 *   otherwise the singular ("مائة دينار"), with construct forms before a noun ("مائتا", "ألفا").
 */
final class Tafqeet
{
    /** code => [major forms, minor forms, minor units per major] ; forms = [singular, dual, plural, accusative] */
    private const CURRENCIES = [
        'LYD' => [['دينار', 'ديناران', 'دنانير', 'دينارًا'], ['درهم', 'درهمان', 'دراهم', 'درهمًا'], 1000],
        'USD' => [['دولار', 'دولاران', 'دولارات', 'دولارًا'], ['سنت', 'سنتان', 'سنتات', 'سنتًا'], 100],
    ];

    private const SCALES = [
        1_000_000_000 => ['مليار', 'ملياران', 'مليارات', 'مليارًا', 'مليارا'],
        1_000_000 => ['مليون', 'مليونان', 'ملايين', 'مليونًا', 'مليونا'],
        1_000 => ['ألف', 'ألفان', 'آلاف', 'ألفًا', 'ألفا'],
    ];

    private const ONES = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة', 'عشرة'];

    private const TEENS = [11 => 'أحد عشر', 12 => 'اثنا عشر', 13 => 'ثلاثة عشر', 14 => 'أربعة عشر', 15 => 'خمسة عشر',
        16 => 'ستة عشر', 17 => 'سبعة عشر', 18 => 'ثمانية عشر', 19 => 'تسعة عشر'];

    private const TENS = [2 => 'عشرون', 3 => 'ثلاثون', 4 => 'أربعون', 5 => 'خمسون', 6 => 'ستون', 7 => 'سبعون', 8 => 'ثمانون', 9 => 'تسعون'];

    private const HUNDREDS = [1 => 'مائة', 2 => 'مائتان', 3 => 'ثلاثمائة', 4 => 'أربعمائة', 5 => 'خمسمائة',
        6 => 'ستمائة', 7 => 'سبعمائة', 8 => 'ثمانمائة', 9 => 'تسعمائة'];

    /**
     * @param  BigDecimal|string|int  $amount
     */
    public static function amount(mixed $amount, string $currencyCode = 'LYD'): string
    {
        if (! isset(self::CURRENCIES[$currencyCode])) {
            throw new InvalidArgumentException("No Arabic wording for currency {$currencyCode}.");
        }

        [$major, $minor, $perMajor] = self::CURRENCIES[$currencyCode];
        $decimals = (int) log10($perMajor);
        $value = Money::of($amount)->abs()->toScale($decimals, RoundingMode::HalfUp);

        [$whole, $fraction] = array_pad(explode('.', (string) $value), 2, '0');
        $whole = (int) $whole;
        $fraction = (int) str_pad($fraction, $decimals, '0');

        $parts = [];
        if ($whole > 0) {
            $parts[] = self::counted($whole, $major);
        }
        if ($fraction > 0) {
            $parts[] = self::counted($fraction, $minor);
        }

        if ($parts === []) {
            return 'صفر '.$major[0];
        }

        return 'فقط '.implode(' و', $parts).' لا غير';
    }

    /**
     * Number + counted noun with the right agreement.
     *
     * @param  array{0: string, 1: string, 2: string, 3: string}  $noun
     */
    public static function counted(int $n, array $noun): string
    {
        if ($n === 1) {
            return $noun[0].' واحد';
        }
        if ($n === 2) {
            return $noun[1];
        }

        $lastTwo = $n % 100;

        return match (true) {
            $lastTwo >= 3 && $lastTwo <= 10 => self::number($n).' '.$noun[2],
            $lastTwo >= 11 => self::number($n).' '.$noun[3],
            default => self::number($n, beforeNoun: true).' '.$noun[0],
        };
    }

    /**
     * Cardinal number in words. $beforeNoun uses construct forms for the final part
     * ("مائتا", "ألفا", "خمسة عشر ألف") because a counted noun follows directly.
     */
    public static function number(int $n, bool $beforeNoun = false): string
    {
        if ($n < 0) {
            throw new InvalidArgumentException('Negative numbers are not worded.');
        }
        if ($n === 0) {
            return 'صفر';
        }

        $parts = [];
        $rest = $n;

        foreach (self::SCALES as $scale => $forms) {
            $count = intdiv($rest, $scale);
            $rest %= $scale;

            if ($count > 0) {
                $parts[] = self::scaled($count, $forms, $beforeNoun && $rest === 0);
            }
        }

        if ($rest > 0) {
            $parts[] = self::group($rest, $beforeNoun);
        }

        return implode(' و', $parts);
    }

    /**
     * @param  array{0: string, 1: string, 2: string, 3: string, 4: string}  $forms  singular, dual, plural, accusative, dual construct
     */
    private static function scaled(int $count, array $forms, bool $beforeNoun): string
    {
        if ($count === 1) {
            return $forms[0];
        }
        if ($count === 2) {
            return $beforeNoun ? $forms[4] : $forms[1];
        }

        $lastTwo = $count % 100;

        return match (true) {
            $lastTwo >= 3 && $lastTwo <= 10 => self::group($count).' '.$forms[2],
            $lastTwo >= 11 => self::group($count).' '.($beforeNoun ? $forms[0] : $forms[3]),
            default => self::group($count, beforeNoun: true).' '.$forms[0],
        };
    }

    /** 1–999 */
    private static function group(int $n, bool $beforeNoun = false): string
    {
        $hundreds = intdiv($n, 100);
        $rest = $n % 100;
        $parts = [];

        if ($hundreds > 0) {
            $parts[] = $hundreds === 2 && $rest === 0 && $beforeNoun ? 'مائتا' : self::HUNDREDS[$hundreds];
        }

        if ($rest > 0) {
            $parts[] = match (true) {
                $rest <= 10 => self::ONES[$rest],
                $rest < 20 => self::TEENS[$rest],
                default => ($rest % 10 > 0 ? self::ONES[$rest % 10].' و' : '').self::TENS[intdiv($rest, 10)],
            };
        }

        return implode(' و', $parts);
    }
}
