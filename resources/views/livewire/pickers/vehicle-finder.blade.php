<div>
    <button type="button" wire:click="openSearch" title="{{ __('vehicle_search.open') }}"
            class="inline-flex items-center gap-2 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-500 hover:bg-gray-100">
        <x-ui.icon name="search" class="h-5 w-5" />
        <span class="hidden md:inline">{{ __('vehicle_search.find') }}</span>
    </button>

    @include('livewire.pickers.partials.vehicle-search')
</div>
