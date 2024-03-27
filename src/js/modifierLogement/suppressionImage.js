function afficherCroix(image) {
    // Affiche la croix quand l'user clique sur l'image
    var croix = image.nextElementSibling;
    croix.style.display = 'block';
    croix.style.width = '40px';
    croix.style.height = '40px';

    image.onclick = function() { return false; };//onclick désactivé
}

function supprimerPhoto(numero) {
    // Supprimer la photo correspondante
    var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette photo ?");
    if (confirmation) {
        // Supprimer l'image du DOM
        var imageContainer = document.getElementsByClassName('photoContainer')[numero - 1];
        imageContainer.parentNode.removeChild(imageContainer);

        // Ici, vous pouvez également ajouter du code pour supprimer la photo du serveur si nécessaire.
    }
}