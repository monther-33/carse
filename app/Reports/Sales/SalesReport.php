<?php

namespace App\Reports\Sales;

use App\Enums\DocumentStatus;
use App\Models\User;
use App\Reports\Report;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Posted sales (returned vehicles excluded) by period, salesperson, brand or month.
 * Cost and profit columns only with vehicles.view_cost; sales staff see only their own.
 */
class SalesReport extends Report
{
    public static function key(): string
    {
        return 'sales';
    }

    public function group(): string
    {
        return 'sales';
    }

    public function permissions(): array
    {
        return ['reports.sales', 'sales.view'];
    }

    public function filters(): array
    {
        return ['from', 'to', 'branch_id', 'currency_id', 'group_by', 'user_id', 'brand_id'];
    }

    public function defaults(): array
    {
        return parent::defaults() + ['group_by' => 'none'];
    }

    public function options(): array
    {
        return ['group_by' => [
            'none' => __('reports.group_by.none'),
            'salesperson' => __('reports.group_by.salesperson'),
            'brand' => __('reports.group_by.brand'),
            'month' => __('reports.group_by.month'),
        ]];
    }

    public function columns(User $user, array $f): array
    {
        $grouped = ($f['group_by'] ?? 'none') !== 'none';
        $columns = $grouped
            ? ['label' => ['label' => __('reports.group'), 'type' => 'text'], 'count' => ['label' => __('reports.vehicles_count'), 'type' => 'int', 'total' => true]]
            : [
                'date' => ['label' => __('app.fields.date'), 'type' => 'date'],
                'number' => ['label' => __('sales.invoice_number'), 'type' => 'text'],
                'customer' => ['label' => __('sales.customer'), 'type' => 'text'],
                'vehicle' => ['label' => __('vehicles.vehicle'), 'type' => 'text'],
                'salesperson' => ['label' => __('sales.salesperson'), 'type' => 'text'],
                'payment' => ['label' => __('sales.payment_type'), 'type' => 'text'],
            ];

        $columns['revenue'] = ['label' => __('reports.revenue'), 'type' => 'money', 'total' => true];

        if ($user->can('vehicles.view_cost')) {
            $columns['cost'] = ['label' => __('sales.cost'), 'type' => 'money', 'total' => true];
            $columns['commission'] = ['label' => __('sales.commission'), 'type' => 'money', 'total' => true];
            $columns['profit'] = ['label' => __('sales.profit'), 'type' => 'money', 'total' => true];
        }

        return $columns;
    }

    public function rows(User $user, array $f): array
    {
        $query = DB::table('sales_invoice_items as i')
            ->join('sales_invoices as s', 's.id', '=', 'i.invoice_id')
            ->join('vehicles as v', 'v.id', '=', 'i.vehicle_id')
            ->join('brands as b', 'b.id', '=', 'v.brand_id')
            ->join('car_models as m', 'm.id', '=', 'v.model_id')
            ->join('parties as p', 'p.id', '=', 's.party_id')
            ->join('users as u', 'u.id', '=', 's.salesperson_id')
            ->where('s.status', DocumentStatus::Posted->value)
            ->whereNull('i.return_id')
            ->whereBetween('s.date', [$f['from'], $f['to']])
            ->when(! empty($f['branch_id']), fn ($q) => $q->where('s.branch_id', $f['branch_id']))
            ->when(! empty($f['currency_id']), fn ($q) => $q->where('s.currency_id', $f['currency_id']))
            ->when(! empty($f['user_id']), fn ($q) => $q->where('s.salesperson_id', $f['user_id']))
            ->when(! empty($f['brand_id']), fn ($q) => $q->where('v.brand_id', $f['brand_id']))
            ->when(! $user->canAny(['sales.view_all', 'reports.sales']), fn ($q) => $q->where('s.salesperson_id', $user->id));

        $group = $f['group_by'] ?? 'none';

        if ($group === 'none') {
            return $query->orderBy('s.date')->orderBy('s.id')
                ->get(['s.date', 's.number', 's.payment_type', 'p.name as customer', 'b.name as brand', 'm.name as model', 'v.year', 'v.vin', 'u.name as salesperson', 'i.net_base', 'i.cost_snapshot', 'i.commission', DB::raw('COALESCE(i.showroom_revenue, i.net_base) AS showroom')])
                ->map(fn ($r) => $this->money($r, [
                    'date' => $r->date, 'number' => $r->number, 'customer' => $r->customer,
                    'vehicle' => "{$r->brand} {$r->model} {$r->year} — {$r->vin}", 'salesperson' => $r->salesperson,
                    'payment' => __('enums.payment_type.'.$r->payment_type),
                ]))->all();
        }

        $label = match ($group) {
            'salesperson' => 'u.name',
            'brand' => 'b.name',
            default => "DATE_FORMAT(s.date, '%Y-%m')",
        };

        return $query->groupByRaw($label)->orderByRaw($label)
            ->selectRaw("{$label} AS label, COUNT(*) AS n, SUM(i.net_base) AS net_base, SUM(COALESCE(i.showroom_revenue, i.net_base)) AS showroom, SUM(i.cost_snapshot) AS cost_snapshot, SUM(i.commission) AS commission")
            ->get()
            ->map(fn ($r) => $this->money($r, ['label' => $r->label, 'count' => (int) $r->n]))->all();
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function money(object $r, array $row): array
    {
        $revenue = Money::of((string) $r->net_base);
        $cost = Money::of((string) $r->cost_snapshot);
        $commission = Money::of((string) $r->commission);

        return $row + [
            'revenue' => $revenue,
            'cost' => $cost,
            'commission' => $commission,
            // Cars with owners: only the showroom's part of the price is its revenue.
            'profit' => Money::of((string) $r->showroom)->minus($cost)->minus($commission),
        ];
    }
}
