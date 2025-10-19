<?php
/**
 * Harmon'Iza - Guide par intentions
 */
$pageTitle = "Guide par Intentions - Harmon'Iza";
$pageDescription = "Trouvez les pierres et bijoux selon vos intentions";
include 'includes/header.php';
?>

<div class="bg-gradient-to-r from-pink-50 to-purple-50 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-center mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-dark);" data-aos="fade-up">
            <i class="fas fa-heart mr-3"></i>Trouvez votre Intention
        </h1>
        <p class="text-center text-gray-600 text-lg max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Laissez-vous guider par vos besoins et découvrez les pierres et bijoux qui vous correspondent
        </p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <!-- Sélection d'intention -->
    <div class="max-w-5xl mx-auto mb-12" data-aos="fade-up">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Choisissez une intention</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="intentionCards">
                <!-- Généré dynamiquement -->
            </div>
        </div>
    </div>

    <!-- Résultats -->
    <div id="intentionResults" class="hidden">
        <div class="text-center mb-8" data-aos="fade-up">
            <h2 id="intentionTitle" class="text-3xl font-bold mb-3" style="color: var(--primary-dark);"></h2>
            <p id="intentionDescription" class="text-gray-600 max-w-2xl mx-auto"></p>
        </div>

        <!-- Pierres recommandées -->
        <div class="mb-12" data-aos="fade-up">
            <h3 class="text-2xl font-bold mb-6 flex items-center">
                <i class="fas fa-gem text-purple-600 mr-3"></i>
                Pierres recommandées
            </h3>
            <div id="recommendedStones" class="grid grid-cols-1 md:grid-cols-3 gap-6"></div>
        </div>

        <!-- Produits associés -->
        <div data-aos="fade-up">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-shopping-bag text-pink-600 mr-3"></i>
                    Produits associés
                </h3>
                <button onclick="addAllToCart()" class="btn btn-secondary">
                    <i class="fas fa-cart-plus mr-2"></i>
                    Tout ajouter au panier
                </button>
            </div>
            <div id="recommendedProducts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof loadData === 'undefined') {
        console.error('main.js pas encore chargé, retry...');
        setTimeout(initIntentions, 100);
        return;
    }
    initIntentions();
});

function initIntentions() {
    const intentionData = {
        'amour': {
            title: 'Amour & Relations',
            description: 'Ouvrez votre cœur, cultivez l\'amour de soi et attirez des relations harmonieuses',
            icon: 'heart',
            color: 'pink'
        },
        'protection': {
            title: 'Protection Énergétique',
            description: 'Créez un bouclier contre les énergies négatives et restez ancré',
            icon: 'shield-alt',
            color: 'purple'
        },
        'ancrage': {
            title: 'Ancrage & Stabilité',
            description: 'Connectez-vous à la terre et trouvez votre équilibre intérieur',
            icon: 'tree',
            color: 'green'
        },
        'abondance': {
            title: 'Abondance & Prospérité',
            description: 'Attirez l\'abondance sous toutes ses formes et manifestez vos objectifs',
            icon: 'coins',
            color: 'yellow'
        },
        'serenite': {
            title: 'Sérénité & Paix',
            description: 'Apaisez votre mental et trouvez la paix intérieure',
            icon: 'spa',
            color: 'blue'
        },
        'chance': {
            title: 'Chance & Opportunités',
            description: 'Ouvrez-vous aux opportunités et attirez la chance',
            icon: 'clover',
            color: 'emerald'
        },
        'intuition': {
            title: 'Intuition & Sagesse',
            description: 'Développez votre sixième sens et accédez à votre sagesse intérieure',
            icon: 'eye',
            color: 'indigo'
        },
        'creativite': {
            title: 'Créativité & Expression',
            description: 'Libérez votre créativité et exprimez votre authenticité',
            icon: 'palette',
            color: 'orange'
        }
    };

    let currentIntentionData = {};

    loadData().then(data => {
        createIntentionCards();
        
        const urlParams = new URLSearchParams(window.location.search);
        const intention = urlParams.get('intention');
        if (intention && intentionData[intention]) {
            showIntentionResults(intention);
        }
    });

    function createIntentionCards() {
        const container = document.getElementById('intentionCards');
        
        const colorMap = {
            'pink': 'bg-pink-50 hover:bg-pink-100 border-pink-200 text-pink-600',
            'purple': 'bg-purple-50 hover:bg-purple-100 border-purple-200 text-purple-600',
            'green': 'bg-green-50 hover:bg-green-100 border-green-200 text-green-600',
            'yellow': 'bg-yellow-50 hover:bg-yellow-100 border-yellow-200 text-yellow-600',
            'blue': 'bg-blue-50 hover:bg-blue-100 border-blue-200 text-blue-600',
            'emerald': 'bg-emerald-50 hover:bg-emerald-100 border-emerald-200 text-emerald-600',
            'indigo': 'bg-indigo-50 hover:bg-indigo-100 border-indigo-200 text-indigo-600',
            'orange': 'bg-orange-50 hover:bg-orange-100 border-orange-200 text-orange-600'
        };
        
        Object.entries(intentionData).forEach(([slug, data]) => {
            const colors = colorMap[data.color] || colorMap['pink'];
            container.innerHTML += `
                <button onclick="showIntentionResults('${slug}')" 
                    class="intention-card flex flex-col items-center p-6 rounded-xl hover:shadow-lg transition ${colors} border-2">
                    <i class="fas fa-${data.icon} text-4xl mb-3"></i>
                    <span class="font-semibold text-center">${data.title.split(' & ')[0]}</span>
                </button>
            `;
        });
    }

    window.showIntentionResults = function(intention) {
        if (!intentionData[intention]) return;

        const info = intentionData[intention];
        currentIntentionData = { intention, ...info };

        document.getElementById('intentionTitle').innerHTML = `
            <i class="fas fa-${info.icon} mr-3"></i>
            ${info.title}
        `;
        document.getElementById('intentionDescription').textContent = info.description;

        document.getElementById('intentionResults').classList.remove('hidden');

        loadData().then(data => {
            const stones = data.stones.filter(s => s.intentions.includes(intention));
            const products = data.products.filter(p => p.intentions.includes(intention));

            displayStones(stones, 'recommendedStones');
            displayProducts(products, 'recommendedProducts');

            document.getElementById('intentionResults').scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        });

        const url = new URL(window.location);
        url.searchParams.set('intention', intention);
        window.history.pushState({}, '', url);
    };

    window.addAllToCart = function() {
        if (!currentIntentionData.intention) return;

        loadData().then(data => {
            const products = data.products.filter(p => 
                p.intentions.includes(currentIntentionData.intention)
            );

            products.forEach(product => {
                Cart.add(product, 1);
            });

            alert(`${products.length} produits ajoutés au panier !`);
        });
    };
}
</script>

<?php include 'includes/footer.php'; ?>
