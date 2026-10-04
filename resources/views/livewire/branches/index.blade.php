<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            <x-ui.button icon="plus" wire:click="create">{{ __('app.add') }}</x-ui.button>
        </x-slot:actions>
        <div class="border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('app.search') }}" class="form-input sm:max-w-xs">
        </div>
        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('app.fields.code') }}</th>
                    <th>{{ __('app.fields.name') }}</th>
                    <th>{{ __('app.fields.phone') }}</th>
                    <th>{{ __('app.fields.address') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($records as $branch)
                    <tr wire:key="br-{{ $branch->id }}">
                        <td class="num font-mono">{{ $branch->code }}</td>
                        <td class="font-medium">{{ $branch->name }}</td>
                        <td class="num">{{ $branch->phone }}</td>
                        <td>{{ $branch->address }}</td>
                        <td><x-ui.badge :color="$branch->is_active ? 'green' : 'red'">{{ $branch->is_active ? __('app.active') : __('app.inactive') }}</x-ui.badge></td>
                        <td class="text-end">
                            <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $branch->id }})">{{ __('app.edit') }}</x-ui.button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-gray-500 py-8">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $records->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('app.edit') : __('app.add')" max-width="lg">
        <form wire:submit="save" id="branch-form" class="grid grid-cols-2 gap-4">
            <x-ui.field :label="__('app.fields.code')" for="f-code" error="form.code" required>
                <input id="f-code" type="text" dir="ltr" wire:model="form.code" class="form-input font-mono">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.name')" for="f-name" error="form.name" required>
                <input id="f-name" type="text" wire:model="form.name" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.phone')" for="f-phone" error="form.phone">
                <input id="f-phone" type="text" dir="ltr" wire:model="form.phone" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.address')" for="f-address" error="form.address">
                <input id="f-address" type="text" wire:model="form.address" class="form-input">
            </x-ui.field>
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="form.is_active" class="rounded border-gray-300 text-brand-600">
                {{ __('app.active') }}
            </label>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="branch-form">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
