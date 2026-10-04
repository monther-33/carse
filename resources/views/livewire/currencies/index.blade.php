<div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
    <x-ui.card :title="__('app.currencies.list')" :padding="false" class="self-start">
        <x-slot:actions>
            @can('create', \App\Models\Currency::class)
                <x-ui.button size="sm" icon="plus" wire:click="create">{{ __('app.add') }}</x-ui.button>
            @endcan
        </x-slot:actions>
        <table class="table-base">
            <thead>
            <tr>
                <th>{{ __('app.fields.code') }}</th>
                <th>{{ __('app.fields.name') }}</th>
                <th>{{ __('app.currencies.symbol') }}</th>
                <th>{{ __('app.fields.status') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach ($currencies as $currency)
                <tr wire:key="cur-{{ $currency->id }}" @class(['bg-brand-50' => $currency->id === $selectedId])>
                    <td class="num font-mono">{{ $currency->code }}</td>
                    <td>
                        <button type="button" class="text-start hover:underline" wire:click="select({{ $currency->id }})">{{ $currency->name }}</button>
                        @if ($currency->is_base)
                            <x-ui.badge color="blue">{{ __('app.currencies.base') }}</x-ui.badge>
                        @endif
                    </td>
                    <td>{{ $currency->symbol }}</td>
                    <td><x-ui.badge :color="$currency->is_active ? 'green' : 'red'">{{ $currency->is_active ? __('app.active') : __('app.inactive') }}</x-ui.badge></td>
                    <td class="text-end">
                        @can('update', $currency)
                            <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $currency->id }})">{{ __('app.edit') }}</x-ui.button>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-ui.card>

    <x-ui.card :title="$selected ? __('app.currencies.rates_of', ['currency' => $selected->name]) : __('app.currencies.rates')" :padding="false">
        @if ($selected?->is_base)
            <p class="p-4 text-sm text-gray-600">{{ __('app.currencies.base_rate_fixed') }}</p>
        @elseif ($selected)
            @can('create', \App\Models\ExchangeRate::class)
                <form wire:submit="saveRate" class="flex flex-wrap items-end gap-3 border-b border-gray-200 p-4">
                    <x-ui.field :label="__('app.fields.date')" for="rate_date" error="rate_date">
                        <input id="rate_date" type="date" wire:model="rate_date" class="form-input">
                    </x-ui.field>
                    <x-ui.field :label="__('app.currencies.rate_label', ['code' => $selected->code])" for="rate" error="rate">
                        <input id="rate" type="text" inputmode="decimal" dir="ltr" wire:model="rate" class="form-input w-40" placeholder="0.000000">
                    </x-ui.field>
                    <x-ui.button type="submit" icon="check">{{ __('app.save') }}</x-ui.button>
                </form>
            @endcan
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('app.fields.date') }}</th>
                    <th>{{ __('app.currencies.rate') }}</th>
                    <th>{{ __('app.fields.user') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($rates as $rate)
                    <tr wire:key="rate-{{ $rate->id }}">
                        <td class="num">{{ $rate->date->format('Y-m-d') }}</td>
                        <td class="num font-mono">{{ \App\Support\Money::format($rate->rate, 6) }}</td>
                        <td class="text-xs text-gray-600">{{ $rate->creator?->name }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-gray-500 py-6">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        @endif
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('app.currencies.edit') : __('app.currencies.new')" max-width="lg">
        <form wire:submit="save" id="currency-form" class="grid grid-cols-2 gap-4">
            <x-ui.field :label="__('app.fields.code')" for="c-code" error="code" required>
                <input id="c-code" type="text" dir="ltr" maxlength="3" wire:model="code" class="form-input uppercase font-mono">
            </x-ui.field>
            <x-ui.field :label="__('app.currencies.symbol')" for="c-symbol" error="symbol" required>
                <input id="c-symbol" type="text" wire:model="symbol" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.name')" for="c-name" error="name" class="col-span-2" required>
                <input id="c-name" type="text" wire:model="name" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.currencies.decimals')" for="c-dec" error="decimals" required>
                <input id="c-dec" type="number" min="0" max="3" dir="ltr" wire:model="decimals" class="form-input">
            </x-ui.field>
            <label class="inline-flex items-center gap-2 pt-6 text-sm">
                <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-brand-600">
                {{ __('app.active') }}
            </label>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="currency-form">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
