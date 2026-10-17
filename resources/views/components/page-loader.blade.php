{{-- Centered loading indicator while moving between pages (wire:navigate). Shown and hidden by resources/js/page-loader.js;
     the animated rings are styled in resources/css/app.css (#wifi-loader), in the theme's colours. --}}
<div id="page-loader" role="status" aria-live="polite" aria-hidden="true"
     class="pointer-events-none fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/10 opacity-0 backdrop-blur-[1px] transition-opacity duration-150">
    <div class="flex items-center justify-center rounded-2xl bg-white px-12 pb-14 pt-8 shadow-xl ring-1 ring-gray-200">
        <div id="wifi-loader">
            <svg viewBox="0 0 86 86" class="circle-outer" aria-hidden="true">
                <circle r="40" cy="43" cx="43" class="back" />
                <circle r="40" cy="43" cx="43" class="front" />
            </svg>
            <svg viewBox="0 0 60 60" class="circle-middle" aria-hidden="true">
                <circle r="27" cy="30" cx="30" class="back" />
                <circle r="27" cy="30" cx="30" class="front" />
            </svg>
            <div class="text" data-text="{{ __('app.loading') }}"></div>
        </div>
    </div>
</div>
