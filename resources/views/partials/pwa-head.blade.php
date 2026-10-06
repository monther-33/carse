{{-- Progressive web app: manifest, icons, theme and the service worker (public/sw.js). --}}
<link rel="manifest" href="/manifest.webmanifest">
<meta name="theme-color" content="#14244f">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ app(\App\Support\Branding::class)->name() }}">
<link rel="apple-touch-icon" href="/pwa/icon-192-any.png">
<script>
    // Keep the install prompt for the "Install app" button (Chrome / Edge / Android).
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        window.__pwaPrompt = e;
        window.dispatchEvent(new CustomEvent('pwa-installable'));
    });
    window.addEventListener('appinstalled', () => { window.__pwaPrompt = null; window.dispatchEvent(new CustomEvent('pwa-installed')); });
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
    }
</script>
