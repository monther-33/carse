<?php

namespace App\Reports\Concerns;

use App\Models\Account;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Shared ledger queries for accounting reports (journal lines joined to their entries).
 */
trait QueriesLedger
{
    /**
     * @param  array<string, mixed>  $f  uses branch_id and currency_id when present
     */
    protected function lines(array $f): Builder
    {
        return DB::table('journal_lines as l')
            ->join('journal_entries as e', 'e.id', '=', 'l.entry_id')
            ->when(! empty($f['branch_id']), fn (Builder $q) => $q->where('e.branch_id', $f['branch_id']))
            ->when(! empty($f['currency_id']), fn (Builder $q) => $q->where('l.currency_id', $f['currency_id']));
    }

    /**
     * Base-currency debit − credit per account for lines matching the date bounds.
     *
     * @param  array<string, mixed>  $f
     * @return Collection<int, BigDecimal> account_id => balance
     */
    protected function balances(array $f, ?string $from, ?string $to, bool $fromExclusive = false): Collection
    {
        return $this->lines($f)
            ->when($from, fn (Builder $q) => $q->where('e.date', $fromExclusive ? '<' : '>=', $from))
            ->when($to, fn (Builder $q) => $q->where('e.date', '<=', $to))
            ->groupBy('l.account_id')
            ->selectRaw('l.account_id, SUM(l.debit_base) AS d, SUM(l.credit_base) AS c')
            ->get()
            ->mapWithKeys(fn ($r) => [(int) $r->account_id => Money::of((string) $r->d)->minus(Money::of((string) $r->c))]);
    }

    /**
     * The account tree in code order, each with its depth.
     *
     * @return Collection<int, array{account: Account, depth: int}>
     */
    protected function tree(): Collection
    {
        $accounts = Account::query()->orderBy('code')->get();
        $byParent = $accounts->groupBy(fn (Account $a) => $a->parent_id ?? 0);
        $rows = collect();

        $walk = function (int $parentId, int $depth) use (&$walk, $byParent, $rows) {
            foreach ($byParent->get($parentId, collect()) as $account) {
                $rows->push(['account' => $account, 'depth' => $depth]);
                $walk($account->id, $depth + 1);
            }
        };
        $walk(0, 0);

        return $rows;
    }

    /**
     * Roll leaf figures up to every ancestor.
     *
     * @param  Collection<int, BigDecimal>  $leafValues
     * @return array<int, BigDecimal>
     */
    protected function rollUp(Collection $leafValues): array
    {
        $parents = Account::query()->pluck('parent_id', 'id');
        $totals = [];

        foreach ($leafValues as $accountId => $value) {
            $id = $accountId;
            while ($id !== null) {
                $totals[$id] = ($totals[$id] ?? Money::zero())->plus($value);
                $id = $parents[$id] ?? null;
            }
        }

        return $totals;
    }

    /**
     * An account and all its descendants.
     *
     * @return list<int>
     */
    protected function withDescendants(int $accountId): array
    {
        $byParent = Account::query()->get(['id', 'parent_id'])->groupBy('parent_id');
        $ids = [];
        $stack = [$accountId];

        while ($stack !== []) {
            $id = array_pop($stack);
            $ids[] = $id;
            foreach ($byParent->get($id, collect()) as $child) {
                $stack[] = $child->id;
            }
        }

        return $ids;
    }
}
