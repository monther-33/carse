<?php

namespace App\Livewire\Accounts;

use App\Actions\Accounting\DeleteAccount;
use App\Actions\Accounting\SaveAccount;
use App\Livewire\Concerns\Notifies;
use App\Models\Account;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    use Notifies;

    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public ?int $parent_id = null;

    public string $code = '';

    public string $name = '';

    public bool $is_group = false;

    public bool $is_active = true;

    public function mount(): void
    {
        $this->authorize('viewAny', Account::class);
    }

    public function create(?int $parentId, SaveAccount $action): void
    {
        $this->authorize('create', Account::class);
        $this->resetForm();

        $this->parent_id = $parentId;
        if ($parentId !== null) {
            $this->code = $action->nextChildCode(Account::query()->findOrFail($parentId));
        }
        $this->showForm = true;
    }

    public function updatedParentId(mixed $value): void
    {
        if ($this->editingId === null && $value) {
            $this->code = app(SaveAccount::class)->nextChildCode(Account::query()->findOrFail((int) $value));
        }
    }

    public function edit(int $id): void
    {
        $account = Account::query()->findOrFail($id);
        $this->authorize('update', $account);

        $this->resetForm();
        $this->editingId = $account->id;
        $this->parent_id = $account->parent_id;
        $this->code = $account->code;
        $this->name = $account->name;
        $this->is_group = $account->is_group;
        $this->is_active = $account->is_active;
        $this->showForm = true;
    }

    public function save(SaveAccount $action): void
    {
        $account = $this->editingId ? Account::query()->findOrFail($this->editingId) : null;
        $account ? $this->authorize('update', $account) : $this->authorize('create', Account::class);

        $data = $this->validate([
            'parent_id' => [$account?->parent_id === null && $account !== null ? 'nullable' : 'required', 'integer', 'exists:accounts,id'],
            'code' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/', Rule::unique('accounts', 'code')->ignore($this->editingId)],
            'name' => ['required', 'string', 'max:255'],
            'is_group' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $action->handle($data, $account);

        $this->showForm = false;
        $this->notify(__('app.saved'));
    }

    public function delete(int $id, DeleteAccount $action): void
    {
        $account = Account::query()->findOrFail($id);
        $this->authorize('delete', $account);

        $action->handle($account);
        $this->notify(__('app.deleted'));
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'parent_id', 'code', 'name', 'is_group', 'is_active']);
        $this->resetValidation();
    }

    public function render(): View
    {
        $accounts = Account::query()->orderBy('code')->get();

        $matches = null;
        if ($this->search !== '') {
            $matches = $accounts->filter(fn (Account $a) => str_contains($a->code, $this->search) || str_contains($a->name, $this->search));
        }

        return view('livewire.accounts.index', [
            'tree' => $this->buildTree($accounts),
            'matches' => $matches,
            'groups' => $accounts->where('is_group', true)->where('id', '!=', $this->editingId),
        ])->title(__('app.nav.accounts'));
    }

    /**
     * @param  Collection<int, Account>  $accounts
     * @return Collection<int, array{account: Account, depth: int}>
     */
    private function buildTree(Collection $accounts): Collection
    {
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
}
