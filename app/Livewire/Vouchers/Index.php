<?php

namespace App\Livewire\Vouchers;

use App\Actions\DeleteDraft;
use App\Actions\Vouchers\CancelVoucher;
use App\Actions\Vouchers\PostVoucher;
use App\Actions\Vouchers\SaveVoucher;
use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\VoucherType;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Account;
use App\Models\Cashbox;
use App\Models\PurchaseInvoice;
use App\Models\Voucher;
use App\Services\Accounting\AccountResolver;
use App\Support\Settings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Receipt, payment and transfer vouchers. The "purpose" picks the counter account
 * (receivables, payables, deposits, commissions or any account for "other").
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use HandlesBusinessErrors, Notifies, WithPagination;

    /** Purposes per voucher type → account role (null = choose an account). */
    public const PURPOSES = [
        'receipt' => ['customer' => AccountRole::Receivables, 'deposit' => AccountRole::CustomerDeposits, 'supplier_refund' => AccountRole::Payables, 'other' => null],
        'payment' => ['supplier' => AccountRole::Payables, 'customer_refund' => AccountRole::Receivables, 'deposit_refund' => AccountRole::CustomerDeposits, 'commissions' => AccountRole::AccruedCommissions, 'other' => null],
    ];

    #[Url]
    public string $type = '';

    #[Url]
    public string $status = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    /** @var array<string, mixed> */
    public array $form = [];

    public bool $showCancel = false;

    public ?int $cancelId = null;

    public string $reason = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Voucher::class);
    }

    /** @return list<int> */
    private function visibleCashboxIds(): array
    {
        return Cashbox::query()->visibleTo(auth()->user())->pluck('id')->map(fn ($id) => (int) $id)->all();
    }

    private function findVisible(int $id): Voucher
    {
        return Voucher::query()->whereIn('cashbox_id', $this->visibleCashboxIds())->findOrFail($id);
    }

    public function create(string $type = 'receipt'): void
    {
        $this->authorize('create', Voucher::class);
        $this->editingId = null;
        $this->form = [
            'type' => $type, 'purpose' => $type === 'payment' ? 'supplier' : 'customer', 'date' => now()->toDateString(),
            'party_id' => null, 'cashbox_id' => null, 'to_cashbox_id' => null, 'account_id' => null,
            'amount' => '', 'rate' => '', 'description' => '', 'reference_id' => null,
        ];
        $this->resetValidation();
        $this->showForm = true;
    }

    public function edit(int $id, AccountResolver $accounts): void
    {
        $voucher = $this->findVisible($id);
        $this->authorize('update', $voucher);

        $purpose = 'other';
        foreach (self::PURPOSES[$voucher->type->value] ?? [] as $key => $role) {
            if ($role !== null && $voucher->account_id === $accounts->idFor($role)) {
                $purpose = $key;
                break;
            }
        }

        $this->editingId = $voucher->id;
        $this->form = $voucher->only(['party_id', 'cashbox_id', 'to_cashbox_id', 'account_id', 'description'])
            + ['type' => $voucher->type->value, 'purpose' => $purpose, 'date' => $voucher->date->toDateString(),
                'amount' => (string) $voucher->amount, 'rate' => (string) $voucher->rate, 'reference_id' => $voucher->reference_id];
        $this->resetValidation();
        $this->showForm = true;
    }

    public function save(SaveVoucher $save, PostVoucher $post, AccountResolver $accounts, Settings $settings): void
    {
        $voucher = $this->editingId ? $this->findVisible($this->editingId) : null;
        $voucher ? $this->authorize('update', $voucher) : $this->authorize('create', Voucher::class);

        $visible = $this->visibleCashboxIds();
        $isTransfer = ($this->form['type'] ?? '') === VoucherType::Transfer->value;

        $data = $this->validate([
            'form.type' => ['required', Rule::in(['receipt', 'payment', 'transfer'])],
            'form.purpose' => [$isTransfer ? 'nullable' : 'required', 'string'],
            'form.date' => ['required', 'date'],
            'form.cashbox_id' => ['required', Rule::in($visible)],
            'form.to_cashbox_id' => [$isTransfer ? 'required' : 'nullable', 'exists:cashboxes,id', 'different:form.cashbox_id'],
            'form.party_id' => ['nullable', 'exists:parties,id'],
            'form.account_id' => ['nullable', 'exists:accounts,id'],
            'form.amount' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'form.rate' => ['nullable', 'numeric', 'gt:0', 'decimal:0,6'],
            'form.description' => ['required', 'string', 'max:255'],
            'form.reference_id' => ['nullable', 'integer'],
        ])['form'];

        if (! $isTransfer) {
            $role = self::PURPOSES[$data['type']][$data['purpose']] ?? null;
            $data['account_id'] = $role !== null ? $accounts->idFor($role) : $data['account_id'];
        }

        // Supplier payments may be tied to one purchase invoice (settled at that invoice's rate).
        $data['reference_type'] = null;
        if (! empty($data['reference_id']) && ($data['purpose'] ?? null) === 'supplier') {
            $invoice = PurchaseInvoice::query()->posted()->where('party_id', $data['party_id'])->find($data['reference_id']);
            $data['reference_type'] = $invoice?->getMorphClass();
            $data['reference_id'] = $invoice?->id;
        } else {
            $data['reference_id'] = null;
        }

        $voucher = $this->attempt(fn () => $save->handle($data, $voucher), 'form.amount');
        if ($voucher === null) {
            return;
        }

        if (! $settings->bool('documents.require_approval', true) && auth()->user()->can('approve', $voucher)) {
            $this->attempt(fn () => $post->handle($voucher), 'form.amount');
        }

        $this->showForm = false;
        $this->notify(__('app.saved'));
    }

    public function approve(int $id, PostVoucher $action): void
    {
        $voucher = $this->findVisible($id);
        $this->authorize('approve', $voucher);

        if ($this->attempt(fn () => $action->handle($voucher)) !== null) {
            $this->notify(__('documents.posted_ok'));
        }
    }

    public function delete(int $id, DeleteDraft $action): void
    {
        $voucher = $this->findVisible($id);
        $this->authorize('delete', $voucher);

        $action->handle($voucher);
        $this->notify(__('app.deleted'));
    }

    public function openCancel(int $id): void
    {
        $this->authorize('cancel', $this->findVisible($id));
        $this->cancelId = $id;
        $this->reset('reason');
        $this->resetValidation();
        $this->showCancel = true;
    }

    public function cancel(CancelVoucher $action): void
    {
        $voucher = $this->findVisible((int) $this->cancelId);
        $this->authorize('cancel', $voucher);
        $this->validate(['reason' => ['required', 'string', 'max:255']]);

        if ($this->attempt(fn () => $action->handle($voucher, $this->reason), 'reason') !== null) {
            $this->showCancel = false;
            $this->notify(__('documents.cancelled_ok'));
        }
    }

    /**
     * Invoices a voucher of the current form can be tied to.
     *
     * @return Collection<int, PurchaseInvoice>
     */
    private function referenceOptions(): Collection
    {
        if (($this->form['purpose'] ?? null) !== 'supplier' || empty($this->form['party_id'])) {
            return collect();
        }

        return PurchaseInvoice::query()->posted()->where('party_id', $this->form['party_id'])->latest('date')->limit(30)->get();
    }

    public function render(): View
    {
        $cashboxIds = $this->visibleCashboxIds();

        return view('livewire.vouchers.index', [
            'vouchers' => Voucher::query()
                ->with(['party', 'cashbox', 'toCashbox', 'account', 'currency'])
                ->whereIn('cashbox_id', $cashboxIds)
                ->when($this->type !== '', fn ($q) => $q->where('type', $this->type))
                ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
                ->latest('date')->latest('id')
                ->paginate(20),
            'cashboxes' => Cashbox::query()->whereIn('id', $cashboxIds)->where('is_active', true)->with('currency')->orderBy('name')->get(),
            'allCashboxes' => Cashbox::query()->where('is_active', true)->with('currency')->orderBy('name')->get(),
            'accounts' => Account::query()->postable()->orderBy('code')->get(),
            'purposes' => self::PURPOSES,
            'statuses' => DocumentStatus::cases(),
            'referenceOptions' => $this->referenceOptions(),
        ])->title(__('app.nav.vouchers'));
    }
}
