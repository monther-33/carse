<?php

namespace App\Reports\Accounting;

use App\Enums\DocumentStatus;
use App\Models\User;
use App\Reports\Report;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Posted expenses per category (base currency), split between operating expenses and
 * costs capitalised on vehicles.
 */
class ExpensesByCategoryReport extends Report
{
    public static function key(): string
    {
        return 'expenses_by_category';
    }

    public function group(): string
    {
        return 'accounting';
    }

    public function permissions(): array
    {
        return ['reports.financial'];
    }

    public function filters(): array
    {
        return ['from', 'to', 'branch_id', 'currency_id', 'category_id'];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'category' => ['label' => __('expenses.category'), 'type' => 'text'],
            'count' => ['label' => __('reports.count'), 'type' => 'int', 'total' => true],
            'operating' => ['label' => __('reports.operating'), 'type' => 'money', 'total' => true],
            'vehicles' => ['label' => __('reports.on_vehicles'), 'type' => 'money', 'total' => true],
            'total' => ['label' => __('documents.total'), 'type' => 'money', 'total' => true],
        ];
    }

    public function rows(User $user, array $f): array
    {
        return DB::table('expenses as x')
            ->join('expense_categories as k', 'k.id', '=', 'x.category_id')
            ->where('x.status', DocumentStatus::Posted->value)
            ->whereBetween('x.date', [$f['from'], $f['to']])
            ->when(! empty($f['branch_id']), fn ($q) => $q->where('x.branch_id', $f['branch_id']))
            ->when(! empty($f['currency_id']), fn ($q) => $q->where('x.currency_id', $f['currency_id']))
            ->when(! empty($f['category_id']), fn ($q) => $q->where('x.category_id', $f['category_id']))
            ->groupBy('k.id', 'k.name')
            ->orderBy('k.name')
            ->selectRaw('k.name, COUNT(*) AS n,
                SUM(CASE WHEN x.vehicle_id IS NULL THEN x.amount_base ELSE 0 END) AS op,
                SUM(CASE WHEN x.vehicle_id IS NULL THEN 0 ELSE x.amount_base END) AS veh,
                SUM(x.amount_base) AS total')
            ->get()
            ->map(fn ($r) => [
                'category' => $r->name,
                'count' => (int) $r->n,
                'operating' => Money::of((string) $r->op),
                'vehicles' => Money::of((string) $r->veh),
                'total' => Money::of((string) $r->total),
            ])->all();
    }
}
