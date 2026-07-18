const CACHE_NAME = 'surface-mine-v1';
const URLS_TO_CACHE = [
    '/',
    '/login',
    '/manifest.json',
    '/offline.html',
    // We would cache CSS and JS here, but since Vite uses dynamic names, 
    // we'll rely on the network-first or stale-while-revalidate strategy.
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(URLS_TO_CACHE);
            })
    );
});

self.addEventListener('fetch', event => {
    // Basic network-first strategy for dynamic content, cache-first for static assets
    if (event.request.method !== 'GET') return;

    if (event.request.url.includes('/build/') || event.request.url.match(/\.(png|jpg|jpeg|gif|svg|ico)$/)) {
        // Cache first, fallback to network for static assets
        event.respondWith(
            caches.match(event.request)
                .then(response => {
                    return response || fetch(event.request).then(fetchResponse => {
                        return caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, fetchResponse.clone());
                            return fetchResponse;
                        });
                    });
                })
        );
    } else {
        // Network first, fallback to cache for everything else
        event.respondWith(
            fetch(event.request)
                .then(response => {
                    const resClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, resClone));
                    return response;
                })
                .catch(() => caches.match(event.request).then(response => response || caches.match('/offline.html')))
        );
    }
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
