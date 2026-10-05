<div class="relative">
    @if ($selected)
        <div class="flex items-center justify-between gap-2 rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm">
            <span>
                <span class="font-medium">{{ $selected->title() }}</span>
                <span class="num font-mono text-xs text-gray-500">{{ $selected->vin }}</span>
                <x-ui.badge :color="$selected->status->color()">{{ $selected->status->label() }}</x-ui.badge>
            </span>
            <span class="flex shrink-0 items-center gap-2">
                <button type="button" wire:click="preview({{ $selected->id }})" class="text-gray-400 hover:text-brand-700" title="{{ __('vehicle_search.details') }}">
                    <x-ui.icon name="eye" class="h-4 w-4" />
                </button>
                <button type="button" wire:click="clear" class="text-gray-400 hover:text-red-600" title="{{ __('app.change') }}">
                    <x-ui.icon name="x" class="h-4 w-4" />
                </button>
            </span>
        </div>
    @else
        <div class="flex gap-2">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('vehicles.search_placeholder') }}" class="form-input min-w-0 flex-1" autocomplete="off">
            <button type="button" wire:click="openSearch" title="{{ __('vehicle_search.open') }}"
                    class="inline-flex shrink-0 items-center gap-1 rounded-md border border-gray-300 bg-white px-2.5 text-sm text-gray-600 shadow-sm hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700">
                <x-ui.icon name="search" class="h-4 w-4" />
                <span class="hidden sm:inline">{{ __('vehicle_search.button') }}</span>
            </button>
        </div>

        @if ($search !== '')
            <ul class="absolute z-30 mt-1 max-h-64 w-full overflow-y-auto rounded-md border border-gray-200 bg-white shadow-lg">
                @forelse ($results as $vehicle)
                    <li>
                        <button type="button" wire:click="choose({{ $vehicle->id }})" class="flex w-full items-center justify-between gap-2 px-3 py-2 text-start text-sm hover:bg-brand-50">
                            <span>{{ $vehicle->title() }} <span class="num font-mono text-xs text-gray-500">{{ $vehicle->vin }}</span></span>
                            <x-ui.badge :color="$vehicle->status->color()">{{ $vehicle->status->label() }}</x-ui.badge>
                        </button>
                    </li>
                @empty
                    <li class="px-3 py-2 text-sm text-gray-500">{{ __('app.no_records') }}</li>
                @endforelse
                <li>
                    <button type="button" wire:click="openSearch" class="flex w-full items-center gap-2 border-t border-gray-200 bg-gray-50 px-3 py-2 text-start text-sm font-medium text-brand-700 hover:bg-brand-50">
                        <x-ui.icon name="search" class="h-4 w-4" /> {{ __('vehicle_search.advanced') }}
                    </button>
                </li>
            </ul>
        @endif
    @endif

    @include('livewire.pickers.partials.vehicle-search')
</div>
