<?php

namespace App\Livewire;

use App\Enums\AccountType;
use App\Enums\PartyType;
use App\Livewire\Concerns\Notifies;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\ExpenseCategory;
use App\Models\Location;
use App\Models\Party;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * One modal, mounted once in the layout, to add a customer / supplier, brand, model, colour,
 * location or expense category without leaving the current screen.
 *
 * Opened from anywhere with the browser event
 *   $dispatch('open-quick-create', { type: 'color', owner: $wire.$id, target: 'form.color_id' })
 * and answers with the Livewire event "quick-created" {type, id, owner, target}, which the
 * screen that asked for it (AcceptsQuickCreate) uses to select the new record.
 */
class QuickCreate extends Component
{
    use Notifies;

    /** type => permission needed */
    public const TYPES = [
        'party' => 'parties.manage',
        'brand' => 'references.manage',
        'model' => 'references.manage',
        'color' => 'references.manage',
        'location' => 'references.manage',
        'expense_category' => 'accounts.manage',
    ];

    public bool $show = false;

    public string $type = 'party';

    public ?string $owner = null;

    public ?string $target = null;

    /** @var array<string, mixed> */
    public array $form = [];

    public static function allowed(string $type): bool
    {
        return isset(self::TYPES[$type]) && (bool) Auth::user()?->can(self::TYPES[$type]);
    }

    /**
     * @param  array<string, mixed>  $preset  e.g. ['name' => 'typed text', 'kind' => 'supplier', 'brand_id' => 3]
     */
    #[On('open-quick-create')]
    public function start(string $type, ?string $owner = null, ?string $target = null, array $preset = []): void
    {
        abort_unless(self::allowed($type), 403);

        $this->type = $type;
        $this->owner = $owner;
        $this->target = $target;
        $this->form = [
            'name' => trim((string) ($preset['name'] ?? '')),
            'party_type' => match ($preset['kind'] ?? 'customer') {
                'supplier' => PartyType::Supplier->value,
                'both' => PartyType::Both->value,
                default => PartyType::Customer->value,
            },
            'phone' => '', 'phone2' => '', 'national_id' => '', 'address' => '',
            'hex' => '#FFFFFF',
            'brand_id' => $preset['brand_id'] ?? null ?: null,
            'account_id' => null,
        ];
        $this->resetValidation();
        $this->show = true;
    }

    public function save(): void
    {
        abort_unless(self::allowed($this->type), 403);

        $branchId = Auth::user()->branch_id ?? Branch::query()->value('id');

        $record = match ($this->type) {
            'party' => $this->party($branchId),
            'brand' => Brand::query()->create($this->validate([
                'form.name' => ['required', 'string', 'max:100', Rule::unique('brands', 'name')],
            ])['form']),
            'model' => CarModel::query()->create($this->validate([
                'form.brand_id' => ['required', 'exists:brands,id'],
                'form.name' => ['required', 'string', 'max:100', Rule::unique('car_models', 'name')->where('brand_id', $this->form['brand_id'] ?? 0)],
            ])['form']),
            'color' => Color::query()->create($this->validate([
                'form.name' => ['required', 'string', 'max:100', Rule::unique('colors', 'name')],
                'form.hex' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            ])['form']),
            'location' => Location::query()->create($this->validate([
                'form.name' => ['required', 'string', 'max:100', Rule::unique('locations', 'name')->where('branch_id', $branchId)],
            ])['form'] + ['branch_id' => $branchId]),
            'expense_category' => ExpenseCategory::query()->create($this->validate([
                'form.name' => ['required', 'string', 'max:100', Rule::unique('expense_categories', 'name')],
                'form.account_id' => ['required', Rule::in($this->expenseAccounts()->pluck('id')->all())],
            ])['form'] + ['is_active' => true]),
            default => abort(404),
        };

        $this->show = false;
        $this->dispatch('quick-created', type: $this->type, id: $record->getKey(), owner: $this->owner, target: $this->target);
        $this->notify(__('quick.created', ['name' => $record->getAttribute('name')]));
    }

    private function party(int $branchId): Party
    {
        $data = $this->validate([
            'form.party_type' => ['required', Rule::enum(PartyType::class)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.phone2' => ['nullable', 'string', 'max:50'],
            'form.national_id' => ['nullable', 'string', 'max:50'],
            'form.address' => ['nullable', 'string', 'max:255'],
        ])['form'];

        return Party::query()->create([
            'branch_id' => $branchId,
            'type' => $data['party_type'],
            'name' => $data['name'],
            'phone' => $data['phone'] ?: null,
            'phone2' => $data['phone2'] ?: null,
            'national_id' => $data['national_id'] ?: null,
            'address' => $data['address'] ?: null,
        ]);
    }

    /** @return Collection<int, Account> */
    private function expenseAccounts()
    {
        return Account::query()->postable()->where('type', AccountType::Expense)->orderBy('code')->get();
    }

    public function render(): View
    {
        return view('livewire.quick-create', [
            'brands' => $this->show && $this->type === 'model' ? Brand::query()->orderBy('name')->get(['id', 'name']) : collect(),
            'accounts' => $this->show && $this->type === 'expense_category' ? $this->expenseAccounts() : collect(),
        ]);
    }
}
