<?php

namespace App\Reports\Sales;

use App\Enums\DocumentStatus;
use App\Models\User;
use App\Reports\Report;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

/**
 * Profit per sold vehicle: sale price − frozen cost − commission (spec 4.3), plus costs
 * charged after the sale (posted straight to cost of sales) for the final profit.
 */
class VehicleProfitReport extends Report
{
    public static function key(): string
    {
        return 'vehicle_profit';
    }

    public function group(): string
    {
        return 'sales';
    }

    public function permissions(): array
    {
        return ['reports.sales', 'reports.financial'];
    }

    public function allows(User $user): bool
    {
        return parent::allows($user) && $user->can('vehicles.view_cost');
    }

    public function filters(): array
    {
        return ['from', 'to', 'branch_id', 'brand_id'];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'date' => ['label' => __('app.fields.date'), 'type' => 'date'],
            'vehicle' => ['label' => __('vehicles.vehicle'), 'type' => 'text'],
            'days' => ['label' => __('reports.days_in_stock'), 'type' => 'int'],
            'revenue' => ['label' => __('reports.revenue'), 'type' => 'money', 'total' => true],
            'cost' => ['label' => __('sales.cost'), 'type' => 'money', 'total' => true],
            'commission' => ['label' => __('sales.commission'), 'type' => 'money', 'total' => true],
            'profit' => ['label' => __('sales.profit'), 'type' => 'money', 'total' => true],
            'after_sale' => ['label' => __('reports.after_sale_costs'), 'type' => 'money', 'total' => true],
            'final' => ['label' => __('reports.final_profit'), 'type' => 'money', 'total' => true],
        ];
    }

    public function rows(User $user, array $f): array
    {
        $afterSale = DB::table('vehicle_costs')->where('to_cost_of_sales', true)
            ->groupBy('vehicle_id')->selectRaw('vehicle_id, SUM(amount) AS a')->pluck('a', 'vehicle_id');

        return DB::table('sales_invoice_items as i')
            ->join('sales_invoices as s', 's.id', '=', 'i.invoice_id')
            ->join('vehicles as v', 'v.id', '=', 'i.vehicle_id')
            ->join('brands as b', 'b.id', '=', 'v.brand_id')
            ->join('car_models as m', 'm.id', '=', 'v.model_id')
            ->where('s.status', DocumentStatus::Posted->value)
            ->whereNull('i.return_id')
            ->whereBetween('s.date', [$f['from'], $f['to']])
            ->when(! empty($f['branch_id']), fn ($q) => $q->where('s.branch_id', $f['branch_id']))
            ->when(! empty($f['brand_id']), fn ($q) => $q->where('v.brand_id', $f['brand_id']))
            ->orderBy('s.date')
            ->get(['s.date', 'v.id', 'v.vin', 'v.year', 'v.received_at', 'b.name as brand', 'm.name as model', 'i.net_base', 'i.cost_snapshot', 'i.commission'])
            ->map(function ($r) use ($afterSale) {
                $profit = Money::of((string) $r->net_base)->minus(Money::of((string) $r->cost_snapshot))->minus(Money::of((string) $r->commission));
                $after = Money::of((string) ($afterSale[$r->id] ?? '0'));

                return [
                    'date' => $r->date,
                    'vehicle' => "{$r->brand} {$r->model} {$r->year} — {$r->vin}",
                    'days' => $r->received_at ? (int) CarbonImmutable::parse($r->received_at)->diffInDays($r->date) : null,
                    'revenue' => Money::of((string) $r->net_base),
                    'cost' => Money::of((string) $r->cost_snapshot),
                    'commission' => Money::of((string) $r->commission),
                    'profit' => $profit,
                    'after_sale' => $after,
                    'final' => $profit->minus($after),
                ];
            })->all();
    }
}
