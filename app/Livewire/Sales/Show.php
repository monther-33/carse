<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\CancelSalesInvoice;
use App\Actions\Sales\DeleteSalesDraft;
use App\Actions\Sales\DeliverSalesInvoice;
use App\Actions\Sales\PostSalesInvoice;
use App\Actions\Sales\ReturnSalesItem;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Voucher;
use App\Services\Ownership\NetPriceCheck;
use App\Services\Sales\CreditLimitCheck;
use App\Support\Money;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    use HandlesBusinessErrors, Notifies;

    public SalesInvoice $invoice;

    public bool $showCancel = false;

    public bool $showReturn = false;

    public ?int $returnItemId = null;

    public string $reason = '';

    public function mount(SalesInvoice $invoice): void
    {
        $this->authorize('view', $invoice);
        $this->invoice = $invoice;
    }

    public function approve(PostSalesInvoice $action): void
    {
        $this->authorize('approve', $this->invoice);

        if ($this->attempt(fn () => $action->handle($this->invoice)) !== null) {
            $this->invoice->refresh();
            $this->notify(__('documents.posted_ok'));
        }
    }

    public function deliver(DeliverSalesInvoice $action): void
    {
        $this->authorize('deliver', $this->invoice);

        if ($this->attempt(fn () => $action->handle($this->invoice)) !== null) {
            $this->invoice->refresh();
            $this->notify(__('sales.delivered_ok'));
        }
    }

    public function delete(DeleteSalesDraft $action): void
    {
        $this->authorize('delete', $this->invoice);

        $action->handle($this->invoice);
        $this->notify(__('app.deleted'));
        $this->redirectRoute('sales.index', navigate: true);
    }

    public function openCancel(): void
    {
        $this->authorize('cancel', $this->invoice);
        $this->reset('reason');
        $this->resetValidation();
        $this->showCancel = true;
    }

    public function cancel(CancelSalesInvoice $action): void
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

    public function returnItem(ReturnSalesItem $action): void
    {
        $this->authorize('returnItem', $this->invoice);
        $this->validate(['reason' => ['required', 'string', 'max:255']]);

        $item = SalesInvoiceItem::query()->where('invoice_id', $this->invoice->id)->findOrFail($this->returnItemId);

        if ($this->attempt(fn () => $action->handle($item, $this->reason), 'reason') !== null) {
            $this->showReturn = false;
            $this->notify(__('sales.returned_ok'));
        }
    }

    public function render(CreditLimitCheck $credit, NetPriceCheck $netPrice): View
    {
        $this->invoice->load([
            'party', 'salesperson', 'currency', 'reservation', 'items.vehicle.brand', 'items.vehicle.carModel', 'items.returnDocument',
            'tradeIn.vehicle.brand', 'tradeIn.vehicle.carModel', 'payments.cashbox', 'installmentPlan.installments', 'installmentPlan.guarantor',
            'journalEntry', 'approver', 'canceller', 'creator', 'deliverer',
        ]);

        $plan = $this->invoice->installmentPlan;

        // Credit limit (warning only): for a draft, the part of this sale that would stay owed.
        $i = $this->invoice;
        $creditWarning = $i->isDraft() ? $credit->warning($i->party, Money::toBase(
            Money::of($i->total)->minus(Money::of($i->trade_in_value))->minus(Money::of($i->deposit_applied))->minus(Money::of($i->paid)),
            $i->rate,
        )) : null;

        $netPriceWarnings = $i->isDraft() && auth()->user()->can('vehicles.view_cost')
            ? $netPrice->warnings($i->items->map(fn ($item) => ['vehicle' => $item->vehicle, 'net_base' => Money::of($item->net_base)])->values()->all())
            : [];

        return view('livewire.sales.show', [
            'creditWarning' => $creditWarning,
            'netPriceWarnings' => $netPriceWarnings,
            'canViewCost' => auth()->user()->can('vehicles.view_cost'),
            'vouchers' => Voucher::query()
                ->where(fn ($q) => $q->whereMorphedTo('reference', $this->invoice)
                    ->when($plan, fn ($q) => $q->orWhere(fn ($q) => $q->whereMorphedTo('reference', $plan))))
                ->with('cashbox')
                ->latest('id')
                ->get(),
        ])->title(__('sales.invoice', ['ref' => $this->invoice->displayNumber()]));
    }
}
