<?php

namespace App\Reports\Sales;

use App\Enums\DocumentStatus;
use App\Enums\InstallmentStatus;
use App\Models\User;
use App\Reports\Report;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

/**
 * Installments due in a period, or everything overdue as of a date.
 */
class InstallmentsReport extends Report
{
    public static function key(): string
    {
        return 'installments';
    }

    public function group(): string
    {
        return 'sales';
    }

    public function permissions(): array
    {
        return ['reports.financial', 'reports.sales', 'vouchers.view'];
    }

    public function filters(): array
    {
        return ['from', 'to', 'branch_id', 'party_id', 'status'];
    }

    public function defaults(): array
    {
        return ['from' => now()->startOfMonth()->toDateString(), 'to' => now()->endOfMonth()->toDateString(), 'status' => 'open'];
    }

    public function options(): array
    {
        return ['status' => [
            'open' => __('installments.filters.open'),
            'overdue' => __('installments.filters.overdue'),
            'paid' => __('installments.filters.paid'),
            'all' => __('app.all'),
        ]];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'customer' => ['label' => __('sales.customer'), 'type' => 'text'],
            'phone' => ['label' => __('app.fields.phone'), 'type' => 'text'],
            'number' => ['label' => __('sales.invoice_number'), 'type' => 'text'],
            'sequence' => ['label' => '#', 'type' => 'text'],
            'due_date' => ['label' => __('sales.due_date'), 'type' => 'date'],
            'amount' => ['label' => __('documents.amount'), 'type' => 'money', 'total' => true],
            'paid' => ['label' => __('documents.paid'), 'type' => 'money', 'total' => true],
            'remaining' => ['label' => __('installments.remaining'), 'type' => 'money', 'total' => true],
            'late' => ['label' => __('reports.days_late'), 'type' => 'int'],
        ];
    }

    public function rows(User $user, array $f): array
    {
        $today = CarbonImmutable::today();
        $open = [InstallmentStatus::Pending->value, InstallmentStatus::Partial->value];

        return DB::table('installments as t')
            ->join('installment_plans as pl', 'pl.id', '=', 't.plan_id')
            ->join('sales_invoices as s', 's.id', '=', 'pl.sales_invoice_id')
            ->join('parties as p', 'p.id', '=', 's.party_id')
            ->where('s.status', DocumentStatus::Posted->value)
            ->when(! empty($f['branch_id']), fn ($q) => $q->where('s.branch_id', $f['branch_id']))
            ->when(! empty($f['party_id']), fn ($q) => $q->where('s.party_id', $f['party_id']))
            ->when(($f['status'] ?? 'open') === 'overdue',
                fn ($q) => $q->whereIn('t.status', $open)->where('t.due_date', '<', $today->toDateString()),
                fn ($q) => $q->whereBetween('t.due_date', [$f['from'], $f['to']])
                    ->when(($f['status'] ?? 'open') === 'open', fn ($q) => $q->whereIn('t.status', $open))
                    ->when(($f['status'] ?? 'open') === 'paid', fn ($q) => $q->where('t.status', InstallmentStatus::Paid->value)))
            ->where('t.status', '!=', InstallmentStatus::Cancelled->value)
            ->orderBy('t.due_date')
            ->get(['p.name', 'p.phone', 's.number', 't.sequence', 'pl.months', 't.due_date', 't.amount', 't.paid_amount', 't.status'])
            ->map(function ($r) use ($today, $open) {
                $remaining = Money::of((string) $r->amount)->minus(Money::of((string) $r->paid_amount));
                $due = CarbonImmutable::parse($r->due_date);

                return [
                    'customer' => $r->name, 'phone' => $r->phone, 'number' => $r->number,
                    'sequence' => "{$r->sequence}/{$r->months}", 'due_date' => $r->due_date,
                    'amount' => Money::of((string) $r->amount), 'paid' => Money::of((string) $r->paid_amount), 'remaining' => $remaining,
                    'late' => in_array($r->status, $open, true) && $due->isBefore($today) ? (int) $due->diffInDays($today) : null,
                ];
            })->all();
    }
}
