# Harmon’Iza - PWA Minimale pour Liquidation Temporaire

## Description
Progressive Web App (PWA) esthétique et fonctionnelle pour la vente de bijoux en pierres naturelles lors d'une liquidation. Design pastel inspiré d'une boutique (rose poudré, doré doux). Pas de base de données, stockage des commandes en JSON. Utilise uniquement des CDN pour dépendances. Déploiement sans build.

## Configuration Minimale
- Serveur PHP 7.3.33 (compatible avec https://app.santementale.org/harmoniza/).
- HTTPS forcé (configurer via .htaccess si besoin, non inclus).
- Pas d'outils supplémentaires côté serveur.

## Déploiement en 5 Étapes
1. Téléchargez et extrayez ce ZIP sur le serveur à https://app.santementale.org/harmoniza/ (racine du dossier harmoniza/).
2. Créez le dossier /admin/commandes/ et définissez les permissions : `chmod 700 admin/commandes/` (dossier lisible/écritable par le processus web uniquement). Pour les fichiers : `chmod 600 admin/commandes/*` après création.
3. Modifiez admin/config.php pour définir le mot de passe admin (par défaut : 'adminpassword').
4. Testez l'API avec cURL : `curl -X POST -H "Content-Type: application/json" -d '{"buyer":{"name":"Test Nom","phone":"+33123456789","notes":""},"items":[{"slug":"bracelet-amour","qty":1}],"client_total":15.00}' https://app.santementale.org/harmoniza/api/commande/create`
   - Attendu : {"ok":true,"order_id":"HZ-...","message":"Commande enregistrée"}
5. Accédez à https://app.santementale.org/harmoniza/ pour tester le front. Installez comme PWA via navigateur.

## Endpoints Disponibles
- POST /api/commande/create : Crée une commande. Payload JSON : {"buyer":{...},"items":[...],"client_total":...}
  - Exemple cURL ci-dessus.
- GET /data/products.json : Catalogue produits (statique).

## Modification des Données
- Éditez data/products.json pour ajouter/modifier produits (format : array d'objets avec slug, name, price, images, stone, intention, description).
- Éditez data/stones.json pour les fiches pierres (array d'objets avec name, image, origin, virtues, associations, precautions, purification).

## Notes
- Sécurité temporaire : Changez le mot de passe admin. Ne pas exposer /admin/commandes/ publiquement.
- Offline : PWA cache pages visitées ; commandes nécessitent connexion.
- Ajoutez .htaccess pour forcer HTTPS si besoin : RewriteEngine On\nRewriteCond %{HTTPS} off\nRewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
