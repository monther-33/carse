<?php

namespace App\Livewire\Sales;

use App\Enums\DocumentStatus;
use App\Enums\PaymentType;
use App\Models\SalesInvoice;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Sales invoices. Sales staff only see their own.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $paymentType = '';

    public function mount(): void
    {
        $this->authorize('viewAny', SalesInvoice::class);
    }

    public function updating(string $name): void
    {
        if (in_array($name, ['search', 'status', 'paymentType'], true)) {
            $this->resetPage();
        }
    }

    public function render(): View
    {
        return view('livewire.sales.index', [
            'invoices' => SalesInvoice::query()
                ->visibleTo(auth()->user())
                ->with(['party', 'salesperson', 'currency', 'items.vehicle.brand', 'items.vehicle.carModel'])
                ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
                ->when($this->paymentType !== '', fn ($q) => $q->where('payment_type', $this->paymentType))
                ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q
                    ->where('number', 'like', "%{$this->search}%")
                    ->orWhereHas('party', fn ($p) => $p->where('name', 'like', "%{$this->search}%")->orWhere('phone', 'like', "%{$this->search}%"))
                    ->orWhereHas('items.vehicle', fn ($v) => $v->where('vin', 'like', "%{$this->search}%")->orWhere('plate_no', 'like', "%{$this->search}%"))))
                ->latest('date')->latest('id')
                ->paginate(20),
            'statuses' => DocumentStatus::cases(),
            'paymentTypes' => PaymentType::cases(),
        ])->title(__('app.nav.sales'));
    }
}
