<?php $slug = $_GET['slug'] ?? ''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produit - Harmon'Iza</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="manifest" href="manifest.json">
</head>
<body class="bg-gray-50">
    <header class="bg-pink-100 p-4">
        <a href="index.php" class="text-xl font-playfair text-yellow-600">Harmon'Iza</a>
        <a href="commande.php" class="float-right bg-pink-300 px-4 py-2 rounded">🛒 <span id="cart-count">0</span></a>
    </header>

    <section class="p-4 max-w-4xl mx-auto">
        <div id="product-detail" class="bg-white rounded-lg shadow p-6"></div>
    </section>

    <script src="js/main.js"></script>
    <script src="js/cart.js"></script>
    <script>
        fetch(`data/products.json`)
            .then(r=>r.json())
            .then(products => {
                const product = products.find(p => p.slug === '<?php echo $slug; ?>');
                if (product) {
                    document.getElementById('product-detail').innerHTML = `
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                ${product.images.map(img => `<img src="${img}" loading="lazy" class="w-full rounded">`).join('')}
                            </div>
                            <div>
                                <h1 class="text-3xl font-playfair text-yellow-600">${product.name}</h1>
                                <p class="text-2xl text-pink-600 font-bold mt-2">${product.price}€</p>
                                <p class="text-gray-600 mt-2">Pierre: ${product.stone}</p>
                                <p class="text-gray-600">Intention: ${product.intention}</p>
                                <p class="mt-4">${product.description}</p>
                                <button onclick="addToCart('${product.slug}')" class="w-full bg-pink-300 text-white py-3 mt-4 rounded-lg font-bold">Ajouter à la commande</button>
                                <a href="pierres.php?stone=${product.stone.toLowerCase().replace(' ', '-')}" class="block text-blue-600 mt-2">📖 Fiche pierre complète</a>
                                <button onclick="shareProduct('${product.name}', window.location.href)" class="w-full bg-yellow-300 text-gray-800 py-2 mt-2 rounded">Partager</button>
                            </div>
                        </div>
                    `;
                }
                updateCartCount();
            });
    </script>
</body>
</html>
