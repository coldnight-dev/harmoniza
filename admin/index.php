<?php
/**
 * Panel Admin - Harmon'Iza
 */

session_start();
require_once 'config.php';

// Vérifier l'authentification
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Gestion du changement de statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $filename = $_POST['filename'] ?? '';
    $newStatus = $_POST['status'] ?? '';
    
    $allowedStatuses = ['en_attente', 'traitee', 'annulee'];
    if (in_array($newStatus, $allowedStatuses) && !empty($filename)) {
        $filepath = __DIR__ . '/commandes/' . basename($filename);
        if (file_exists($filepath)) {
            $orderData = json_decode(file_get_contents($filepath), true);
            if ($orderData) {
                $orderData['status'] = $newStatus;
                $orderData['updated_at'] = gmdate('Y-m-d\TH:i:s\Z');
                file_put_contents($filepath, json_encode($orderData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $message = 'Statut mis à jour';
            }
        }
    }
}

// Lister les commandes
$commandesDir = __DIR__ . '/commandes/';
$commandes = [];

if (is_dir($commandesDir)) {
    $files = scandir($commandesDir, SCANDIR_SORT_DESCENDING);
    foreach ($files as $file) {
        if (substr($file, -5) === '.json' && $file !== 'commande_example.json') {
            $filepath = $commandesDir . $file;
            $content = file_get_contents($filepath);
            $data = json_decode($content, true);
            if ($data) {
                $data['filename'] = $file;
                $commandes[] = $data;
            }
        }
    }
}

$stats = [
    'total' => count($commandes),
    'en_attente' => 0,
    'traitee' => 0,
    'annulee' => 0
];

foreach ($commandes as $cmd) {
    $status = $cmd['status'] ?? 'en_attente';
    if (isset($stats[$status])) {
        $stats[$status]++;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Commandes | Harmon'Iza</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-pink-600">Harmon'Iza Admin</h1>
                <p class="text-sm text-gray-600">Connecté : <?php echo htmlspecialchars($_SESSION['admin_user']); ?></p>
            </div>
            <div class="flex gap-4">
                <a href="<?php echo BASE_PATH; ?>" target="_blank" class="text-gray-600 hover:text-pink-600">
                    <i class="fas fa-external-link-alt"></i> Voir le site
                </a>
                <a href="logout.php" class="text-red-600 hover:text-red-700">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <?php if (isset($message)): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-shopping-bag text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Total</p>
                        <p class="text-3xl font-bold"><?php echo $stats['total']; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-full p-3 mr-4">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">En attente</p>
                        <p class="text-3xl font-bold"><?php echo $stats['en_attente']; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Traitées</p>
                        <p class="text-3xl font-bold"><?php echo $stats['traitee']; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-red-100 rounded-full p-3 mr-4">
                        <i class="fas fa-times text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Annulées</p>
                        <p class="text-3xl font-bold"><?php echo $stats['annulee']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des commandes -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Commandes récentes</h2>
            </div>
            <div class="overflow-x-auto">
                <?php if (empty($commandes)): ?>
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-4"></i>
                    <p>Aucune commande pour le moment</p>
                </div>
                <?php else: ?>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Articles</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($commandes as $cmd): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-mono"><?php echo htmlspecialchars($cmd['order_id']); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo date('d/m/Y H:i', strtotime($cmd['created_at'])); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium"><?php echo htmlspecialchars($cmd['buyer']['name']); ?></div>
                                <div class="text-gray-500"><?php echo htmlspecialchars($cmd['buyer']['phone']); ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm"><?php echo count($cmd['items']); ?> article(s)</td>
                            <td class="px-6 py-4 text-sm font-semibold"><?php echo number_format($cmd['total'], 2, ',', ' '); ?> €</td>
                            <td class="px-6 py-4 text-sm">
                                <?php
                                $statusClass = [
                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                    'traitee' => 'bg-green-100 text-green-800',
                                    'annulee' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabel = [
                                    'en_attente' => 'En attente',
                                    'traitee' => 'Traitée',
                                    'annulee' => 'Annulée'
                                ];
                                $status = $cmd['status'] ?? 'en_attente';
                                ?>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold <?php echo $statusClass[$status] ?? 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo $statusLabel[$status] ?? $status; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <button onclick="viewOrder('<?php echo htmlspecialchars($cmd['filename']); ?>')" 
                                    class="text-blue-600 hover:text-blue-800 mr-3">
                                    <i class="fas fa-eye"></i> Voir
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal détails commande -->
    <div id="orderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
                <h3 class="text-xl font-bold">Détails de la commande</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="orderContent" class="p-6"></div>
        </div>
    </div>

    <script>
    function viewOrder(filename) {
        fetch('view_order.php?file=' + encodeURIComponent(filename))
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                displayOrder(data);
            });
    }

    function displayOrder(order) {
        const content = document.getElementById('orderContent');
        const statusOptions = {
            'en_attente': 'En attente',
            'traitee': 'Traitée',
            'annulee': 'Annulée'
        };
        
        let itemsHTML = '';
        order.items.forEach(item => {
            itemsHTML += `
                <tr class="border-b">
                    <td class="py-2">${item.name}</td>
                    <td class="py-2 text-center">${item.qty}</td>
                    <td class="py-2 text-right">${item.unit_price.toFixed(2)} €</td>
                    <td class="py-2 text-right font-semibold">${item.line_total.toFixed(2)} €</td>
                </tr>
            `;
        });

        content.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="font-bold text-gray-700 mb-2">Informations</h4>
                    <p><strong>ID:</strong> ${order.order_id}</p>
                    <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString('fr-FR')}</p>
                    <form method="POST" class="mt-3">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="filename" value="${order.filename}">
                        <label class="block text-sm font-medium mb-1">Statut:</label>
                        <select name="status" class="border rounded px-3 py-2 mr-2">
                            ${Object.entries(statusOptions).map(([val, label]) => 
                                `<option value="${val}" ${order.status === val ? 'selected' : ''}>${label}</option>`
                            ).join('')}
                        </select>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Mettre à jour
                        </button>
                    </form>
                </div>
                <div>
                    <h4 class="font-bold text-gray-700 mb-2">Client</h4>
                    <p><strong>Nom:</strong> ${order.buyer.name}</p>
                    <p><strong>Téléphone:</strong> ${order.buyer.phone}</p>
                    ${order.buyer.notes ? `<p><strong>Notes:</strong> ${order.buyer.notes}</p>` : ''}
                </div>
            </div>

            <h4 class="font-bold text-gray-700 mb-3">Articles commandés</h4>
            <table class="w-full mb-6">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 text-left px-2">Produit</th>
                        <th class="py-2 text-center">Qté</th>
                        <th class="py-2 text-right">Prix unitaire</th>
                        <th class="py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHTML}
                    <tr class="font-bold">
                        <td colspan="3" class="py-3 text-right">TOTAL:</td>
                        <td class="py-3 text-right text-xl">${order.total.toFixed(2)} €</td>
                    </tr>
                </tbody>
            </table>

            <div class="bg-gray-50 p-4 rounded text-sm text-gray-600">
                <p><strong>IP:</strong> ${order.meta.client_ip}</p>
                <p><strong>User Agent:</strong> ${order.meta.user_agent}</p>
            </div>
        `;
        
        document.getElementById('orderModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }
    </script>
</body>
</html>
