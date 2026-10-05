<?php

namespace App\Reports\Accounting;

use App\Enums\AccountRole;
use App\Models\User;
use App\Reports\Concerns\QueriesLedger;
use App\Reports\Report;
use App\Services\Accounting\AccountResolver;
use App\Support\Money;
use Carbon\CarbonImmutable;

/**
 * Debt ageing (base currency) for customers (receivables) or suppliers (payables).
 * Payments are applied to the oldest debts first (FIFO); what is still open is aged by
 * the date of the debt: 0–30, 31–60, 61–90, over 90 days.
 */
class AgingReport extends Report
{
    use QueriesLedger;

    private const BUCKETS = ['d30' => 30, 'd60' => 60, 'd90' => 90];

    public function __construct(private readonly AccountResolver $accounts) {}

    public static function key(): string
    {
        return 'aging';
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
        return ['as_of', 'branch_id', 'side'];
    }

    public function defaults(): array
    {
        return parent::defaults() + ['side' => 'receivables'];
    }

    public function options(): array
    {
        return ['side' => ['receivables' => __('enums.account_role.receivables'), 'payables' => __('enums.account_role.payables')]];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'party' => ['label' => __('vouchers.party'), 'type' => 'text'],
            'phone' => ['label' => __('app.fields.phone'), 'type' => 'text'],
            'd30' => ['label' => __('reports.aging.d30'), 'type' => 'money', 'total' => true],
            'd60' => ['label' => __('reports.aging.d60'), 'type' => 'money', 'total' => true],
            'd90' => ['label' => __('reports.aging.d90'), 'type' => 'money', 'total' => true],
            'over' => ['label' => __('reports.aging.over'), 'type' => 'money', 'total' => true],
            'total' => ['label' => __('documents.total'), 'type' => 'money', 'total' => true],
        ];
    }

    public function rows(User $user, array $f): array
    {
        $payables = ($f['side'] ?? 'receivables') === 'payables';
        $account = $this->accounts->idFor($payables ? AccountRole::Payables : AccountRole::Receivables);
        $asOf = CarbonImmutable::parse($f['as_of']);

        $lines = $this->lines($f)
            ->where('l.account_id', $account)->whereNotNull('l.party_id')->where('e.date', '<=', $asOf->toDateString())
            ->join('parties as p', 'p.id', '=', 'l.party_id')
            ->orderBy('e.date')->orderBy('l.id')
            ->select(['l.party_id', 'p.name', 'p.phone', 'e.date', 'l.debit_base', 'l.credit_base'])
            ->get()->groupBy('party_id');

        $rows = [];
        foreach ($lines as $partyLines) {
            // "Debt" lines increase what is owed (debits for customers, credits for suppliers).
            $debts = [];
            $paid = Money::zero();
            foreach ($partyLines as $line) {
                $increase = Money::of((string) ($payables ? $line->credit_base : $line->debit_base));
                $decrease = Money::of((string) ($payables ? $line->debit_base : $line->credit_base));
                if ($increase->isPositive()) {
                    $debts[] = ['date' => $line->date, 'open' => $increase];
                }
                $paid = $paid->plus($decrease);
            }

            $row = ['party' => $partyLines->first()->name, 'phone' => $partyLines->first()->phone,
                'd30' => Money::zero(), 'd60' => Money::zero(), 'd90' => Money::zero(), 'over' => Money::zero(), 'total' => Money::zero()];

            foreach ($debts as $debt) {
                $applied = $paid->isGreaterThan($debt['open']) ? $debt['open'] : $paid;
                $paid = $paid->minus($applied);
                $open = $debt['open']->minus($applied);
                if ($open->isZero()) {
                    continue;
                }
                $bucket = $this->bucket((int) CarbonImmutable::parse($debt['date'])->diffInDays($asOf));
                $row[$bucket] = $row[$bucket]->plus($open);
                $row['total'] = $row['total']->plus($open);
            }

            // Overpaid: a credit in the party's favour, shown as a negative total.
            if ($paid->isPositive()) {
                $row['total'] = $row['total']->minus($paid);
            }

            if (! $row['total']->isZero()) {
                $rows[] = $row;
            }
        }

        usort($rows, fn ($a, $b) => $b['total']->compareTo($a['total']));

        return $rows;
    }

    private function bucket(int $days): string
    {
        foreach (self::BUCKETS as $key => $limit) {
            if ($days <= $limit) {
                return $key;
            }
        }

        return 'over';
    }
}
