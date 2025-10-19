<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intentions - Harmon'Iza</title>
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
    </header>

    <section class="p-4 max-w-2xl mx-auto">
        <h1 class="text-3xl font-playfair text-yellow-600 text-center mb-6">Choisissez votre Intention</h1>
        <select id="intention-select" class="w-full p-3 border rounded-lg text-lg mb-6">
            <option value="">Sélectionnez une intention</option>
            <option value="amour">💖 Amour & Relations</option>
            <option value="protection">🛡️ Protection</option>
            <option value="ancrage">🌍 Ancrage</option>
            <option value="abondance">💰 Abondance</option>
            <option value="serenite">🕊️ Sérénité</option>
            <option value="chance">🍀 Chance</option>
        </select>

        <div id="recommendations" class="space-y-4"></div>
    </section>

    <script src="js/main.js"></script>
    <script src="js/cart.js"></script>
    <script>
        document.getElementById('intention-select').onchange = (e) => {
            const intention = e.target.value;
            if (!intention) return document.getElementById('recommendations').innerHTML = '';

            Promise.all([
                fetch('data/products.json'), 
                fetch('data/stones.json')
            ]).then(([p,r]) => Promise.all([p.json(), r.json()])).then(([products, stones]) => {
                const recs = document.getElementById('recommendations');
                const prod = products.filter(p => p.intention === intention);
                const stn = stones.filter(s => s.associations.toLowerCase().includes(intention));

                recs.innerHTML = `
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-xl font-bold text-yellow-600">Produits recommandés</h2>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            ${prod.slice(0,4).map(p => `
                                <div onclick="addToCart('${p.slug}')" class="p-2 border rounded cursor-pointer">
                                    <img src="${p.images[0]}" class="w-full h-20 object-cover rounded">
                                    <p class="text-sm">${p.name} - ${p.price}€</p>
                                </div>
                            `).join('')}
                        </div>
                        ${prod.length > 4 ? `<button onclick="addAllToCart(${JSON.stringify(prod.map(p=>p.slug))})" class="w-full bg-pink-300 text-white py-2 mt-2 rounded">Ajouter tous (${prod.length})</button>` : ''}
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-xl font-bold text-yellow-600">Pierres recommandées</h2>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            ${stn.slice(0,4).map(s => `<div class="p-2"><img src="${s.image}" class="w-full h-20 object-cover rounded"><p class="text-sm">${s.name}</p></div>`).join('')}
                        </div>
                    </div>
                `;
            });
        };
    </script>
</body>
</html>
