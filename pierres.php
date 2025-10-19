<?php
/**
 * Harmon'Iza - Découvrir les pierres
 */
$pageTitle = "Découvrir les Pierres - Harmon'Iza";
$pageDescription = "Guide complet des pierres naturelles et leurs vertus énergétiques";
include 'includes/header.php';
?>

<div class="bg-gradient-to-r from-purple-50 to-pink-50 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-center mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-dark);" data-aos="fade-up">
            <i class="fas fa-gem mr-3"></i>Guide des Pierres
        </h1>
        <p class="text-center text-gray-600 text-lg max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Explorez les vertus, origines et méthodes d'entretien de nos pierres naturelles
        </p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <!-- Recherche et filtres -->
    <div class="max-w-4xl mx-auto mb-12">
        <div class="search-bar mb-6" data-aos="fade-up">
            <i class="fas fa-search"></i>
            <input type="text" 
                   id="stoneSearch" 
                   placeholder="Rechercher une pierre par nom..."
                   onkeyup="searchStones(this.value)"
                   autocomplete="off">
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
            <h3 class="font-bold text-lg mb-4 flex items-center">
                <i class="fas fa-filter text-purple-600 mr-2"></i>
                Filtrer par intention
            </h3>
            <div class="flex flex-wrap gap-2" id="intentionFilters">
                <button onclick="filterStonesByIntention('all')" 
                    class="filter-btn active px-4 py-2 rounded-full border-2 border-pink-300 bg-pink-50 hover:bg-pink-100 transition font-semibold text-sm">
                    Toutes
                </button>
            </div>
        </div>
    </div>

    <!-- Index alphabétique -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-12 sticky top-20 z-10" data-aos="fade-up">
        <div class="flex flex-wrap justify-center gap-2" id="alphaIndex">
            <!-- Généré dynamiquement -->
        </div>
    </div>

    <!-- Grille des pierres -->
    <div id="stonesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="col-span-full flex justify-center py-12">
            <div class="loader"></div>
        </div>
    </div>

    <!-- Message aucun résultat -->
    <div id="noStonesFound" class="hidden text-center py-12">
        <i class="fas fa-gem text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucune pierre trouvée</h3>
        <p class="text-gray-600">Essayez une autre recherche ou réinitialisez les filtres</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof loadData === 'undefined') {
        console.error('main.js pas encore chargé, retry...');
        setTimeout(initPierres, 100);
        return;
    }
    initPierres();
});

function initPierres() {
    let allStones = [];
    let filteredStones = [];
    let currentIntention = 'all';

    loadData().then(data => {
        allStones = data.stones;
        filteredStones = allStones;
        
        createIntentionFilters();
        createAlphaIndex();
        displayStones(allStones, 'stonesGrid');
    });

    window.createIntentionFilters = function() {
        const intentions = new Set();
        allStones.forEach(stone => {
            stone.intentions.forEach(int => intentions.add(int));
        });

        const container = document.getElementById('intentionFilters');
        
        Array.from(intentions).sort().forEach(intention => {
            container.innerHTML += `
                <button onclick="filterStonesByIntention('${intention}')" 
                    class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 hover:border-pink-300 hover:bg-pink-50 transition font-semibold text-sm">
                    ${intention}
                </button>
            `;
        });
    };

    window.filterStonesByIntention = function(intention) {
        currentIntention = intention;
        
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-pink-50', 'border-pink-300');
            btn.classList.add('border-gray-300');
        });
        event.target.classList.add('active', 'bg-pink-50', 'border-pink-300');
        event.target.classList.remove('border-gray-300');

        if (intention === 'all') {
            filteredStones = allStones;
        } else {
            filteredStones = allStones.filter(s => s.intentions.includes(intention));
        }

        displayStones(filteredStones, 'stonesGrid');
        updateNoResultsMessage();
    };

    window.searchStones = function(query) {
        if (query.length < 2) {
            filteredStones = currentIntention === 'all' 
                ? allStones 
                : allStones.filter(s => s.intentions.includes(currentIntention));
        } else {
            const q = query.toLowerCase();
            const baseStones = currentIntention === 'all' 
                ? allStones 
                : allStones.filter(s => s.intentions.includes(currentIntention));
            
            filteredStones = baseStones.filter(s => 
                s.name.toLowerCase().includes(q) ||
                s.virtues.toLowerCase().includes(q) ||
                s.origin.toLowerCase().includes(q)
            );
        }

        displayStones(filteredStones, 'stonesGrid');
        updateNoResultsMessage();
    };

    function updateNoResultsMessage() {
        const hasResults = filteredStones.length > 0;
        document.getElementById('stonesGrid').classList.toggle('hidden', !hasResults);
        document.getElementById('noStonesFound').classList.toggle('hidden', hasResults);
    }

    function createAlphaIndex() {
        const letters = new Set();
        allStones.forEach(stone => {
            letters.add(stone.name[0].toUpperCase());
        });

        const container = document.getElementById('alphaIndex');
        Array.from(letters).sort().forEach(letter => {
            container.innerHTML += `
                <button onclick="scrollToLetter('${letter}')" 
                    class="w-10 h-10 rounded-full border-2 border-purple-300 hover:bg-purple-100 font-bold transition">
                    ${letter}
                </button>
            `;
        });
    }

    window.scrollToLetter = function(letter) {
        const stone = allStones.find(s => s.name[0].toUpperCase() === letter);
        if (stone) {
            const element = document.querySelector(`[data-stone="${stone.slug}"]`);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                element.classList.add('ring-4', 'ring-purple-300');
                setTimeout(() => {
                    element.classList.remove('ring-4', 'ring-purple-300');
                }, 2000);
            }
        }
    };

    window.displayStones = function(stones, containerId = 'stonesGrid') {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (stones.length === 0) {
            container.innerHTML = '';
            return;
        }

        container.innerHTML = stones.map(s => `
            <div class="stone-card transition" data-stone="${s.slug}" data-aos="fade-up">
                <img src="${s.image}" alt="${s.name}" loading="lazy">
                <h3>${s.name}</h3>
                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-map-marker-alt text-pink-500 mr-1"></i>
                    ${s.origin}
                </p>
                <p class="text-sm text-gray-700 mb-3">${s.virtues.substring(0, 150)}...</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    ${s.intentions.slice(0, 3).map(i => `<span class="tag">${i}</span>`).join('')}
                </div>
                <div class="flex gap-2">
                    <button onclick="showStoneDetail('${s.slug}')" class="btn btn-primary flex-1 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>Détails
                    </button>
                    <button onclick="findProductsWithStone('${s.slug}')" class="btn btn-outline text-sm">
                        <i class="fas fa-shopping-bag"></i>
                    </button>
                </div>
            </div>
        `).join('');
    };
}
</script>

<?php include 'includes/footer.php'; ?>
