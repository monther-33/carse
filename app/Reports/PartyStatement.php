<?php

namespace App\Reports;

use App\Models\Party;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Carbon\CarbonInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Statement of account of one party over the control accounts (receivables, payables,
 * deposits), with an opening balance and a running balance in the base currency.
 * Each line also shows the original currency amount.
 *
 * Signed debit − credit: positive = the party owes us, negative = we owe the party.
 */
class PartyStatement
{
    /**
     * @param  list<int>  $accountIds
     */
    public function __construct(
        private readonly Party $party,
        private readonly array $accountIds,
        private readonly ?CarbonInterface $from = null,
        private readonly ?CarbonInterface $to = null,
    ) {}

    public function opening(): BigDecimal
    {
        if ($this->from === null) {
            return Money::zero();
        }

        $value = $this->base()
            ->where('e.date', '<', $this->from->toDateString())
            ->selectRaw('COALESCE(SUM(l.debit_base - l.credit_base), 0) AS b')
            ->value('b');

        return Money::of((string) $value);
    }

    /**
     * @return Collection<int, \stdClass>
     */
    public function lines(): Collection
    {
        $running = $this->opening();

        return $this->base()
            ->when($this->from, fn ($q) => $q->where('e.date', '>=', $this->from->toDateString()))
            ->when($this->to, fn ($q) => $q->where('e.date', '<=', $this->to->toDateString()))
            ->join('accounts as a', 'a.id', '=', 'l.account_id')
            ->join('currencies as c', 'c.id', '=', 'l.currency_id')
            ->orderBy('e.date')->orderBy('e.id')->orderBy('l.line_no')
            ->select([
                'e.id as entry_id', 'e.date', 'e.number', 'e.description', 'e.status',
                'a.name as account', 'c.code as currency',
                'l.debit', 'l.credit', 'l.debit_base', 'l.credit_base', 'l.rate', 'l.memo',
            ])
            ->get()
            ->map(function (object $row) use (&$running) {
                $running = $running->plus(Money::of((string) $row->debit_base))->minus(Money::of((string) $row->credit_base));
                $row->balance = $running;

                return $row;
            });
    }

    public function closing(): BigDecimal
    {
        $value = $this->base()
            ->when($this->to, fn ($q) => $q->where('e.date', '<=', $this->to->toDateString()))
            ->selectRaw('COALESCE(SUM(l.debit_base - l.credit_base), 0) AS b')
            ->value('b');

        return Money::of((string) $value);
    }

    /**
     * Open balance per currency (original amounts), e.g. ['USD' => 1200.000, 'LYD' => -50.000].
     *
     * @return array<string, BigDecimal>
     */
    public function balancesByCurrency(): array
    {
        return $this->base()
            ->when($this->to, fn ($q) => $q->where('e.date', '<=', $this->to->toDateString()))
            ->join('currencies as c', 'c.id', '=', 'l.currency_id')
            ->groupBy('c.code')
            ->selectRaw('c.code, SUM(l.debit - l.credit) AS amount')
            ->pluck('amount', 'code')
            ->map(fn ($amount) => Money::of((string) $amount))
            ->filter(fn (BigDecimal $amount) => ! $amount->isZero())
            ->all();
    }

    private function base(): Builder
    {
        return DB::table('journal_lines as l')
            ->join('journal_entries as e', 'e.id', '=', 'l.entry_id')
            ->where('l.party_id', $this->party->id)
            ->whereIn('l.account_id', $this->accountIds);
    }
}
