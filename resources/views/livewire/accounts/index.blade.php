<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\Account::class)
                <x-ui.button icon="plus" wire:click="create(null)">{{ __('app.accounts.new') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('app.accounts.search') }}" class="form-input sm:max-w-xs">
        </div>

        @error('account')<p class="px-4 pt-3 text-sm text-red-600">{{ $message }}</p>@enderror

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('app.fields.code') }}</th>
                    <th>{{ __('app.fields.name') }}</th>
                    <th>{{ __('app.fields.type') }}</th>
                    <th>{{ __('app.fields.nature') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach ($matches ? $matches->map(fn ($a) => ['account' => $a, 'depth' => 0]) : $tree as $row)
                    @php($account = $row['account'])
                    <tr wire:key="acc-{{ $account->id }}">
                        <td class="num font-mono text-gray-600">{{ $account->code }}</td>
                        <td>
                            <span style="padding-inline-start: {{ $row['depth'] * 1.5 }}rem" @class(['font-semibold' => $account->is_group])>
                                {{ $account->name }}
                            </span>
                            @if ($account->is_system)
                                <x-ui.icon name="lock" class="inline h-3.5 w-3.5 text-gray-400" title="{{ __('app.accounts.system') }}" />
                            @endif
                        </td>
                        <td>{{ $account->type->label() }}</td>
                        <td>{{ $account->nature->label() }}</td>
                        <td>
                            @if ($account->is_group)
                                <x-ui.badge color="blue">{{ __('app.accounts.group') }}</x-ui.badge>
                            @endif
                            @unless ($account->is_active)
                                <x-ui.badge color="red">{{ __('app.inactive') }}</x-ui.badge>
                            @endunless
                        </td>
                        <td class="text-end whitespace-nowrap">
                            @if ($account->is_group)
                                @can('create', \App\Models\Account::class)
                                    <x-ui.button variant="ghost" size="sm" icon="plus" wire:click="create({{ $account->id }})">{{ __('app.accounts.add_child') }}</x-ui.button>
                                @endcan
                            @endif
                            @can('update', $account)
                                <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $account->id }})">{{ __('app.edit') }}</x-ui.button>
                            @endcan
                            @can('delete', $account)
                                <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $account->id }})" wire:confirm="{{ __('app.confirm_delete') }}">{{ __('app.delete') }}</x-ui.button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('app.accounts.edit') : __('app.accounts.new')" max-width="lg">
        <form wire:submit="save" id="account-form" class="space-y-4">
            <x-ui.field :label="__('app.accounts.parent')" for="parent_id" error="parent_id" required>
                <select id="parent_id" wire:model.live="parent_id" class="form-input">
                    <option value="">—</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->label() }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.fields.code')" for="code" error="code" required>
                <input id="code" type="text" dir="ltr" wire:model="code" class="form-input font-mono">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.name')" for="name" error="name" required>
                <input id="name" type="text" wire:model="name" class="form-input">
            </x-ui.field>
            <div class="flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="is_group" class="rounded border-gray-300 text-brand-600">
                    {{ __('app.accounts.is_group') }}
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-brand-600">
                    {{ __('app.active') }}
                </label>
            </div>
            @error('is_group')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500">{{ __('app.accounts.type_hint') }}</p>
        </form>

        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="account-form" wire:loading.attr="disabled">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
