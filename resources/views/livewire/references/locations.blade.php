<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            <x-ui.button icon="plus" wire:click="create">{{ __('app.add') }}</x-ui.button>
        </x-slot:actions>
        <div class="border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('app.search') }}" class="form-input sm:max-w-xs">
        </div>
        <table class="table-base">
            <thead><tr><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.branch') }}</th><th></th></tr></thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($records as $location)
                <tr wire:key="loc-{{ $location->id }}">
                    <td>{{ $location->name }}</td>
                    <td>{{ $location->branch->name }}</td>
                    <td class="text-end whitespace-nowrap">
                        <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $location->id }})">{{ __('app.edit') }}</x-ui.button>
                        <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $location->id }})" wire:confirm="{{ __('app.confirm_delete') }}">{{ __('app.delete') }}</x-ui.button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center text-gray-500 py-8">{{ __('app.no_records') }}</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $records->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('app.edit') : __('app.add')" max-width="md">
        <form wire:submit="save" id="location-form" class="space-y-4">
            <x-ui.field :label="__('app.fields.branch')" for="f-branch" error="form.branch_id" required>
                <select id="f-branch" wire:model="form.branch_id" class="form-input">
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.fields.name')" for="f-name" error="form.name" required>
                <input id="f-name" type="text" wire:model="form.name" class="form-input">
            </x-ui.field>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="location-form">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
