<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pierres - Harmon'Iza</title>
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

    <section class="p-4">
        <h1 class="text-3xl font-playfair text-yellow-600 text-center mb-6">Découvrir les Pierres</h1>
        <input id="stone-search" type="text" placeholder="Rechercher une pierre..." class="w-full max-w-md mx-auto p-2 border rounded mb-6 block">
        
        <div id="stones-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </section>

    <script src="js/main.js"></script>
    <script>
        fetch('data/stones.json').then(r=>r.json()).then(stones => {
            const grid = document.getElementById('stones-grid');
            stones.forEach(stone => {
                grid.innerHTML += `
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <img src="${stone.image}" loading="lazy" class="w-full h-48 object-cover rounded">
                        <h3 class="text-xl font-semibold mt-2">${stone.name}</h3>
                        <p class="text-sm text-gray-600">Origine: ${stone.origin}</p>
                        <p class="mt-2">${stone.virtues}</p>
                        <details class="mt-2 text-sm">
                            <summary class="cursor-pointer text-blue-600">Précautions & Purification</summary>
                            <p class="ml-2 mt-1">⚠️ ${stone.precautions}</p>
                            <p class="ml-2">🔮 ${stone.purification}</p>
                        </details>
                        <a href="boutique.php?intention=${stone.associations.split(' ')[0].toLowerCase()}" class="block mt-2 text-blue-600">Produits associés</a>
                    </div>
                `;
            });

            document.getElementById('stone-search').oninput = (e) => {
                const q = e.target.value.toLowerCase();
                Array.from(grid.children).forEach(el => {
                    el.style.display = el.textContent.toLowerCase().includes(q) ? 'block' : 'none';
                });
            };
        });
    </script>
</body>
</html>
