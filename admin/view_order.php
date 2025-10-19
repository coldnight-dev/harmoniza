<?php
/**
 * API pour afficher une commande - Harmon'Iza Admin
 */

session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Vérifier l'authentification
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

$filename = $_GET['file'] ?? '';
if (empty($filename)) {
    echo json_encode(['error' => 'Fichier non spécifié']);
    exit;
}

// Sécurité : empêcher directory traversal
$filename = basename($filename);
$filepath = __DIR__ . '/commandes/' . $filename;

if (!file_exists($filepath)) {
    echo json_encode(['error' => 'Commande introuvable']);
    exit;
}

$content = file_get_contents($filepath);
$order = json_decode($content, true);

if (!$order) {
    echo json_encode(['error' => 'Fichier corrompu']);
    exit;
}

$order['filename'] = $filename;
echo json_encode($order);
