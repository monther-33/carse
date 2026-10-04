<div class="relative">
    @if ($selected)
        <div class="flex items-center justify-between rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm">
            <span>
                <span class="font-medium">{{ $selected->title() }}</span>
                <span class="num font-mono text-xs text-gray-500">{{ $selected->vin }}</span>
                <x-ui.badge :color="$selected->status->color()">{{ $selected->status->label() }}</x-ui.badge>
            </span>
            <button type="button" wire:click="clear" class="text-gray-400 hover:text-red-600" title="{{ __('app.change') }}">
                <x-ui.icon name="x" class="h-4 w-4" />
            </button>
        </div>
    @else
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('vehicles.search_placeholder') }}" class="form-input" autocomplete="off">

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
            </ul>
        @endif
    @endif
</div>
