self.addEventListener('install', function (event) {
    event.waitUntil(caches.open('static')
        .then(function (cache) {
            cache.addAll([
                '/',
                '/movie/trending',
                '/build/app.css',
                '/build/app.js',
                '/images/logo.svg',
                '/images/popcorn.svg',
                '/images/ticket.svg'
            ]);
        })
    );
});

self.addEventListener('activate', function (event) {
    return self.clients.claim();
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request).then(function(response) {
            return response || fetch(event.request).catch(function (err) {});
        })
    );
});

let deferredPrompt;
self.addEventListener('beforeinstallprompt', function(event) {
    event.preventDefault();
    deferredPrompt = event;
    return false;
});