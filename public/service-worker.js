const CACHE_NAME = "agrowerk-pwa-v1";
const OFFLINE_URL = "/offline.html";
const PRECACHE_URLS = [
    "/",
    OFFLINE_URL,
    "/manifest.webmanifest",
    "/icons/icon-192x192.png",
    "/icons/icon-512x512.png",
    "/icons/maskable-icon-512x512.png",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_URLS))
    );
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(
                    keys
                        .filter((key) => key !== CACHE_NAME)
                        .map((key) => caches.delete(key))
                )
            )
    );
    self.clients.claim();
});

self.addEventListener("fetch", (event) => {
    const { request } = event;

    if (request.method !== "GET") {
        return;
    }

    // Navigation requests: network first, fallback to cache or offline page
    if (request.mode === "navigate") {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    return response;
                })
                .catch(async () => {
                    const cached = await caches.match(request);
                    return cached || caches.match(OFFLINE_URL);
                })
        );
        return;
    }

    // Static assets: cache first, then network
    if (
        request.destination === "style" ||
        request.destination === "script" ||
        request.destination === "image" ||
        request.destination === "font"
    ) {
        event.respondWith(
            caches.match(request).then((cached) => {
                const fetchAndCache = fetch(request)
                    .then((response) => {
                        const copy = response.clone();
                        caches.open(CACHE_NAME).then((cache) =>
                            cache.put(request, copy)
                        );
                        return response;
                    })
                    .catch(() => cached);

                return cached || fetchAndCache;
            })
        );
    }
});
