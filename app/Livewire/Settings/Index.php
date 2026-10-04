<?php

namespace App\Livewire\Settings;

use App\Enums\AccountRole;
use App\Livewire\Concerns\Notifies;
use App\Models\Account;
use App\Support\Settings;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{
    use Notifies, WithFileUploads;

    public string $company_name = '';

    public string $company_phone = '';

    public string $company_address = '';

    public ?string $company_logo = null;

    /** @var TemporaryUploadedFile|null */
    public $logo = null;

    public string $contract_terms = '';

    public bool $require_approval = true;

    public string $commission_type = 'percent';

    public string $commission_value = '0';

    public int $stale_warning = 60;

    public int $stale_critical = 90;

    /** @var array<string, int|string|null> */
    public array $accounts = [];

    public ?int $cash_parent = null;

    public ?int $bank_parent = null;

    public function mount(Settings $settings): void
    {
        $this->authorize('settings.manage');

        $this->company_name = (string) $settings->get('company.name', '');
        $this->company_phone = (string) $settings->get('company.phone', '');
        $this->company_address = (string) $settings->get('company.address', '');
        $this->company_logo = $settings->get('company.logo');
        $this->contract_terms = (string) $settings->get('print.contract_terms', '');
        $this->require_approval = $settings->bool('documents.require_approval', true);
        $this->commission_type = (string) $settings->get('sales.commission_type', 'percent');
        $this->commission_value = (string) $settings->get('sales.commission_value', '0');
        $this->stale_warning = $settings->int('inventory.stale_days_warning', 60);
        $this->stale_critical = $settings->int('inventory.stale_days_critical', 90);
        $this->cash_parent = $settings->int('cashbox.parent.cash') ?: null;
        $this->bank_parent = $settings->int('cashbox.parent.bank') ?: null;

        foreach (AccountRole::cases() as $role) {
            $this->accounts[$role->value] = $settings->int($role->settingKey()) ?: null;
        }
    }

    public function save(Settings $settings): void
    {
        $this->authorize('settings.manage');

        $postable = Rule::exists('accounts', 'id')->where('is_group', false);
        $group = Rule::exists('accounts', 'id')->where('is_group', true);

        $rules = [
            'company_name' => ['required', 'string', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:100'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:1024'],
            'contract_terms' => ['nullable', 'string', 'max:10000'],
            'require_approval' => ['boolean'],
            'commission_type' => ['required', 'in:percent,fixed'],
            'commission_value' => ['required', 'numeric', 'min:0', 'decimal:0,3'],
            'stale_warning' => ['required', 'integer', 'min:1'],
            'stale_critical' => ['required', 'integer', 'gt:stale_warning'],
            'cash_parent' => ['required', $group],
            'bank_parent' => ['required', $group],
        ];
        foreach (AccountRole::cases() as $role) {
            $rules['accounts.'.$role->value] = ['required', $postable];
        }
        $this->validate($rules);

        if ($this->logo !== null) {
            $this->company_logo = $this->logo->store('branding', 'public');
            $this->logo = null;
        }

        $values = [
            'company.name' => $this->company_name,
            'company.phone' => $this->company_phone,
            'company.address' => $this->company_address,
            'company.logo' => $this->company_logo,
            'print.contract_terms' => $this->contract_terms,
            'documents.require_approval' => $this->require_approval,
            'sales.commission_type' => $this->commission_type,
            'sales.commission_value' => $this->commission_value,
            'inventory.stale_days_warning' => $this->stale_warning,
            'inventory.stale_days_critical' => $this->stale_critical,
            'cashbox.parent.cash' => $this->cash_parent,
            'cashbox.parent.bank' => $this->bank_parent,
        ];
        foreach (AccountRole::cases() as $role) {
            $values[$role->settingKey()] = $this->accounts[$role->value];
        }

        $settings->set($values);
        $this->notify(__('app.saved'));
    }

    public function render(): View
    {
        $accounts = Account::query()->where('is_active', true)->orderBy('code')->get();

        return view('livewire.settings.index', [
            'roles' => AccountRole::cases(),
            'postable' => $accounts->where('is_group', false),
            'groups' => $accounts->where('is_group', true),
        ])->title(__('app.nav.settings'));
    }
}
