<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique - Harmon'Iza</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="manifest" href="manifest.json">
</head>
<body class="bg-gray-50">
    <header class="bg-pink-100 p-4 text-center sticky top-0 z-50">
        <a href="index.php" class="text-2xl font-playfair text-yellow-600">Harmon'Iza</a>
        <a href="commande.php" class="float-right bg-pink-300 px-4 py-2 rounded">🛒 <span id="cart-count">0</span></a>
    </header>

    <section class="p-4">
        <h1 class="text-3xl font-playfair text-yellow-600 text-center mb-6">Boutique</h1>
        
        <div class="filters bg-white p-4 rounded-lg shadow mb-6">
            <select id="intention" class="mr-2 p-2 border rounded">
                <option value="">Toutes intentions</option>
                <option value="amour">Amour</option>
                <option value="protection">Protection</option>
                <option value="ancrage">Ancrage</option>
                <option value="abondance">Abondance</option>
                <option value="serenite">Sérénité</option>
                <option value="chance">Chance</option>
            </select>
            <select id="sort" class="p-2 border rounded" onchange="sortProducts()">
                <option value="new">Nouveautés</option>
                <option value="price-asc">Prix croissant</option>
                <option value="price-desc">Prix décroissant</option>
            </select>
        </div>

        <div id="products-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
    </section>

    <script src="js/main.js"></script>
    <script src="js/cart.js"></script>
    <script>
        let products = [];
        fetch('data/products.json').then(r=>r.json()).then(data => {
            products = data;
            renderProducts();
            updateCartCount();
        });

        function renderProducts() {
            const grid = document.getElementById('products-grid');
            let filtered = products;
            
            const intention = document.getElementById('intention').value;
            if (intention) filtered = filtered.filter(p => p.intention === intention);
            
            const sort = document.getElementById('sort').value;
            filtered.sort((a,b) => sort === 'price-asc' ? a.price - b.price : 
                                sort === 'price-desc' ? b.price - a.price : 0);

            grid.innerHTML = filtered.map(p => `
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg">
                    <img src="${p.images[0]}" loading="lazy" class="w-full h-32 object-cover rounded">
                    <h3 class="font-semibold mt-2">${p.name}</h3>
                    <p class="text-pink-600 font-bold">${p.price}€</p>
                    <p class="text-sm">${p.stone}</p>
                    <button onclick="addToCart('${p.slug}')" class="w-full mt-2 bg-pink-300 text-white py-1 rounded">Ajouter</button>
                    <a href="produit.php?slug=${p.slug}" class="block text-xs text-blue-600 mt-1">Voir</a>
                </div>
            `).join('');
        }

        document.getElementById('intention').onchange = renderProducts;
    </script>
</body>
</html>
