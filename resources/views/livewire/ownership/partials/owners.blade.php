{{--
    Owner rows (party + share %). Params:
      $path    wire:model path of the rows ("owners", "items.0.partners")
      $rows    the rows
      $add     Livewire call that adds a row ("addOwner", "addPartner(0)")
      $remove  Livewire call prefix that removes row j ("removeOwner(", "removePartner(0, ")
      $total   what the shares must add up to, shown as a hint (optional)
--}}
<div class="space-y-2">
    @foreach ($rows as $j => $row)
        <div class="flex flex-wrap items-start gap-2 sm:flex-nowrap" wire:key="own-{{ $path }}-{{ $j }}">
            <div class="min-w-0 flex-1 basis-full sm:basis-auto">
                <livewire:pickers.party-picker wire:model="{{ $path }}.{{ $j }}.party_id" :allow-create="true" :key="'pp-'.$path.'-'.$j" />
                @error($path.'.'.$j.'.party_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="w-28">
                <div class="relative">
                    <input type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.400ms="{{ $path }}.{{ $j }}.share"
                           placeholder="{{ __('ownership.share') }}" class="form-input pe-7">
                    <span class="pointer-events-none absolute inset-y-0 end-2 flex items-center text-sm text-gray-400">%</span>
                </div>
                @error($path.'.'.$j.'.share')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <button type="button" wire:click="{{ $remove }}{{ $j }})" class="mt-2 text-gray-400 hover:text-red-600" title="{{ __('app.delete') }}">
                <x-ui.icon name="trash" class="h-4 w-4" />
            </button>
        </div>
    @endforeach
    @error($path)<p class="text-xs text-red-600">{{ $message }}</p>@enderror
    <div class="flex flex-wrap items-center justify-between gap-2">
        <x-ui.button variant="secondary" size="sm" icon="plus" wire:click="{{ $add }}">{{ __('ownership.add_owner') }}</x-ui.button>
        @isset($total)
            <span class="text-xs text-gray-500">{{ $total }}</span>
        @endisset
    </div>
</div>
