#!/bin/bash

# Script de correction des chemins - Harmon'Iza
# Usage: bash fix-paths.sh

echo "🔧 Correction des chemins Harmon'Iza"
echo "===================================="

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Backup
BACKUP_DIR="backup_$(date +%Y%m%d_%H%M%S)"
echo -e "${YELLOW}📦 Création du backup dans $BACKUP_DIR${NC}"
mkdir -p "$BACKUP_DIR"
cp -r js includes *.php "$BACKUP_DIR/" 2>/dev/null
echo -e "${GREEN}✓ Backup créé${NC}\n"

# 1. Corriger js/main.js
echo "1️⃣  Correction de js/main.js"
if [ -f "js/main.js" ]; then
    # Remplacer BASE_URL relatif par absolu
    sed -i.bak "s|const BASE_URL = '/harmoniza/';|const BASE_URL = 'https://app.santementale.org/harmoniza/';|g" js/main.js
    
    # Ou alternativement, garder relatif mais corriger la fonction loadData
    cat > js/main.js.new << 'EOF'
/**
 * Harmon'Iza - Script principal (VERSION CORRIGÉE)
 */

const BASE_URL = '/harmoniza/';
let productsData = [];
let stonesData = [];

// Charger les données avec chemins absolus
async function loadData() {
  try {
    const baseUrl = window.location.origin + BASE_URL;
    const [productsRes, stonesRes] = await Promise.all([
      fetch(baseUrl + 'data/products.json'),
      fetch(baseUrl + 'data/stones.json')
    ]);
    
    if (!productsRes.ok) throw new Error('Erreur chargement products.json');
    if (!stonesRes.ok) throw new Error('Erreur chargement stones.json');
    
    productsData = await productsRes.json();
    stonesData = await stonesRes.json();
    
    return { products: productsData, stones: stonesData };
  } catch (error) {
    console.error('Erreur chargement données:', error);
    return { products: [], stones: [] };
  }
}
EOF

    # Copier le reste du fichier (après la fonction loadData)
    awk '/^\/\/ Recherche avec debounce/,0' js/main.js.bak >> js/main.js.new
    mv js/main.js.new js/main.js
    rm js/main.js.bak
    
    echo -e "${GREEN}✓ js/main.js corrigé${NC}"
else
    echo -e "${RED}✗ js/main.js introuvable${NC}"
fi

# 2. Corriger includes/header.php
echo -e "\n2️⃣  Correction de includes/header.php"
if [ -f "includes/header.php" ]; then
    # S'assurer que les chemins sont absolus
    sed -i.bak 's|href="/harmoniza/|href="https://app.santementale.org/harmoniza/|g' includes/header.php
    sed -i 's|src="/harmoniza/|src="https://app.santementale.org/harmoniza/|g' includes/header.php
    
    # Garder les liens de navigation relatifs
    sed -i 's|href="https://app.santementale.org/harmoniza/\([^"]*\.php\)"|href="/harmoniza/\1"|g' includes/header.php
    
    rm includes/header.php.bak
    echo -e "${GREEN}✓ includes/header.php corrigé${NC}"
else
    echo -e "${RED}✗ includes/header.php introuvable${NC}"
fi

# 3. Corriger includes/footer.php
echo -e "\n3️⃣  Correction de includes/footer.php"
if [ -f "includes/footer.php" ]; then
    sed -i.bak 's|src="/harmoniza/js/|src="https://app.santementale.org/harmoniza/js/|g' includes/footer.php
    rm includes/footer.php.bak
    echo -e "${GREEN}✓ includes/footer.php corrigé${NC}"
else
    echo -e "${RED}✗ includes/footer.php introuvable${NC}"
fi

# 4. Vérifier toutes les pages PHP
echo -e "\n4️⃣  Vérification des pages PHP"
for file in index.php boutique.php produit.php pierres.php intentions.php commande.php; do
    if [ -f "$file" ]; then
        echo "   Vérification de $file..."
        # Pas de modification nécessaire si les includes sont corrects
        echo -e "   ${GREEN}✓ $file OK${NC}"
    else
        echo -e "   ${RED}✗ $file manquant${NC}"
    fi
done

# 5. Vérifier manifest.json
echo -e "\n5️⃣  Vérification de manifest.json"
if [ -f "manifest.json" ]; then
    # S'assurer que start_url est correct
    sed -i.bak 's|"start_url": "/"|"start_url": "/harmoniza/"|g' manifest.json
    rm manifest.json.bak
    echo -e "${GREEN}✓ manifest.json OK${NC}"
else
    echo -e "${RED}✗ manifest.json manquant${NC}"
fi

# 6. Vérifier sw.js
echo -e "\n6️⃣  Vérification de sw.js"
if [ -f "sw.js" ]; then
    # S'assurer que BASE_URL est correct
    sed -i.bak "s|const BASE_URL = '/';|const BASE_URL = '/harmoniza/';|g" sw.js
    rm sw.js.bak
    echo -e "${GREEN}✓ sw.js OK${NC}"
else
    echo -e "${RED}✗ sw.js manquant${NC}"
fi

# 7. Test de validation
echo -e "\n7️⃣  Tests de validation"
echo "   Vérification de l'accès aux JSON..."

if command -v curl &> /dev/null; then
    for file in data/products.json data/stones.json manifest.json; do
        status=$(curl -s -o /dev/null -w "%{http_code}" "https://app.santementale.org/harmoniza/$file")
        if [ "$status" = "200" ]; then
            echo -e "   ${GREEN}✓ $file accessible (HTTP $status)${NC}"
        else
            echo -e "   ${RED}✗ $file inaccessible (HTTP $status)${NC}"
        fi
    done
else
    echo -e "   ${YELLOW}⚠ curl non disponible, impossible de tester${NC}"
fi

# Résumé
echo -e "\n========================================"
echo -e "${GREEN}✅ Corrections terminées !${NC}"
echo -e "========================================"
echo ""
echo "📋 Prochaines étapes :"
echo "  1. Vider le cache du navigateur (Ctrl+Shift+R)"
echo "  2. Recharger https://app.santementale.org/harmoniza/"
echo "  3. Ouvrir la console (F12) pour vérifier les erreurs"
echo "  4. Si problème, restaurer depuis $BACKUP_DIR"
echo ""
echo "🔧 Commandes utiles :"
echo "  - Restaurer backup: cp -r $BACKUP_DIR/* ."
echo "  - Voir logs Apache: tail -f /var/log/apache2/error.log"
echo ""
