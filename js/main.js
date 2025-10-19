let deferredPrompt;
let products = [];
let stones = [];

// PWA Install
window.addEventListener('beforeinstallprompt', (e) => {
    deferredPrompt = e;
    document.getElementById('install-btn')?.classList.remove('hidden');
});
document.getElementById('install-btn')?.addEventListener('click', () => {
    deferredPrompt.prompt();
});

// Share
function shareProduct(title, url) {
    if (navigator.share) {
        navigator.share({ title, url });
    } else {
        alert('Partage non disponible');
    }
}

// Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js');
}
