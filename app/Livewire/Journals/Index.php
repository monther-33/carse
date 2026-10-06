<?php

namespace App\Livewire\Journals;

use App\Actions\DeleteDraft;
use App\Actions\Journals\PostManualJournal;
use App\Actions\Journals\SaveManualJournal;
use App\Enums\DocumentStatus;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Account;
use App\Models\Currency;
use App\Models\ManualJournal;
use App\Models\Party;
use App\Services\Accounting\AccountResolver;
use App\Support\Money;
use App\Support\Settings;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Manual journal entries: capital, partner drawings, opening balances, adjustments.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use HandlesBusinessErrors, Notifies, WithPagination;

    #[Url]
    public string $status = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $date = '';

    public string $description = '';

    /** @var list<array{account_id: int|null, party_id: int|null, debit: string, credit: string, currency_id: int|null, rate: string, memo: string}> */
    public array $lines = [];

    public bool $showCancel = false;

    public ?int $cancelId = null;

    public string $reason = '';

    public function mount(): void
    {
        $this->authorize('viewAny', ManualJournal::class);

        // ?new=1 (sidebar / quick-add menu) opens the form straight away.
        if (request()->boolean('new') && auth()->user()->can('create', ManualJournal::class)) {
            $this->create();
        }
    }

    /** @return array{account_id: int|null, party_id: int|null, debit: string, credit: string, currency_id: int|null, rate: string, memo: string} */
    private function blankLine(): array
    {
        return ['account_id' => null, 'party_id' => null, 'debit' => '', 'credit' => '', 'currency_id' => null, 'rate' => '', 'memo' => ''];
    }

    public function create(): void
    {
        $this->authorize('create', ManualJournal::class);
        $this->editingId = null;
        $this->date = now()->toDateString();
        $this->description = '';
        $this->lines = [$this->blankLine(), $this->blankLine()];
        $this->resetValidation();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $journal = ManualJournal::query()->with('lines')->findOrFail($id);
        $this->authorize('update', $journal);

        $this->editingId = $journal->id;
        $this->date = $journal->date->toDateString();
        $this->description = $journal->description;
        $this->lines = $journal->lines->map(fn ($l) => [
            'account_id' => $l->account_id, 'party_id' => $l->party_id,
            'debit' => Money::of($l->debit)->isZero() ? '' : (string) $l->debit,
            'credit' => Money::of($l->credit)->isZero() ? '' : (string) $l->credit,
            'currency_id' => $l->currency_id, 'rate' => (string) $l->rate, 'memo' => (string) $l->memo,
        ])->all();
        $this->resetValidation();
        $this->showForm = true;
    }

    public function addLine(): void
    {
        $this->lines[] = $this->blankLine();
    }

    public function removeLine(int $index): void
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);
    }

    public function save(SaveManualJournal $save, PostManualJournal $post, Settings $settings): void
    {
        $journal = $this->editingId ? ManualJournal::query()->findOrFail($this->editingId) : null;
        $journal ? $this->authorize('update', $journal) : $this->authorize('create', ManualJournal::class);

        $this->validate([
            'date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'exists:accounts,id'],
            'lines.*.party_id' => ['nullable', 'exists:parties,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0', 'decimal:0,3'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0', 'decimal:0,3'],
            'lines.*.currency_id' => ['nullable', 'exists:currencies,id'],
            'lines.*.rate' => ['nullable', 'numeric', 'gt:0', 'decimal:0,6'],
            'lines.*.memo' => ['nullable', 'string', 'max:255'],
        ]);

        $journal = $this->attempt(fn () => $save->handle([
            'date' => $this->date, 'description' => $this->description, 'lines' => $this->lines,
        ], $journal));

        if ($journal === null) {
            return;
        }

        if (! $settings->bool('documents.require_approval', true) && auth()->user()->can('approve', $journal)) {
            $this->attempt(fn () => $post->handle($journal));
        }

        $this->showForm = false;
        $this->notify(__('app.saved'));
    }

    public function approve(int $id, PostManualJournal $action): void
    {
        $journal = ManualJournal::query()->findOrFail($id);
        $this->authorize('approve', $journal);

        if ($this->attempt(fn () => $action->handle($journal)) !== null) {
            $this->notify(__('documents.posted_ok'));
        }
    }

    public function delete(int $id, DeleteDraft $action): void
    {
        $journal = ManualJournal::query()->findOrFail($id);
        $this->authorize('delete', $journal);

        $journal->lines()->delete();
        $action->handle($journal);
        $this->notify(__('app.deleted'));
    }

    public function openCancel(int $id): void
    {
        $this->authorize('cancel', ManualJournal::query()->findOrFail($id));
        $this->cancelId = $id;
        $this->reset('reason');
        $this->resetValidation();
        $this->showCancel = true;
    }

    public function cancel(PostManualJournal $action): void
    {
        $journal = ManualJournal::query()->findOrFail($this->cancelId);
        $this->authorize('cancel', $journal);
        $this->validate(['reason' => ['required', 'string', 'max:255']]);

        if ($this->attempt(fn () => $action->cancel($journal, $this->reason), 'reason') !== null) {
            $this->showCancel = false;
            $this->notify(__('documents.cancelled_ok'));
        }
    }

    public function render(AccountResolver $accounts): View
    {
        $num = fn ($v) => is_numeric($v) ? Money::of((string) $v) : Money::zero();

        return view('livewire.journals.index', [
            'journals' => ManualJournal::query()
                ->with(['lines', 'creator'])
                ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
                ->latest('date')->latest('id')
                ->paginate(20),
            'accounts' => Account::query()->postable()->orderBy('code')->get(),
            'currencies' => Currency::query()->active()->orderByDesc('is_base')->get(),
            'parties' => Party::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'partyAccounts' => $accounts->partyAccountIds(),
            'statuses' => DocumentStatus::cases(),
            'debitTotal' => Money::sum(array_map(fn ($l) => $num($l['debit']), $this->lines)),
            'creditTotal' => Money::sum(array_map(fn ($l) => $num($l['credit']), $this->lines)),
        ])->title(__('app.nav.journals'));
    }
}
