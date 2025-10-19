<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harmon'Iza - Accueil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="manifest" href="manifest.json">
</head>
<body class="bg-gray-50 text-gray-800">
    <header class="bg-pink-100 p-4 text-center sticky top-0 z-50">
        <h1 class="text-3xl md:text-4xl font-playfair text-yellow-600 mb-2">Harmon'Iza</h1>
        <input id="search" type="text" placeholder="🔍 Rechercher produit, pierre ou intention..." class="w-full max-w-md p-2 rounded-lg border">
    </header>

    <section class="hero py-8 text-center">
        <img src="https://source.unsplash.com/random/1200x600/?jewelry,boutique,pastel" alt="Boutique Harmon'Iza" class="w-full rounded-lg mx-auto max-w-4xl" loading="lazy">
        <p class="text-2xl md:text-3xl font-playfair mt-4 text-yellow-600">Harmonisez votre énergie, sublimez votre style</p>
        <div class="mt-6 space-x-4">
            <a href="boutique.php" class="bg-pink-300 hover:bg-pink-400 text-white px-6 py-3 rounded-lg inline-block">🛒 Boutique</a>
            <a href="pierres.php" class="bg-yellow-300 hover:bg-yellow-400 text-gray-800 px-6 py-3 rounded-lg inline-block">✨ Découvrir les pierres</a>
        </div>
    </section>

    <section id="selection" class="py-8 px-4">
        <h2 class="text-2xl font-playfair text-center text-yellow-600 mb-6">Sélection du moment</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-6xl mx-auto"></div>
    </section>

    <button id="install-btn" class="fixed bottom-4 right-4 bg-pink-300 p-3 rounded-full shadow-lg hidden">
        <i class="fas fa-download"></i>
    </button>

    <script src="js/main.js"></script>
    <script src="js/cart.js"></script>
    <script>
        // Charger sélection
        fetch('data/products.json').then(r=>r.json()).then(products => {
            const grid = document.querySelector('#selection div');
            products.slice(0,8).forEach(p => {
                grid.innerHTML += `
                    <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition">
                        <img src="${p.images[0]}" loading="lazy" class="w-full h-32 object-cover rounded">
                        <h3 class="font-semibold mt-2">${p.name}</h3>
                        <p class="text-pink-600 font-bold">${p.price}€</p>
                        <button onclick="addToCart('${p.slug}')" class="w-full mt-2 bg-pink-300 text-white py-1 rounded">Ajouter</button>
                    </div>
                `;
            });
        });
    </script>
</body>
</html>
