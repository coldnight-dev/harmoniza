# Structure complète du projet Harmon'Iza

## 📁 Arborescence des fichiers

```
/harmoniza/
│
├── 📄 index.php                    # Page d'accueil
├── 📄 boutique.php                 # Liste produits avec filtres
├── 📄 produit.php                  # Fiche produit détaillée
├── 📄 pierres.php                  # Guide des pierres
├── 📄 intentions.php               # Navigation par intentions
├── 📄 commande.php                 # Panier et formulaire commande
├── 📄 offline.html                 # Page hors ligne (PWA)
├── 📄 404.html                     # Page erreur 404
├── 📄 php.php                      # PHPInfo (à supprimer en prod)
├── 📄 .htaccess                    # Configuration Apache
├── 📄 manifest.json                # Manifest PWA
├── 📄 sw.js                        # Service Worker
├── 📄 README.md                    # Documentation principale
├── 📄 STRUCTURE_PROJET.md          # Ce fichier
│
├── 📂 includes/
│   ├── header.php                  # En-tête HTML commun
│   └── footer.php                  # Pied de page commun
│
├── 📂 css/
│   └── style.css                   # Styles personnalisés
│
├── 📂 js/
│   ├── main.js                     # Script principal
│   ├── cart.js                     # Gestion panier localStorage
│   └── pwa.js                      # Installation PWA
│
├── 📂 data/
│   ├── products.json               # Catalogue produits (16 items)
│   └── stones.json                 # Base données pierres (20 items)
│
├── 📂 api/
│   └── commande/
│       └── create.php              # Endpoint création commande
│
└── 📂 admin/
    ├── config.php                  # Configuration admin
    ├── login.php                   # Page connexion
    ├── logout.php                  # Déconnexion
    ├── index.php                   # Panel admin principal
    ├── view_order.php              # API affichage commande
    └── commandes/                  # Dossier stockage commandes
        └── commande_example.json   # Exemple de commande
```

## 📋 Checklist de déploiement

### Avant le déploiement

- [ ] Modifier les identifiants dans `admin/config.php`
- [ ] Vérifier les URLs dans tous les fichiers (BASE_URL)
- [ ] Tester le catalogue `data/products.json` avec jsonlint
- [ ] Vérifier `data/stones.json` est valide

### Après le déploiement

- [ ] Créer le dossier `admin/commandes/`
- [ ] Définir les permissions : `chmod 700 admin/commandes`
- [ ] Ajuster le propriétaire : `chown www-data:www-data admin/commandes`
- [ ] Tester l'accès : https://app.santementale.org/harmoniza/
- [ ] Tester l'API : POST /api/commande/create
- [ ] Vérifier le panel admin : https://app.santementale.org/harmoniza/admin/
- [ ] Tester l'installation PWA
- [ ] **SUPPRIMER php.php** en production

### Vérifications de sécurité

- [ ] HTTPS actif et forcé
- [ ] Mot de passe admin changé
- [ ] Dossier `admin/commandes/` non accessible directement
- [ ] Permissions fichiers correctes (600 pour commandes)
- [ ] php.php supprimé

## 🔧 Commandes utiles

### Création du dossier commandes
```bash
mkdir -p admin/commandes
chmod 700 admin/commandes
chown www-data:www-data admin/commandes
```

### Test de l'API
```bash
curl -X POST https://app.santementale.org/harmoniza/api/commande/create \
  -H "Content-Type: application/json" \
  -d '{
    "buyer": {
      "name": "Test User",
      "phone": "+33612345678",
      "notes": "Test"
    },
    "items": [{"slug": "bracelet-chance-abondance", "qty": 1}],
    "client_total": 10.00
  }'
```

### Vérifier les permissions
```bash
ls -la admin/commandes/
```

### Voir les dernières commandes
```bash
ls -lt admin/commandes/ | head -10
```

## 📊 Fonctionnalités implémentées

### Frontend
- ✅ PWA installable (manifest + service worker)
- ✅ Design responsive mobile-first
- ✅ Recherche avec autocomplétion (debounced)
- ✅ Filtres produits (catégorie, intention, tri)
- ✅ Panier localStorage
- ✅ Navigation par intentions
- ✅ Guide des pierres interactif
- ✅ Partage Web Share API
- ✅ Mode offline (pages en cache)
- ✅ Animations AOS
- ✅ Design pastel / rose poudré

### Backend
- ✅ API REST création commande (PHP 7.3)
- ✅ Validation côté serveur
- ✅ Recalcul des totaux serveur
- ✅ Stockage fichiers JSON atomique
- ✅ Panel admin protégé
- ✅ Gestion statuts commandes
- ✅ Aucune base de données requise

### Sécurité
- ✅ HTTPS forcé (.htaccess)
- ✅ Validation/échappement entrées
- ✅ Protection dossier commandes
- ✅ Session PHP admin
- ✅ Headers sécurité
- ✅ Pas de SQL injection (pas de BDD)

## 🎨 Technologies utilisées (CDN uniquement)

- **Tailwind CSS 3.4** - Framework CSS
- **Font Awesome 6.5.1** - Icônes
- **Google Fonts** - Poppins & Playfair Display
- **AOS 2.3.4** - Animations scroll
- **Vanilla JavaScript** - Pas de framework lourd
- **PHP 7.3.33** - Backend

## 📞 Points de contact API

### POST /api/commande/create
Crée une nouvelle commande

**Payload:**
```json
{
  "buyer": {
    "name": "string",
    "phone": "string",
    "notes": "string (optionnel)"
  },
  "items": [
    {"slug": "string", "qty": number}
  ],
  "client_total": number
}
```

**Réponse succès (200):**
```json
{
  "ok": true,
  "order_id": "HZ-YYYYMMDD-HHMMSS-XXXX",
  "message": "Commande enregistrée avec succès"
}
```

**Réponse erreur (400/500):**
```json
{
  "ok": false,
  "error": "Description de l'erreur"
}
```

## 🐛 Debugging

### Logs à vérifier
- Logs Apache : `/var/log/apache2/error.log`
- Logs PHP : selon configuration `php.ini`

### Erreurs courantes

**Commandes ne s'enregistrent pas**
- Vérifier permissions dossier `admin/commandes/`
- Vérifier que `data/products.json` est valide
- Vérifier les logs PHP

**PWA ne s'installe pas**
- Vérifier HTTPS actif
- Vérifier `manifest.json` accessible
- Vérifier `sw.js` sans erreur (console F12)

**API retourne 500**
- Vérifier logs PHP
- Vérifier `data/products.json` valide
- Vérifier permissions écriture

## 📝 Notes importantes

1. **Pas de gestion de stock** - Tout est confirmé manuellement
2. **Pas de paiement en ligne** - Coordination manuelle
3. **Usage temporaire** - Architecture simple pour liquidation
4. **Backup régulier** - Sauvegarder `/admin/commandes/`
5. **Support PHP 7.3** - Compatible ancien serveur
6. **Performance** - Lazy loading images, cache service worker
7. **Accessibilité** - Labels formulaires, contrastes, navigation clavier

## 🎯 Prochaines étapes après déploiement

1. Tester toutes les pages
2. Passer une commande test complète
3. Vérifier réception dans admin
4. Tester sur mobile (responsive)
5. Installer la PWA sur un appareil test
6. Partager le lien final

## 📧 Support

Pour toute question sur le déploiement ou l'utilisation :
- Vérifier d'abord le README.md
- Consulter les logs serveur
- Tester avec curl pour débugger l'API
- Vérifier la console navigateur (F12) pour le frontend

---

**Version:** 1.0  
**Date:** Octobre 2025  
**Compatibilité:** PHP 7.3.33+, Apache 2.4+  
**Navigateurs:** Tous navigateurs modernes + PWA
