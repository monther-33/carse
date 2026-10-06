{{-- The showroom logo from the settings, or an emblem with its initial when none was uploaded.
     Size it with the class, e.g. <x-brand-logo class="h-10 w-10 text-lg" />. --}}
@php($brand = app(\App\Support\Branding::class))
@if ($url = $brand->logoUrl())
    <img src="{{ $url }}" alt="{{ $brand->name() }}" {{ $attributes->merge(['class' => 'object-contain']) }}>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-900 font-bold text-white shadow-inner']) }} aria-label="{{ $brand->name() }}">
        <span class="leading-none">{{ $brand->initial() }}</span>
    </span>
@endif
