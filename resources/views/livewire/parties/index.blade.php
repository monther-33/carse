<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\Party::class)
                <x-ui.button icon="plus" wire:click="create">{{ __('parties.new') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="flex flex-wrap gap-3 border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('parties.search_placeholder') }}" class="form-input sm:max-w-xs">
            <select wire:model.live="type" class="form-input sm:w-44">
                <option value="">{{ __('app.all') }}</option>
                <option value="customer">{{ __('parties.customers') }}</option>
                <option value="supplier">{{ __('parties.suppliers') }}</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('app.fields.name') }}</th>
                    <th>{{ __('app.fields.type') }}</th>
                    <th>{{ __('app.fields.phone') }}</th>
                    <th>{{ __('parties.national_id') }}</th>
                    <th>{{ __('parties.credit_limit') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($parties as $party)
                    <tr wire:key="party-{{ $party->id }}">
                        <td class="font-medium">
                            {{ $party->name }}
                            @if ($party->getFirstMedia('id_card'))
                                <a href="{{ $party->getFirstMediaUrl('id_card') }}" target="_blank" class="ms-1 text-brand-600" title="{{ __('parties.id_card') }}">
                                    <x-ui.icon name="document" class="inline h-4 w-4" />
                                </a>
                            @endif
                        </td>
                        <td>{{ $party->type->label() }}</td>
                        <td class="num">{{ $party->phone }}@if ($party->phone2)<br>{{ $party->phone2 }}@endif</td>
                        <td class="num">{{ $party->national_id }}</td>
                        <td class="num">{{ \App\Support\Money::format($party->credit_limit) }}</td>
                        <td><x-ui.badge :color="$party->is_active ? 'green' : 'red'">{{ $party->is_active ? __('app.active') : __('app.inactive') }}</x-ui.badge></td>
                        <td class="text-end whitespace-nowrap">
                            @can('viewStatement', $party)
                                <x-ui.button variant="ghost" size="sm" icon="document" :href="route('parties.statement', $party)" wire:navigate>{{ __('parties.statement') }}</x-ui.button>
                            @endcan
                            @can('update', $party)
                                <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $party->id }})">{{ __('app.edit') }}</x-ui.button>
                            @endcan
                            @can('delete', $party)
                                <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $party->id }})" wire:confirm="{{ __('app.confirm_delete') }}">{{ __('app.delete') }}</x-ui.button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $parties->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('parties.edit') : __('parties.new')">
        <form wire:submit="save" id="party-form" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field :label="__('app.fields.type')" for="p-type" error="form.type" required>
                <select id="p-type" wire:model="form.type" class="form-input">
                    @foreach ($types as $t)
                        <option value="{{ $t->value }}">{{ $t->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.fields.name')" for="p-name" error="form.name" required>
                <input id="p-name" type="text" wire:model="form.name" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.phone')" for="p-phone" error="form.phone">
                <input id="p-phone" type="text" dir="ltr" wire:model="form.phone" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('parties.phone2')" for="p-phone2" error="form.phone2">
                <input id="p-phone2" type="text" dir="ltr" wire:model="form.phone2" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('parties.national_id')" for="p-nid" error="form.national_id">
                <input id="p-nid" type="text" dir="ltr" wire:model="form.national_id" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('parties.credit_limit')" for="p-limit" error="form.credit_limit">
                <input id="p-limit" type="text" inputmode="decimal" dir="ltr" wire:model="form.credit_limit" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.address')" for="p-address" error="form.address" class="sm:col-span-2">
                <input id="p-address" type="text" wire:model="form.address" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('parties.id_card')" for="p-idcard" error="idCard">
                <input id="p-idcard" type="file" accept="image/*,application/pdf" wire:model="idCard" class="text-sm">
            </x-ui.field>
            <label class="inline-flex items-center gap-2 pt-6 text-sm">
                <input type="checkbox" wire:model="form.is_active" class="rounded border-gray-300 text-brand-600">
                {{ __('app.active') }}
            </label>
            <x-ui.field :label="__('app.fields.notes')" for="p-notes" error="form.notes" class="sm:col-span-2">
                <textarea id="p-notes" rows="2" wire:model="form.notes" class="form-input"></textarea>
            </x-ui.field>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="party-form" wire:loading.attr="disabled">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
