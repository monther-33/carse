<div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
    <x-ui.card :title="__('app.nav.brands')" :padding="false" class="self-start">
        <x-slot:actions>
            <x-ui.button size="sm" icon="plus" wire:click="create">{{ __('app.add') }}</x-ui.button>
        </x-slot:actions>
        <div class="border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('app.search') }}" class="form-input">
        </div>
        <table class="table-base">
            <thead><tr><th>{{ __('app.fields.name') }}</th><th>{{ __('app.brands.models_count') }}</th><th></th></tr></thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($records as $record)
                <tr wire:key="brand-{{ $record->id }}" @class(['bg-brand-50' => $record->id === $brandId])>
                    <td><button type="button" class="hover:underline" wire:click="selectBrand({{ $record->id }})">{{ $record->name }}</button></td>
                    <td class="num">{{ $record->models_count }}</td>
                    <td class="text-end whitespace-nowrap">
                        <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $record->id }})">{{ __('app.edit') }}</x-ui.button>
                        <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $record->id }})" wire:confirm="{{ __('app.confirm_delete') }}">{{ __('app.delete') }}</x-ui.button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center text-gray-500 py-8">{{ __('app.no_records') }}</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $records->links() }}</div>
    </x-ui.card>

    <x-ui.card :title="$brand ? __('app.brands.models_of', ['brand' => $brand->name]) : __('app.brands.models')" :padding="false" class="self-start">
        @if ($brand)
            <form wire:submit="saveModel" class="flex items-end gap-2 border-b border-gray-200 p-4">
                <x-ui.field :label="$editingModelId ? __('app.brands.edit_model') : __('app.brands.new_model')" for="modelName" error="modelName" class="flex-1">
                    <input id="modelName" type="text" wire:model="modelName" class="form-input">
                </x-ui.field>
                <x-ui.button type="submit" icon="check">{{ __('app.save') }}</x-ui.button>
            </form>
            <ul class="divide-y divide-gray-100">
                @forelse ($models as $model)
                    <li class="flex items-center justify-between px-4 py-2 text-sm" wire:key="model-{{ $model->id }}">
                        <span>{{ $model->name }}</span>
                        <span class="whitespace-nowrap">
                            <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="editModel({{ $model->id }})">{{ __('app.edit') }}</x-ui.button>
                            <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="deleteModel({{ $model->id }})" wire:confirm="{{ __('app.confirm_delete') }}">{{ __('app.delete') }}</x-ui.button>
                        </span>
                    </li>
                @empty
                    <li class="px-4 py-6 text-center text-sm text-gray-500">{{ __('app.no_records') }}</li>
                @endforelse
            </ul>
        @else
            <p class="p-4 text-sm text-gray-500">{{ __('app.brands.select_hint') }}</p>
        @endif
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('app.edit') : __('app.add')" max-width="md">
        <form wire:submit="save" id="brand-form">
            <x-ui.field :label="__('app.fields.name')" for="f-name" error="form.name" required>
                <input id="f-name" type="text" wire:model="form.name" class="form-input">
            </x-ui.field>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="brand-form">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
