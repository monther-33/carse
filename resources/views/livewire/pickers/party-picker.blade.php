<div class="relative" x-data @click.outside="$wire.set('open', false)">
    @if ($selected)
        <div class="flex items-center justify-between rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm">
            <span>
                <span class="font-medium">{{ $selected->name }}</span>
                @if ($selected->phone)<span class="num text-gray-500"> · {{ $selected->phone }}</span>@endif
            </span>
            <button type="button" wire:click="clear" class="text-gray-400 hover:text-red-600" title="{{ __('app.change') }}">
                <x-ui.icon name="x" class="h-4 w-4" />
            </button>
        </div>
    @else
        <div class="flex gap-2">
            <input type="search" wire:model.live.debounce.300ms="search" wire:focus="$set('open', true)"
                   placeholder="{{ __('parties.search_placeholder') }}" class="form-input min-w-0 flex-1" autocomplete="off">
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
</div>
