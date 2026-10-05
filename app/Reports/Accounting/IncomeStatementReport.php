<?php

namespace App\Reports\Accounting;

use App\Enums\AccountRole;
use App\Enums\AccountType;
use App\Models\Account;
use App\Models\User;
use App\Reports\Concerns\QueriesLedger;
use App\Reports\Report;
use App\Services\Accounting\AccountResolver;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * Income statement for a period. Sections follow the top-level revenue and expense accounts
 * of the chart; gross profit is shown after the section holding "cost of vehicles sold".
 */
class IncomeStatementReport extends Report
{
    use QueriesLedger;

    /** @var array<int, int|null>|null */
    private ?array $parents = null;

    public function __construct(private readonly AccountResolver $accounts) {}

    public static function key(): string
    {
        return 'income_statement';
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
            'name' => ['label' => __('app.fields.account'), 'type' => 'text'],
            'amount' => ['label' => __('documents.amount'), 'type' => 'money'],
        ];
    }

    /**
     * @param  array<string, mixed>  $f
     * @return array{rows: list<array<string, mixed>>, net: BigDecimal}
     */
    public function build(array $f): array
    {
        $balances = $this->balances($f, $f['from'], $f['to']);
        $rolled = $this->rollUp($balances);
        $tree = $this->tree();
        $cogsRoot = $this->rootOf($this->accounts->idFor(AccountRole::CostOfSales));

        $rows = [];
        $net = Money::zero();
        $revenueTotal = Money::zero();

        $roots = $tree->filter(fn ($n) => $n['depth'] === 0 && in_array($n['account']->type, [AccountType::Revenue, AccountType::Expense], true))
            ->sortBy(fn ($n) => $n['account']->type === AccountType::Revenue ? 0 : 1)
            ->pluck('account');

        foreach ($roots as $root) {
            // Revenue reads credit − debit; expenses debit − credit.
            $sign = $root->type === AccountType::Revenue ? -1 : 1;
            $total = ($rolled[$root->id] ?? Money::zero())->multipliedBy($sign);

            $rows[] = ['name' => $root->name, 'amount' => null, '_style' => 'heading'];
            foreach ($tree as ['account' => $account, 'depth' => $depth]) {
                if ($depth === 0 || $account->is_group || $this->rootOf($account->id) !== $root->id) {
                    continue;
                }
                $value = ($balances[$account->id] ?? Money::zero())->multipliedBy($sign);
                if (! $value->isZero()) {
                    $rows[] = ['name' => $account->name, 'amount' => $value, '_indent' => 1];
                }
            }
            $rows[] = ['name' => __('reports.total_of', ['name' => $root->name]), 'amount' => $total, '_style' => 'subtotal'];

            if ($root->type === AccountType::Revenue) {
                $revenueTotal = $revenueTotal->plus($total);
                $net = $net->plus($total);
            } else {
                $net = $net->minus($total);
                if ($root->id === $cogsRoot) {
                    $rows[] = ['name' => __('reports.gross_profit'), 'amount' => $revenueTotal->minus($total), '_style' => 'total'];
                }
            }
        }

        $rows[] = ['name' => $net->isNegative() ? __('reports.net_loss') : __('reports.net_profit'), 'amount' => $net, '_style' => 'total'];

        return ['rows' => $rows, 'net' => $net];
    }

    public function rows(User $user, array $f): array
    {
        return $this->build($f)['rows'];
    }

    private function rootOf(int $accountId): int
    {
        $this->parents ??= Account::query()->pluck('parent_id', 'id')->all();

        $id = $accountId;
        while (($this->parents[$id] ?? null) !== null) {
            $id = $this->parents[$id];
        }

        return $id;
    }
}
