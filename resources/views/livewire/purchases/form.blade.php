<form wire:submit="save" class="space-y-4">
    @error('document')<div class="rounded-md bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror

    <x-ui.card :title="__('purchases.header')">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.field :label="__('purchases.supplier')" error="party_id" class="lg:col-span-2" required>
                <livewire:pickers.party-picker wire:model="party_id" kind="supplier" :allow-create="true" />
            </x-ui.field>
            <x-ui.field :label="__('app.fields.date')" for="date" error="date" required>
                <input id="date" type="date" wire:model="date" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('purchases.source')" for="source" error="source" required>
                <select id="source" wire:model="source" class="form-input">
                    @foreach ($sources as $s)
                        <option value="{{ $s->value }}">{{ $s->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.fields.currency')" for="currency_id" error="currency_id" required>
                <select id="currency_id" wire:model.live="currency_id" class="form-input">
                    @foreach ($currencies as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('documents.rate')" for="rate" error="rate" required>
                <input id="rate" type="text" dir="ltr" inputmode="decimal" wire:model="rate" class="form-input" @disabled($currencies->firstWhere('id', $currency_id)?->is_base)>
            </x-ui.field>
        </div>
    </x-ui.card>

    @foreach ($items as $i => $item)
        <x-ui.card :title="__('purchases.vehicle_n', ['n' => $i + 1])" wire:key="item-{{ $i }}">
            <x-slot:actions>
                @if (count($items) > 1)
                    <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="removeItem({{ $i }})">{{ __('app.delete') }}</x-ui.button>
                @endif
            </x-slot:actions>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <x-ui.field :label="__('vehicles.vin')" error="items.{{ $i }}.vin" required>
                    <input type="text" dir="ltr" wire:model="items.{{ $i }}.vin" class="form-input font-mono uppercase">
                </x-ui.field>
                <x-ui.field :label="__('purchases.price')" error="items.{{ $i }}.price" required>
                    <input type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.500ms="items.{{ $i }}.price" class="form-input">
                </x-ui.field>
                <x-ui.field :label="__('purchases.entry_status')" error="items.{{ $i }}.entry_status" required>
                    <select wire:model="items.{{ $i }}.entry_status" class="form-input">
                        @foreach ($entryStatuses as $s)
                            <option value="{{ $s->value }}">{{ $s->label() }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
                @include('livewire.vehicles.partials.fields', [
                    'prefix' => 'items.'.$i,
                    'brandModels' => $modelsByBrand[$item['brand_id'] ?? 0] ?? collect(),
                ])
            </div>

            {{-- Partners who own a share of this car with the showroom (optional). --}}
            @feature('consignment')
            @php($partners = $item['partners'] ?? [])
            @php($partnerSum = array_sum(array_map(fn ($p) => is_numeric($p['share'] ?? '') ? (float) $p['share'] : 0, $partners)))
            <div class="mt-4 border-t border-gray-100 pt-4">
                <div class="mb-2 flex flex-wrap items-baseline justify-between gap-2">
                    <h4 class="text-sm font-semibold text-gray-700">{{ __('ownership.partners') }}</h4>
                    <p class="text-xs text-gray-500">{{ __('ownership.partners_hint') }}</p>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        @include('livewire.ownership.partials.owners', [
                            'path' => 'items.'.$i.'.partners',
                            'rows' => $partners,
                            'add' => 'addPartner('.$i.')',
                            'remove' => 'removePartner('.$i.', ',
                            'total' => $partners ? __('ownership.showroom_share_is', ['share' => rtrim(rtrim(number_format(100 - $partnerSum, 4, '.', ''), '0'), '.')]) : null,
                        ])
                    </div>
                    @if ($partners)
                        <x-ui.field :label="__('ownership.payout')" error="items.{{ $i }}.partner_payout">
                            <select wire:model="items.{{ $i }}.partner_payout" class="form-input">
                                @foreach ($payouts as $p)
                                    <option value="{{ $p->value }}">{{ $p->label() }}</option>
                                @endforeach
                            </select>
                        </x-ui.field>
                    @endif
                </div>
            </div>
            @endfeature
        </x-ui.card>
    @endforeach

    <div>
        <x-ui.button variant="secondary" icon="plus" wire:click="addItem">{{ __('purchases.add_vehicle') }}</x-ui.button>
    </div>

    <x-ui.card :title="__('purchases.totals')">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <p class="text-xs text-gray-500">{{ __('documents.subtotal') }}</p>
                <p class="num text-lg font-semibold">{{ \App\Support\Money::format($subtotal) }}</p>
            </div>
            <x-ui.field :label="__('documents.discount')" for="discount" error="discount">
                <input id="discount" type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.500ms="discount" class="form-input">
            </x-ui.field>
            <div>
                <p class="text-xs text-gray-500">{{ __('documents.total') }}</p>
                <p class="num text-lg font-bold text-brand-700">{{ \App\Support\Money::format($total) }}</p>
            </div>
            <div></div>
            <x-ui.field :label="__('purchases.paid_now')" for="paid" error="paid" :hint="__('purchases.paid_hint')">
                <input id="paid" type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.500ms="paid" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('documents.cashbox')" for="cashbox_id" error="cashbox_id">
                <select id="cashbox_id" wire:model="cashbox_id" class="form-input">
                    <option value="">—</option>
                    @foreach ($cashboxes as $cb)
                        <option value="{{ $cb->id }}">{{ $cb->name }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.fields.notes')" for="notes" error="notes" class="sm:col-span-2">
                <input id="notes" type="text" wire:model="notes" class="form-input">
            </x-ui.field>
        </div>
    </x-ui.card>

    <div class="flex justify-end gap-2">
        <x-ui.button variant="secondary" :href="$invoice ? route('purchases.show', $invoice) : route('purchases.index')" wire:navigate>{{ __('app.cancel') }}</x-ui.button>
        <x-ui.button type="submit" icon="check" wire:loading.attr="disabled">{{ __('documents.save_draft') }}</x-ui.button>
    </div>
</form>
