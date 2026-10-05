<?php

namespace App\Reports\Accounting;

use App\Models\User;
use App\Reports\Concerns\QueriesLedger;
use App\Reports\Report;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * Trial balance on the account tree: opening balance, period debits and credits, closing
 * balance (base currency, debit − credit). Group accounts show the sum of their children.
 */
class TrialBalanceReport extends Report
{
    use QueriesLedger;

    public static function key(): string
    {
        return 'trial_balance';
    }

    public function group(): string
    {
        return 'accounting';
    }

    public function permissions(): array
    {
        return ['reports.financial'];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'code' => ['label' => __('app.fields.code'), 'type' => 'text'],
            'name' => ['label' => __('app.fields.account'), 'type' => 'text'],
            'opening' => ['label' => __('reports.opening_balance'), 'type' => 'money'],
            'debit' => ['label' => __('reports.debit'), 'type' => 'money'],
            'credit' => ['label' => __('reports.credit'), 'type' => 'money'],
            'closing' => ['label' => __('reports.closing_balance'), 'type' => 'money'],
        ];
    }

    /**
     * @param  array<string, mixed>  $f
     * @return array{opening: array<int, BigDecimal>, debit: array<int, BigDecimal>, credit: array<int, BigDecimal>}
     */
    private function figures(array $f): array
    {
        $opening = $this->lines($f)->where('e.date', '<', $f['from'])
            ->groupBy('l.account_id')->selectRaw('l.account_id, SUM(l.debit_base - l.credit_base) AS b')
            ->pluck('b', 'account_id')->map(fn ($v) => Money::of((string) $v));

        $movement = $this->lines($f)->whereBetween('e.date', [$f['from'], $f['to']])
            ->groupBy('l.account_id')->selectRaw('l.account_id, SUM(l.debit_base) AS d, SUM(l.credit_base) AS c')
            ->get()->keyBy('account_id');

        return [
            'opening' => $this->rollUp($opening),
            'debit' => $this->rollUp($movement->map(fn ($r) => Money::of((string) $r->d))),
            'credit' => $this->rollUp($movement->map(fn ($r) => Money::of((string) $r->c))),
        ];
    }

    public function rows(User $user, array $f): array
    {
        $fig = $this->figures($f);
        $rows = [];
        $totals = ['opening' => Money::zero(), 'debit' => Money::zero(), 'credit' => Money::zero()];

        foreach ($this->tree() as ['account' => $account, 'depth' => $depth]) {
            $opening = $fig['opening'][$account->id] ?? Money::zero();
            $debit = $fig['debit'][$account->id] ?? Money::zero();
            $credit = $fig['credit'][$account->id] ?? Money::zero();

            if ($opening->isZero() && $debit->isZero() && $credit->isZero()) {
                continue;
            }

            $rows[] = [
                'code' => $account->code,
                'name' => $account->name,
                'opening' => $opening,
                'debit' => $debit,
                'credit' => $credit,
                'closing' => $opening->plus($debit)->minus($credit),
                '_style' => $account->is_group ? 'subtotal' : null,
                '_indent' => $depth,
            ];

            if ($account->parent_id === null) {
                $totals['opening'] = $totals['opening']->plus($opening);
                $totals['debit'] = $totals['debit']->plus($debit);
                $totals['credit'] = $totals['credit']->plus($credit);
            }
        }

        $rows[] = [
            'code' => '', 'name' => __('documents.total'),
            'opening' => $totals['opening'], 'debit' => $totals['debit'], 'credit' => $totals['credit'],
            'closing' => $totals['opening']->plus($totals['debit'])->minus($totals['credit']),
            '_style' => 'total',
        ];

        return $rows;
    }

    public function notes(User $user, array $f): array
    {
        $rows = $this->rows($user, $f);
        $total = end($rows);
        $balanced = $total['debit']->isEqualTo($total['credit']) && $total['closing']->isZero();

        return [$balanced ? __('reports.balanced') : __('reports.not_balanced')];
    }
}
