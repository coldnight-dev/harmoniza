<?php
/**
 * API Harmon'Iza - Création de commande
 * Endpoint: POST /api/commande/create
 * PHP 7.3.33 compatible
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gérer les requêtes OPTIONS (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Accepter uniquement POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

// Lire le payload JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'JSON invalide']);
    exit;
}

// Validation des champs obligatoires
if (empty($data['buyer']['name'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Le nom est obligatoire']);
    exit;
}

if (empty($data['buyer']['phone'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Le téléphone est obligatoire']);
    exit;
}

if (empty($data['items']) || !is_array($data['items']) || count($data['items']) === 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'La commande doit contenir au moins un article']);
    exit;
}

if (!isset($data['client_total']) || !is_numeric($data['client_total'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Montant client invalide']);
    exit;
}

// Normaliser et valider le téléphone
$phone = preg_replace('/[^0-9+]/', '', $data['buyer']['phone']);
$phoneDigits = preg_replace('/[^0-9]/', '', $phone);

if (strlen($phoneDigits) < 7 || strlen($phoneDigits) > 15) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Numéro de téléphone invalide (7-15 chiffres attendus)']);
    exit;
}

// Charger le catalogue produits
$catalogPath = __DIR__ . '/../../data/products.json';
if (!file_exists($catalogPath)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Catalogue produits introuvable']);
    exit;
}

$catalogJson = file_get_contents($catalogPath);
$catalog = json_decode($catalogJson, true);

if (json_last_error() !== JSON_ERROR_NONE || !is_array($catalog)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Catalogue produits corrompu']);
    exit;
}

// Créer un index slug => produit
$catalogIndex = [];
foreach ($catalog as $product) {
    $catalogIndex[$product['slug']] = $product;
}

// Recalculer le total serveur
$items = [];
$subtotal = 0.0;

foreach ($data['items'] as $item) {
    if (empty($item['slug']) || !isset($item['qty']) || $item['qty'] < 1) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Article invalide dans la commande']);
        exit;
    }

    $slug = trim($item['slug']);
    $qty = intval($item['qty']);

    if (!isset($catalogIndex[$slug])) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Produit introuvable: ' . htmlspecialchars($slug)]);
        exit;
    }

    $product = $catalogIndex[$slug];
    $unitPrice = isset($product['price']) ? floatval($product['price']) : 0.0;
    $lineTotal = $unitPrice * $qty;
    $subtotal += $lineTotal;

    $items[] = [
        'slug' => $slug,
        'name' => $product['name'],
        'qty' => $qty,
        'unit_price' => $unitPrice,
        'line_total' => round($lineTotal, 2)
    ];
}

$serverTotal = round($subtotal, 2);
$clientTotal = round(floatval($data['client_total']), 2);

// Vérifier la cohérence du montant (tolérance 0.50 EUR)
if (abs($serverTotal - $clientTotal) > 0.50) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'error' => 'Différence de montant détectée',
        'client_total' => $clientTotal,
        'server_total' => $serverTotal
    ]);
    exit;
}

// Générer l'ID de commande
$timestamp = gmdate('Ymd-His');
$randomHex = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
$orderId = "HZ-{$timestamp}-{$randomHex}";

// Construire l'objet commande complet
$order = [
    'order_id' => $orderId,
    'created_at' => gmdate('Y-m-d\TH:i:s\Z'),
    'status' => 'en_attente',
    'buyer' => [
        'name' => trim($data['buyer']['name']),
        'phone' => $phone,
        'notes' => isset($data['buyer']['notes']) ? trim($data['buyer']['notes']) : ''
    ],
    'items' => $items,
    'subtotal' => $serverTotal,
    'total' => $serverTotal,
    'meta' => [
        'client_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'server_received_at' => gmdate('Y-m-d\TH:i:s\Z')
    ]
];

// Préparer le chemin de sauvegarde
$ordersDir = __DIR__ . '/../../admin/commandes/';
if (!is_dir($ordersDir)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Dossier commandes introuvable']);
    exit;
}

$filename = 'commande_' . gmdate('Ymd\THis\Z') . '_' . $randomHex . '.json';
$filepath = $ordersDir . $filename;
$tmpPath = $ordersDir . 'tmp_' . $randomHex . '.json';

// Écriture atomique (tmp puis rename)
$jsonContent = json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if (file_put_contents($tmpPath, $jsonContent) === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Impossible d\'écrire le fichier']);
    exit;
}

if (!rename($tmpPath, $filepath)) {
    @unlink($tmpPath);
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Impossible de finaliser la commande']);
    exit;
}

// Définir les permissions (600 pour sécurité)
@chmod($filepath, 0600);

// Réponse de succès
http_response_code(200);
echo json_encode([
    'ok' => true,
    'order_id' => $orderId,
    'message' => 'Commande enregistrée avec succès'
]);
