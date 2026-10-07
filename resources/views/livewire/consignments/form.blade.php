<form wire:submit="save" class="space-y-4">
    @error('document')<div class="rounded-md bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror

    <x-ui.card :title="__('ownership.the_vehicle')">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <x-ui.field :label="__('vehicles.vin')" error="vehicle.vin" required>
                <input type="text" dir="ltr" wire:model="vehicle.vin" class="form-input font-mono uppercase">
            </x-ui.field>
            <x-ui.field :label="__('ownership.received_at')" error="received_at" required>
                <input type="date" wire:model="received_at" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('purchases.entry_status')" error="vehicle.entry_status" required>
                <select wire:model="vehicle.entry_status" class="form-input">
                    @foreach ($entryStatuses as $s)
                        <option value="{{ $s->value }}">{{ $s->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            @include('livewire.vehicles.partials.fields', ['prefix' => 'vehicle'])
        </div>
    </x-ui.card>

    <x-ui.card :title="__('ownership.owners')">
        <p class="mb-3 text-xs text-gray-500">{{ __('ownership.owners_hint') }}</p>
        @include('livewire.ownership.partials.owners', [
            'path' => 'owners',
            'rows' => $owners,
            'add' => 'addOwner',
            'remove' => 'removeOwner(',
            'total' => __('ownership.shares_total_is', ['total' => rtrim(rtrim(number_format($sharesTotal, 4, '.', ''), '0'), '.')]),
        ])
    </x-ui.card>

    <x-ui.card :title="__('ownership.agreement')">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.field :label="__('ownership.earning_mode')" error="earning_mode" class="sm:col-span-2" required>
                <select wire:model.live="earning_mode" class="form-input">
                    @foreach ($modes as $m)
                        <option value="{{ $m->value }}">{{ $m->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            @if ($earning_mode === 'percent')
                <x-ui.field :label="__('ownership.earning_percent')" error="earning_percent" required>
                    <input type="text" dir="ltr" inputmode="decimal" wire:model="earning_percent" class="form-input">
                </x-ui.field>
            @elseif ($earning_mode !== 'none')
                <x-ui.field :label="$earning_mode === 'net_price' ? __('ownership.net_price') : __('ownership.fixed_commission')" error="earning_amount"
                            :hint="__('ownership.amount_in_base')" required>
                    <input type="text" dir="ltr" inputmode="decimal" wire:model="earning_amount" class="form-input">
                </x-ui.field>
            @endif
            <x-ui.field :label="__('ownership.payout')" error="payout" required>
                <select wire:model="payout" class="form-input">
                    @foreach ($payouts as $p)
                        <option value="{{ $p->value }}">{{ $p->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.fields.notes')" for="notes" error="notes" class="sm:col-span-2 lg:col-span-4">
                <input id="notes" type="text" wire:model="notes" class="form-input">
            </x-ui.field>
        </div>
        <p class="mt-3 rounded-md bg-blue-50 p-3 text-sm text-blue-800">{{ __('ownership.intake_hint') }}</p>
    </x-ui.card>

    <div class="flex justify-end gap-2">
        <x-ui.button variant="secondary" :href="route('consignments.index')" wire:navigate>{{ __('app.cancel') }}</x-ui.button>
        <x-ui.button type="submit" icon="check" wire:loading.attr="disabled">{{ __('ownership.receive') }}</x-ui.button>
    </div>
</form>
