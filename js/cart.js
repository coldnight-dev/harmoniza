/**
 * Harmon'Iza - Gestion du panier (localStorage)
 */

const Cart = {
  STORAGE_KEY: 'harmoniza_cart',

  // Récupérer le panier
  get() {
    try {
      const data = localStorage.getItem(this.STORAGE_KEY);
      return data ? JSON.parse(data) : [];
    } catch (e) {
      console.error('Erreur lecture panier:', e);
      return [];
    }
  },

  // Sauvegarder le panier
  save(items) {
    try {
      localStorage.setItem(this.STORAGE_KEY, JSON.stringify(items));
      this.updateBadge();
    } catch (e) {
      console.error('Erreur sauvegarde panier:', e);
    }
  },

  // Ajouter un article
  add(product, qty = 1) {
    const items = this.get();
    const existing = items.find(item => item.slug === product.slug);

    if (existing) {
      existing.qty += qty;
    } else {
      items.push({
        slug: product.slug,
        name: product.name,
        price: product.price || 0,
        image: product.images[0],
        qty: qty
      });
    }

    this.save(items);
    this.showNotification(`${product.name} ajouté au panier`);
  },

  // Mettre à jour la quantité
  updateQty(slug, qty) {
    const items = this.get();
    const item = items.find(i => i.slug === slug);

    if (item) {
      if (qty <= 0) {
        this.remove(slug);
      } else {
        item.qty = qty;
        this.save(items);
      }
    }
  },

  // Supprimer un article
  remove(slug) {
    const items = this.get();
    const filtered = items.filter(item => item.slug !== slug);
    this.save(filtered);
  },

  // Vider le panier
  clear() {
    localStorage.removeItem(this.STORAGE_KEY);
    this.updateBadge();
  },

  // Calculer le total
  getTotal() {
    const items = this.get();
    return items.reduce((sum, item) => sum + (item.price * item.qty), 0);
  },

  // Compter les articles
  count() {
    const items = this.get();
    return items.reduce((sum, item) => sum + item.qty, 0);
  },

  // Mettre à jour le badge
  updateBadge() {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
      const count = this.count();
      badge.textContent = count;
      badge.style.display = count > 0 ? 'flex' : 'none';
    }
  },

  // Notification
  showNotification(message) {
    // Créer notification toast
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-up';
    toast.innerHTML = `
      <div class="flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
      </div>
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(20px)';
      setTimeout(() => toast.remove(), 300);
    }, 2000);
  },

  // Préparer le payload pour l'API
  prepareOrder(buyer) {
    const items = this.get();
    return {
      buyer: buyer,
      items: items.map(item => ({
        slug: item.slug,
        qty: item.qty
      })),
      client_total: this.getTotal()
    };
  }
};

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', () => {
  Cart.updateBadge();
});
