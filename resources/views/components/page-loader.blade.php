{{-- Centered loading indicator while moving between pages (wire:navigate). Shown and hidden by resources/js/page-loader.js. --}}
<div id="page-loader" role="status" aria-live="polite" aria-hidden="true"
     class="pointer-events-none fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/10 opacity-0 backdrop-blur-[1px] transition-opacity duration-150">
    <div class="flex flex-col items-center gap-3 rounded-2xl bg-white px-8 py-6 shadow-xl ring-1 ring-gray-200">
        <span class="relative block h-12 w-12">
            <span class="absolute inset-0 rounded-full border-4 border-brand-100"></span>
            <span class="absolute inset-0 animate-spin rounded-full border-4 border-transparent border-t-brand-600"></span>
        </span>
        <span class="text-sm font-medium text-gray-700">{{ __('app.loading') }}</span>
    </div>
</div>
