<?php

namespace App\Livewire\Users;

use App\Actions\Users\SaveUser;
use App\Livewire\Concerns\Notifies;
use App\Models\Branch;
use App\Models\Cashbox;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Index extends Component
{
    use Notifies, WithPagination;

    #[Url]
    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public ?int $branch_id = null;

    public string $max_discount = '0';

    public bool $is_active = true;

    /** @var list<string> */
    public array $roles = [];

    /** @var list<int> */
    public array $cashbox_ids = [];

    public function mount(): void
    {
        $this->authorize('viewAny', User::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', User::class);
        $this->resetForm();
        $this->branch_id = auth()->user()->branch_id;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $user = User::query()->with(['roles', 'cashboxes'])->findOrFail($id);
        $this->authorize('update', $user);

        $this->resetForm();
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->branch_id = $user->branch_id;
        $this->max_discount = (string) $user->max_discount;
        $this->is_active = $user->is_active;
        $this->roles = $user->roles->pluck('name')->all();
        $this->cashbox_ids = $user->cashboxes->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->showForm = true;
    }

    public function save(SaveUser $action): void
    {
        $user = $this->editingId ? User::query()->findOrFail($this->editingId) : null;
        $user ? $this->authorize('update', $user) : $this->authorize('create', User::class);

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'password' => [$user ? 'nullable' : 'required', Password::min(8)],
            'branch_id' => ['required', 'exists:branches,id'],
            'max_discount' => ['required', 'numeric', 'min:0', 'decimal:0,3'],
            'is_active' => ['boolean'],
            'roles' => ['array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
            'cashbox_ids' => ['array'],
            'cashbox_ids.*' => ['integer', 'exists:cashboxes,id'],
        ]);

        if ($user !== null && $user->is(auth()->user()) && ! $data['is_active']) {
            $this->addError('is_active', __('app.users.cannot_deactivate_self'));

            return;
        }

        $action->handle($data, $user);

        $this->showForm = false;
        $this->notify(__('app.saved'));
    }

    public function toggleActive(int $id, SaveUser $action): void
    {
        $user = User::query()->findOrFail($id);
        $this->authorize('toggleActive', $user);

        $action->toggleActive($user);
        $this->notify($user->is_active ? __('app.users.activated') : __('app.users.deactivated'));
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'email', 'password', 'branch_id', 'max_discount', 'is_active', 'roles', 'cashbox_ids']);
        $this->resetValidation();
    }

    public function render(): View
    {
        $users = User::query()
            ->with(['branch', 'roles'])
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.users.index', [
            'users' => $users,
            'branches' => Branch::query()->where('is_active', true)->orderBy('name')->get(),
            'allRoles' => Role::query()->orderBy('id')->pluck('name'),
            'cashboxes' => Cashbox::query()->where('is_active', true)->orderBy('name')->get(),
        ])->title(__('app.nav.users'));
    }
}
