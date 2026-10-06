@php($brand = app(\App\Support\Branding::class))
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $brand->name() }}</title>
    @if ($brand->logoUrl())
        <link rel="icon" href="{{ $brand->logoUrl() }}">
    @endif

    @include('partials.pwa-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="flex min-h-screen bg-gray-100">
    {{-- Showroom side (large screens): the showroom photo, or the brand gradient. --}}
    <aside class="relative hidden overflow-hidden lg:flex lg:w-1/2 xl:w-3/5">
        @if ($cover = $brand->coverUrl())
            <img src="{{ $cover }}" alt="{{ $brand->name() }}" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-900/90 via-brand-900/40 to-brand-900/10"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-brand-700 via-brand-900 to-gray-900"></div>
            <x-ui.icon name="car" class="absolute -bottom-16 -end-16 h-[28rem] w-[28rem] text-white/5" />
        @endif

        <div class="relative z-10 mt-auto w-full p-10 text-white xl:p-14">
            <x-brand-logo class="mb-6 h-20 w-20 rounded-2xl bg-white/95 p-2 text-4xl shadow-lg" />
            <h2 class="text-3xl font-bold xl:text-4xl">{{ $brand->name() }}</h2>
            <p class="mt-3 max-w-md text-base text-white/80">{{ __('app.auth.tagline') }}</p>
            @if ($brand->phone() || $brand->address())
                <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm text-white/80">
                    @if ($brand->phone())
                        <span class="num">{{ $brand->phone() }}</span>
                    @endif
                    @if ($brand->address())
                        <span>{{ $brand->address() }}</span>
                    @endif
                </div>
            @endif
        </div>
    </aside>

    {{-- Form side --}}
    <main class="flex w-full flex-col items-center justify-center px-4 py-10 sm:px-6 lg:w-1/2 xl:w-2/5">
        <div class="w-full max-w-sm">
            <div class="mb-8 flex flex-col items-center text-center">
                <x-brand-logo class="h-20 w-20 rounded-2xl text-4xl lg:hidden" />
                <h1 class="mt-4 text-2xl font-bold text-gray-900 lg:mt-0">{{ __('app.auth.welcome') }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ __('app.auth.subtitle', ['name' => $brand->name()]) }}</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-xl ring-1 ring-gray-900/5 sm:p-8">
                {{ $slot }}
            </div>

            <x-pwa-install class="mt-4 text-center" buttonClass="text-sm font-medium text-brand-700 hover:underline" />

            <p class="mt-6 text-center text-xs text-gray-400">© {{ now()->year }} {{ $brand->name() }}</p>
        </div>
    </main>
</div>
</body>
</html>
