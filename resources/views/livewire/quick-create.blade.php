<div>
    <x-ui.modal wire:model="show" :title="__('quick.titles.'.$type)" max-width="lg">
        <form wire:submit="save" id="quick-create-form" class="space-y-4">
            @if ($type === 'party')
                <x-ui.field :label="__('app.fields.type')" error="form.party_type" required>
                    <select wire:model="form.party_type" class="form-input">
                        @foreach (\App\Enums\PartyType::cases() as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
            @endif

            @if ($type === 'model')
                <x-ui.field :label="__('vehicles.brand')" error="form.brand_id" required>
                    <select wire:model="form.brand_id" class="form-input">
                        <option value="">—</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
            @endif

            <x-ui.field :label="__('app.fields.name')" error="form.name" required>
                <input type="text" wire:model="form.name" class="form-input" x-init="$nextTick(() => $el.focus())" autocomplete="off">
            </x-ui.field>

            @if ($type === 'party')
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-ui.field :label="__('app.fields.phone')" error="form.phone">
                        <input type="text" dir="ltr" wire:model="form.phone" class="form-input">
                    </x-ui.field>
                    <x-ui.field :label="__('parties.phone2')" error="form.phone2">
                        <input type="text" dir="ltr" wire:model="form.phone2" class="form-input">
                    </x-ui.field>
                    <x-ui.field :label="__('parties.national_id')" error="form.national_id">
                        <input type="text" dir="ltr" wire:model="form.national_id" class="form-input">
                    </x-ui.field>
                    <x-ui.field :label="__('app.fields.address')" error="form.address">
                        <input type="text" wire:model="form.address" class="form-input">
                    </x-ui.field>
                </div>
                <p class="text-xs text-gray-500">{{ __('quick.party_hint') }}</p>
            @endif

            @if ($type === 'color')
                <x-ui.field :label="__('quick.hex')" error="form.hex">
                    <div class="flex items-center gap-2">
                        <input type="color" wire:model.live="form.hex" class="h-9 w-14 cursor-pointer rounded border border-gray-300">
                        <input type="text" dir="ltr" wire:model.live="form.hex" class="form-input w-32">
                    </div>
                </x-ui.field>
            @endif

            @if ($type === 'expense_category')
                <x-ui.field :label="__('app.fields.account')" error="form.account_id" required>
                    <select wire:model="form.account_id" class="form-input">
                        <option value="">—</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->label() }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
            @endif
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="quick-create-form" icon="plus" wire:loading.attr="disabled">{{ __('app.add') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
