<?php

namespace App\Reports\Inventory;

use App\Models\User;
use App\Models\Vehicle;
use App\Reports\Report;
use App\Support\Money;
use App\Support\Settings;

/**
 * Stock ageing: count (and cost, with vehicles.view_cost) of in-stock vehicles per age
 * bracket, using the stale-stock thresholds from settings (default 60 / 90 days).
 */
class StockAgingReport extends Report
{
    public function __construct(private readonly Settings $settings) {}

    public static function key(): string
    {
        return 'stock_aging';
    }

    public function group(): string
    {
        return 'inventory';
    }

    public function permissions(): array
    {
        return ['reports.inventory', 'vehicles.view'];
    }

    public function filters(): array
    {
        return ['branch_id', 'brand_id'];
    }

    public function columns(User $user, array $f): array
    {
        $columns = [
            'bracket' => ['label' => __('reports.age_bracket'), 'type' => 'text'],
            'count' => ['label' => __('reports.vehicles_count'), 'type' => 'int', 'total' => true],
        ];

        if ($user->can('vehicles.view_cost')) {
            $columns['cost'] = ['label' => __('vehicles.total_cost'), 'type' => 'money', 'total' => true];
        }

        return $columns;
    }

    public function rows(User $user, array $f): array
    {
        $warning = $this->settings->int('inventory.stale_days_warning', 60);
        $critical = $this->settings->int('inventory.stale_days_critical', 90);
        $brackets = [
            [0, 30, '0 – 30'],
            [31, $warning, '31 – '.$warning],
            [$warning + 1, $critical, ($warning + 1).' – '.$critical],
            [$critical + 1, PHP_INT_MAX, __('reports.over_days', ['days' => $critical])],
        ];

        $vehicles = Vehicle::query()->inStock()->whereNotNull('received_at')
            ->when(! empty($f['branch_id']), fn ($q) => $q->where('branch_id', $f['branch_id']))
            ->when(! empty($f['brand_id']), fn ($q) => $q->where('brand_id', $f['brand_id']))
            ->get(['id', 'received_at', 'total_cost']);

        return array_map(function (array $bracket) use ($vehicles) {
            [$min, $max, $label] = $bracket;
            $in = $vehicles->filter(fn (Vehicle $v) => $v->daysInStock() >= $min && $v->daysInStock() <= $max);

            return [
                'bracket' => $label,
                'count' => $in->count(),
                'cost' => Money::sum($in->pluck('total_cost')->all()),
            ];
        }, $brackets);
    }
}
