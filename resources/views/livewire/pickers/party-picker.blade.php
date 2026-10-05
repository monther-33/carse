<div class="relative" x-data @click.outside="$wire.set('open', false)">
    @if ($selected)
        <div class="flex items-center justify-between gap-2 rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm">
            <span>
                <span class="font-medium">{{ $selected->name }}</span>
                @if ($selected->phone)<span class="num text-gray-500"> · {{ $selected->phone }}</span>@endif
            </span>
            <span class="flex shrink-0 items-center gap-2">
                <button type="button" wire:click="preview({{ $selected->id }})" class="text-gray-400 hover:text-brand-700" title="{{ __('party_search.details') }}">
                    <x-ui.icon name="eye" class="h-4 w-4" />
                </button>
                <button type="button" wire:click="clear" class="text-gray-400 hover:text-red-600" title="{{ __('app.change') }}">
                    <x-ui.icon name="x" class="h-4 w-4" />
                </button>
            </span>
        </div>
    @else
        <div class="flex gap-2">
            <input type="search" wire:model.live.debounce.300ms="search" wire:focus="$set('open', true)"
                   placeholder="{{ __('parties.search_placeholder') }}" class="form-input min-w-0 flex-1" autocomplete="off">
            <button type="button" wire:click="openSearch" title="{{ __('party_search.title') }}"
                    class="inline-flex shrink-0 items-center gap-1 rounded-md border border-gray-300 bg-white px-2.5 text-sm text-gray-600 shadow-sm hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700">
                <x-ui.icon name="search" class="h-4 w-4" />
                <span class="hidden sm:inline">{{ __('vehicle_search.button') }}</span>
            </button>
            @if ($allowCreate)
                <x-ui.quick-add type="party" target="value" :preset="'{ name: $wire.search, kind: '.\Illuminate\Support\Js::from($kind).' }'" />
            @endif
        </div>

        @if ($open && $search !== '')
            <div class="absolute z-30 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg">
                <ul class="max-h-60 overflow-y-auto">
                    @forelse ($results as $party)
                        <li>
                            <button type="button" wire:click="choose({{ $party->id }})" class="block w-full px-3 py-2 text-start text-sm hover:bg-brand-50">
                                {{ $party->name }}
                                <span class="num text-xs text-gray-500">{{ $party->phone }} {{ $party->national_id }}</span>
                            </button>
                        </li>
                    @empty
                        <li class="px-3 py-2 text-sm text-gray-500">{{ __('app.no_records') }}</li>
                    @endforelse
                </ul>

                <button type="button" wire:click="openSearch" class="flex w-full items-center gap-2 border-t border-gray-200 px-3 py-2 text-start text-sm text-brand-700 hover:bg-brand-50">
                    <x-ui.icon name="search" class="h-4 w-4" /> {{ __('party_search.advanced') }}
                </button>
                @if ($allowCreate && \App\Livewire\QuickCreate::allowed('party'))
                    <button type="button"
                            x-on:click="$dispatch('open-quick-create', { type: 'party', owner: $wire.$id, target: 'value', preset: { name: $wire.search, kind: @js($kind) } }); $wire.set('open', false)"
                            class="flex w-full items-center gap-2 border-t border-gray-200 bg-gray-50 px-3 py-2 text-start text-sm font-medium text-brand-700 hover:bg-brand-50">
                        <x-ui.icon name="plus" class="h-4 w-4" />
                        {{ __('quick.add_named', ['name' => $search]) }}
                    </button>
                @endif
            </div>
        @endif
    @endif

    <x-ui.modal wire:model="showSearch" :title="__('party_search.title')" max-width="4xl">
        @if ($ps)
            @php($p = $ps['preview'])
            @if ($p)
                <div class="space-y-4">
                    <button type="button" wire:click="closePreview" class="inline-flex items-center gap-1 text-sm text-brand-700 hover:underline">
                        <x-ui.icon name="chevron" class="h-4 w-4 ltr:rotate-180" /> {{ __('vehicle_search.back') }}
                    </button>
                    <div class="flex flex-wrap items-center gap-2">
                        <h4 class="text-lg font-semibold">{{ $p->name }}</h4>
                        <x-ui.badge color="blue">{{ $p->type->label() }}</x-ui.badge>
                    </div>
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm sm:grid-cols-4">
                        <dt class="text-gray-500">{{ __('app.fields.phone') }}</dt><dd class="num">{{ $p->phone ?? '—' }}</dd>
                        <dt class="text-gray-500">{{ __('parties.phone2') }}</dt><dd class="num">{{ $p->phone2 ?? '—' }}</dd>
                        <dt class="text-gray-500">{{ __('parties.national_id') }}</dt><dd class="num">{{ $p->national_id ?? '—' }}</dd>
                        <dt class="text-gray-500">{{ __('parties.credit_limit') }}</dt><dd class="num">{{ \App\Support\Money::format($p->credit_limit) }}</dd>
                        <dt class="text-gray-500">{{ __('app.fields.address') }}</dt><dd class="sm:col-span-3">{{ $p->address ?? '—' }}</dd>
                    </dl>
                    @if ($p->notes)
                        <p class="rounded-md bg-gray-50 p-2 text-sm text-gray-600">{{ $p->notes }}</p>
                    @endif
                    <div class="flex flex-wrap justify-end gap-2 border-t border-gray-100 pt-3">
                        @can('viewStatement', $p)
                            <x-ui.button variant="secondary" icon="document" :href="route('parties.statement', $p)" target="_blank">{{ __('party_search.statement') }}</x-ui.button>
                        @endcan
                        <x-ui.button icon="check" wire:click="choose({{ $p->id }})">{{ __('vehicle_search.choose') }}</x-ui.button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <input type="search" wire:model.live.debounce.300ms="filter.q" placeholder="{{ __('parties.search_placeholder') }}"
                               class="form-input sm:col-span-2" autocomplete="off" x-init="$nextTick(() => $el.focus())">
                        <select wire:model.live="filter.type" class="form-input">
                            <option value="">{{ __('app.fields.type') }}: {{ __('app.all') }}</option>
                            @foreach (\App\Enums\PartyType::cases() as $case)
                                <option value="{{ $case->value }}">{{ $case->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="max-h-[55vh] overflow-auto rounded-md border border-gray-200">
                        <table class="table-base">
                            <thead class="sticky top-0"><tr>
                                <th>{{ __('app.fields.name') }}</th>
                                <th>{{ __('app.fields.type') }}</th>
                                <th>{{ __('app.fields.phone') }}</th>
                                <th>{{ __('parties.national_id') }}</th>
                                <th>{{ __('app.fields.address') }}</th>
                                <th></th>
                            </tr></thead>
                            <tbody class="divide-y divide-gray-100">
                            @forelse ($ps['results'] as $party)
                                <tr wire:key="ps-{{ $party->id }}">
                                    <td class="font-medium">{{ $party->name }}</td>
                                    <td class="text-xs">{{ $party->type->label() }}</td>
                                    <td class="num">{{ $party->phone }}</td>
                                    <td class="num">{{ $party->national_id }}</td>
                                    <td class="text-xs">{{ $party->address }}</td>
                                    <td class="whitespace-nowrap text-end">
                                        <x-ui.button variant="ghost" size="sm" icon="eye" wire:click="preview({{ $party->id }})" />
                                        <x-ui.button size="sm" wire:click="choose({{ $party->id }})">{{ __('vehicle_search.choose') }}</x-ui.button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="flex items-center justify-between">
                        @if ($allowCreate && \App\Livewire\QuickCreate::allowed('party'))
                            <x-ui.button variant="secondary" size="sm" icon="plus"
                                         x-on:click="$dispatch('open-quick-create', { type: 'party', owner: $wire.$id, target: 'value', preset: { name: $wire.filter.q, kind: {{ \Illuminate\Support\Js::from($kind) }} } }); show = false">{{ __('quick.titles.party') }}</x-ui.button>
                        @else
                            <span></span>
                        @endif
                        @if ($ps['hasMore'])
                            <x-ui.button variant="secondary" size="sm" wire:click="loadMore">{{ __('vehicle_search.more') }}</x-ui.button>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </x-ui.modal>
</div>
