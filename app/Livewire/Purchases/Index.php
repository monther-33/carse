<?php

namespace App\Livewire\Purchases;

use App\Enums\DocumentStatus;
use App\Models\PurchaseInvoice;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    public function mount(): void
    {
        $this->authorize('viewAny', PurchaseInvoice::class);
    }

    public function updating(string $name): void
    {
        if (in_array($name, ['search', 'status'], true)) {
            $this->resetPage();
        }
    }

    public function render(): View
    {
        return view('livewire.purchases.index', [
            'invoices' => PurchaseInvoice::query()
                ->with(['party', 'currency'])
                ->withCount('items')
                ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
                ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q
                    ->where('number', 'like', "%{$this->search}%")
                    ->orWhereHas('party', fn ($p) => $p->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('items.vehicle', fn ($v) => $v->where('vin', 'like', "%{$this->search}%"))))
                ->latest('date')->latest('id')
                ->paginate(20),
            'statuses' => DocumentStatus::cases(),
        ])->title(__('app.nav.purchases'));
    }
}
