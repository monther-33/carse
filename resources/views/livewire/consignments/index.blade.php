<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('consignments.manage')
                <x-ui.button icon="plus" :href="route('consignments.create')" wire:navigate>{{ __('app.nav_actions.new_consignment') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="grid grid-cols-1 gap-3 border-b border-gray-200 p-4 sm:grid-cols-4">
            <input type="search" wire:model.live.debounce.300ms="q" placeholder="{{ __('ownership.search_placeholder') }}" class="form-input sm:col-span-2">
            <select wire:model.live="kind" class="form-input">
                <option value="">{{ __('ownership.kind') }}: {{ __('app.all') }}</option>
                @foreach ($kinds as $k)
                    <option value="{{ $k->value }}">{{ $k->label() }}</option>
                @endforeach
            </select>
            <select wire:model.live="status" class="form-input">
                <option value="">{{ __('app.fields.status') }}: {{ __('app.all') }}</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('documents.number') }}</th>
                    <th>{{ __('vehicles.vehicle') }}</th>
                    <th>{{ __('ownership.owners') }}</th>
                    <th class="hidden md:table-cell">{{ __('ownership.agreement') }}</th>
                    <th class="hidden sm:table-cell">{{ __('ownership.received_at') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($ownerships as $o)
                    <tr wire:key="own-{{ $o->id }}">
                        <td class="whitespace-nowrap">
                            <span class="num font-mono text-xs">{{ $o->number ?? '—' }}</span>
                            <x-ui.badge :color="$o->isConsignment() ? 'purple' : 'blue'" class="block w-fit">{{ $o->kind->label() }}</x-ui.badge>
                        </td>
                        <td>
                            <a href="{{ route('vehicles.show', $o->vehicle) }}" wire:navigate class="hover:text-brand-700 hover:underline">{{ $o->vehicle->title() }}</a>
                            <span class="block num font-mono text-xs text-gray-500">{{ $o->vehicle->vin }}</span>
                        </td>
                        <td class="text-sm">
                            @foreach ($o->owners as $owner)
                                <span class="block">{{ $owner->party->name }} <span class="num text-xs text-gray-500">{{ rtrim(rtrim($owner->share, '0'), '.') }}%</span></span>
                            @endforeach
                            @unless ($o->isConsignment())
                                <span class="block text-xs text-gray-500">{{ __('ownership.showroom') }} <span class="num">{{ rtrim(rtrim($o->showroom_share, '0'), '.') }}%</span></span>
                            @endunless
                        </td>
                        <td class="hidden text-xs md:table-cell">
                            @include('livewire.ownership.partials.agreement', ['ownership' => $o])
                        </td>
                        <td class="num hidden sm:table-cell">{{ $o->received_at->format('Y-m-d') }}</td>
                        <td><x-ui.badge :color="$o->status === \App\Enums\OwnershipStatus::Active ? 'green' : 'gray'">{{ $o->status->label() }}</x-ui.badge></td>
                        <td class="whitespace-nowrap text-end">
                            @if ($o->isConsignment())
                                <x-ui.button variant="ghost" size="sm" icon="printer" :href="route('print.consignment', $o)" target="_blank" :title="__('ownership.print_receipt')" />
                            @endif
                            @if ($o->isConsignment() && $o->status === \App\Enums\OwnershipStatus::Active)
                                @can('consignments.manage')
                                    <x-ui.button variant="ghost" size="sm" icon="return" wire:click="openReturn({{ $o->id }})">{{ __('ownership.return_to_owner') }}</x-ui.button>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $ownerships->links() }}</div>
    </x-ui.card>

    @if ($returning)
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-gray-900/50 px-4 py-10" wire:key="ret-{{ $returning->id }}">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <h3 class="text-base font-semibold">{{ __('ownership.return_to_owner') }} — <span class="num">{{ $returning->number }}</span></h3>
                    <button type="button" wire:click="closeReturn" class="text-gray-400 hover:text-gray-600"><x-ui.icon name="x" class="h-5 w-5" /></button>
                </div>
                <form wire:submit="returnToOwner" class="space-y-4 px-6 py-5">
                    <p class="text-sm text-gray-600">{{ $returning->vehicle->vin }}</p>
                    <p class="rounded-md bg-yellow-50 p-3 text-sm text-yellow-800">{{ __('ownership.return_hint') }}</p>
                    <x-ui.field :label="__('ownership.return_reason')" error="reason" required>
                        <input type="text" wire:model="reason" class="form-input">
                    </x-ui.field>
                    <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                        <x-ui.button variant="secondary" wire:click="closeReturn">{{ __('app.close') }}</x-ui.button>
                        <x-ui.button type="submit" variant="danger" wire:loading.attr="disabled">{{ __('ownership.return_to_owner') }}</x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
