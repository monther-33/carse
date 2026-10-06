<?php

namespace App\Reports\Sales;

use App\Enums\CommissionStatus;
use App\Models\User;
use App\Reports\Report;
use App\Support\Features;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Commissions by sale date; sales staff only see their own.
 */
class CommissionsReport extends Report
{
    public static function key(): string
    {
        return 'commissions';
    }

    public function feature(): ?string
    {
        return Features::COMMISSIONS;
    }

    public function group(): string
    {
        return 'sales';
    }

    public function permissions(): array
    {
        return ['reports.sales', 'commissions.view'];
    }

    public function filters(): array
    {
        return ['from', 'to', 'user_id', 'status'];
    }

    public function options(): array
    {
        return ['status' => collect(CommissionStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'date' => ['label' => __('app.fields.date'), 'type' => 'date'],
            'number' => ['label' => __('sales.invoice_number'), 'type' => 'text'],
            'salesperson' => ['label' => __('sales.salesperson'), 'type' => 'text'],
            'vehicle' => ['label' => __('vehicles.vehicle'), 'type' => 'text'],
            'amount' => ['label' => __('documents.amount'), 'type' => 'money', 'total' => true],
            'status' => ['label' => __('app.fields.status'), 'type' => 'text'],
        ];
    }

    public function rows(User $user, array $f): array
    {
        return DB::table('commissions as c')
            ->join('sales_invoices as s', 's.id', '=', 'c.sales_invoice_id')
            ->join('sales_invoice_items as i', 'i.id', '=', 'c.sales_invoice_item_id')
            ->join('vehicles as v', 'v.id', '=', 'i.vehicle_id')
            ->join('users as u', 'u.id', '=', 'c.user_id')
            ->whereBetween('s.date', [$f['from'], $f['to']])
            ->when(! empty($f['user_id']), fn ($q) => $q->where('c.user_id', $f['user_id']))
            ->when(! empty($f['status']), fn ($q) => $q->where('c.status', $f['status']))
            ->when(! $user->canAny(['commissions.view_all', 'reports.sales']), fn ($q) => $q->where('c.user_id', $user->id))
            ->orderBy('s.date')
            ->get(['s.date', 's.number', 'u.name', 'v.vin', 'c.amount', 'c.status'])
            ->map(fn ($r) => [
                'date' => $r->date, 'number' => $r->number, 'salesperson' => $r->name, 'vehicle' => $r->vin,
                'amount' => Money::of((string) $r->amount), 'status' => CommissionStatus::from($r->status)->label(),
            ])->all();
    }
}
