<?php

namespace App\Livewire\Expenses;

use App\Actions\DeleteDraft;
use App\Actions\Expenses\CancelExpense;
use App\Actions\Expenses\PostExpense;
use App\Actions\Expenses\SaveExpense;
use App\Enums\CostBearer;
use App\Enums\DocumentStatus;
use App\Livewire\Concerns\AcceptsQuickCreate;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Cashbox;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vehicle;
use App\Support\Settings;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * Expenses list + form. A treasurer only sees and uses expenses of their own cashboxes.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use AcceptsQuickCreate, HandlesBusinessErrors, Notifies, WithFileUploads, WithPagination;

    #[Url]
    public string $status = '';

    #[Url]
    public bool $dueOnly = false;

    public bool $showForm = false;

    public ?int $editingId = null;

    /** @var array<string, mixed> */
    public array $form = [];

    /** @var TemporaryUploadedFile|null */
    public $receipt = null;

    /** The recurring expense being repeated; its reminder is cleared once the copy is saved. */
    public ?int $repeatOf = null;

    public bool $showCancel = false;

    public ?int $cancelId = null;

    public string $reason = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Expense::class);

        // ?new=1 (quick-add menu) opens the form straight away.
        if (request()->boolean('new') && auth()->user()->can('create', Expense::class)) {
            $this->create();
        }
    }

    /** @return list<int> */
    private function visibleCashboxIds(): array
    {
        return Cashbox::query()->visibleTo(auth()->user())->pluck('id')->map(fn ($id) => (int) $id)->all();
    }

    private function findVisible(int $id): Expense
    {
        return Expense::query()->whereIn('cashbox_id', $this->visibleCashboxIds())->findOrFail($id);
    }

    public function create(?int $copyFrom = null): void
    {
        $this->authorize('create', Expense::class);
        $this->editingId = null;
        $this->repeatOf = $copyFrom;
        $this->form = ['date' => now()->toDateString(), 'category_id' => null, 'cashbox_id' => null, 'amount' => '', 'rate' => '',
            'vehicle_id' => null, 'borne_by' => 'showroom', 'description' => '', 'recurs_every_months' => null];

        if ($copyFrom !== null) {
            // "Repeat" a recurring expense: same category, cashbox, amount and text, new date.
            $source = $this->findVisible($copyFrom);
            $this->form = array_merge($this->form, $source->only(['category_id', 'cashbox_id', 'vehicle_id', 'description', 'recurs_every_months']), ['amount' => (string) $source->amount, 'borne_by' => $source->borne_by->value ?? 'showroom']);
        }

        $this->receipt = null;
        $this->resetValidation();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $expense = $this->findVisible($id);
        $this->authorize('update', $expense);

        $this->editingId = $expense->id;
        $this->repeatOf = null;
        $this->form = $expense->only(['category_id', 'cashbox_id', 'vehicle_id', 'description', 'recurs_every_months'])
            + ['date' => $expense->date->toDateString(), 'amount' => (string) $expense->amount, 'rate' => (string) $expense->rate, 'borne_by' => $expense->borne_by->value ?? 'showroom'];
        $this->receipt = null;
        $this->resetValidation();
        $this->showForm = true;
    }

    public function save(SaveExpense $save, PostExpense $post, Settings $settings): void
    {
        $expense = $this->editingId ? $this->findVisible($this->editingId) : null;
        $expense ? $this->authorize('update', $expense) : $this->authorize('create', Expense::class);

        $data = $this->validate([
            'form.date' => ['required', 'date'],
            'form.category_id' => ['required', 'exists:expense_categories,id'],
            'form.cashbox_id' => ['required', 'in:'.implode(',', $this->visibleCashboxIds())],
            'form.amount' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'form.rate' => ['nullable', 'numeric', 'gt:0', 'decimal:0,6'],
            'form.vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'form.borne_by' => ['nullable', Rule::enum(CostBearer::class)],
            'form.description' => ['required', 'string', 'max:255'],
            'form.recurs_every_months' => ['nullable', 'integer', 'between:1,24'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:8192'],
        ])['form'];

        $expense = $this->attempt(fn () => $save->handle($data, $expense), 'form.amount');
        if ($expense === null) {
            return;
        }

        if ($this->repeatOf !== null) {
            $this->findVisible($this->repeatOf)->update(['next_due_date' => null]);
            $this->repeatOf = null;
        }

        if ($this->receipt !== null) {
            $expense->addMedia($this->receipt->getRealPath())->usingFileName($this->receipt->hashName())->toMediaCollection('receipt');
        }

        if (! $settings->bool('documents.require_approval', true) && auth()->user()->can('approve', $expense)) {
            $this->attempt(fn () => $post->handle($expense), 'form.amount');
        }

        $this->showForm = false;
        $this->notify(__('app.saved'));
    }

    public function approve(int $id, PostExpense $action): void
    {
        $expense = $this->findVisible($id);
        $this->authorize('approve', $expense);

        if ($this->attempt(fn () => $action->handle($expense)) !== null) {
            $this->notify(__('documents.posted_ok'));
        }
    }

    public function delete(int $id, DeleteDraft $action): void
    {
        $expense = $this->findVisible($id);
        $this->authorize('delete', $expense);

        $action->handle($expense);
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

    public function cancel(CancelExpense $action): void
    {
        $expense = $this->findVisible((int) $this->cancelId);
        $this->authorize('cancel', $expense);
        $this->validate(['reason' => ['required', 'string', 'max:255']]);

        if ($this->attempt(fn () => $action->handle($expense, $this->reason), 'reason') !== null) {
            $this->showCancel = false;
            $this->notify(__('documents.cancelled_ok'));
        }
    }

    public function render(): View
    {
        $cashboxIds = $this->visibleCashboxIds();

        return view('livewire.expenses.index', [
            'expenses' => Expense::query()
                ->with(['category', 'cashbox', 'currency', 'vehicle.brand', 'vehicle.carModel', 'media'])
                ->whereIn('cashbox_id', $cashboxIds)
                ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
                ->when($this->dueOnly, fn ($q) => $q->where('status', DocumentStatus::Posted)->whereDate('next_due_date', '<=', today()))
                ->latest('date')->latest('id')
                ->paginate(20),
            'categories' => ExpenseCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'cashboxes' => Cashbox::query()->whereIn('id', $cashboxIds)->where('is_active', true)->with('currency')->orderBy('name')->get(),
            'statuses' => DocumentStatus::cases(),
            // A car with owners: ask who bears the expense.
            'ownedVehicle' => empty($this->form['vehicle_id']) ? null
                : Vehicle::query()->with('ownership')->whereNotNull('ownership_id')->find($this->form['vehicle_id']),
            'dueCount' => Expense::query()->whereIn('cashbox_id', $cashboxIds)->where('status', DocumentStatus::Posted)->whereDate('next_due_date', '<=', today())->count(),
        ])->title(__('app.nav.expenses'));
    }
}
