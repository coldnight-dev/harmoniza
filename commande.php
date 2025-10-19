<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande - Harmon'Iza</title>
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
        <h1 class="text-3xl font-playfair text-yellow-600 text-center mb-6">Ma Commande</h1>
        
        <div id="cart-items" class="bg-white p-4 rounded-lg shadow mb-6"></div>
        <p class="text-xl font-bold text-right text-pink-600" id="total">Total: 0€</p>

        <form class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Mes coordonnées</h2>
            <input id="name" type="text" placeholder="Nom complet *" class="w-full p-3 border rounded mb-3" required>
            <input id="phone" type="tel" placeholder="Téléphone *" class="w-full p-3 border rounded mb-3" required>
            <textarea id="notes" placeholder="Coordonnées supplémentaires (profil Facebook / e-mail / adresse) ou infos livraison" class="w-full p-3 border rounded mb-3"></textarea>
            <p class="text-sm text-gray-600 mb-4">RGPD : Vos données sont utilisées uniquement pour traiter cette commande temporaire.</p>
            <button type="button" onclick="submitOrder()" class="w-full bg-pink-300 text-white py-3 rounded-lg font-bold text-lg">✨ ENVOYER MA COMMANDE</button>
        </form>
    </section>

    <script src="js/main.js"></script>
    <script src="js/cart.js"></script>
    <script>
        updateCartDisplay();
        updateCartCount();
    </script>
</body>
</html>
