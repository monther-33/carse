<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title.' — ' : '' }}{{ app(\App\Support\Settings::class)->get('company.name', config('app.name')) }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
<div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">
    {{-- Sidebar: off-canvas on tablet/mobile, fixed on desktop --}}
    <div x-show="sidebarOpen" x-cloak x-transition.opacity class="fixed inset-0 z-30 bg-gray-900/40 lg:hidden" @click="sidebarOpen = false"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : 'ltr:-translate-x-full rtl:translate-x-full'"
           class="fixed inset-y-0 start-0 z-40 w-64 transform bg-brand-900 text-gray-100 transition-transform duration-200 lg:static lg:translate-x-0 rtl:lg:translate-x-0 ltr:lg:translate-x-0 flex flex-col">
        <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
            <x-application-logo class="h-8 w-8 fill-current text-white" />
            <span class="truncate text-base font-semibold">{{ app(\App\Support\Settings::class)->get('company.name', config('app.name')) }}</span>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5">
            @foreach (\App\Support\Navigation::for(auth()->user()) as $section)
                <div>
                    @if ($section['title'])
                        <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $section['title'] }}</p>
                    @endif
                    <ul class="space-y-1">
                        @foreach ($section['items'] as $item)
                            @php($active = request()->routeIs($item['active']))
                            <li>
                                <a href="{{ route($item['route']) }}" wire:navigate
                                   @class([
                                       'flex items-center gap-3 rounded-md px-3 py-2 text-sm transition',
                                       'bg-white/15 text-white font-medium' => $active,
                                       'text-gray-300 hover:bg-white/10 hover:text-white' => ! $active,
                                   ])>
                                    <x-ui.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        {{-- Topbar --}}
        <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 sm:px-6">
            <button type="button" class="rounded-md p-2 text-gray-500 hover:bg-gray-100 lg:hidden" @click="sidebarOpen = true" aria-label="{{ __('app.menu') }}">
                <x-ui.icon name="menu" class="h-6 w-6" />
            </button>

            <h1 class="flex-1 truncate text-lg font-semibold text-gray-800">{{ $title ?? '' }}</h1>

            <span class="hidden sm:inline text-sm text-gray-500">{{ auth()->user()->branch?->name }}</span>

            <livewire:notifications.bell />

            <x-dropdown width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                        <x-ui.icon name="user" class="h-5 w-5" />
                        <span>{{ auth()->user()->name }}</span>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile')" wire:navigate>{{ __('Profile') }}</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </header>

        <main class="flex-1 p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

<x-ui.toasts />
</body>
</html>
