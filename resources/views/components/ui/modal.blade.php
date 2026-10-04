{{-- Livewire-bound modal: <x-ui.modal wire:model="showForm" title="..."> --}}
@props(['title' => null, 'maxWidth' => '2xl'])
@php
    $width = ['md' => 'sm:max-w-md', 'lg' => 'sm:max-w-lg', '2xl' => 'sm:max-w-2xl', '4xl' => 'sm:max-w-4xl'][$maxWidth];
@endphp
<div x-data="{ show: @entangle($attributes->wire('model')) }"
     x-show="show" x-cloak
     x-on:keydown.escape.window="show = false"
     class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/50" x-on:click="show = false"></div>

    <div x-show="show" x-transition
         class="relative mx-auto mt-10 w-full {{ $width }} rounded-lg bg-white shadow-xl">
        @if ($title)
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-800">{{ $title }}</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" x-on:click="show = false">
                    <x-ui.icon name="x" class="h-5 w-5" />
                </button>
            </div>
        @endif
        <div class="px-6 py-5">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="flex justify-end gap-2 border-t border-gray-200 bg-gray-50 px-6 py-3 rounded-b-lg">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
