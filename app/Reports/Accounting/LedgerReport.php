<?php

namespace App\Reports\Accounting;

use App\Models\User;
use App\Reports\Concerns\QueriesLedger;
use App\Reports\Report;
use App\Support\Money;

/**
 * General ledger of one account (a group account includes all its sub-accounts):
 * opening balance, every line with its running balance, closing balance (base currency).
 * Optionally narrowed to one party.
 */
class LedgerReport extends Report
{
    use QueriesLedger;

    public static function key(): string
    {
        return 'ledger';
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
        return ['account_id', 'from', 'to', 'branch_id', 'currency_id', 'party_id'];
    }

    public function required(): array
    {
        return ['account_id'];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'date' => ['label' => __('app.fields.date'), 'type' => 'date'],
            'number' => ['label' => __('documents.number'), 'type' => 'text'],
            'description' => ['label' => __('app.fields.description'), 'type' => 'text'],
            'party' => ['label' => __('vouchers.party'), 'type' => 'text'],
            'original' => ['label' => __('reports.original_amount'), 'type' => 'text'],
            'debit' => ['label' => __('reports.debit'), 'type' => 'money', 'total' => true],
            'credit' => ['label' => __('reports.credit'), 'type' => 'money', 'total' => true],
            'balance' => ['label' => __('reports.balance'), 'type' => 'money'],
        ];
    }

    public function rows(User $user, array $f): array
    {
        $accounts = $this->withDescendants((int) $f['account_id']);
        $base = fn () => $this->lines($f)
            ->whereIn('l.account_id', $accounts)
            ->when(! empty($f['party_id']), fn ($q) => $q->where('l.party_id', $f['party_id']));

        $running = Money::of((string) $base()->where('e.date', '<', $f['from'])->sum(\DB::raw('l.debit_base - l.credit_base')));
        $rows = [['description' => __('reports.opening_balance'), 'balance' => $running, '_style' => 'subtotal']];

        $lines = $base()
            ->whereBetween('e.date', [$f['from'], $f['to']])
            ->leftJoin('parties as p', 'p.id', '=', 'l.party_id')
            ->join('currencies as c', 'c.id', '=', 'l.currency_id')
            ->orderBy('e.date')->orderBy('e.id')->orderBy('l.line_no')
            ->select(['e.date', 'e.number', 'e.description', 'l.memo', 'p.name as party', 'c.code', 'c.is_base', 'l.debit', 'l.credit', 'l.debit_base', 'l.credit_base'])
            ->get();

        foreach ($lines as $line) {
            $running = $running->plus(Money::of((string) $line->debit_base))->minus(Money::of((string) $line->credit_base));
            $original = Money::of((string) $line->debit)->isPositive() ? $line->debit : $line->credit;

            $rows[] = [
                'date' => $line->date,
                'number' => $line->number,
                'description' => $line->memo ? $line->description.' — '.$line->memo : $line->description,
                'party' => $line->party,
                'original' => $line->is_base ? '' : Money::format($original).' '.$line->code,
                'debit' => Money::of((string) $line->debit_base),
                'credit' => Money::of((string) $line->credit_base),
                'balance' => $running,
            ];
        }

        $rows[] = ['description' => __('reports.closing_balance'), 'balance' => $running, '_style' => 'total'];

        return $rows;
    }
}
