/*
 * Service worker of the showroom PWA. Deliberately cautious for an accounting system:
 *  - pages, Livewire requests, reports, prints and every non-GET request always go to the
 *    network: balances, prices and stock are never served from a cache;
 *  - only the fingerprinted build files (CSS, JS, fonts under /build/) are cached, so the
 *    app shell opens fast;
 *  - when the network is down, a page navigation gets the offline page instead of the
 *    browser's error.
 */
const VERSION = 'v1';
const STATIC_CACHE = `cars-static-${VERSION}`;
const OFFLINE_URL = '/offline';

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => cache.add(new Request(OFFLINE_URL, { cache: 'reload' }))),
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((key) => key !== STATIC_CACHE).map((key) => caches.delete(key))))
            .then(() => self.clients.claim()),
    );
});

self.addEventListener('fetch', (event) => {
    const request = event.request;
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) {
        return;
    }

    // Pages: always the network; the offline page only when it is unreachable.
    if (request.mode === 'navigate') {
        event.respondWith(fetch(request).catch(() => caches.match(OFFLINE_URL)));
        return;
    }

    // Fingerprinted build files never change under the same name: cache first.
    if (url.pathname.startsWith('/build/')) {
        event.respondWith(
            caches.open(STATIC_CACHE).then((cache) => cache.match(request).then((cached) => cached || fetch(request).then((response) => {
                if (response.ok) {
                    cache.put(request, response.clone());
                }
                return response;
            }))),
        );
    }

    // Everything else (Livewire, data, images, PDFs): straight to the network, untouched.
});
