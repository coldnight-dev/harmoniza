/**
 * Harmon'Iza - PWA Installation
 */

let deferredPrompt;

// Enregistrer le Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/harmoniza/sw.js')
      .then(registration => {
        console.log('SW enregistré:', registration.scope);
      })
      .catch(err => {
        console.error('Erreur SW:', err);
      });
  });
}

// Gérer l'événement beforeinstallprompt
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;
  
  // Afficher le bouton d'installation
  const installBtn = document.getElementById('installPWA');
  if (installBtn) {
    installBtn.classList.add('visible');
  }
});

// Installation PWA
function installPWA() {
  if (!deferredPrompt) {
    return;
  }

  deferredPrompt.prompt();
  
  deferredPrompt.userChoice.then((choiceResult) => {
    if (choiceResult.outcome === 'accepted') {
      console.log('PWA installée');
    }
    deferredPrompt = null;
    
    const installBtn = document.getElementById('installPWA');
    if (installBtn) {
      installBtn.classList.remove('visible');
    }
  });
}

// Détecter si déjà installé
window.addEventListener('appinstalled', () => {
  console.log('PWA installée avec succès');
  deferredPrompt = null;
});
