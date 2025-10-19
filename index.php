<?php
/**
 * Harmon'Iza - Page d'accueil (VERSION CORRIGÉE)
 */
$pageTitle = "Harmon'Iza - Bijoux & Pierres Énergétiques";
$pageDescription = "Harmonisez votre énergie, sublimez votre style";
include 'includes/header.php';
?>

<section class="hero" style="background-image: url('https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=1600&h=900&fit=crop');">
    <div class="hero-content" data-aos="fade-up">
        <h1>Harmon'Iza</h1>
        <p>Harmonisez votre énergie, sublimez votre style</p>
        <div class="flex gap-4 justify-center flex-wrap">
            <a href="boutique.php" class="btn btn-primary">
                <i class="fas fa-shopping-bag mr-2"></i>Découvrir la boutique
            </a>
            <a href="pierres.php" class="btn btn-secondary">
                <i class="fas fa-gem mr-2"></i>Les pierres
            </a>
        </div>
    </div>
</section>

<div class="container mx-auto px-4 py-12">
    <!-- Barre de recherche -->
    <div class="search-bar" data-aos="fade-up">
        <i class="fas fa-search"></i>
        <input type="text" 
               id="globalSearch" 
               placeholder="Rechercher un produit, une pierre, une intention..."
               onkeyup="handleSearch(this.value)"
               autocomplete="off">
        <div id="searchSuggestions" class="search-suggestions" style="display: none;"></div>
    </div>

    <!-- Présentation -->
    <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
        <h2 class="text-4xl font-bold mb-6" style="font-family: 'Playfair Display', serif; color: var(--primary-dark);">
            Bienvenue chez Harmon'Iza
        </h2>
        <p class="text-lg text-gray-700 leading-relaxed">
            Découvrez notre collection unique de bijoux et pierres naturelles, sélectionnés avec soin 
            pour leurs vertus énergétiques. Chaque création est pensée pour vous accompagner dans votre 
            quotidien et harmoniser vos énergies.
        </p>
    </div>

    <!-- Sélection du moment -->
    <div class="mb-16">
        <div class="flex justify-between items-center mb-8" data-aos="fade-up">
            <h2 class="text-3xl font-bold" style="color: var(--primary-dark);">
                <i class="fas fa-star text-yellow-500 mr-2"></i>Sélection du moment
            </h2>
            <a href="boutique.php" class="text-pink-600 hover:underline font-semibold">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div id="featuredProducts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"></div>
    </div>

    <!-- Intentions (VERSION CORRIGÉE) -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-16" data-aos="fade-up">
        <h2 class="text-3xl font-bold mb-6 text-center" style="color: var(--primary-dark);">
            Trouvez votre intention
        </h2>
        <p class="text-center text-gray-600 mb-8">
            Laissez-vous guider par vos besoins du moment
        </p>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
            <!-- Amour -->
            <a href="intentions.php?intention=amour" 
               class="flex flex-col items-center p-6 rounded-xl hover:shadow-lg transition bg-pink-50 hover:bg-pink-100 border-2 border-pink-200">
                <i class="fas fa-heart text-4xl mb-3 text-pink-600"></i>
                <span class="text-sm font-semibold text-center text-gray-700">Amour</span>
            </a>
            
            <!-- Protection -->
            <a href="intentions.php?intention=protection" 
               class="flex flex-col items-center p-6 rounded-xl hover:shadow-lg transition bg-purple-50 hover:bg-purple-100 border-2 border-purple-200">
                <i class="fas fa-shield-alt text-4xl mb-3 text-purple-600"></i>
                <span class="text-sm font-semibold text-center text-gray-700">Protection</span>
            </a>
            
            <!-- Ancrage -->
            <a href="intentions.php?intention=ancrage" 
               class="flex flex-col items-center p-6 rounded-xl hover:shadow-lg transition bg-green-50 hover:bg-green-100 border-2 border-green-200">
                <i class="fas fa-tree text-4xl mb-3 text-green-600"></i>
                <span class="text-sm font-semibold text-center text-gray-700">Ancrage</span>
            </a>
            
            <!-- Abondance -->
            <a href="intentions.php?intention=abondance" 
               class="flex flex-col items-center p-6 rounded-xl hover:shadow-lg transition bg-yellow-50 hover:bg-yellow-100 border-2 border-yellow-200">
                <i class="fas fa-coins text-4xl mb-3 text-yellow-600"></i>
                <span class="text-sm font-semibold text-center text-gray-700">Abondance</span>
            </a>
            
            <!-- Sérénité -->
            <a href="intentions.php?intention=serenite" 
               class="flex flex-col items-center p-6 rounded-xl hover:shadow-lg transition bg-blue-50 hover:bg-blue-100 border-2 border-blue-200">
                <i class="fas fa-spa text-4xl mb-3 text-blue-600"></i>
                <span class="text-sm font-semibold text-center text-gray-700">Sérénité</span>
            </a>
            
            <!-- Chance -->
            <a href="intentions.php?intention=chance" 
               class="flex flex-col items-center p-6 rounded-xl hover:shadow-lg transition bg-emerald-50 hover:bg-emerald-100 border-2 border-emerald-200">
                <i class="fas fa-clover text-4xl mb-3 text-emerald-600"></i>
                <span class="text-sm font-semibold text-center text-gray-700">Chance</span>
            </a>
            
            <!-- Intuition -->
            <a href="intentions.php?intention=intuition" 
               class="flex flex-col items-center p-6 rounded-xl hover:shadow-lg transition bg-indigo-50 hover:bg-indigo-100 border-2 border-indigo-200">
                <i class="fas fa-eye text-4xl mb-3 text-indigo-600"></i>
                <span class="text-sm font-semibold text-center text-gray-700">Intuition</span>
            </a>
        </div>
        <div class="text-center mt-6">
            <a href="intentions.php" class="btn btn-outline">
                Explorer toutes les intentions
            </a>
        </div>
    </div>

    <!-- Découvrir les pierres -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-8 md:p-12 text-center" data-aos="fade-up">
        <i class="fas fa-gem text-6xl text-purple-400 mb-4"></i>
        <h2 class="text-3xl font-bold mb-4" style="color: var(--primary-dark);">
            Découvrez les vertus des pierres
        </h2>
        <p class="text-lg text-gray-700 mb-6 max-w-2xl mx-auto">
            Explorez notre dictionnaire interactif des pierres naturelles et leurs propriétés énergétiques. 
            Apprenez à les utiliser, les purifier et les associer.
        </p>
        <a href="pierres.php" class="btn btn-primary btn-lg">
            <i class="fas fa-book-open mr-2"></i>Accéder au guide des pierres
        </a>
    </div>
</div>

<!-- Bouton installation PWA -->
<button id="installPWA" class="install-pwa" onclick="installPWA()">
    <i class="fas fa-download"></i>
    Installer l'application
</button>

<script>
// Charger et afficher les produits en vedette
loadData().then(data => {
    const featured = data.products.filter(p => p.featured);
    displayProducts(featured, 'featuredProducts');
});
</script>

<?php include 'includes/footer.php'; ?>
