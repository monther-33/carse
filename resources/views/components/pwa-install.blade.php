{{-- "Install app": shown only when the browser offers installation (and hidden once installed). --}}
@props(['buttonClass' => ''])
<div x-data="{ can: !!window.__pwaPrompt }" x-show="can" x-cloak
     @pwa-installable.window="can = true" @pwa-installed.window="can = false" {{ $attributes }}>
    <button type="button" class="{{ $buttonClass }}"
            @click="window.__pwaPrompt?.prompt(); window.__pwaPrompt?.userChoice.then(() => { window.__pwaPrompt = null; can = false; })">
        {{ $slot->isEmpty() ? __('pwa.install') : $slot }}
    </button>
</div>
