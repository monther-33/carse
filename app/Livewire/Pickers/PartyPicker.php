<?php

namespace App\Livewire\Pickers;

use App\Enums\PartyType;
use App\Livewire\Concerns\AcceptsQuickCreate;
use App\Models\Party;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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

    public bool $showSearch = false;

    /** @var array{q: string, type: string} */
    public array $filter = ['q' => '', 'type' => ''];

    public int $limit = 20;

    public ?int $previewId = null;

    public function updatedSearch(): void
    {
        $this->open = true;
    }

    public function choose(int $id): void
    {
        // Only a party this picker offers (active, of its kind) can be chosen.
        $this->value = $this->query()->whereKey($id)->value('id');
        $this->search = '';
        $this->open = false;
        $this->showSearch = false;
        $this->previewId = null;
    }

    /** Opens the search modal, starting from what was typed. */
    public function openSearch(): void
    {
        $this->filter = ['q' => trim($this->search), 'type' => ''];
        $this->limit = 20;
        $this->previewId = null;
        $this->open = false;
        $this->showSearch = true;
    }

    public function updatedFilter(): void
    {
        $this->limit = 20;
    }

    public function loadMore(): void
    {
        $this->limit += 20;
    }

    public function preview(int $id): void
    {
        $this->previewId = $this->query()->whereKey($id)->value('id');
        $this->showSearch = true;
    }

    public function closePreview(): void
    {
        $this->previewId = null;
    }

    /** @return Builder<Party> */
    private function query()
    {
        $query = Party::query()->where('is_active', true);

        return match ($this->kind) {
            'customer' => $query->customers(),
            'supplier' => $query->suppliers(),
            default => $query,
        };
    }

    public function clear(): void
    {
        $this->value = null;
    }

    /** The quick-create modal answered this picker: select the new party. */
    protected function applyQuickCreated(string $type, int $id, string $target): void
    {
        if ($type === 'party') {
            $this->value = $id;
            $this->search = '';
            $this->open = false;
        }
    }

    public function render(): View
    {
        $search = null;
        if ($this->showSearch) {
            $results = $this->query()
                ->when($this->filter['q'] !== '', fn ($q) => $q->search($this->filter['q']))
                ->when(in_array($this->filter['type'], array_column(PartyType::cases(), 'value'), true), fn ($q) => $q->where('type', $this->filter['type']))
                ->orderBy('name')->limit($this->limit + 1)->get();

            $search = [
                'results' => $results->take($this->limit),
                'hasMore' => $results->count() > $this->limit,
                'preview' => $this->previewId ? Party::query()->find($this->previewId) : null,
            ];
        }

        return view('livewire.pickers.party-picker', [
            'selected' => $this->value ? Party::withTrashed()->find($this->value) : null,
            'results' => $this->search !== '' ? $this->query()->search($this->search)->orderBy('name')->limit(8)->get() : collect(),
            'ps' => $search,
        ]);
    }
}
