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
        <input type="search" wire:model.live.debounce.300ms="search" wire:focus="$set('open', true)"
               placeholder="{{ __('parties.search_placeholder') }}" class="form-input" autocomplete="off">

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

                @if ($allowCreate && auth()->user()->can('parties.manage'))
                    <div class="space-y-2 border-t border-gray-200 bg-gray-50 p-3">
                        <p class="text-xs font-semibold text-gray-600">{{ __('parties.quick_create') }}</p>
                        <input type="text" wire:model="newName" placeholder="{{ __('app.fields.name') }}" class="form-input">
                        @error('newName')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                        <input type="text" dir="ltr" wire:model="newPhone" placeholder="{{ __('app.fields.phone') }}" class="form-input">
                        <x-ui.button size="sm" icon="plus" wire:click="createParty">{{ __('app.add') }}</x-ui.button>
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>
