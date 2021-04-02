const STATIC_CACHE_NAME = 'static';

self.addEventListener('install', function (event) {
    event.waitUntil(caches.open(STATIC_CACHE_NAME)
        .then(function (cache) {
            cache.addAll([
                '/offline.html',
                '/images/logo.svg'
            ]).catch(function (err) {
                console.log(err);
            });
        })
    );
});

self.addEventListener('activate', function (event) {
    return self.clients.claim();
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                return response || fetch(event.request).catch(function (err) {
                    return caches.open(STATIC_CACHE_NAME)
                        .then(function (cache) {
                            return cache.match('/offline.html');
                        })
                });
            })
    );
});

let deferredPrompt;
self.addEventListener('beforeinstallprompt', function(event) {
    event.preventDefault();
    deferredPrompt = event;
    return false;
});