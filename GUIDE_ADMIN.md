# 📖 Guide Administrateur - Harmon'Iza

## Accès au panel admin

**URL :** https://app.santementale.org/harmoniza/admin/

**Identifiants :** Définis dans `admin/config.php`

---

## 🎯 Interface d'administration

### Tableau de bord

**Statistiques en temps réel :**
- 📦 **Total** : Nombre total de commandes
- ⏳ **En attente** : Commandes à traiter
- ✅ **Traitées** : Commandes finalisées
- ❌ **Annulées** : Commandes annulées

### Liste des commandes

**Colonnes :**
- **ID** : Numéro unique (HZ-YYYYMMDD-HHMMSS-XXXX)
- **Date** : Date et heure de création
- **Client** : Nom et téléphone
- **Articles** : Nombre de produits
- **Total** : Montant en euros
- **Statut** : État actuel
- **Actions** : Boutons d'action

---

## 📋 Traiter une commande

### Étape 1 : Consulter les détails

1. Cliquer sur **👁️ Voir** dans la liste
2. Une fenêtre s'ouvre avec :
   - Informations client (nom, téléphone, notes)
   - Liste détaillée des articles
   - Total de la commande
   - Métadonnées (IP, navigateur)

### Étape 2 : Contacter le client

**Informations de contact :**
- **Téléphone** : Toujours fourni
- **Notes** : Peut contenir profil Facebook, email, adresse

**Message type :**
```
Bonjour [Nom],

Nous avons bien reçu votre commande Harmon'Iza n°[ID].

Articles commandés :
- [Liste]

Total : [Montant] €

Nous confirmons la disponibilité et les modalités de livraison.
Quand souhaitez-vous récupérer/recevoir votre commande ?

Belle journée,
Harmon'Iza
```

### Étape 3 : Vérifier la disponibilité

- Consulter le stock physique
- Vérifier l'état des produits
- Confirmer possibilité de préparation

### Étape 4 : Organiser la livraison

**Options possibles :**
- 📍 Retrait sur place (à définir)
- 📮 Envoi postal (frais à discuter)
- 🚗 Livraison locale (selon zone)
- 🤝 Remise en main propre

### Étape 5 : Mettre à jour le statut

Dans la modale de détails :
1. Sélectionner le nouveau statut :
   - **En attente** : Commande reçue, non traitée
   - **Traitée** : Commande finalisée, livrée/remise
   - **Annulée** : Client annule ou produit indisponible
2. Cliquer **Mettre à jour**

**Le fichier JSON est automatiquement modifié avec :**
- Nouveau statut
- Date de mise à jour (`updated_at`)

---

## 🔍 Gestion des commandes

### Rechercher une commande

**Par ID :**
- Utiliser Ctrl+F dans le navigateur
- Chercher "HZ-YYYYMMDD"

**Par client :**
- Ctrl+F puis chercher le nom
- Ou trier par date (plus récent en premier)

**Par date :**
- Les commandes sont triées par défaut (plus récent d'abord)

### Filtrer par statut

**Astuce :**
- Ctrl+F → chercher "En attente" pour voir uniquement celles-ci
- Idem pour "Traitée" ou "Annulée"

### Supprimer une commande

**Méthode manuelle (si nécessaire) :**
1. Se connecter en SSH ou FTP
2. Aller dans `/admin/commandes/`
3. Supprimer le fichier `commande_XXXXXX.json`
4. ⚠️ Action irréversible !

**Recommandation :**
- Préférer marquer "Annulée" plutôt que supprimer
- Garder un historique pour statistiques

---

## 📊 Statistiques et analyses

### Produits populaires

**Via admin (à venir) ou manuel :**
```bash
# En SSH
cd /var/www/html/harmoniza/admin/commandes
grep -rh '"slug"' *.json | grep -v order_id | cut -d'"' -f4 | sort | uniq -c | sort -rn
```

### Revenus

**Calcul manuel :**
- Additionner les totaux des commandes "Traitées"
- Exclure les commandes annulées

**Formule :**
```
Revenus = Σ (Total des commandes traitées)
```

### Taux de conversion

```
Taux = (Commandes traitées / Commandes totales) × 100
```

---

## 🛠️ Maintenance régulière

### Quotidien

- [ ] Consulter nouvelles commandes
- [ ] Répondre aux clients en attente
- [ ] Mettre à jour les statuts

### Hebdomadaire

- [ ] Backup du dossier `/admin/commandes/`
- [ ] Vérifier espace disque disponible
- [ ] Nettoyer anciennes commandes si besoin

### Mensuel

- [ ] Analyser les ventes
- [ ] Identifier produits populaires
- [ ] Mettre à jour catalogue si besoin

---

## 🔧 Personnalisation du catalogue

### Modifier un produit existant

1. Se connecter en FTP/SSH
2. Éditer `data/products.json`
3. Trouver le produit (chercher par slug ou nom)
4. Modifier les champs :
   ```json
   {
     "slug": "identifiant-unique",
     "name": "Nom du Produit",
     "category": "bracelet|collier|pierre",
     "price": 12.00,
     "images": ["URL_image"],
     "stones": ["pierre1", "pierre2"],
     "intentions": ["amour", "protection"],
     "description": "Description courte...",
     "virtues": "Vertus énergétiques...",
     "featured": true|false
   }
   ```
5. Sauvegarder
6. **Valider le JSON** : https://jsonlint.com/
7. Recharger la boutique (Ctrl+F5)

### Ajouter un nouveau produit

1. Copier un produit existant dans `products.json`
2. Modifier tous les champs (surtout le `slug` !)
3. Ajouter une virgule après le produit précédent
4. Valider le JSON
5. Sauvegarder

**Exemple :**
```json
{
  "slug": "nouveau-bracelet-lune",
  "name": "Bracelet Lune Mystique",
  "category": "bracelet",
  "price": 15.00,
  "images": [
    "https://via.placeholder.com/600x600/c8d5e8/ffffff?text=Bracelet+Lune"
  ],
  "stones": ["pierre-de-lune", "labradorite"],
  "intentions": ["intuition", "féminité"],
  "description": "Bracelet célébrant l'énergie lunaire...",
  "virtues": "Favorise l'intuition et la connexion aux cycles naturels...",
  "featured": false
}
```

### Retirer un produit

**Option 1 : Suppression**
- Supprimer l'objet JSON complet du fichier
- Attention aux virgules (dernier élément n'en a pas)

**Option 2 : Masquage (recommandé)**
- Créer un nouveau champ `"visible": false`
- Modifier le code pour filtrer les produits invisibles
- Permet de garder l'historique

### Modifier une fiche pierre

1. Éditer `data/stones.json`
2. Structure complète :
   ```json
   {
     "slug": "nom-pierre",
     "name": "Nom Pierre",
     "image": "URL_image",
     "origin": "Pays d'origine",
     "intentions": ["intention1", "intention2"],
     "virtues": "Description complète des vertus...",
     "associations": ["pierre1", "pierre2"],
     "care": {
       "purification": ["eau", "lune", "sel"],
       "charging": ["soleil", "lune"],
       "avoid": ["eau salée", "chocs"]
     },
     "chakra": "Nom du chakra"
   }
   ```
3. Sauvegarder et valider JSON

---

## ⚠️ Problèmes courants et solutions

### Client ne reçoit pas de confirmation

**Cause :** Application côté client uniquement, pas d'email automatique

**Solution :** Contacter le client manuellement via téléphone/Facebook

### Commande avec montant incorrect

**Cause :** Client a modifié le panier après calcul ou bug cache

**Solution :**
1. Vérifier les prix dans `products.json`
2. Recalculer manuellement
3. Contacter le client pour confirmer le bon montant

### Impossible de voir les détails d'une commande

**Cause :** Fichier JSON corrompu ou permissions

**Solution :**
```bash
# Vérifier permissions
ls -la admin/commandes/commande_XXXXX.json

# Vérifier validité JSON
cat admin/commandes/commande_XXXXX.json | jq empty
```

### Panel admin inaccessible (erreur 403/404)

**Solutions :**
1. Vérifier URL : `/admin/` (avec slash final)
2. Vérifier fichier `admin/index.php` existe
3. Vérifier permissions : `chmod 644 admin/*.php`
4. Tester en navigation privée (vider cache)

---

## 📞 Communication avec les clients

### Modèles de messages

**Confirmation de commande :**
```
Bonjour [Nom],

Merci pour votre commande Harmon'Iza ! 💎

N° [ID]
[Liste articles]
Total : [Montant]€

Nous vous contactons sous 24h pour organiser la livraison.

À très vite,
Harmon'Iza
```

**Commande prête :**
```
Bonjour [Nom],

Votre commande Harmon'Iza est prête ! ✨

Vous pouvez venir la récupérer [lieu/horaires]
ou nous organisons la livraison comme convenu.

Belle journée,
Harmon'Iza
```

**Produit indisponible :**
```
Bonjour [Nom],

Malheureusement, [produit] n'est plus disponible.

Souhaitez-vous :
- Un produit similaire ?
- Annuler cette partie de la commande ?

Nous restons à votre disposition.

Harmon'Iza
```

---

## 🎓 Bonnes pratiques

### Réactivité
- ✅ Répondre dans les 24h maximum
- ✅ Traiter les commandes par ordre d'arrivée
- ✅ Confirmer disponibilité avant validation

### Organisation
- ✅ Noter statut dès qu'une action est prise
- ✅ Archiver/marquer "Traitée" une fois livré
- ✅ Garder trace des échanges clients

### Sécurité
- ✅ Ne jamais partager identifiants admin
- ✅ Se déconnecter après usage
- ✅ Backup régulier des commandes

### Service client
- ✅ Être courtois et professionnel
- ✅ Expliquer clairement les délais
- ✅ Proposer alternatives si indisponibilité

---

## 📚 Ressources utiles

**Validateurs JSON :**
- https://jsonlint.com/
- https://jsonformatter.org/

**Générateurs d'images placeholder :**
- https://placeholder.com/
- https://via.placeholder.com/

**Vérification sécurité :**
- https://securityheaders.com/
- https://www.ssllabs.com/ssltest/

---

**Guide créé :** Octobre 2025  
**Pour questions techniques :** Consulter README.md et INSTALLATION.md
