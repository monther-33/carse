<?php

namespace App\Imports;

use App\Support\Money;
use BackedEnum;
use Brick\Math\BigDecimal;
use Brick\Math\Exception\MathException;
use Carbon\CarbonImmutable;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Reads typed values out of raw spreadsheet cells. Excel hands numbers over as floats, so
 * they are turned into decimal strings here before they reach Money (which refuses floats).
 * Arabic-Indic digits and thousands separators are accepted.
 */
final class CellParser
{
    public static function text(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }
        if (is_float($value)) {
            $value = self::floatToString($value);
        }

        $text = trim(preg_replace('/\s+/u', ' ', (string) $value) ?? '');

        return $text === '' ? null : $text;
    }

    /** A non-negative amount with at most three decimals. */
    public static function amount(mixed $value, string $column): ?BigDecimal
    {
        $number = self::number($value, $column);
        if ($number === null) {
            return null;
        }
        if ($number->isNegative() || $number->stripTrailingZeros()->getScale() > Money::SCALE) {
            throw new InvalidCell(__('imports.errors.amount', ['column' => self::label($column)]));
        }

        return Money::of($number);
    }

    public static function rate(mixed $value, string $column): ?BigDecimal
    {
        $number = self::number($value, $column);
        if ($number === null) {
            return null;
        }
        if (! $number->isPositive() || $number->stripTrailingZeros()->getScale() > Money::RATE_SCALE) {
            throw new InvalidCell(__('imports.errors.number', ['column' => self::label($column)]));
        }

        return Money::rate($number);
    }

    public static function integer(mixed $value, string $column, int $min, int $max): ?int
    {
        $number = self::number($value, $column);
        if ($number === null) {
            return null;
        }
        if ($number->stripTrailingZeros()->getScale() > 0 || $number->isLessThan($min) || $number->isGreaterThan($max)) {
            throw new InvalidCell(__('imports.errors.integer', ['column' => self::label($column), 'min' => $min, 'max' => $max]));
        }

        return $number->toInt();
    }

    /** Excel date serials, or text as YYYY-MM-DD or DD/MM/YYYY. */
    public static function date(mixed $value, string $column): ?CarbonImmutable
    {
        if (is_int($value) || is_float($value)) {
            return CarbonImmutable::instance(ExcelDate::excelToDateTimeObject($value))->startOfDay();
        }

        $text = self::digits(self::text($value));
        if ($text === null) {
            return null;
        }

        if (preg_match('/^(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})(?:\s.*)?$/', $text, $m)) {
            [, $year, $month, $day] = $m;
        } elseif (preg_match('/^(\d{1,2})[-\/.](\d{1,2})[-\/.](\d{4})(?:\s.*)?$/', $text, $m)) {
            [, $day, $month, $year] = $m;
        }

        if (isset($year, $month, $day) && checkdate((int) $month, (int) $day, (int) $year)) {
            return CarbonImmutable::create((int) $year, (int) $month, (int) $day);
        }

        throw new InvalidCell(__('imports.errors.date', ['column' => self::label($column)]));
    }

    /**
     * Matches the enum value or its Arabic / English label.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enum
     * @param  string  $labels  translation group of the labels, e.g. "enums.fuel_type"
     * @param  list<T>|null  $allowed
     * @return T|null
     */
    public static function enum(string $enum, mixed $value, string $column, string $labels, ?array $allowed = null): ?BackedEnum
    {
        $text = self::text($value);
        if ($text === null) {
            return null;
        }

        $needle = mb_strtolower($text);
        foreach ($allowed ?? $enum::cases() as $case) {
            $names = [(string) $case->value, __("{$labels}.{$case->value}", [], 'ar'), __("{$labels}.{$case->value}", [], 'en')];
            if (in_array($needle, array_map(mb_strtolower(...), $names), true)) {
                return $case;
            }
        }

        $choices = array_map(fn ($case) => __("{$labels}.{$case->value}"), $allowed ?? $enum::cases());

        throw new InvalidCell(__('imports.errors.choice', ['column' => self::label($column), 'choices' => implode('، ', $choices)]));
    }

    public static function label(string $column): string
    {
        return __('imports.columns.'.$column);
    }

    private static function number(mixed $value, string $column): ?BigDecimal
    {
        if (is_int($value)) {
            return BigDecimal::of($value);
        }
        if (is_float($value)) {
            return BigDecimal::of(self::floatToString($value));
        }

        $text = self::digits(self::text($value));
        if ($text === null) {
            return null;
        }

        try {
            return BigDecimal::of(str_replace([',', '٬', ' '], '', $text));
        } catch (MathException) {
            throw new InvalidCell(__('imports.errors.number', ['column' => self::label($column)]));
        }
    }

    /** Arabic-Indic and Persian digits → ASCII; Arabic decimal separator → ".". */
    private static function digits(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        return strtr($text, [
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٫' => '.',
        ]);
    }

    private static function floatToString(float $value): string
    {
        $text = rtrim(rtrim(sprintf('%.6F', $value), '0'), '.');

        return $text === '-0' ? '0' : $text;
    }
}
