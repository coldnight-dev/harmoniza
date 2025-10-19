<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $pageDescription ?? 'Harmon\'Iza - Bijoux et pierres énergétiques'; ?>">
    <title><?php echo $pageTitle ?? 'Harmon\'Iza'; ?></title>
    
    <!-- PWA -->
    <link rel="manifest" href="https://app.santementale.org/harmoniza/manifest.json">
    <meta name="theme-color" content="#f8b4d9">
    <link rel="icon" type="image/png" href="https://placehold.co/32x32/f8b4d9/ffffff?text=HZ">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://app.santementale.org/harmoniza/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="https://app.santementale.org/harmoniza/" class="logo">
                <i class="fas fa-gem mr-2"></i>Harmon'Iza
            </a>
            
            <ul class="nav-links hidden md:flex">
                <li><a href="https://app.santementale.org/harmoniza/"><i class="fas fa-home mr-1"></i>Accueil</a></li>
                <li><a href="/harmoniza/boutique.php"><i class="fas fa-shopping-bag mr-1"></i>Boutique</a></li>
                <li><a href="/harmoniza/pierres.php"><i class="fas fa-gem mr-1"></i>Les Pierres</a></li>
                <li><a href="/harmoniza/intentions.php"><i class="fas fa-heart mr-1"></i>Intentions</a></li>
            </ul>
            
            <div class="flex items-center gap-4">
                <a href="/harmoniza/commande.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge">0</span>
                </a>
                <button id="mobileMenuBtn" class="md:hidden text-2xl text-pink-600">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <!-- Menu mobile -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200 py-4">
            <ul class="flex flex-col gap-2 px-4">
                <li><a href="https://app.santementale.org/harmoniza/" class="block py-2 hover:text-pink-600"><i class="fas fa-home mr-2"></i>Accueil</a></li>
                <li><a href="/harmoniza/boutique.php" class="block py-2 hover:text-pink-600"><i class="fas fa-shopping-bag mr-2"></i>Boutique</a></li>
                <li><a href="/harmoniza/pierres.php" class="block py-2 hover:text-pink-600"><i class="fas fa-gem mr-2"></i>Les Pierres</a></li>
                <li><a href="/harmoniza/intentions.php" class="block py-2 hover:text-pink-600"><i class="fas fa-heart mr-2"></i>Intentions</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main>
