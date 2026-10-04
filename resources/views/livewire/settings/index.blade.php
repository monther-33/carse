<form wire:submit="save" class="space-y-4 max-w-5xl">
    <x-ui.card :title="__('app.settings.company')">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field :label="__('app.settings.company_name')" for="company_name" error="company_name" required>
                <input id="company_name" type="text" wire:model="company_name" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.phone')" for="company_phone" error="company_phone">
                <input id="company_phone" type="text" dir="ltr" wire:model="company_phone" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.address')" for="company_address" error="company_address" class="sm:col-span-2">
                <input id="company_address" type="text" wire:model="company_address" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.settings.logo')" for="logo" error="logo" :hint="__('app.settings.logo_hint')">
                <div class="flex items-center gap-4">
                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="h-14 w-14 rounded object-contain ring-1 ring-gray-200" alt="">
                    @elseif ($company_logo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($company_logo) }}" class="h-14 w-14 rounded object-contain ring-1 ring-gray-200" alt="">
                    @endif
                    <input id="logo" type="file" accept="image/*" wire:model="logo" class="text-sm">
                </div>
            </x-ui.field>
        </div>
    </x-ui.card>

    <x-ui.card :title="__('app.settings.printing')">
        <x-ui.field :label="__('app.settings.contract_terms')" for="contract_terms" error="contract_terms" :hint="__('app.settings.contract_terms_hint')">
            <textarea id="contract_terms" rows="6" wire:model="contract_terms" class="form-input"></textarea>
        </x-ui.field>
    </x-ui.card>

    <x-ui.card :title="__('app.settings.operations')">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <label class="inline-flex items-start gap-2 text-sm sm:col-span-2">
                <input type="checkbox" wire:model="require_approval" class="mt-0.5 rounded border-gray-300 text-brand-600">
                <span>
                    <span class="font-medium">{{ __('app.settings.require_approval') }}</span>
                    <span class="block text-xs text-gray-500">{{ __('app.settings.require_approval_hint') }}</span>
                </span>
            </label>
            <x-ui.field :label="__('app.settings.commission_type')" for="commission_type" error="commission_type">
                <select id="commission_type" wire:model="commission_type" class="form-input">
                    <option value="percent">{{ __('app.settings.commission_percent') }}</option>
                    <option value="fixed">{{ __('app.settings.commission_fixed') }}</option>
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.settings.commission_value')" for="commission_value" error="commission_value">
                <input id="commission_value" type="text" inputmode="decimal" dir="ltr" wire:model="commission_value" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.settings.stale_warning')" for="stale_warning" error="stale_warning">
                <input id="stale_warning" type="number" dir="ltr" wire:model="stale_warning" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.settings.stale_critical')" for="stale_critical" error="stale_critical">
                <input id="stale_critical" type="number" dir="ltr" wire:model="stale_critical" class="form-input">
            </x-ui.field>
        </div>
    </x-ui.card>

    <x-ui.card :title="__('app.settings.accounts')">
        <p class="mb-4 text-sm text-gray-600">{{ __('app.settings.accounts_hint') }}</p>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field :label="__('app.settings.cash_parent')" for="cash_parent" error="cash_parent" required>
                <select id="cash_parent" wire:model="cash_parent" class="form-input">
                    <option value="">—</option>
                    @foreach ($groups as $account)
                        <option value="{{ $account->id }}">{{ $account->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.settings.bank_parent')" for="bank_parent" error="bank_parent" required>
                <select id="bank_parent" wire:model="bank_parent" class="form-input">
                    <option value="">—</option>
                    @foreach ($groups as $account)
                        <option value="{{ $account->id }}">{{ $account->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            @foreach ($roles as $role)
                <x-ui.field :label="$role->label()" for="acc-{{ $role->value }}" error="accounts.{{ $role->value }}" required>
                    <select id="acc-{{ $role->value }}" wire:model="accounts.{{ $role->value }}" class="form-input">
                        <option value="">—</option>
                        @foreach ($postable as $account)
                            <option value="{{ $account->id }}">{{ $account->label() }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
            @endforeach
        </div>
    </x-ui.card>

    <div class="flex justify-end">
        <x-ui.button type="submit" icon="check" wire:loading.attr="disabled">{{ __('app.save') }}</x-ui.button>
    </div>
</form>
