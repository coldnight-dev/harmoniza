<?php
/**
 * Harmon'Iza - Panier et commande
 */
$pageTitle = "Mon Panier - Harmon'Iza";
$pageDescription = "Finalisez votre commande";
include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold mb-8 text-center" style="font-family: 'Playfair Display', serif; color: var(--primary-dark);" data-aos="fade-up">
        <i class="fas fa-shopping-cart mr-3"></i>Mon Panier
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Liste des articles -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-right">
                <h2 class="text-2xl font-bold mb-6">Articles</h2>
                <div id="cartItems">
                    <!-- Chargé dynamiquement -->
                </div>
                <div id="emptyCart" class="hidden text-center py-12">
                    <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Votre panier est vide</h3>
                    <p class="text-gray-600 mb-6">Découvrez notre sélection de bijoux et pierres</p>
                    <a href="/harmoniza/boutique.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag mr-2"></i>Voir la boutique
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulaire de commande -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24" data-aos="fade-left">
                <h2 class="text-2xl font-bold mb-6">Finaliser la commande</h2>

                <!-- Résumé -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Sous-total</span>
                        <span id="subtotalDisplay" class="font-semibold">0,00 €</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold border-t pt-2">
                        <span>Total</span>
                        <span id="totalDisplay" class="text-pink-600">0,00 €</span>
                    </div>
                </div>

                <!-- Formulaire -->
                <form id="orderForm" class="space-y-4">
                    <div class="form-group">
                        <label for="buyerName">
                            <i class="fas fa-user mr-1"></i>Nom complet *
                        </label>
                        <input type="text" id="buyerName" name="name" required
                            placeholder="Prénom Nom"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-pink-500 focus:outline-none">
                    </div>

                    <div class="form-group">
                        <label for="buyerPhone">
                            <i class="fas fa-phone mr-1"></i>Téléphone *
                        </label>
                        <input type="tel" id="buyerPhone" name="phone" required
                            placeholder="+33 6 12 34 56 78"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-pink-500 focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Format international accepté</p>
                    </div>

                    <div class="form-group">
                        <label for="buyerNotes">
                            <i class="fas fa-comment mr-1"></i>Informations complémentaires
                        </label>
                        <textarea id="buyerNotes" name="notes" rows="4"
                            placeholder="Profil Facebook, email, adresse de livraison ou toute autre information utile..."
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-pink-500 focus:outline-none resize-none"></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Indiquez comment vous contacter (Facebook, email) et vos préférences de livraison
                        </p>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 text-sm">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                            <div class="text-gray-700">
                                <strong>Informations importantes :</strong>
                                <ul class="list-disc ml-4 mt-2 space-y-1">
                                    <li>Aucun paiement en ligne requis</li>
                                    <li>Vous serez contacté pour confirmation</li>
                                    <li>Disponibilité vérifiée après commande</li>
                                    <li>Modalités de livraison discutées ensemble</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 text-sm">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-amber-600 mt-1 mr-3"></i>
                            <div class="text-gray-700">
                                Vos données sont traitées de manière sécurisée et ne seront utilisées que pour le traitement de votre commande.
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-primary w-full text-lg py-4">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Envoyer ma commande
                    </button>
                </form>

                <!-- Messages -->
                <div id="orderError" class="alert alert-error mt-4 hidden"></div>
                <div id="orderSuccess" class="alert alert-success mt-4 hidden"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Charger et afficher le panier
function loadCart() {
    const items = Cart.get();
    const container = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');

    if (items.length === 0) {
        container.classList.add('hidden');
        emptyCart.classList.remove('hidden');
        updateTotals(0);
        return;
    }

    container.classList.remove('hidden');
    emptyCart.classList.add('hidden');

    container.innerHTML = items.map(item => `
        <div class="cart-item flex gap-4 items-center py-4 border-b" data-slug="${item.slug}">
            <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded-lg">
            <div class="flex-1">
                <h3 class="font-semibold text-lg">${item.name}</h3>
                <p class="text-gray-600">${item.price ? item.price.toFixed(2) + ' €' : 'Prix non défini'}</p>
            </div>
            <div class="cart-item-actions flex items-center gap-3">
                <button onclick="updateItemQty('${item.slug}', ${item.qty - 1})" class="qty-btn">
                    <i class="fas fa-minus"></i>
                </button>
                <span class="font-bold text-lg w-8 text-center">${item.qty}</span>
                <button onclick="updateItemQty('${item.slug}', ${item.qty + 1})" class="qty-btn">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="font-bold text-lg w-24 text-right">
                ${item.price ? (item.price * item.qty).toFixed(2) + ' €' : '-'}
            </div>
            <button onclick="removeItem('${item.slug}')" class="text-red-500 hover:text-red-700 ml-2">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `).join('');

    updateTotals(Cart.getTotal());
}

function updateItemQty(slug, qty) {
    Cart.updateQty(slug, qty);
    loadCart();
}

function removeItem(slug) {
    if (confirm('Supprimer cet article du panier ?')) {
        Cart.remove(slug);
        loadCart();
    }
}

function updateTotals(total) {
    const formatted = total.toFixed(2).replace('.', ',') + ' €';
    document.getElementById('subtotalDisplay').textContent = formatted;
    document.getElementById('totalDisplay').textContent = formatted;
}

// Soumettre la commande
document.getElementById('orderForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const items = Cart.get();
    if (items.length === 0) {
        showError('Votre panier est vide');
        return;
    }

    // Vérifier la connexion
    if (!isOnline()) {
        showError('Vous êtes hors ligne. Veuillez vous reconnecter à Internet pour passer commande.');
        return;
    }

    const name = document.getElementById('buyerName').value.trim();
    const phone = document.getElementById('buyerPhone').value.trim();
    const notes = document.getElementById('buyerNotes').value.trim();

    // Validation basique
    if (!name || name.length < 2) {
        showError('Veuillez entrer un nom valide');
        return;
    }

    if (!phone || phone.length < 7) {
        showError('Veuillez entrer un numéro de téléphone valide');
        return;
    }

    // Préparer le payload
    const order = Cart.prepareOrder({
        name: name,
        phone: phone,
        notes: notes
    });

    // Désactiver le bouton
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';

    try {
        // Envoyer la commande
        const response = await fetch('/harmoniza/api/commande/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(order)
        });

        const result = await response.json();

        if (response.ok && result.ok) {
            // Succès
            Cart.clear();
            showSuccess(`Commande enregistrée avec succès !<br><strong>Numéro : ${result.order_id}</strong><br><br>Vous serez contacté prochainement pour confirmer la disponibilité et organiser la livraison.`);
            
            // Cacher le formulaire et vider le panier
            document.getElementById('orderForm').classList.add('hidden');
            loadCart();

            // Scroll vers le message
            document.getElementById('orderSuccess').scrollIntoView({ behavior: 'smooth' });
        } else {
            showError(result.error || 'Une erreur est survenue lors de l\'enregistrement de votre commande');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showError('Impossible de contacter le serveur. Vérifiez votre connexion Internet et réessayez.');
    } finally {
        // Réactiver le bouton
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Envoyer ma commande';
    }
});

function showError(message) {
    const errorDiv = document.getElementById('orderError');
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${message}`;
    errorDiv.classList.remove('hidden');
    document.getElementById('orderSuccess').classList.add('hidden');
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function showSuccess(message) {
    const successDiv = document.getElementById('orderSuccess');
    successDiv.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
    successDiv.classList.remove('hidden');
    document.getElementById('orderError').classList.add('hidden');
}

// Charger le panier au chargement
document.addEventListener('DOMContentLoaded', loadCart);
</script>

<?php include 'includes/footer.php'; ?>
