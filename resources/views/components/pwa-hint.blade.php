{{--
    "Install the app" hint, shown ONCE per device: on iPhone / iPad (no install prompt there)
    with the Share → "Add to Home Screen" steps, and on Chrome / Edge / Android with an
    Install button. Hidden for good once closed or installed (localStorage on the device),
    and never shown inside the installed app.
--}}
<div x-data="{
        open: false,
        ios: false,
        key: 'pwa-install-hint-v1',
        seen() { try { return localStorage.getItem(this.key) === '1'; } catch (e) { return false; } },
        remember() { try { localStorage.setItem(this.key, '1'); } catch (e) {} },
        standalone() { return window.navigator.standalone === true || window.matchMedia('(display-mode: standalone)').matches; },
        init() {
            if (this.seen() || this.standalone()) return;
            const ua = navigator.userAgent;
            this.ios = /iphone|ipad|ipod/i.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            if (this.ios) {
                setTimeout(() => this.open = true, 2000);
            } else if (window.__pwaPrompt) {
                setTimeout(() => this.open = true, 2000);
            }
        },
        close() { this.open = false; this.remember(); },
        install() {
            window.__pwaPrompt?.prompt();
            window.__pwaPrompt?.userChoice.finally(() => { window.__pwaPrompt = null; this.close(); });
        },
     }"
     @pwa-installable.window="if (! ios && ! seen() && ! standalone()) setTimeout(() => open = true, 2000)"
     @pwa-installed.window="close()"
     x-show="open" x-cloak
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-x-0 bottom-0 z-[70] p-3 sm:inset-x-auto sm:bottom-4 sm:end-4 sm:w-96 sm:p-0"
     role="dialog" aria-live="polite" data-pwa-hint>
    <div class="rounded-2xl bg-white p-4 shadow-2xl ring-1 ring-gray-900/10">
        <div class="flex items-start gap-3">
            <img src="/pwa/icon-192-any.png" alt="" class="h-12 w-12 shrink-0 rounded-xl">
            <div class="min-w-0 flex-1">
                <p class="font-semibold text-gray-900">{{ __('pwa.hint_title') }}</p>
                <p class="mt-0.5 text-sm text-gray-600">{{ __('pwa.hint_body') }}</p>
            </div>
            <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600" aria-label="{{ __('app.close') }}">
                <x-ui.icon name="x" class="h-5 w-5" />
            </button>
        </div>

        <template x-if="ios">
            <ol class="mt-3 space-y-2 rounded-xl bg-gray-50 p-3 text-sm text-gray-700">
                <li class="flex items-center gap-2">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-700 text-xs font-bold text-white">1</span>
                    <span>{{ __('pwa.ios_step1') }}</span>
                    <x-ui.icon name="share" class="h-5 w-5 shrink-0 text-blue-600" />
                </li>
                <li class="flex items-center gap-2">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-700 text-xs font-bold text-white">2</span>
                    <span>{{ __('pwa.ios_step2') }}</span>
                    <x-ui.icon name="plus-square" class="h-5 w-5 shrink-0 text-gray-700" />
                </li>
            </ol>
        </template>

        <div class="mt-3 flex justify-end gap-2">
            <button type="button" @click="close()" class="rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">{{ __('pwa.not_now') }}</button>
            <template x-if="! ios">
                <button type="button" @click="install()" class="rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-900">{{ __('pwa.install') }}</button>
            </template>
            <template x-if="ios">
                <button type="button" @click="close()" class="rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-900">{{ __('pwa.got_it') }}</button>
            </template>
        </div>
    </div>
</div>
