<?php

namespace App\Reports;

use App\Support\Money;
use Brick\Math\BigDecimal;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Trial balance in the base currency, per posting account, from journal lines.
 */
class TrialBalance
{
    public function __construct(
        private readonly ?CarbonInterface $to = null,
        private readonly ?CarbonInterface $from = null,
        private readonly ?int $branchId = null,
    ) {}

    /**
     * @return Collection<int, TrialBalanceRow>
     */
    public function rows(): Collection
    {
        return DB::table('journal_lines as l')
            ->join('journal_entries as e', 'e.id', '=', 'l.entry_id')
            ->join('accounts as a', 'a.id', '=', 'l.account_id')
            ->when($this->from, fn ($q) => $q->where('e.date', '>=', $this->from->toDateString()))
            ->when($this->to, fn ($q) => $q->where('e.date', '<=', $this->to->toDateString()))
            ->when($this->branchId, fn ($q) => $q->where('e.branch_id', $this->branchId))
            ->groupBy('a.id', 'a.code', 'a.name')
            ->orderBy('a.code')
            ->selectRaw('a.id AS account_id, a.code, a.name, SUM(l.debit_base) AS debit, SUM(l.credit_base) AS credit')
            ->get()
            ->map(fn (object $row) => new TrialBalanceRow(
                accountId: (int) $row->account_id,
                code: (string) $row->code,
                name: (string) $row->name,
                debit: Money::of((string) $row->debit),
                credit: Money::of((string) $row->credit),
            ));
    }

    /**
     * @return array{debit: BigDecimal, credit: BigDecimal}
     */
    public function totals(): array
    {
        $rows = $this->rows();

        return [
            'debit' => Money::sum($rows->pluck('debit')->all()),
            'credit' => Money::sum($rows->pluck('credit')->all()),
        ];
    }

    public function isBalanced(): bool
    {
        $totals = $this->totals();

        return $totals['debit']->isEqualTo($totals['credit']);
    }
}
