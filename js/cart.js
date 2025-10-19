const CART_KEY = 'harmoniza-cart';

function getCart() {
    return JSON.parse(localStorage.getItem(CART_KEY)) || [];
}

function addToCart(slug, qty = 1) {
    let cart = getCart();
    const item = cart.find(i => i.slug === slug);
    if (item) item.qty += qty;
    else cart.push({ slug, qty });
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateCartCount();
    updateCartDisplay();
    alert('Ajouté au panier !');
}

function addAllToCart(slugs) {
    slugs.forEach(slug => addToCart(slug));
}

function updateCartCount() {
    const count = getCart().reduce((sum, i) => sum + i.qty, 0);
    document.querySelectorAll('#cart-count').forEach(el => el.textContent = count);
}

function updateCartDisplay() {
    const cart = getCart();
    const items = document.getElementById('cart-items');
    const totalEl = document.getElementById('total');
    
    if (cart.length === 0) {
        items.innerHTML = '<p class="text-center text-gray-500">Panier vide</p>';
        totalEl.textContent = 'Total: 0€';
        return;
    }

    fetch('data/products.json').then(r=>r.json()).then(products => {
        items.innerHTML = cart.map(item => {
            const p = products.find(pr => pr.slug === item.slug);
            return p ? `
                <div class="flex justify-between items-center py-2 border-b">
                    <span>${p.name}</span>
                    <div class="text-right">
                        <span>${item.qty} × ${p.price}€</span><br>
                        <small>${(item.qty * p.price).toFixed(2)}€</small>
                    </div>
                </div>
            ` : '';
        }).join('');
        
        const total = cart.reduce((sum, item) => {
            const p = products.find(pr => pr.slug === item.slug);
            return sum + (p ? p.price * item.qty : 0);
        }, 0);
        totalEl.textContent = `Total: ${total.toFixed(2)}€`;
    });
}

function submitOrder() {
    const cart = getCart();
    if (cart.length === 0) return alert('Panier vide');

    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const notes = document.getElementById('notes').value;

    if (!name) return alert('Nom requis');
    if (phone.length < 7 || phone.length > 15) return alert('Téléphone invalide');

    const payload = { 
        buyer: { name, phone, notes }, 
        items: cart, 
        client_total: parseFloat(document.getElementById('total').textContent.replace('Total: ', '')) 
    };

    if (!navigator.onLine) return alert('Vous êtes hors-ligne — réessayez en ligne');

    fetch('/api/commande/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            localStorage.removeItem(CART_KEY);
            alert(`✅ Commande enregistrée !\nID: ${data.order_id}`);
            window.location.href = 'index.php';
        } else {
            alert(`❌ ${data.error}`);
        }
    })
    .catch(() => alert('Vous êtes hors-ligne — réessayez en ligne'));
}

// Charger au démarrage
if (document.getElementById('cart-items')) updateCartDisplay();
