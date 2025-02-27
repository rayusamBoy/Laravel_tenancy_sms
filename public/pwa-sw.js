importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.4.1/workbox-sw.js');

workbox.recipes.pageCache();

workbox.recipes.googleFontsCache();

workbox.recipes.staticResourceCache();

workbox.recipes.imageCache({
    maxEntries: 200,
});

workbox.recipes.offlineFallback({
    pageFallback: '/offline',
});