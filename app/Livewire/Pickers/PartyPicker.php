<?php

namespace App\Livewire\Pickers;

use App\Livewire\Concerns\AcceptsQuickCreate;
use App\Models\Party;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

/**
 * Searchable party selector: <livewire:pickers.party-picker wire:model="party_id" kind="supplier" />
 * With :allow-create="true" a "+" button opens the quick-create modal (full party form) when the
 * user may manage parties; the new party is then selected here.
 */
class PartyPicker extends Component
{
    use AcceptsQuickCreate;

    #[Modelable]
    public ?int $value = null;

    /** customer | supplier | any */
    public string $kind = 'any';

    public bool $allowCreate = false;

    public string $search = '';

    public bool $open = false;

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

    /** The quick-create modal answered this picker: select the new party. */
    protected function applyQuickCreated(string $type, int $id, string $target): void
    {
        if ($type === 'party') {
            $this->choose($id);
        }
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
