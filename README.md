# Harmon'Iza - Progressive Web App

## 📋 Description

Harmon'Iza est une PWA de boutique en ligne pour la vente de bijoux et pierres énergétiques. Application temporaire pour liquidation, sans base de données, utilisant uniquement des fichiers JSON.

## 🚀 Installation & Déploiement (5 étapes)

### 1. Upload des fichiers
Transférez tous les fichiers à la racine du serveur : `https://app.santementale.org/harmoniza/`

### 2. Création du dossier commandes
```bash
mkdir -p admin/commandes
```

### 3. Configuration des permissions
```bash
# Dossier commandes accessible en écriture par le serveur web
chmod 700 admin/commandes

# Si besoin, ajuster le propriétaire (exemple avec www-data)
chown www-data:www-data admin/commandes
```

### 4. Configuration admin
Éditez `admin/config.php` et définissez vos identifiants :
```php
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'VotreMotDePasseSecurise');
```

### 5. Test de l'installation
- Accédez à : `https://app.santementale.org/harmoniza/`
- Testez l'admin : `https://app.santementale.org/harmoniza/admin/`
- Vérifiez PHPInfo : `https://app.santementale.org/harmoniza/php.php`

## 📁 Structure du projet

```
/harmoniza/
├── index.php               # Page d'accueil
├── boutique.php            # Liste des produits
├── produit.php             # Fiche produit détaillée
├── pierres.php             # Dictionnaire des pierres
├── intentions.php          # Guide par intention
├── commande.php            # Panier et formulaire
├── manifest.json           # Manifest PWA
├── sw.js                   # Service Worker
├── php.php                 # PHPInfo (à supprimer en prod)
│
├── css/
│   └── style.css           # Styles personnalisés
│
├── js/
│   ├── main.js             # Script principal
│   ├── cart.js             # Gestion panier
│   └── pwa.js              # Installation PWA
│
├── data/
│   ├── products.json       # Catalogue produits
│   └── stones.json         # Base de données pierres
│
├── api/
│   └── commande/
│       └── create.php      # Endpoint création commande
│
└── admin/
    ├── config.php          # Configuration admin
    ├── index.php           # Panel admin
    ├── login.php           # Authentification
    ├── logout.php          # Déconnexion
    └── commandes/          # Stockage commandes (JSON)
        └── commande_example.json
```

## 🔌 Endpoints API

### POST /api/commande/create

Crée une nouvelle commande.

**Headers:**
```
Content-Type: application/json
```

**Payload:**
```json
{
  "buyer": {
    "name": "Prénom Nom",
    "phone": "+33123456789",
    "notes": "Profil FB: @user ou email@example.com"
  },
  "items": [
    {
      "slug": "bracelet-chance-abondance",
      "qty": 1
    }
  ],
  "client_total": 10.00
}
```

**Réponse succès (200):**
```json
{
  "ok": true,
  "order_id": "HZ-20251018-214503-A3F2",
  "message": "Commande enregistrée avec succès"
}
```

**Réponse erreur (400):**
```json
{
  "ok": false,
  "error": "Erreur de validation ou différence de montant"
}
```

**Exemple curl:**
```bash
curl -X POST https://app.santementale.org/harmoniza/api/commande/create \
  -H "Content-Type: application/json" \
  -d '{
    "buyer": {
      "name": "Test User",
      "phone": "+33612345678",
      "notes": "Test commande"
    },
    "items": [{"slug": "bracelet-chance-abondance", "qty": 1}],
    "client_total": 10.00
  }'
```

## 📝 Gestion du catalogue

### Modifier products.json

Éditez `data/products.json` pour ajouter/modifier des produits :

```json
{
  "slug": "nouveau-produit",
  "name": "Nouveau Produit",
  "category": "bracelet",
  "price": 15.00,
  "images": [
    "https://via.placeholder.com/600x600/f8b4d9/ffffff?text=Produit"
  ],
  "stones": ["améthyste", "quartz rose"],
  "intentions": ["amour", "sérénité"],
  "description": "Description du produit...",
  "virtues": "Vertus énergétiques...",
  "featured": true
}
```

### Modifier stones.json

Éditez `data/stones.json` pour gérer les fiches pierres :

```json
{
  "slug": "amethyste",
  "name": "Améthyste",
  "image": "https://...",
  "origin": "Brésil, Uruguay",
  "intentions": ["sérénité", "intuition"],
  "virtues": "L'améthyste...",
  "care": {
    "purification": ["lune", "selenite"],
    "charging": ["lune"],
    "avoid": ["soleil prolongé", "eau salée"]
  }
}
```

## 🔒 Sécurité

- **HTTPS obligatoire** : vérifiez que le site est accessible uniquement en HTTPS
- **Admin protégé** : changez immédiatement les identifiants dans `config.php`
- **Permissions** : dossier commandes en 700, fichiers en 600
- **Validation** : toutes les entrées sont validées côté serveur
- **Pas de listing** : le dossier `/admin/commandes/` n'est pas accessible directement

## 🛠️ Technologies utilisées

Toutes les dépendances sont chargées via CDN :

- **Tailwind CSS 3.4** : framework CSS
- **Font Awesome 6.5** : icônes
- **Google Fonts** : Poppins & Playfair Display
- **AOS 2.3** : animations scroll
- **Vanilla JavaScript** : pas de framework lourd

## 📱 PWA (Progressive Web App)

- **Installable** : bouton d'installation apparaît automatiquement
- **Offline** : pages visitées consultables hors ligne
- **Cache intelligent** : stratégie cache-first pour les assets
- **Icônes** : 192x192 et 512x512 fournies

## 🐛 Dépannage

### Les commandes ne s'enregistrent pas
```bash
# Vérifier les permissions
ls -la admin/commandes/
# Doit afficher : drwx------ ... www-data www-data

# Tester l'écriture
touch admin/commandes/test.txt
# Si erreur : ajuster propriétaire et permissions
```

### Erreur 500 sur l'API
- Vérifier les logs PHP du serveur
- S'assurer que `data/products.json` est valide (testez avec jsonlint.com)
- Vérifier que PHP 7.3+ est actif

### PWA ne s'installe pas
- Vérifier HTTPS actif
- Inspecter la console navigateur (F12)
- Vérifier que `manifest.json` et `sw.js` sont accessibles

## 📞 Support

Pour toute question technique, vérifiez :
1. Les logs serveur
2. La console JavaScript (F12)
3. Les permissions fichiers/dossiers
4. La validité des fichiers JSON

## ⚠️ Notes importantes

- **Usage temporaire** : cette app est conçue pour une durée limitée
- **Pas de stock** : aucune gestion de stock implémentée
- **Validation manuelle** : toutes les commandes nécessitent validation humaine
- **Backup** : sauvegardez régulièrement le dossier `/admin/commandes/`

## 📄 Licence

Usage privé - Harmon'Iza © 2025
