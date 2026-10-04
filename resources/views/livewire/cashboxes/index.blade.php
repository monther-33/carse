<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\Cashbox::class)
                <x-ui.button icon="plus" wire:click="create">{{ __('app.cashboxes.new') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('app.fields.name') }}</th>
                    <th>{{ __('app.fields.type') }}</th>
                    <th>{{ __('app.fields.currency') }}</th>
                    <th>{{ __('app.fields.account') }}</th>
                    <th>{{ __('app.fields.branch') }}</th>
                    <th>{{ __('app.cashboxes.users') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($cashboxes as $cashbox)
                    <tr wire:key="cb-{{ $cashbox->id }}">
                        <td class="font-medium">
                            {{ $cashbox->name }}
                            @if ($cashbox->bank_name)
                                <span class="block text-xs text-gray-500">{{ $cashbox->bank_name }} <span class="num">{{ $cashbox->account_number }}</span></span>
                            @endif
                        </td>
                        <td>{{ $cashbox->type->label() }}</td>
                        <td>{{ $cashbox->currency->code }}</td>
                        <td class="num font-mono text-gray-600">{{ $cashbox->account->code }}</td>
                        <td>{{ $cashbox->branch->name }}</td>
                        <td class="text-xs text-gray-600">{{ $cashbox->users->pluck('name')->join('، ') ?: '—' }}</td>
                        <td>
                            <x-ui.badge :color="$cashbox->is_active ? 'green' : 'red'">{{ $cashbox->is_active ? __('app.active') : __('app.inactive') }}</x-ui.badge>
                        </td>
                        <td class="text-end">
                            @can('update', $cashbox)
                                <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $cashbox->id }})">{{ __('app.edit') }}</x-ui.button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-gray-500 py-8">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('app.cashboxes.edit') : __('app.cashboxes.new')">
        <form wire:submit="save" id="cashbox-form" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field :label="__('app.fields.name')" for="cb-name" error="name" required>
                <input id="cb-name" type="text" wire:model="name" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.type')" for="cb-type" error="type" required>
                <select id="cb-type" wire:model.live="type" class="form-input" @disabled($editingId)>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.fields.currency')" for="cb-currency" error="currency_id" required>
                <select id="cb-currency" wire:model="currency_id" class="form-input" @disabled($editingId)>
                    <option value="">—</option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.fields.branch')" for="cb-branch" error="branch_id" required>
                <select id="cb-branch" wire:model="branch_id" class="form-input">
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            @if ($type === 'bank')
                <x-ui.field :label="__('app.cashboxes.bank_name')" for="cb-bank" error="bank_name">
                    <input id="cb-bank" type="text" wire:model="bank_name" class="form-input">
                </x-ui.field>
                <x-ui.field :label="__('app.cashboxes.account_number')" for="cb-accno" error="account_number">
                    <input id="cb-accno" type="text" dir="ltr" wire:model="account_number" class="form-input">
                </x-ui.field>
            @endif
            <label class="inline-flex items-center gap-2 text-sm sm:col-span-2">
                <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-brand-600">
                {{ __('app.active') }}
            </label>
            <x-ui.field :label="__('app.cashboxes.users')" error="user_ids" class="sm:col-span-2" :hint="__('app.cashboxes.users_hint')">
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @foreach ($users as $user)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" value="{{ $user->id }}" wire:model="user_ids" class="rounded border-gray-300 text-brand-600">
                            {{ $user->name }}
                        </label>
                    @endforeach
                </div>
            </x-ui.field>
            @unless ($editingId)
                <p class="text-xs text-gray-500 sm:col-span-2">{{ __('app.cashboxes.account_hint') }}</p>
            @endunless
        </form>

        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="cashbox-form" wire:loading.attr="disabled">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
