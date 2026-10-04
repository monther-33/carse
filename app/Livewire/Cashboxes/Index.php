<?php

namespace App\Livewire\Cashboxes;

use App\Actions\Accounting\SaveCashbox;
use App\Enums\CashboxType;
use App\Livewire\Concerns\Notifies;
use App\Models\Branch;
use App\Models\Cashbox;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    use Notifies;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $type = 'cash';

    public ?int $currency_id = null;

    public ?int $branch_id = null;

    public string $bank_name = '';

    public string $account_number = '';

    public bool $is_active = true;

    /** @var list<int> */
    public array $user_ids = [];

    public function mount(): void
    {
        $this->authorize('viewAny', Cashbox::class);
    }

    public function create(): void
    {
        $this->authorize('create', Cashbox::class);
        $this->resetForm();
        $this->branch_id = auth()->user()->branch_id;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $cashbox = Cashbox::query()->with('users')->findOrFail($id);
        $this->authorize('update', $cashbox);

        $this->resetForm();
        $this->editingId = $cashbox->id;
        $this->name = $cashbox->name;
        $this->type = $cashbox->type->value;
        $this->currency_id = $cashbox->currency_id;
        $this->branch_id = $cashbox->branch_id;
        $this->bank_name = (string) $cashbox->bank_name;
        $this->account_number = (string) $cashbox->account_number;
        $this->is_active = $cashbox->is_active;
        $this->user_ids = $cashbox->users->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->showForm = true;
    }

    public function save(SaveCashbox $action): void
    {
        $cashbox = $this->editingId ? Cashbox::query()->with('account')->findOrFail($this->editingId) : null;
        $cashbox ? $this->authorize('update', $cashbox) : $this->authorize('create', Cashbox::class);

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(CashboxType::class)],
            'currency_id' => ['required', 'exists:currencies,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
            'user_ids' => ['array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $action->handle($data, $cashbox);

        $this->showForm = false;
        $this->notify(__('app.saved'));
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'type', 'currency_id', 'branch_id', 'bank_name', 'account_number', 'is_active', 'user_ids']);
        $this->resetValidation();
    }

    public function render(): View
    {
        $user = auth()->user();

        return view('livewire.cashboxes.index', [
            'cashboxes' => Cashbox::query()
                ->visibleTo($user)
                ->with(['currency', 'account', 'branch', 'users'])
                ->orderBy('name')
                ->get(),
            'currencies' => Currency::query()->active()->orderByDesc('is_base')->get(),
            'branches' => Branch::query()->where('is_active', true)->orderBy('name')->get(),
            'users' => User::query()->where('is_active', true)->orderBy('name')->get(),
            'types' => CashboxType::cases(),
        ])->title(__('app.nav.cashboxes'));
    }
}
