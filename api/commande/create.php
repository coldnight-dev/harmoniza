<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || empty($data['buyer']['name']) || empty($data['buyer']['phone']) || empty($data['items'])) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Données incomplètes']);
  exit;
}

// Normaliser phone
$phone = preg_replace('/[^0-9+ ]/', '', $data['buyer']['phone']);
if (strlen(preg_replace('/[^0-9]/', '', $phone)) < 7 || strlen(preg_replace('/[^0-9]/', '', $phone)) > 15) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Téléphone invalide']);
  exit;
}
$data['buyer']['phone'] = $phone;

// Charger products
$products = json_decode(file_get_contents(__DIR__ . '/../../data/products.json'), true);
$server_total = 0;
foreach ($data['items'] as &$item) {
  $prod = array_filter($products, fn($p) => $p['slug'] === $item['slug']);
  $prod = reset($prod);
  if (!$prod) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Produit invalide']);
    exit;
  }
  $item['name'] = $prod['name'];
  $item['unit_price'] = $prod['price'];
  $item['line_total'] = $prod['price'] * $item['qty'];
  $server_total += $item['line_total'];
}
$server_total = round($server_total, 2);

if (abs($server_total - (float)$data['client_total']) > 0.50) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Total mismatch']);
  exit;
}

// Générer order_id
$order_id = 'HZ-' . date('Ymd-His-') . bin2hex(random_bytes(2));

// Construire objet
$order = [
  'order_id' => $order_id,
  'created_at' => gmdate('c'),
  'status' => 'en_attente',
  'buyer' => $data['buyer'],
  'items' => $data['items'],
  'subtotal' => $server_total,
  'total' => $server_total,
  'meta' => [
    'client_ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
    'server_received_at' => gmdate('c')
  ]
];

// Sauvegarder atomiquement
$tmp_file = tempnam(sys_get_temp_dir(), 'order_');
file_put_contents($tmp_file, json_encode($order));
$final_file = __DIR__ . '/../../admin/commandes/commande_' . gmdate('Ymd\THis\Z_') . bin2hex(random_bytes(2)) . '.json';
rename($tmp_file, $final_file);

echo json_encode(['ok' => true, 'order_id' => $order_id, 'message' => 'Commande enregistrée']);
?>
