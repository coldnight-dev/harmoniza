/**
 * Harmon'Iza - Script principal (VERSION CORRIGÉE)
 */

const BASE_URL = '/harmoniza/';
let productsData = [];
let stonesData = [];

// Charger les données avec chemins absolus
async function loadData() {
  try {
    // Utiliser window.location.origin pour avoir le chemin absolu
    const baseUrl = window.location.origin + BASE_URL;
    
    console.log('Chargement depuis:', baseUrl);
    
    const [productsRes, stonesRes] = await Promise.all([
      fetch(baseUrl + 'data/products.json'),
      fetch(baseUrl + 'data/stones.json')
    ]);
    
    if (!productsRes.ok) {
      throw new Error(`Erreur chargement products.json: HTTP ${productsRes.status}`);
    }
    if (!stonesRes.ok) {
      throw new Error(`Erreur chargement stones.json: HTTP ${stonesRes.status}`);
    }
    
    productsData = await productsRes.json();
    stonesData = await stonesRes.json();
    
    console.log('Données chargées:', productsData.length, 'produits,', stonesData.length, 'pierres');
    
    return { products: productsData, stones: stonesData };
  } catch (error) {
    console.error('Erreur chargement données:', error);
    return { products: [], stones: [] };
  }
}

// Recherche avec debounce
let searchTimeout;
function handleSearch(query) {
  clearTimeout(searchTimeout);
  
  searchTimeout = setTimeout(() => {
    if (query.length < 2) {
      hideSuggestions();
      return;
    }

    const results = searchProducts(query);
    showSuggestions(results);
  }, 300);
}

function searchProducts(query) {
  const q = query.toLowerCase();
  return productsData.filter(p => 
    p.name.toLowerCase().includes(q) ||
    p.stones.some(s => s.toLowerCase().includes(q)) ||
    p.intentions.some(i => i.toLowerCase().includes(q))
  ).slice(0, 5);
}

function showSuggestions(results) {
  const container = document.getElementById('searchSuggestions');
  if (!container) return;

  if (results.length === 0) {
    container.innerHTML = '<div class="search-suggestion">Aucun résultat</div>';
  } else {
    container.innerHTML = results.map(p => `
      <div class="search-suggestion" onclick="goToProduct('${p.slug}')">
        <div class="flex items-center gap-3">
          <img src="${p.images[0]}" alt="${p.name}" class="w-12 h-12 object-cover rounded">
          <div>
            <div class="font-semibold">${p.name}</div>
            <div class="text-sm text-gray-600">${p.price ? p.price.toFixed(2) + ' €' : 'Prix non défini'}</div>
          </div>
        </div>
      </div>
    `).join('');
  }
  
  container.style.display = 'block';
}

function hideSuggestions() {
  const container = document.getElementById('searchSuggestions');
  if (container) {
    container.style.display = 'none';
  }
}

function goToProduct(slug) {
  window.location.href = BASE_URL + 'produit.php?slug=' + slug;
}

// Filtrer les produits
function filterProducts(filters) {
  let filtered = [...productsData];

  if (filters.category && filters.category !== 'all') {
    filtered = filtered.filter(p => p.category === filters.category);
  }

  if (filters.intention && filters.intention !== 'all') {
    filtered = filtered.filter(p => p.intentions.includes(filters.intention));
  }

  if (filters.sort) {
    switch(filters.sort) {
      case 'price_asc':
        filtered.sort((a, b) => (a.price || 0) - (b.price || 0));
        break;
      case 'price_desc':
        filtered.sort((a, b) => (b.price || 0) - (a.price || 0));
        break;
      case 'name':
        filtered.sort((a, b) => a.name.localeCompare(b.name));
        break;
    }
  }

  return filtered;
}

// Afficher les produits
function displayProducts(products, containerId = 'productsGrid') {
  const container = document.getElementById(containerId);
  if (!container) return;

  if (products.length === 0) {
    container.innerHTML = '<div class="col-span-full text-center text-gray-600 py-12">Aucun produit trouvé</div>';
    return;
  }

  container.innerHTML = products.map(p => createProductCard(p)).join('');
}

function createProductCard(product) {
  const priceHTML = product.price ? `<div class="price">${product.price.toFixed(2)} €</div>` : '';
  const featuredBadge = product.featured ? '<span class="absolute top-2 right-2 bg-yellow-400 text-white px-2 py-1 rounded text-xs font-bold">★ Sélection</span>' : '';
  
  return `
    <div class="product-card" onclick="goToProduct('${product.slug}')" data-aos="fade-up">
      <div class="relative">
        ${featuredBadge}
        <img src="${product.images[0]}" alt="${product.name}" loading="lazy">
      </div>
      <div class="product-card-body">
        <h3>${product.name}</h3>
        ${priceHTML}
        <p class="text-sm text-gray-600 mb-2">${product.description.substring(0, 80)}...</p>
        <div class="tags">
          ${product.intentions.slice(0, 2).map(i => `<span class="tag">${i}</span>`).join('')}
        </div>
      </div>
    </div>
  `;
}

// Afficher les pierres
function displayStones(stones, containerId = 'stonesGrid') {
  const container = document.getElementById(containerId);
  if (!container) return;

  container.innerHTML = stones.map(s => createStoneCard(s)).join('');
}

function createStoneCard(stone) {
  return `
    <div class="stone-card" data-aos="fade-up">
      <img src="${stone.image}" alt="${stone.name}" loading="lazy">
      <h3>${stone.name}</h3>
      <p class="text-sm text-gray-600 mb-3">${stone.virtues.substring(0, 120)}...</p>
      <div class="flex flex-wrap gap-2 mb-3">
        ${stone.intentions.slice(0, 3).map(i => `<span class="tag">${i}</span>`).join('')}
      </div>
      <button onclick="showStoneDetail('${stone.slug}')" class="btn btn-outline btn-sm w-full">
        En savoir plus
      </button>
    </div>
  `;
}

// Modal pierre détail
function showStoneDetail(slug) {
  const stone = stonesData.find(s => s.slug === slug);
  if (!stone) return;

  const modal = document.createElement('div');
  modal.className = 'modal';
  modal.innerHTML = `
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="text-2xl font-bold">${stone.name}</h2>
        <button class="modal-close" onclick="this.closest('.modal').remove()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <img src="${stone.image}" alt="${stone.name}" class="w-full h-64 object-cover rounded-lg mb-4">
        
        <div class="mb-4">
          <h3 class="font-bold text-lg mb-2">Origine</h3>
          <p>${stone.origin}</p>
        </div>

        <div class="mb-4">
          <h3 class="font-bold text-lg mb-2">Vertus</h3>
          <p class="text-gray-700">${stone.virtues}</p>
        </div>

        <div class="mb-4">
          <h3 class="font-bold text-lg mb-2">Intentions</h3>
          <div class="flex flex-wrap gap-2">
            ${stone.intentions.map(i => `<span class="tag">${i}</span>`).join('')}
          </div>
        </div>

        <div class="mb-4">
          <h3 class="font-bold text-lg mb-2">Chakra</h3>
          <p>${stone.chakra || 'Non spécifié'}</p>
        </div>

        <div class="bg-amber-50 p-4 rounded-lg mb-4">
          <h3 class="font-bold text-lg mb-2 text-amber-800">
            <i class="fas fa-spa mr-2"></i>Entretien
          </h3>
          <div class="space-y-2 text-sm">
            <div>
              <strong>Purification:</strong> ${stone.care.purification.join(', ')}
            </div>
            <div>
              <strong>Rechargement:</strong> ${stone.care.charging.join(', ')}
            </div>
            ${stone.care.avoid.length > 0 ? `
              <div class="text-red-700">
                <strong>À éviter:</strong> ${stone.care.avoid.join(', ')}
              </div>
            ` : ''}
          </div>
        </div>

        ${stone.associations.length > 0 ? `
          <div class="mb-4">
            <h3 class="font-bold text-lg mb-2">Associations recommandées</h3>
            <p class="text-sm text-gray-600">Se combine bien avec : ${stone.associations.join(', ')}</p>
          </div>
        ` : ''}

        <div class="mt-6">
          <button onclick="findProductsWithStone('${stone.slug}')" class="btn btn-primary w-full">
            Voir les produits avec ${stone.name}
          </button>
        </div>
      </div>
    </div>
  `;
  
  document.body.appendChild(modal);
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.remove();
    }
  });
}

// Trouver produits avec une pierre
function findProductsWithStone(stoneSlug) {
  const products = productsData.filter(p => p.stones.includes(stoneSlug));
  if (products.length > 0) {
    window.location.href = BASE_URL + 'boutique.php?stone=' + stoneSlug;
  } else {
    alert('Aucun produit ne contient cette pierre actuellement.');
  }
}

// Gérer les intentions
function showIntentionProducts(intention) {
  const products = productsData.filter(p => p.intentions.includes(intention));
  const stones = stonesData.filter(s => s.intentions.includes(intention));
  
  return { products, stones };
}

// Partage (Web Share API)
async function shareProduct(product) {
  if (navigator.share) {
    try {
      await navigator.share({
        title: product.name,
        text: product.description,
        url: window.location.href
      });
    } catch (err) {
      console.log('Partage annulé ou échoué');
    }
  } else {
    // Fallback: copier le lien
    navigator.clipboard.writeText(window.location.href);
    alert('Lien copié dans le presse-papier');
  }
}

// Mettre à jour l'URL avec les filtres
function updateURL(params) {
  const url = new URL(window.location);
  Object.keys(params).forEach(key => {
    if (params[key] && params[key] !== 'all') {
      url.searchParams.set(key, params[key]);
    } else {
      url.searchParams.delete(key);
    }
  });
  window.history.pushState({}, '', url);
}

// Lire les paramètres URL
function getURLParams() {
  const params = new URLSearchParams(window.location.search);
  return {
    category: params.get('category') || 'all',
    intention: params.get('intention') || 'all',
    stone: params.get('stone') || '',
    sort: params.get('sort') || 'featured'
  };
}

// Formater le prix
function formatPrice(price) {
  return price ? price.toFixed(2).replace('.', ',') + ' €' : 'Prix non défini';
}

// Vérifier si en ligne
function isOnline() {
  return navigator.onLine;
}

// Gérer le mode offline
window.addEventListener('online', () => {
  const offlineBanner = document.getElementById('offlineBanner');
  if (offlineBanner) {
    offlineBanner.style.display = 'none';
  }
});

window.addEventListener('offline', () => {
  let offlineBanner = document.getElementById('offlineBanner');
  if (!offlineBanner) {
    offlineBanner = document.createElement('div');
    offlineBanner.id = 'offlineBanner';
    offlineBanner.className = 'fixed top-0 left-0 right-0 bg-orange-500 text-white text-center py-2 z-50';
    offlineBanner.innerHTML = '<i class="fas fa-wifi-slash mr-2"></i>Mode hors ligne - certaines fonctionnalités sont limitées';
    document.body.appendChild(offlineBanner);
  }
  offlineBanner.style.display = 'block';
});

// Initialisation globale
document.addEventListener('DOMContentLoaded', () => {
  // Cacher les suggestions au clic extérieur
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-bar')) {
      hideSuggestions();
    }
  });

  // Menu mobile toggle
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  
  if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }
});
