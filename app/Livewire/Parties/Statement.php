<?php

namespace App\Livewire\Parties;

use App\Enums\AccountRole;
use App\Models\Party;
use App\Reports\PartyStatement;
use App\Services\Accounting\AccountResolver;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Statement of account for a customer or supplier (receivables, payables, deposits).
 */
#[Layout('layouts.app')]
class Statement extends Component
{
    public Party $party;

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    /** all | receivables | payables | customer_deposits */
    #[Url]
    public string $account = 'all';

    public function mount(Party $party): void
    {
        $this->authorize('viewStatement', $party);
        $this->party = $party;
        $this->from = $this->from ?: now()->startOfYear()->toDateString();
        $this->to = $this->to ?: now()->toDateString();
    }

    /**
     * @return list<int>
     */
    private function accountIds(AccountResolver $accounts): array
    {
        return match ($this->account) {
            'receivables' => [$accounts->idFor(AccountRole::Receivables)],
            'payables' => [$accounts->idFor(AccountRole::Payables)],
            'customer_deposits' => [$accounts->idFor(AccountRole::CustomerDeposits)],
            default => $accounts->partyAccountIds(),
        };
    }

    public function render(AccountResolver $accounts): View
    {
        $statement = new PartyStatement(
            $this->party,
            $this->accountIds($accounts),
            $this->from ? CarbonImmutable::parse($this->from) : null,
            $this->to ? CarbonImmutable::parse($this->to) : null,
        );

        return view('livewire.parties.statement', [
            'opening' => $statement->opening(),
            'lines' => $statement->lines(),
            'closing' => $statement->closing(),
            'byCurrency' => $statement->balancesByCurrency(),
        ])->title(__('parties.statement_of', ['name' => $this->party->name]));
    }
}
