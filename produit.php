<?php
/**
 * Harmon'Iza - Fiche produit
 */
$pageTitle = "Produit - Harmon'Iza";
$pageDescription = "Découvrez nos bijoux et pierres énergétiques";
include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div id="productDetail">
        <!-- Chargement -->
        <div class="flex justify-center py-20">
            <div class="loader"></div>
        </div>
    </div>
</div>

<script>
const slug = new URLSearchParams(window.location.search).get('slug');

if (!slug) {
    window.location.href = '/harmoniza/boutique.php';
}

loadData().then(data => {
    const product = data.products.find(p => p.slug === slug);
    
    if (!product) {
        document.getElementById('productDetail').innerHTML = `
            <div class="text-center py-20">
                <i class="fas fa-exclamation-triangle text-6xl text-yellow-500 mb-4"></i>
                <h2 class="text-3xl font-bold mb-4">Produit introuvable</h2>
                <a href="/harmoniza/boutique.php" class="btn btn-primary">Retour à la boutique</a>
            </div>
        `;
        return;
    }

    // Mettre à jour le titre de la page
    document.title = product.name + ' - Harmon\'Iza';

    // Afficher le produit
    displayProductDetail(product, data.stones);
});

function displayProductDetail(product, stones) {
    const container = document.getElementById('productDetail');
    
    const priceHTML = product.price 
        ? `<div class="text-4xl font-bold text-yellow-600 mb-4">${product.price.toFixed(2)} €</div>`
        : '<div class="text-lg text-gray-600 mb-4">Prix sur demande</div>';

    const stonesList = product.stones.map(stoneSlug => {
        const stone = stones.find(s => s.slug === stoneSlug);
        return stone ? `
            <button onclick="showStoneDetail('${stone.slug}')" 
                class="inline-flex items-center gap-2 bg-purple-100 text-purple-800 px-4 py-2 rounded-full hover:bg-purple-200 transition">
                <i class="fas fa-gem"></i>
                <span>${stone.name}</span>
            </button>
        ` : '';
    }).join('');

    const intentionsList = product.intentions.map(int => 
        `<span class="tag">${int}</span>`
    ).join('');

    container.innerHTML = `
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm text-gray-600" data-aos="fade-up">
            <a href="/harmoniza/" class="hover:text-pink-600">Accueil</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <a href="/harmoniza/boutique.php" class="hover:text-pink-600">Boutique</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">${product.name}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Galerie images -->
            <div data-aos="fade-right">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-4">
                    <img id="mainImage" src="${product.images[0]}" alt="${product.name}" 
                        class="w-full h-96 object-cover">
                </div>
                ${product.images.length > 1 ? `
                    <div class="flex gap-4">
                        ${product.images.map((img, idx) => `
                            <button onclick="changeImage('${img}')" 
                                class="w-20 h-20 rounded-lg overflow-hidden border-2 border-transparent hover:border-pink-500 transition">
                                <img src="${img}" alt="Image ${idx + 1}" class="w-full h-full object-cover">
                            </button>
                        `).join('')}
                    </div>
                ` : ''}
            </div>

            <!-- Informations produit -->
            <div data-aos="fade-left">
                <h1 class="text-4xl font-bold mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-dark);">
                    ${product.name}
                </h1>

                ${priceHTML}

                <div class="flex gap-2 mb-6">
                    ${intentionsList}
                </div>

                <div class="bg-pink-50 rounded-xl p-6 mb-6">
                    <h3 class="font-bold text-lg mb-3 flex items-center">
                        <i class="fas fa-info-circle text-pink-600 mr-2"></i>
                        Description
                    </h3>
                    <p class="text-gray-700 leading-relaxed">${product.description}</p>
                </div>

                <div class="bg-purple-50 rounded-xl p-6 mb-6">
                    <h3 class="font-bold text-lg mb-3 flex items-center">
                        <i class="fas fa-spa text-purple-600 mr-2"></i>
                        Vertus énergétiques
                    </h3>
                    <p class="text-gray-700 leading-relaxed">${product.virtues}</p>
                </div>

                <div class="mb-6">
                    <h3 class="font-bold text-lg mb-3 flex items-center">
                        <i class="fas fa-gem text-purple-600 mr-2"></i>
                        Pierres utilisées
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        ${stonesList}
                    </div>
                </div>

                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-amber-600 mt-1 mr-3"></i>
                        <div class="text-sm text-gray-700">
                            <strong>Note:</strong> Stock non géré. Disponibilité confirmée après commande.
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 mb-6">
                    <button onclick="addToCart()" class="btn btn-primary flex-1 text-lg py-4">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Ajouter au panier
                    </button>
                    <button onclick="shareProduct()" class="btn btn-outline px-6">
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>

                <div class="text-center text-sm text-gray-600">
                    <i class="fas fa-shield-alt text-green-600 mr-1"></i>
                    Commande sécurisée • Traitement manuel
                </div>
            </div>
        </div>

        <!-- Produits similaires -->
        <div class="mt-16">
            <h2 class="text-3xl font-bold mb-8 text-center" style="color: var(--primary-dark);" data-aos="fade-up">
                Produits similaires
            </h2>
            <div id="similarProducts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"></div>
        </div>
    `;

    // Charger produits similaires
    loadSimilarProducts(product);
}

function changeImage(src) {
    document.getElementById('mainImage').src = src;
}

function addToCart() {
    const slug = new URLSearchParams(window.location.search).get('slug');
    loadData().then(data => {
        const product = data.products.find(p => p.slug === slug);
        if (product) {
            Cart.add(product);
        }
    });
}

function shareProduct() {
    loadData().then(data => {
        const slug = new URLSearchParams(window.location.search).get('slug');
        const product = data.products.find(p => p.slug === slug);
        if (product) {
            shareProduct(product);
        }
    });
}

function loadSimilarProducts(currentProduct) {
    loadData().then(data => {
        // Trouver produits de même catégorie ou avec intentions similaires
        const similar = data.products.filter(p => 
            p.slug !== currentProduct.slug && 
            (p.category === currentProduct.category || 
             p.intentions.some(i => currentProduct.intentions.includes(i)))
        ).slice(0, 4);

        displayProducts(similar, 'similarProducts');
    });
}
</script>

<?php include 'includes/footer.php'; ?>
