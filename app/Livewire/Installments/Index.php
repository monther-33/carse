<?php

namespace App\Livewire\Installments;

use App\Actions\Vouchers\PostVoucher;
use App\Actions\Vouchers\SaveVoucher;
use App\Enums\AccountRole;
use App\Enums\InstallmentStatus;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Cashbox;
use App\Models\Installment;
use App\Models\Voucher;
use App\Services\Accounting\AccountResolver;
use App\Support\Settings;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Due and overdue installments, with collection: a receipt voucher on the installment
 * plan, spread over its installments oldest first.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use HandlesBusinessErrors, Notifies, WithPagination;

    /** overdue | week | open | paid */
    #[Url]
    public string $filter = 'overdue';

    public string $search = '';

    public bool $showCollect = false;

    public ?int $installmentId = null;

    /** @var array<string, mixed> */
    public array $form = [];

    public function mount(): void
    {
        abort_unless(auth()->user()->canAny(['vouchers.view', 'sales.view_all']), 403);
    }

    public function updating(string $name): void
    {
        if (in_array($name, ['filter', 'search'], true)) {
            $this->resetPage();
        }
    }

    public function openCollect(int $id): void
    {
        $this->authorize('create', Voucher::class);
        $installment = Installment::query()->findOrFail($id);

        $this->installmentId = $installment->id;
        $this->form = ['date' => now()->toDateString(), 'cashbox_id' => null, 'amount' => (string) $installment->remaining(), 'rate' => ''];
        $this->resetValidation();
        $this->showCollect = true;
    }

    public function collect(SaveVoucher $save, PostVoucher $post, AccountResolver $accounts, Settings $settings): void
    {
        $this->authorize('create', Voucher::class);
        $installment = Installment::query()->with('plan.invoice')->findOrFail($this->installmentId);
        $invoice = $installment->plan->invoice;

        $data = $this->validate([
            'form.date' => ['required', 'date'],
            'form.cashbox_id' => ['required', Rule::in(Cashbox::query()->visibleTo(auth()->user())->where('currency_id', $invoice->currency_id)->pluck('id')->all())],
            'form.amount' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'form.rate' => ['nullable', 'numeric', 'gt:0', 'decimal:0,6'],
        ])['form'];

        $voucher = $this->attempt(fn () => $save->handle($data + [
            'type' => 'receipt',
            'party_id' => $invoice->party_id,
            'account_id' => $accounts->idFor(AccountRole::Receivables),
            'description' => __('installments.collection_description', ['number' => $invoice->number]),
            'reference_type' => $installment->plan->getMorphClass(),
            'reference_id' => $installment->plan_id,
        ]), 'form.amount');

        if ($voucher === null) {
            return;
        }

        $canPost = ! $settings->bool('documents.require_approval', true) || auth()->user()->can('approve', $voucher);
        if ($canPost && $this->attempt(fn () => $post->handle($voucher), 'form.amount') === null) {
            return;
        }

        $this->showCollect = false;
        $this->notify($canPost ? __('installments.collected') : __('installments.collection_pending'));
    }

    public function render(): View
    {
        $query = Installment::query()
            ->with(['plan.invoice.party', 'plan.invoice.currency'])
            ->whereHas('plan.invoice', fn ($q) => $q->posted())
            ->when($this->search !== '', fn ($q) => $q->whereHas('plan.invoice', fn ($i) => $i
                ->where('number', 'like', "%{$this->search}%")
                ->orWhereHas('party', fn ($p) => $p->where('name', 'like', "%{$this->search}%")->orWhere('phone', 'like', "%{$this->search}%"))));

        match ($this->filter) {
            'overdue' => $query->open()->whereDate('due_date', '<', today()),
            'week' => $query->open()->whereBetween('due_date', [today()->toDateString(), today()->addWeek()->toDateString()]),
            'paid' => $query->where('status', InstallmentStatus::Paid),
            default => $query->open(),
        };

        return view('livewire.installments.index', [
            'installments' => $query->orderBy('due_date')->paginate(25),
            'cashboxes' => Cashbox::query()->visibleTo(auth()->user())->where('is_active', true)->with('currency')->orderBy('name')->get(),
        ])->title(__('app.nav.installments'));
    }
}
