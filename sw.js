// Service Worker simple pour cache
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open('harmoniza-cache').then((cache) => {
      return cache.addAll([
        '/',
        '/index.php',
        '/boutique.php',
        '/produit.php',
        '/pierres.php',
        '/intentions.php',
        '/commande.php',
        '/css/style.css',
        '/js/main.js',
        '/js/cart.js',
        '/data/products.json',
        '/data/stones.json',
        '/manifest.json'
      ]);
    })
  );
});

self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);
  if (url.pathname.startsWith('/api/')) {
    // Stale-while-revalidate pour API
    event.respondWith(
      caches.match(event.request).then((response) => {
        return response || fetch(event.request).then((fetchResponse) => {
          return caches.open('harmoniza-cache').then((cache) => {
            cache.put(event.request, fetchResponse.clone());
            return fetchResponse;
          });
        });
      })
    );
  } else if (url.pathname.match(/\.(jpg|png|gif|svg)$/)) {
    // Cache-first pour images
    event.respondWith(
      caches.match(event.request).then((response) => {
        return response || fetch(event.request).then((fetchResponse) => {
          caches.open('harmoniza-cache').then((cache) => {
            cache.put(event.request, fetchResponse.clone());
          });
          return fetchResponse;
        });
      })
    );
  } else {
    // Network fallback to cache
    event.respondWith(
      fetch(event.request).catch(() => {
        return caches.match(event.request).then((response) => {
          if (response) return response;
          return new Response('<html><body><h1>Offline</h1><p>Vous êtes hors ligne. Connectez-vous pour commander.</p></body></html>', { headers: { 'Content-Type': 'text/html' } });
        });
      })
    );
  }
});
