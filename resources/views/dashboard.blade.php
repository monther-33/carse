<x-app-layout :title="__('app.nav.dashboard')">
    <x-ui.card>
        <p class="text-gray-700">{{ __('app.dashboard.welcome', ['name' => auth()->user()->name]) }}</p>
        <p class="mt-2 text-sm text-gray-500">{{ __('app.dashboard.coming_soon') }}</p>
    </x-ui.card>
</x-app-layout>
