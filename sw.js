const CACHE_NAME = 'harmoniza-v1';
const BASE_URL = '/harmoniza/';

const STATIC_ASSETS = [
  BASE_URL,
  BASE_URL + 'index.php',
  BASE_URL + 'boutique.php',
  BASE_URL + 'pierres.php',
  BASE_URL + 'intentions.php',
  BASE_URL + 'commande.php',
  BASE_URL + 'css/style.css',
  BASE_URL + 'js/main.js',
  BASE_URL + 'js/cart.js',
  BASE_URL + 'js/pwa.js',
  BASE_URL + 'data/products.json',
  BASE_URL + 'data/stones.json',
  BASE_URL + 'manifest.json'
];

// Installation du Service Worker
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_ASSETS).catch((err) => {
        console.error('Erreur lors de la mise en cache:', err);
      });
    })
  );
  self.skipWaiting();
});

// Activation et nettoyage des anciens caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames
          .filter((name) => name !== CACHE_NAME)
          .map((name) => caches.delete(name))
      );
    })
  );
  self.clients.claim();
});

// Stratégie de fetch
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Ignorer les requêtes non-GET
  if (request.method !== 'GET') {
    return;
  }

  // Ignorer les requêtes API (toujours réseau)
  if (url.pathname.includes('/api/')) {
    return;
  }

  // Stratégie Cache-First pour les assets statiques
  if (
    url.pathname.includes('/css/') ||
    url.pathname.includes('/js/') ||
    url.pathname.includes('/data/') ||
    url.pathname.endsWith('.png') ||
    url.pathname.endsWith('.jpg') ||
    url.pathname.endsWith('.svg')
  ) {
    event.respondWith(
      caches.match(request).then((cached) => {
        return cached || fetch(request).then((response) => {
          return caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, response.clone());
            return response;
          });
        });
      })
    );
    return;
  }

  // Stratégie Stale-While-Revalidate pour les pages HTML
  event.respondWith(
    caches.match(request).then((cached) => {
      const fetchPromise = fetch(request).then((response) => {
        return caches.open(CACHE_NAME).then((cache) => {
          cache.put(request, response.clone());
          return response;
        });
      }).catch(() => {
        // Si hors ligne et pas en cache, retourner page offline
        if (cached) return cached;
        return caches.match(BASE_URL + 'offline.html');
      });

      return cached || fetchPromise;
    })
  );
});
