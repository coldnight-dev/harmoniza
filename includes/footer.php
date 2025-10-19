</main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-pink-100 to-purple-100 mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-dark);">
                        <i class="fas fa-gem mr-2"></i>Harmon'Iza
                    </h3>
                    <p class="text-gray-700">
                        Harmonisez votre énergie avec nos bijoux et pierres naturelles sélectionnés avec amour.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4 text-lg">Navigation</h4>
                    <ul class="space-y-2">
                        <li><a href="/harmoniza/" class="text-gray-700 hover:text-pink-600 transition">Accueil</a></li>
                        <li><a href="/harmoniza/boutique.php" class="text-gray-700 hover:text-pink-600 transition">Boutique</a></li>
                        <li><a href="/harmoniza/pierres.php" class="text-gray-700 hover:text-pink-600 transition">Les Pierres</a></li>
                        <li><a href="/harmoniza/intentions.php" class="text-gray-700 hover:text-pink-600 transition">Intentions</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4 text-lg">Informations</h4>
                    <p class="text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-2 text-pink-600"></i>
                        Boutique en liquidation
                    </p>
                    <p class="text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2 text-pink-600"></i>
                        Commandes limitées
                    </p>
                    <p class="text-sm text-gray-600 mt-4">
                        Les commandes sont traitées manuellement. Vous serez contacté pour confirmer la disponibilité et les modalités de livraison.
                    </p>
                </div>
            </div>
            
            <div class="border-t border-pink-200 pt-6 text-center">
                <p class="text-gray-600">
                    &copy; <?php echo date('Y'); ?> Harmon'Iza - Tous droits réservés
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Fait avec <i class="fas fa-heart text-pink-500"></i> pour harmoniser vos énergies
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://app.santementale.org/harmoniza/js/cart.js"></script>
    <script src="https://app.santementale.org/harmoniza/js/main.js"></script>
    <script src="https://app.santementale.org/harmoniza/js/pwa.js"></script>
    <script>
        // Initialiser AOS (animations)
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
