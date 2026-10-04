<?php

namespace App\Livewire\Pickers;

use App\Enums\PartyType;
use App\Models\Party;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Modelable;
use Livewire\Component;

/**
 * Searchable party selector: <livewire:pickers.party-picker wire:model="party_id" kind="supplier" />
 * With :allow-create="true" a new party (name + phone) can be added inline when the user may manage parties.
 */
class PartyPicker extends Component
{
    #[Modelable]
    public ?int $value = null;

    /** customer | supplier | any */
    public string $kind = 'any';

    public bool $allowCreate = false;

    public string $search = '';

    public bool $open = false;

    public string $newName = '';

    public string $newPhone = '';

    public function updatedSearch(): void
    {
        $this->open = true;
    }

    public function choose(int $id): void
    {
        $this->value = $id;
        $this->search = '';
        $this->open = false;
    }

    public function clear(): void
    {
        $this->value = null;
    }

    public function createParty(): void
    {
        abort_unless(Auth::user()?->can('parties.manage'), 403);

        $data = $this->validate([
            'newName' => ['required', 'string', 'max:255'],
            'newPhone' => ['nullable', 'string', 'max:50'],
        ]);

        $party = Party::query()->create([
            'branch_id' => Auth::user()->branch_id,
            'type' => $this->kind === 'supplier' ? PartyType::Supplier : PartyType::Customer,
            'name' => $data['newName'],
            'phone' => $data['newPhone'] ?: null,
        ]);

        $this->reset(['newName', 'newPhone']);
        $this->choose($party->id);
    }

    public function render(): View
    {
        $query = Party::query()->where('is_active', true);
        $query = match ($this->kind) {
            'customer' => $query->customers(),
            'supplier' => $query->suppliers(),
            default => $query,
        };

        return view('livewire.pickers.party-picker', [
            'selected' => $this->value ? Party::withTrashed()->find($this->value) : null,
            'results' => $this->search !== '' ? $query->search($this->search)->orderBy('name')->limit(8)->get() : collect(),
        ]);
    }
}
