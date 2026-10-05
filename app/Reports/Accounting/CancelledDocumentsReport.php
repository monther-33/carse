<?php

namespace App\Reports\Accounting;

use App\Enums\DocumentStatus;
use App\Enums\ReservationStatus;
use App\Models\User;
use App\Reports\Report;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Every document cancelled in the period (by its cancellation date), with who and why.
 */
class CancelledDocumentsReport extends Report
{
    /** table => translation key of the document type */
    private const TABLES = [
        'purchase_invoices' => 'reports.doc_types.purchase_invoice',
        'sales_invoices' => 'reports.doc_types.sales_invoice',
        'expenses' => 'reports.doc_types.expense',
        'vouchers' => 'reports.doc_types.voucher',
        'manual_journals' => 'reports.doc_types.manual_journal',
        'opening_stocks' => 'reports.doc_types.opening_stock',
    ];

    public static function key(): string
    {
        return 'cancelled_documents';
    }

    public function group(): string
    {
        return 'accounting';
    }

    public function permissions(): array
    {
        return ['reports.financial', 'audit.view'];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'type' => ['label' => __('app.fields.type'), 'type' => 'text'],
            'number' => ['label' => __('documents.number'), 'type' => 'text'],
            'date' => ['label' => __('app.fields.date'), 'type' => 'date'],
            'amount' => ['label' => __('documents.amount'), 'type' => 'money'],
            'cancelled_at' => ['label' => __('reports.cancelled_at'), 'type' => 'text'],
            'cancelled_by' => ['label' => __('reports.cancelled_by'), 'type' => 'text'],
            'reason' => ['label' => __('documents.cancel_reason'), 'type' => 'text'],
        ];
    }

    public function rows(User $user, array $f): array
    {
        $rows = [];
        $amountColumn = ['purchase_invoices' => 'total', 'sales_invoices' => 'total', 'expenses' => 'amount', 'vouchers' => 'amount', 'manual_journals' => null, 'opening_stocks' => null];

        foreach (self::TABLES as $table => $label) {
            $query = DB::table("{$table} as d")
                ->leftJoin('users as u', 'u.id', '=', 'd.cancelled_by')
                ->where('d.status', DocumentStatus::Cancelled->value)
                ->whereBetween(DB::raw('DATE(d.cancelled_at)'), [$f['from'], $f['to']])
                ->when(! empty($f['branch_id']), fn ($q) => $q->where('d.branch_id', $f['branch_id']))
                ->select(['d.number', 'd.date', 'd.cancelled_at', 'd.cancel_reason', 'u.name as by']);

            if ($amountColumn[$table]) {
                $query->addSelect("d.{$amountColumn[$table]} as amount");
            }

            foreach ($query->get() as $doc) {
                $rows[] = [
                    'type' => __($label), 'number' => $doc->number, 'date' => $doc->date,
                    'amount' => isset($doc->amount) ? Money::of((string) $doc->amount) : null,
                    'cancelled_at' => $doc->cancelled_at, 'cancelled_by' => $doc->by, 'reason' => $doc->cancel_reason,
                ];
            }
        }

        $reservations = DB::table('reservations as r')
            ->leftJoin('users as u', 'u.id', '=', 'r.cancelled_by')
            ->where('r.status', ReservationStatus::Cancelled->value)
            ->whereBetween(DB::raw('DATE(r.cancelled_at)'), [$f['from'], $f['to']])
            ->when(! empty($f['branch_id']), fn ($q) => $q->where('r.branch_id', $f['branch_id']))
            ->get(['r.number', 'r.date', 'r.deposit', 'r.cancelled_at', 'r.cancel_reason', 'u.name as by']);

        foreach ($reservations as $doc) {
            $rows[] = [
                'type' => __('reports.doc_types.reservation'), 'number' => $doc->number, 'date' => $doc->date,
                'amount' => Money::of((string) $doc->deposit), 'cancelled_at' => $doc->cancelled_at,
                'cancelled_by' => $doc->by, 'reason' => $doc->cancel_reason,
            ];
        }

        usort($rows, fn ($a, $b) => strcmp((string) $b['cancelled_at'], (string) $a['cancelled_at']));

        return $rows;
    }
}
