<?php

namespace App\Reports\Accounting;

use App\Models\User;
use App\Reports\Concerns\QueriesLedger;
use App\Reports\Report;
use App\Support\Money;

/**
 * Journal book: every entry of the period with its lines, in date order.
 */
class JournalBookReport extends Report
{
    use QueriesLedger;

    public static function key(): string
    {
        return 'journal_book';
    }

    public function group(): string
    {
        return 'accounting';
    }

    public function permissions(): array
    {
        return ['reports.financial', 'journal.view'];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'date' => ['label' => __('app.fields.date'), 'type' => 'date'],
            'number' => ['label' => __('documents.number'), 'type' => 'text'],
            'account' => ['label' => __('app.fields.account'), 'type' => 'text'],
            'description' => ['label' => __('app.fields.description'), 'type' => 'text'],
            'debit' => ['label' => __('reports.debit'), 'type' => 'money', 'total' => true],
            'credit' => ['label' => __('reports.credit'), 'type' => 'money', 'total' => true],
        ];
    }

    public function rows(User $user, array $f): array
    {
        $lines = $this->lines($f)
            ->whereBetween('e.date', [$f['from'], $f['to']])
            ->join('accounts as a', 'a.id', '=', 'l.account_id')
            ->leftJoin('parties as p', 'p.id', '=', 'l.party_id')
            ->orderBy('e.date')->orderBy('e.id')->orderBy('l.line_no')
            ->select(['e.id', 'e.date', 'e.number', 'e.description', 'e.status', 'a.code', 'a.name', 'p.name as party', 'l.memo', 'l.debit_base', 'l.credit_base'])
            ->get();

        $rows = [];
        $current = null;

        foreach ($lines as $line) {
            if ($line->id !== $current) {
                $current = $line->id;
                $rows[] = [
                    'date' => $line->date,
                    'number' => $line->number,
                    'description' => $line->description.($line->status === 'cancelled' ? ' ('.__('enums.document_status.cancelled').')' : ''),
                    '_style' => 'heading',
                ];
            }

            $rows[] = [
                'account' => $line->code.' - '.$line->name,
                'description' => trim(($line->party ?? '').' '.($line->memo ?? '')),
                'debit' => Money::of((string) $line->debit_base),
                'credit' => Money::of((string) $line->credit_base),
                '_indent' => 1,
            ];
        }

        return $rows;
    }
}
