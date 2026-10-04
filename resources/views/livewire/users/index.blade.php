<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            <x-ui.button icon="plus" wire:click="create">{{ __('app.users.new') }}</x-ui.button>
        </x-slot:actions>

        <div class="border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('app.search') }}" class="form-input sm:max-w-xs">
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('app.fields.name') }}</th>
                    <th>{{ __('app.fields.email') }}</th>
                    <th>{{ __('app.fields.branch') }}</th>
                    <th>{{ __('app.fields.roles') }}</th>
                    <th>{{ __('app.fields.max_discount') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td class="font-medium">{{ $user->name }}</td>
                        <td class="num">{{ $user->email }}</td>
                        <td>{{ $user->branch->name }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                <x-ui.badge color="blue">{{ \App\Support\Labels::role($role->name) }}</x-ui.badge>
                            @endforeach
                        </td>
                        <td class="num">{{ \App\Support\Money::format($user->max_discount) }}</td>
                        <td>
                            <x-ui.badge :color="$user->is_active ? 'green' : 'red'">
                                {{ $user->is_active ? __('app.active') : __('app.inactive') }}
                            </x-ui.badge>
                        </td>
                        <td class="text-end whitespace-nowrap">
                            <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $user->id }})">{{ __('app.edit') }}</x-ui.button>
                            @can('toggleActive', $user)
                                <x-ui.button variant="ghost" size="sm" icon="power"
                                             wire:click="toggleActive({{ $user->id }})"
                                             wire:confirm="{{ $user->is_active ? __('app.users.confirm_deactivate') : __('app.users.confirm_activate') }}">
                                    {{ $user->is_active ? __('app.deactivate') : __('app.activate') }}
                                </x-ui.button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-gray-500 py-8">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $users->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('app.users.edit') : __('app.users.new')">
        <form wire:submit="save" id="user-form" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field :label="__('app.fields.name')" for="name" error="name" required>
                <input id="name" type="text" wire:model="name" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.email')" for="email" error="email" required>
                <input id="email" type="email" dir="ltr" wire:model="email" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.password')" for="password" error="password" :required="! $editingId"
                        :hint="$editingId ? __('app.users.password_hint') : null">
                <input id="password" type="password" dir="ltr" wire:model="password" class="form-input" autocomplete="new-password">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.branch')" for="branch_id" error="branch_id" required>
                <select id="branch_id" wire:model="branch_id" class="form-input">
                    <option value="">—</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('app.fields.max_discount')" for="max_discount" error="max_discount" :hint="__('app.users.max_discount_hint')">
                <input id="max_discount" type="text" inputmode="decimal" dir="ltr" wire:model="max_discount" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.status')" error="is_active">
                <label class="inline-flex items-center gap-2 pt-2">
                    <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-brand-600">
                    <span class="text-sm">{{ __('app.active') }}</span>
                </label>
            </x-ui.field>

            <x-ui.field :label="__('app.fields.roles')" error="roles" class="sm:col-span-2" required>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @foreach ($allRoles as $role)
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" value="{{ $role }}" wire:model="roles" class="rounded border-gray-300 text-brand-600">
                            <span class="text-sm">{{ \App\Support\Labels::role($role) }}</span>
                        </label>
                    @endforeach
                </div>
            </x-ui.field>

            <x-ui.field :label="__('app.users.cashboxes')" error="cashbox_ids" class="sm:col-span-2" :hint="__('app.users.cashboxes_hint')">
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @foreach ($cashboxes as $cashbox)
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" value="{{ $cashbox->id }}" wire:model="cashbox_ids" class="rounded border-gray-300 text-brand-600">
                            <span class="text-sm">{{ $cashbox->name }}</span>
                        </label>
                    @endforeach
                </div>
            </x-ui.field>
        </form>

        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="user-form" wire:loading.attr="disabled">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
