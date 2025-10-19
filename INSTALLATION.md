# 🚀 Guide d'installation Harmon'Iza

## Installation rapide (5 minutes)

### Étape 1 : Upload des fichiers (2 min)

Transférez tous les fichiers du projet vers : `https://app.santementale.org/harmoniza/`

**Via FTP/SFTP :**
```bash
# Exemple avec scp
scp -r harmoniza/* user@app.santementale.org:/var/www/html/harmoniza/
```

**Via panel hébergement :**
- Uploader le ZIP complet
- Extraire dans `/harmoniza/`

### Étape 2 : Créer le dossier commandes (1 min)

**Via SSH :**
```bash
cd /var/www/html/harmoniza/admin
mkdir -p commandes
chmod 700 commandes
chown www-data:www-data commandes  # Adapter selon votre serveur
```

**Via FTP :**
- Créer le dossier `admin/commandes/`
- Définir permissions : `drwx------` (700)

### Étape 3 : Configuration admin (1 min)

Éditer `admin/config.php` :

```php
define('ADMIN_USER', 'votre_username');  // CHANGEZ CECI
define('ADMIN_PASS', 'VotreMotDePasse123!');  // CHANGEZ CECI
```

### Étape 4 : Vérifications (1 min)

**Test des URLs :**
- ✅ https://app.santementale.org/harmoniza/ → Page d'accueil
- ✅ https://app.santementale.org/harmoniza/boutique.php → Boutique
- ✅ https://app.santementale.org/harmoniza/admin/ → Login admin

**Test de l'API :**
```bash
curl -X POST https://app.santementale.org/harmoniza/api/commande/create \
  -H "Content-Type: application/json" \
  -d '{
    "buyer": {"name": "Test", "phone": "+33612345678", "notes": "Test"},
    "items": [{"slug": "bracelet-chance-abondance", "qty": 1}],
    "client_total": 10.00
  }'
```

Réponse attendue :
```json
{"ok":true,"order_id":"HZ-20251018-...","message":"Commande enregistrée avec succès"}
```

### Étape 5 : Finalisation (30 sec)

- [ ] Supprimer `php.php` (PHPInfo)
- [ ] Tester installation PWA (mobile)
- [ ] Vérifier admin → voir les commandes

**🎉 C'est terminé !**

---

## 🔧 Configuration avancée

### Personnaliser le catalogue

**Modifier les produits :**
Éditer `data/products.json` - Respecter la structure JSON

**Modifier les pierres :**
Éditer `data/stones.json` - Ajouter vertus, soins, etc.

**Valider le JSON :**
```bash
# Avec jq
cat data/products.json | jq empty && echo "✅ Valid JSON"

# Ou en ligne : https://jsonlint.com/
```

### Ajuster les permissions

**Configuration recommandée :**
```bash
# Dossiers
chmod 755 /harmoniza/
chmod 755 /harmoniza/admin/
chmod 700 /harmoniza/admin/commandes/

# Fichiers PHP
chmod 644 *.php

# Fichiers JSON sensibles
chmod 600 admin/config.php

# Fichiers de commande
chmod 600 admin/commandes/*.json
```

### Configuration Apache (.htaccess)

Le fichier `.htaccess` fourni inclut :
- ✅ Redirection HTTPS forcée
- ✅ Protection dossier commandes
- ✅ Headers de sécurité
- ✅ Cache et compression
- ✅ Blocage listing répertoires

**Si vous utilisez Nginx**, convertir les règles :
```nginx
# Exemple règles Nginx
location /harmoniza/admin/commandes/ {
    deny all;
}

location ~ ^/harmoniza/.*\.json$ {
    if ($request_filename !~* "products|stones") {
        deny all;
    }
}
```

### Logs et monitoring

**Activer les logs PHP :**
```php
// En développement uniquement
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

**Surveiller les commandes :**
```bash
# Voir les nouvelles commandes
watch -n 5 'ls -lt admin/commandes/ | head -5'

# Compter les commandes
ls -1 admin/commandes/*.json | wc -l
```

---

## 🐛 Résolution de problèmes

### Erreur : "Permission denied" lors création commande

**Diagnostic :**
```bash
ls -la admin/commandes/
# Doit afficher : drwx------ ... www-data www-data
```

**Solution :**
```bash
chmod 700 admin/commandes/
chown www-data:www-data admin/commandes/
# ou selon votre serveur : chown apache:apache
```

### Erreur : API retourne 500

**Vérifier les logs :**
```bash
tail -f /var/log/apache2/error.log
# ou
tail -f /var/log/php-fpm/error.log
```

**Causes fréquentes :**
1. `products.json` invalide → valider avec jsonlint
2. Dossier commandes introuvable → vérifier le chemin
3. Permissions insuffisantes → voir ci-dessus
4. PHP < 7.3 → mettre à jour

### Erreur : PWA ne s'installe pas

**Checklist :**
- [ ] HTTPS actif (obligatoire)
- [ ] `manifest.json` accessible
- [ ] `sw.js` sans erreur JavaScript
- [ ] Testé dans Chrome/Edge/Safari

**Débug :**
1. Ouvrir DevTools (F12)
2. Onglet "Application"
3. Vérifier "Manifest" et "Service Workers"
4. Voir la console pour erreurs

### Erreur : "Différence de montant"

**Cause :** Décalage entre total client et serveur

**Solution :**
1. Vérifier prix dans `products.json`
2. Vider cache navigateur
3. Recharger la page boutique
4. Recréer le panier

### Erreur : Page blanche / 404

**Vérifier :**
```bash
# Fichiers présents ?
ls -la /var/www/html/harmoniza/index.php

# Permissions lecture ?
chmod 644 /var/www/html/harmoniza/*.php

# Logs Apache
tail -20 /var/log/apache2/error.log
```

---

## 📱 Test sur mobile

### Installation PWA

**Android (Chrome) :**
1. Ouvrir le site
2. Menu → "Installer l'application"
3. Accepter

**iOS (Safari) :**
1. Ouvrir le site
2. Bouton partage
3. "Sur l'écran d'accueil"

### Test responsive

**Tailles à tester :**
- 320px (petit mobile)
- 375px (iPhone SE)
- 768px (tablette portrait)
- 1024px (tablette paysage)
- 1920px (desktop)

**DevTools :**
- F12 → Toggle device toolbar
- Tester rotation écran
- Tester navigation tactile

---

## 🔐 Sécurité post-installation

### Checklist sécurité

- [ ] Mot de passe admin changé (fort : 12+ caractères)
- [ ] php.php supprimé
- [ ] HTTPS forcé et certificat valide
- [ ] Dossier commandes inaccessible (tester l'URL directe)
- [ ] Headers sécurité actifs (vérifier avec securityheaders.com)
- [ ] Backup configuré (copie régulière de `/admin/commandes/`)

### Backup automatique

**Via cron (recommandé) :**
```bash
# Ajouter au crontab
0 2 * * * tar -czf /backup/harmoniza_$(date +\%Y\%m\%d).tar.gz /var/www/html/harmoniza/admin/commandes/
```

**Via script manuel :**
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf "backup_harmoniza_${DATE}.tar.gz" admin/commandes/
```

---

## 📊 Monitoring et statistiques

### Commandes par jour
```bash
# Compter commandes aujourd'hui
ls admin/commandes/commande_$(date +%Y%m%d)*.json 2>/dev/null | wc -l
```

### Statut des commandes
```bash
# Compter par statut
grep -r '"status"' admin/commandes/*.json | cut -d'"' -f4 | sort | uniq -c
```

### Produits populaires
```bash
# Top 5 produits
grep -rh '"slug"' admin/commandes/*.json | grep -v order_id | cut -d'"' -f4 | sort | uniq -c | sort -rn | head -5
```

---

## 🎓 Utilisation quotidienne

### Workflow traitement commande

1. **Recevoir notification** (configurer alertes email si besoin)
2. **Se connecter** à `/admin/`
3. **Voir détails** commande
4. **Contacter client** (téléphone/Facebook)
5. **Vérifier disponibilité** produits
6. **Confirmer livraison**
7. **Marquer "traitée"** dans l'admin

### Modifier le catalogue

**Ajouter un produit :**
1. Éditer `data/products.json`
2. Ajouter l'objet JSON (respecter la structure)
3. Sauvegarder
4. Recharger la page boutique (Ctrl+F5)

**Retirer un produit :**
1. Éditer `data/products.json`
2. Supprimer l'objet
3. Sauvegarder

**Modifier prix :**
1. Trouver le produit dans `products.json`
2. Changer valeur `"price": X.XX`
3. Sauvegarder

---

## 🆘 Support et aide

### Ressources

- **Documentation PHP 7.3 :** https://www.php.net/manual/fr/
- **JSON Validator :** https://jsonlint.com/
- **PWA Testing :** https://www.pwabuilder.com/
- **Tailwind CSS :** https://tailwindcss.com/docs

### Contact développeur

Si problème technique majeur :
1. Collecter logs (Apache + PHP)
2. Capturer erreurs console (F12)
3. Noter les étapes de reproduction
4. Envoyer screenshot si erreur visuelle

---

**Document mis à jour :** Octobre 2025  
**Version application :** 1.0  
**Compatibilité testée :** PHP 7.3.33, Apache 2.4+
