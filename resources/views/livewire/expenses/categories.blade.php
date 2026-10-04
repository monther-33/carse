<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            <x-ui.button icon="plus" wire:click="create">{{ __('app.add') }}</x-ui.button>
        </x-slot:actions>
        <div class="border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('app.search') }}" class="form-input sm:max-w-xs">
        </div>
        <table class="table-base">
            <thead><tr><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.account') }}</th><th>{{ __('app.fields.status') }}</th><th></th></tr></thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($records as $category)
                <tr wire:key="cat-{{ $category->id }}">
                    <td>{{ $category->name }}</td>
                    <td class="text-sm text-gray-600">{{ $category->account->label() }}</td>
                    <td><x-ui.badge :color="$category->is_active ? 'green' : 'red'">{{ $category->is_active ? __('app.active') : __('app.inactive') }}</x-ui.badge></td>
                    <td class="text-end whitespace-nowrap">
                        <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $category->id }})">{{ __('app.edit') }}</x-ui.button>
                        <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $category->id }})" wire:confirm="{{ __('app.confirm_delete') }}">{{ __('app.delete') }}</x-ui.button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $records->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('app.edit') : __('app.add')" max-width="md">
        <form wire:submit="save" id="cat-form" class="space-y-4">
            <x-ui.field :label="__('app.fields.name')" for="c-name" error="form.name" required>
                <input id="c-name" type="text" wire:model="form.name" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.account')" for="c-account" error="form.account_id" required :hint="__('expenses.category_account_hint')">
                <select id="c-account" wire:model="form.account_id" class="form-input">
                    <option value="">—</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="form.is_active" class="rounded border-gray-300 text-brand-600">
                {{ __('app.active') }}
            </label>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="cat-form">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
