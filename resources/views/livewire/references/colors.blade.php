<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            <x-ui.button icon="plus" wire:click="create">{{ __('app.add') }}</x-ui.button>
        </x-slot:actions>
        <div class="border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('app.search') }}" class="form-input sm:max-w-xs">
        </div>
        <table class="table-base">
            <thead><tr><th>{{ __('app.fields.name') }}</th><th>{{ __('app.colors.hex') }}</th><th></th></tr></thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($records as $color)
                <tr wire:key="color-{{ $color->id }}">
                    <td>
                        <span class="inline-block h-4 w-4 rounded-full ring-1 ring-gray-300 align-middle" style="background: {{ $color->hex ?? 'transparent' }}"></span>
                        <span class="ms-2">{{ $color->name }}</span>
                    </td>
                    <td class="num font-mono text-gray-600">{{ $color->hex }}</td>
                    <td class="text-end whitespace-nowrap">
                        <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $color->id }})">{{ __('app.edit') }}</x-ui.button>
                        <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $color->id }})" wire:confirm="{{ __('app.confirm_delete') }}">{{ __('app.delete') }}</x-ui.button>
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
        <form wire:submit="save" id="color-form" class="space-y-4">
            <x-ui.field :label="__('app.fields.name')" for="f-name" error="form.name" required>
                <input id="f-name" type="text" wire:model="form.name" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.colors.hex')" for="f-hex" error="form.hex">
                <input id="f-hex" type="color" wire:model="form.hex" class="h-10 w-20 rounded border-gray-300">
            </x-ui.field>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="color-form">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
