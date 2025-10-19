<?php
/**
 * Script de diagnostic Harmon'Iza
 * Placer à la racine et visiter pour vérifier la config
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic Harmon'Iza</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        h2 { border-bottom: 2px solid #333; padding-bottom: 10px; }
        pre { background: white; padding: 10px; border-left: 3px solid #333; }
    </style>
</head>
<body>
    <h1>🔍 Diagnostic Harmon'Iza</h1>
    
    <h2>1. Vérification PHP</h2>
    <p>Version PHP: <strong><?php echo phpversion(); ?></strong> 
        <?php echo version_compare(phpversion(), '7.3', '>=') ? '<span class="success">✓ OK</span>' : '<span class="error">✗ Trop ancien</span>'; ?>
    </p>
    
    <h2>2. Vérification des fichiers</h2>
    <?php
    $requiredFiles = [
        'index.php',
        'boutique.php',
        'produit.php',
        'pierres.php',
        'intentions.php',
        'commande.php',
        'manifest.json',
        'sw.js',
        'data/products.json',
        'data/stones.json',
        'admin/config.php',
        'admin/index.php',
        'api/commande/create.php',
        'css/style.css',
        'js/main.js',
        'js/cart.js',
        'js/pwa.js'
    ];
    
    foreach ($requiredFiles as $file) {
        $exists = file_exists(__DIR__ . '/' . $file);
        echo '<p>' . $file . ': ' . ($exists ? '<span class="success">✓ Présent</span>' : '<span class="error">✗ Manquant</span>') . '</p>';
    }
    ?>
    
    <h2>3. Vérification des permissions</h2>
    <?php
    $commandesDir = __DIR__ . '/admin/commandes';
    if (is_dir($commandesDir)) {
        echo '<p class="success">✓ Dossier admin/commandes existe</p>';
        echo '<p>Permissions: ' . substr(sprintf('%o', fileperms($commandesDir)), -4) . '</p>';
        echo '<p>Writable: ' . (is_writable($commandesDir) ? '<span class="success">✓ Oui</span>' : '<span class="error">✗ Non - chmod 700 requis</span>') . '</p>';
    } else {
        echo '<p class="error">✗ Dossier admin/commandes manquant</p>';
        echo '<p>Créer avec: mkdir -p admin/commandes && chmod 700 admin/commandes</p>';
    }
    ?>
    
    <h2>4. Vérification des données JSON</h2>
    <?php
    // Products
    $productsPath = __DIR__ . '/data/products.json';
    if (file_exists($productsPath)) {
        $productsContent = file_get_contents($productsPath);
        $products = json_decode($productsContent, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo '<p class="success">✓ products.json valide (' . count($products) . ' produits)</p>';
        } else {
            echo '<p class="error">✗ products.json invalide: ' . json_last_error_msg() . '</p>';
        }
    } else {
        echo '<p class="error">✗ products.json manquant</p>';
    }
    
    // Stones
    $stonesPath = __DIR__ . '/data/stones.json';
    if (file_exists($stonesPath)) {
        $stonesContent = file_get_contents($stonesPath);
        $stones = json_decode($stonesContent, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo '<p class="success">✓ stones.json valide (' . count($stones) . ' pierres)</p>';
        } else {
            echo '<p class="error">✗ stones.json invalide: ' . json_last_error_msg() . '</p>';
        }
    } else {
        echo '<p class="error">✗ stones.json manquant</p>';
    }
    ?>
    
    <h2>5. Test de l'URL de base</h2>
    <p>URL actuelle: <strong><?php echo $_SERVER['REQUEST_URI']; ?></strong></p>
    <p>Document root: <strong><?php echo $_SERVER['DOCUMENT_ROOT']; ?></strong></p>
    
    <h2>6. Modules PHP</h2>
    <?php
    $required_modules = ['json', 'session'];
    foreach ($required_modules as $module) {
        $loaded = extension_loaded($module);
        echo '<p>' . $module . ': ' . ($loaded ? '<span class="success">✓ Chargé</span>' : '<span class="error">✗ Manquant</span>') . '</p>';
    }
    ?>
    
    <h2>7. Test JavaScript (console navigateur)</h2>
    <p>Ouvrir la console (F12) et vérifier les erreurs JavaScript</p>
    <button onclick="testLoadData()">Tester loadData()</button>
    <div id="jsResult"></div>
    
    <script>
    // Test de chargement des données
    const BASE_URL = '/harmoniza/';
    
    async function testLoadData() {
        const result = document.getElementById('jsResult');
        result.innerHTML = '<p>Chargement...</p>';
        
        try {
            // Tester products.json
            const productsRes = await fetch(BASE_URL + 'data/products.json');
            if (!productsRes.ok) {
                throw new Error('products.json: HTTP ' + productsRes.status);
            }
            const products = await productsRes.json();
            
            // Tester stones.json
            const stonesRes = await fetch(BASE_URL + 'data/stones.json');
            if (!stonesRes.ok) {
                throw new Error('stones.json: HTTP ' + stonesRes.status);
            }
            const stones = await stonesRes.json();
            
            result.innerHTML = `
                <p class="success">✓ Données chargées avec succès</p>
                <p>Produits: ${products.length}</p>
                <p>Pierres: ${stones.length}</p>
                <pre>${JSON.stringify(products[0], null, 2).substring(0, 300)}...</pre>
            `;
        } catch (error) {
            result.innerHTML = `
                <p class="error">✗ Erreur: ${error.message}</p>
                <p>Vérifier la console (F12) pour plus de détails</p>
            `;
            console.error('Erreur détaillée:', error);
        }
    }
    </script>
    
    <hr>
    <p><em>Pour voir les logs d'erreur PHP, consultez les logs serveur</em></p>
    <p><em>Pour supprimer ce fichier après diagnostic: rm debug.php</em></p>
</body>
</html>
