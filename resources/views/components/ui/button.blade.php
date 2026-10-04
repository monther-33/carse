@props(['variant' => 'primary', 'size' => 'md', 'icon' => null, 'href' => null])
@php
    $variants = [
        'primary' => 'bg-brand-600 text-white hover:bg-brand-700 focus:ring-brand-500',
        'secondary' => 'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:ring-brand-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'ghost' => 'text-gray-600 hover:bg-gray-100 focus:ring-gray-300',
    ];
    $sizes = [
        'sm' => 'px-2.5 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
    ];
    $classes = 'inline-flex items-center justify-center gap-1.5 rounded-md font-medium shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:cursor-not-allowed disabled:opacity-60 '
        .$variants[$variant].' '.$sizes[$size];
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-ui.icon :name="$icon" class="h-4 w-4" />@endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
        @if ($icon)<x-ui.icon :name="$icon" class="h-4 w-4" />@endif
        {{ $slot }}
    </button>
@endif
