<?php

namespace App\Livewire\Parties;

use App\Enums\PartyType;
use App\Livewire\Concerns\Notifies;
use App\Models\Party;
use App\Services\Trash\RecycleBin;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use Notifies, WithFileUploads, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $type = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    /** @var array<string, mixed> */
    public array $form = [];

    /** @var TemporaryUploadedFile|null */
    public $idCard = null;

    public function mount(): void
    {
        $this->authorize('viewAny', Party::class);

        // ?new=1 (sidebar / quick-add menu) opens the form straight away.
        if (request()->boolean('new') && auth()->user()->can('create', Party::class)) {
            $this->create();
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', Party::class);
        $this->editingId = null;
        $this->form = ['type' => PartyType::Customer->value, 'name' => '', 'phone' => '', 'phone2' => '', 'national_id' => '', 'address' => '', 'credit_limit' => '0', 'notes' => '', 'is_active' => true];
        $this->idCard = null;
        $this->resetValidation();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $party = Party::query()->findOrFail($id);
        $this->authorize('update', $party);

        $this->editingId = $party->id;
        $this->form = [
            'type' => $party->type->value, 'name' => $party->name, 'phone' => (string) $party->phone, 'phone2' => (string) $party->phone2,
            'national_id' => (string) $party->national_id, 'address' => (string) $party->address,
            'credit_limit' => (string) $party->credit_limit, 'notes' => (string) $party->notes, 'is_active' => $party->is_active,
        ];
        $this->idCard = null;
        $this->resetValidation();
        $this->showForm = true;
    }

    public function save(): void
    {
        $party = $this->editingId ? Party::query()->findOrFail($this->editingId) : null;
        $party ? $this->authorize('update', $party) : $this->authorize('create', Party::class);

        $data = $this->validate([
            'form.type' => ['required', Rule::enum(PartyType::class)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.phone2' => ['nullable', 'string', 'max:50'],
            'form.national_id' => ['nullable', 'string', 'max:50'],
            'form.address' => ['nullable', 'string', 'max:255'],
            'form.credit_limit' => ['required', 'numeric', 'min:0', 'decimal:0,3'],
            'form.notes' => ['nullable', 'string', 'max:2000'],
            'form.is_active' => ['boolean'],
            'idCard' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
        ])['form'];

        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);
        $party ??= new Party(['branch_id' => Auth::user()->branch_id]);
        $party->fill($data)->save();

        if ($this->idCard !== null) {
            $party->addMedia($this->idCard->getRealPath())
                ->usingFileName($this->idCard->hashName())
                ->toMediaCollection('id_card');
        }

        $this->showForm = false;
        $this->notify(__('app.saved'));
    }

    public function delete(int $id): void
    {
        $party = Party::query()->findOrFail($id);
        $this->authorize('delete', $party);

        app(RecycleBin::class)->keep($party, fn () => $party->delete());
        $this->notify(__('app.deleted'));
    }

    public function render(): View
    {
        return view('livewire.parties.index', [
            'parties' => Party::query()
                ->with('media')
                ->when($this->search !== '', fn ($q) => $q->search($this->search))
                ->when($this->type === 'customer', fn ($q) => $q->customers())
                ->when($this->type === 'supplier', fn ($q) => $q->suppliers())
                ->orderBy('name')
                ->paginate(20),
            'types' => PartyType::cases(),
        ])->title(__('app.nav.parties'));
    }
}
