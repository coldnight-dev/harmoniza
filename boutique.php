<?php
/**
 * Harmon'Iza - Boutique
 */
$pageTitle = "Boutique - Harmon'Iza";
$pageDescription = "Découvrez notre collection de bijoux et pierres énergétiques";
include 'includes/header.php';
?>

<div class="bg-gradient-to-r from-pink-50 to-purple-50 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-center mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-dark);" data-aos="fade-up">
            Notre Boutique
        </h1>
        <p class="text-center text-gray-600 text-lg max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Explorez notre sélection de bijoux et pierres naturelles
        </p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar filtres -->
        <aside class="lg:col-span-1">
            <div class="filter-bar sticky top-24" data-aos="fade-right">
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <i class="fas fa-filter mr-2 text-pink-600"></i>
                    Filtres
                </h3>
                
                <div class="filter-group">
                    <label for="categoryFilter">
                        <i class="fas fa-tag mr-1"></i>Catégorie
                    </label>
                    <select id="categoryFilter" onchange="applyFilters()">
                        <option value="all">Toutes</option>
                        <option value="bracelet">Bracelets</option>
                        <option value="collier">Colliers</option>
                        <option value="pierre">Pierres</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="intentionFilter">
                        <i class="fas fa-heart mr-1"></i>Intention
                    </label>
                    <select id="intentionFilter" onchange="applyFilters()">
                        <option value="all">Toutes</option>
                        <option value="amour">Amour</option>
                        <option value="protection">Protection</option>
                        <option value="ancrage">Ancrage</option>
                        <option value="abondance">Abondance</option>
                        <option value="serenite">Sérénité</option>
                        <option value="chance">Chance</option>
                        <option value="intuition">Intuition</option>
                        <option value="creativite">Créativité</option>
                        <option value="guerison">Guérison</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="sortFilter">
                        <i class="fas fa-sort mr-1"></i>Trier par
                    </label>
                    <select id="sortFilter" onchange="applyFilters()">
                        <option value="featured">En vedette</option>
                        <option value="name">Nom (A-Z)</option>
                        <option value="price_asc">Prix croissant</option>
                        <option value="price_desc">Prix décroissant</option>
                    </select>
                </div>

                <button onclick="resetFilters()" class="btn btn-outline w-full mt-4">
                    <i class="fas fa-redo mr-2"></i>Réinitialiser
                </button>
            </div>
        </aside>

        <!-- Grille produits -->
        <div class="lg:col-span-3">
            <div class="flex justify-between items-center mb-6" data-aos="fade-up">
                <p id="resultsCount" class="text-gray-600">
                    <i class="fas fa-box mr-1"></i>Chargement...
                </p>
                <div class="hidden md:flex gap-2">
                    <button onclick="toggleView('grid')" id="gridViewBtn" class="p-2 border rounded hover:bg-gray-100 bg-pink-50">
                        <i class="fas fa-th"></i>
                    </button>
                    <button onclick="toggleView('list')" id="listViewBtn" class="p-2 border rounded hover:bg-gray-100">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- Produits chargés dynamiquement -->
                <div class="col-span-full flex justify-center py-12">
                    <div class="loader"></div>
                </div>
            </div>

            <!-- Pas de résultat -->
            <div id="noResults" class="hidden text-center py-12">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun produit trouvé</h3>
                <p class="text-gray-600 mb-4">Essayez de modifier vos filtres</p>
                <button onclick="resetFilters()" class="btn btn-primary">
                    Voir tous les produits
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof loadData === 'undefined' || typeof filterProducts === 'undefined') {
        console.error('Scripts non chargés, retry...');
        setTimeout(initBoutique, 100);
        return;
    }
    initBoutique();
});

function initBoutique() {
    let currentView = 'grid';
    let currentFilters = {
        category: 'all',
        intention: 'all',
        sort: 'featured'
    };

    loadData().then(data => {
        const urlParams = getURLParams();
        
        if (urlParams.category !== 'all') {
            document.getElementById('categoryFilter').value = urlParams.category;
        }
        if (urlParams.intention !== 'all') {
            document.getElementById('intentionFilter').value = urlParams.intention;
        }
        if (urlParams.stone) {
            currentFilters.stone = urlParams.stone;
        }
        
        applyFilters();
    });

    window.applyFilters = function() {
        currentFilters = {
            category: document.getElementById('categoryFilter').value,
            intention: document.getElementById('intentionFilter').value,
            sort: document.getElementById('sortFilter').value
        };

        const filtered = filterProducts(currentFilters);
        displayProducts(filtered, 'productsGrid');
        
        const count = filtered.length;
        document.getElementById('resultsCount').innerHTML = `
            <i class="fas fa-box mr-1"></i>${count} produit${count > 1 ? 's' : ''} trouvé${count > 1 ? 's' : ''}
        `;

        document.getElementById('noResults').classList.toggle('hidden', count > 0);
        document.getElementById('productsGrid').classList.toggle('hidden', count === 0);

        updateURL(currentFilters);
    };

    window.resetFilters = function() {
        document.getElementById('categoryFilter').value = 'all';
        document.getElementById('intentionFilter').value = 'all';
        document.getElementById('sortFilter').value = 'featured';
        applyFilters();
    };

    window.toggleView = function(view) {
        currentView = view;
        const grid = document.getElementById('productsGrid');
        
        if (view === 'list') {
            grid.classList.remove('md:grid-cols-2', 'xl:grid-cols-3');
            grid.classList.add('grid-cols-1');
            document.getElementById('listViewBtn').classList.add('bg-pink-50');
            document.getElementById('gridViewBtn').classList.remove('bg-pink-50');
        } else {
            grid.classList.add('md:grid-cols-2', 'xl:grid-cols-3');
            grid.classList.remove('grid-cols-1');
            document.getElementById('gridViewBtn').classList.add('bg-pink-50');
            document.getElementById('listViewBtn').classList.remove('bg-pink-50');
        }
    };
}
</script>

<?php include 'includes/footer.php'; ?>
