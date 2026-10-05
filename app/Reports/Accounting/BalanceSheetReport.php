<?php

namespace App\Reports\Accounting;

use App\Enums\AccountType;
use App\Models\User;
use App\Reports\Concerns\QueriesLedger;
use App\Reports\Report;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * Balance sheet as of a date. Revenue and expense balances not yet closed into retained
 * earnings appear in equity as "current earnings", so assets = liabilities + equity holds
 * without any year-end closing entry.
 */
class BalanceSheetReport extends Report
{
    use QueriesLedger;

    public static function key(): string
    {
        return 'balance_sheet';
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
        return ['as_of', 'branch_id'];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'name' => ['label' => __('app.fields.account'), 'type' => 'text'],
            'amount' => ['label' => __('reports.balance'), 'type' => 'money'],
        ];
    }

    /**
     * @param  array<string, mixed>  $f
     * @return array{rows: list<array<string, mixed>>, assets: BigDecimal, liabilities: BigDecimal, equity: BigDecimal}
     */
    public function build(array $f): array
    {
        $balances = $this->balances($f, null, $f['as_of']);
        $rolled = $this->rollUp($balances);
        $tree = $this->tree();
        $rows = [];
        $totals = [];

        // Current earnings = all revenue/expense balances (credit − debit) up to the date.
        $earnings = Money::zero();
        foreach ($tree as ['account' => $account, 'depth' => $depth]) {
            if ($depth === 0 && in_array($account->type, [AccountType::Revenue, AccountType::Expense], true)) {
                $earnings = $earnings->minus($rolled[$account->id] ?? Money::zero());
            }
        }

        foreach ([AccountType::Asset, AccountType::Liability, AccountType::Equity] as $type) {
            $sign = $type === AccountType::Asset ? 1 : -1;
            $sectionTotal = Money::zero();
            $rows[] = ['name' => $type->label(), 'amount' => null, '_style' => 'heading'];

            foreach ($tree as ['account' => $account, 'depth' => $depth]) {
                if ($account->type !== $type) {
                    continue;
                }
                $value = ($rolled[$account->id] ?? Money::zero())->multipliedBy($sign);
                if ($value->isZero()) {
                    continue;
                }
                $rows[] = ['name' => $account->name, 'amount' => $value, '_style' => $account->is_group ? 'subtotal' : null, '_indent' => $depth + 1];
                if ($depth === 0) {
                    $sectionTotal = $sectionTotal->plus($value);
                }
            }

            if ($type === AccountType::Equity) {
                $rows[] = ['name' => __('reports.current_earnings'), 'amount' => $earnings, '_indent' => 1];
                $sectionTotal = $sectionTotal->plus($earnings);
            }

            $rows[] = ['name' => __('reports.total_of', ['name' => $type->label()]), 'amount' => $sectionTotal, '_style' => 'total'];
            $totals[$type->value] = $sectionTotal;
        }

        $rows[] = ['name' => __('reports.liabilities_and_equity'), 'amount' => $totals['liability']->plus($totals['equity']), '_style' => 'total'];

        return ['rows' => $rows, 'assets' => $totals['asset'], 'liabilities' => $totals['liability'], 'equity' => $totals['equity']];
    }

    public function rows(User $user, array $f): array
    {
        return $this->build($f)['rows'];
    }

    /** @param  array<string, mixed>  $f */
    public function isBalanced(array $f): bool
    {
        $b = $this->build($f);

        return $b['assets']->isEqualTo($b['liabilities']->plus($b['equity']));
    }

    public function notes(User $user, array $f): array
    {
        return [$this->isBalanced($f) ? __('reports.balance_sheet_balanced') : __('reports.not_balanced')];
    }
}
