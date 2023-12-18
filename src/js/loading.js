//Ajoute un écran de chargement lorsque le DOM est chargé
function loading() {
    let loading = document.createElement('div');
    loading.id = 'loading';
    loading.style.position = 'fixed';
    loading.style.top = 0;
    loading.style.left = 0;
    loading.style.width = '100vw';
    loading.style.height = '100vh';
    loading.style.backgroundColor = '#fff';
    loading.style.zIndex = 1000;
    document.body.appendChild(loading);
}

loading();

//Loesque le DOM est chargé, on retire l'écran de chargement
document.addEventListener('DOMContentLoaded', () => {
    let loading = document.getElementById('loading');
    loading.remove();
});

