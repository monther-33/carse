<div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
    <x-ui.card :title="__('app.roles.list')" :padding="false" class="lg:col-span-1 self-start">
        <ul class="divide-y divide-gray-100">
            @foreach ($roles as $role)
                <li wire:key="role-{{ $role->id }}">
                    <button type="button" wire:click="select({{ $role->id }})"
                            @class(['flex w-full items-center justify-between px-4 py-3 text-sm text-start',
                                    'bg-brand-50 font-semibold text-brand-700' => $role->id === $roleId,
                                    'hover:bg-gray-50' => $role->id !== $roleId])>
                        <span>{{ \App\Support\Labels::role($role->name) }}</span>
                        <x-ui.badge>{{ $role->users_count }}</x-ui.badge>
                    </button>
                </li>
            @endforeach
        </ul>
        <form wire:submit="createRole" class="space-y-2 border-t border-gray-200 p-4">
            <x-ui.field :label="__('app.roles.new')" for="newRole" error="newRole">
                <input id="newRole" type="text" wire:model="newRole" class="form-input">
            </x-ui.field>
            <x-ui.button type="submit" size="sm" icon="plus">{{ __('app.add') }}</x-ui.button>
        </form>
    </x-ui.card>

    <x-ui.card :title="$current ? __('app.roles.permissions_of', ['role' => \App\Support\Labels::role($current->name)]) : ''" class="lg:col-span-3">
        <x-slot:actions>
            @unless ($isSystem)
                <x-ui.button variant="danger" size="sm" icon="trash" wire:click="deleteRole" wire:confirm="{{ __('app.confirm_delete') }}">{{ __('app.delete') }}</x-ui.button>
            @endunless
            @unless ($isAdmin)
                <x-ui.button size="sm" icon="check" wire:click="save" wire:loading.attr="disabled">{{ __('app.save') }}</x-ui.button>
            @endunless
        </x-slot:actions>

        @if ($isAdmin)
            <p class="mb-4 rounded-md bg-yellow-50 p-3 text-sm text-yellow-800">{{ __('app.roles.admin_locked') }}</p>
        @endif
        @error('permissions')<p class="mb-4 text-sm text-red-600">{{ $message }}</p>@enderror
        @error('role')<p class="mb-4 text-sm text-red-600">{{ $message }}</p>@enderror

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($modules as $module => $actions)
                <fieldset class="rounded-md border border-gray-200 p-3" wire:key="module-{{ $module }}">
                    <legend class="px-1 text-sm font-semibold text-gray-700">{{ \App\Support\Labels::module($module) }}</legend>
                    <div class="space-y-1.5">
                        @foreach ($actions as $action)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" value="{{ $module }}.{{ $action }}" wire:model="permissions"
                                       class="rounded border-gray-300 text-brand-600" @disabled($isAdmin)>
                                <span>{{ \App\Support\Labels::permission($module.'.'.$action) }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach
        </div>
    </x-ui.card>
</div>
