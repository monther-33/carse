<?php

namespace App\Reports\Inventory;

use App\Enums\DocumentStatus;
use App\Models\User;
use App\Reports\Report;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Posted purchases per supplier: invoices, vehicles and cost in dinars.
 */
class PurchasesBySupplierReport extends Report
{
    public static function key(): string
    {
        return 'purchases_by_supplier';
    }

    public function group(): string
    {
        return 'inventory';
    }

    public function permissions(): array
    {
        return ['reports.inventory', 'purchases.view'];
    }

    public function allows(User $user): bool
    {
        return parent::allows($user) && $user->can('vehicles.view_cost');
    }

    public function filters(): array
    {
        return ['from', 'to', 'branch_id', 'currency_id', 'party_id'];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'supplier' => ['label' => __('purchases.supplier'), 'type' => 'text'],
            'invoices' => ['label' => __('reports.invoices_count'), 'type' => 'int', 'total' => true],
            'vehicles' => ['label' => __('reports.vehicles_count'), 'type' => 'int', 'total' => true],
            'cost' => ['label' => __('purchases.cost_base'), 'type' => 'money', 'total' => true],
        ];
    }

    public function rows(User $user, array $f): array
    {
        return DB::table('purchase_invoice_items as i')
            ->join('purchase_invoices as pi', 'pi.id', '=', 'i.invoice_id')
            ->join('parties as p', 'p.id', '=', 'pi.party_id')
            ->where('pi.status', DocumentStatus::Posted->value)
            ->whereBetween('pi.date', [$f['from'], $f['to']])
            ->when(! empty($f['branch_id']), fn ($q) => $q->where('pi.branch_id', $f['branch_id']))
            ->when(! empty($f['currency_id']), fn ($q) => $q->where('pi.currency_id', $f['currency_id']))
            ->when(! empty($f['party_id']), fn ($q) => $q->where('pi.party_id', $f['party_id']))
            ->groupBy('p.id', 'p.name')
            ->orderByDesc(DB::raw('SUM(i.cost_base)'))
            ->selectRaw('p.name, COUNT(DISTINCT pi.id) AS invoices, COUNT(*) AS vehicles, SUM(i.cost_base) AS cost')
            ->get()
            ->map(fn ($r) => ['supplier' => $r->name, 'invoices' => (int) $r->invoices, 'vehicles' => (int) $r->vehicles, 'cost' => Money::of((string) $r->cost)])
            ->all();
    }
}
