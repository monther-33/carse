<?php

namespace App\Reports;

use App\Models\User;
use Carbon\CarbonImmutable;

/**
 * A report = a query object rendered by one generic screen, PDF and Excel export.
 *
 * Columns: key => ['label' => ..., 'type' => text|money|int|date|rate, 'total' => bool].
 * Rows: arrays keyed by column, plus optional '_style' (heading|subtotal|total|muted)
 * and '_indent' (tree depth). Money values are BigDecimal or numeric strings.
 */
abstract class Report
{
    abstract public static function key(): string;

    /** accounting | sales | inventory | admin */
    abstract public function group(): string;

    /** @return list<string> any one of them opens the report */
    abstract public function permissions(): array;

    /**
     * @param  array<string, mixed>  $f
     * @return array<string, array{label: string, type: string, total?: bool}>
     */
    abstract public function columns(User $user, array $f): array;

    /**
     * @param  array<string, mixed>  $f
     * @return list<array<string, mixed>>
     */
    abstract public function rows(User $user, array $f): array;

    /**
     * Filters shown on the screen: from, to, as_of, branch_id, currency_id, account_id,
     * party_id, cashbox_id, user_id, brand_id, category_id, plus selects from options().
     *
     * @return list<string>
     */
    public function filters(): array
    {
        return ['from', 'to', 'branch_id'];
    }

    /** @return array<string, mixed> */
    public function defaults(): array
    {
        return [
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->toDateString(),
            'as_of' => now()->toDateString(),
        ];
    }

    /**
     * Choices for report-specific select filters, e.g. ['group_by' => ['brand' => 'By brand']].
     *
     * @return array<string, array<string, string>>
     */
    public function options(): array
    {
        return [];
    }

    /** @return list<string> filters that must be filled before the report runs */
    public function required(): array
    {
        return [];
    }

    /**
     * Lines printed under the table (balance checks, legends).
     *
     * @param  array<string, mixed>  $f
     * @return list<string>
     */
    public function notes(User $user, array $f): array
    {
        return [];
    }

    public function title(): string
    {
        return __('reports.titles.'.static::key());
    }

    public function allows(User $user): bool
    {
        return $user->canAny($this->permissions());
    }

    /**
     * Input from the screen/URL merged over the defaults, limited to this report's filters.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function resolve(array $input): array
    {
        $f = $this->defaults();

        foreach ($this->filters() as $key) {
            if (array_key_exists($key, $input) && $input[$key] !== '' && $input[$key] !== null) {
                $f[$key] = $input[$key];
            }
        }

        return $f;
    }

    /** @param array<string, mixed> $f */
    protected function date(array $f, string $key): ?CarbonImmutable
    {
        return empty($f[$key]) ? null : CarbonImmutable::parse($f[$key]);
    }
}
