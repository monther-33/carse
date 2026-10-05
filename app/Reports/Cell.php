<?php

namespace App\Reports;

use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * Formats report cells the same way on screen, in PDF and in Excel.
 */
final class Cell
{
    public static function format(mixed $value, string $type): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return match ($type) {
            'money' => Money::format($value instanceof BigDecimal ? $value : (string) $value),
            'rate' => Money::format((string) $value, Money::RATE_SCALE),
            'int' => number_format((int) $value),
            default => (string) $value,
        };
    }

    /** Excel gets real numbers for numeric columns so the sheet can sum them. */
    public static function excel(mixed $value, string $type): string|int|float|null
    {
        if ($value === null || $value === '') {
            return null;
        }

        return match ($type) {
            'money', 'rate' => (float) (string) ($value instanceof BigDecimal ? $value : Money::of((string) $value)),
            'int' => (int) $value,
            default => (string) $value,
        };
    }

    /**
     * Column totals for columns flagged 'total', over the plain (unstyled) rows.
     *
     * @param  array<string, array{label: string, type: string, total?: bool}>  $columns
     * @param  list<array<string, mixed>>  $rows
     * @return array<string, mixed>|null
     */
    public static function totals(array $columns, array $rows): ?array
    {
        $keys = array_keys(array_filter($columns, fn ($c) => $c['total'] ?? false));
        if ($keys === []) {
            return null;
        }

        $plain = array_filter($rows, fn ($r) => empty($r['_style']));
        $totals = [];
        foreach ($keys as $key) {
            $values = array_values(array_filter(array_column($plain, $key), fn ($v) => $v !== null && $v !== ''));
            $totals[$key] = $columns[$key]['type'] === 'int'
                ? array_sum(array_map('intval', $values))
                : Money::sum(array_map(fn ($v) => $v instanceof BigDecimal ? $v : (string) $v, $values));
        }

        return $totals;
    }
}
