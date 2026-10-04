<?php

namespace App\Livewire\Purchases;

use App\Actions\Purchases\CancelPurchaseInvoice;
use App\Actions\Purchases\DeletePurchaseDraft;
use App\Actions\Purchases\PostPurchaseInvoice;
use App\Actions\Purchases\ReturnPurchaseItem;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    use HandlesBusinessErrors, Notifies;

    public PurchaseInvoice $invoice;

    public bool $showCancel = false;

    public bool $showReturn = false;

    public ?int $returnItemId = null;

    public string $reason = '';

    public function mount(PurchaseInvoice $invoice): void
    {
        $this->authorize('view', $invoice);
        $this->invoice = $invoice;
    }

    public function approve(PostPurchaseInvoice $action): void
    {
        $this->authorize('approve', $this->invoice);

        if ($this->attempt(fn () => $action->handle($this->invoice)) !== null) {
            $this->invoice->refresh();
            $this->notify(__('documents.posted_ok'));
        }
    }

    public function delete(DeletePurchaseDraft $action): void
    {
        $this->authorize('delete', $this->invoice);

        $action->handle($this->invoice);
        $this->notify(__('app.deleted'));
        $this->redirectRoute('purchases.index', navigate: true);
    }

    public function openCancel(): void
    {
        $this->authorize('cancel', $this->invoice);
        $this->reset('reason');
        $this->resetValidation();
        $this->showCancel = true;
    }

    public function cancel(CancelPurchaseInvoice $action): void
    {
        $this->authorize('cancel', $this->invoice);
        $this->validate(['reason' => ['required', 'string', 'max:255']]);

        if ($this->attempt(fn () => $action->handle($this->invoice, $this->reason), 'reason') !== null) {
            $this->showCancel = false;
            $this->invoice->refresh();
            $this->notify(__('documents.cancelled_ok'));
        }
    }

    public function openReturn(int $itemId): void
    {
        $this->authorize('returnItem', $this->invoice);
        $this->returnItemId = $itemId;
        $this->reset('reason');
        $this->resetValidation();
        $this->showReturn = true;
    }

    public function returnItem(ReturnPurchaseItem $action): void
    {
        $this->authorize('returnItem', $this->invoice);
        $this->validate(['reason' => ['required', 'string', 'max:255']]);

        $item = PurchaseInvoiceItem::query()->where('invoice_id', $this->invoice->id)->findOrFail($this->returnItemId);

        if ($this->attempt(fn () => $action->handle($item, $this->reason), 'reason') !== null) {
            $this->showReturn = false;
            $this->notify(__('purchases.returned_ok'));
        }
    }

    public function render(): View
    {
        $this->invoice->load(['party', 'currency', 'cashbox', 'items.vehicle.brand', 'items.vehicle.carModel', 'items.returnDocument', 'journalEntry', 'approver', 'canceller', 'creator']);

        return view('livewire.purchases.show', [
            'vouchers' => $this->invoice->vouchers()->with('cashbox')->latest('id')->get(),
        ])->title(__('purchases.invoice', ['ref' => $this->invoice->displayNumber()]));
    }
}
