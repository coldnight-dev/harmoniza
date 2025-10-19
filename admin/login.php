<?php
/**
 * Admin Login - Harmon'Iza
 */

session_start();
require_once 'config.php';

// Si déjà connecté, rediriger
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Identifiants incorrects';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Harmon'Iza</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-pink-600 mb-2">Harmon'Iza</h1>
            <p class="text-gray-600">Administration</p>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Utilisateur</label>
                <input type="text" name="username" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            </div>
            <button type="submit" 
                class="w-full bg-gradient-to-r from-pink-500 to-purple-500 text-white font-semibold py-3 rounded-lg hover:from-pink-600 hover:to-purple-600 transition">
                Se connecter
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="<?php echo BASE_PATH; ?>" class="text-pink-600 hover:underline">
                ← Retour à la boutique
            </a>
        </div>
    </div>
</body>
</html>
