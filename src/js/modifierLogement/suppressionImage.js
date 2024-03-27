function afficherCroix(image) {
    // Affiche la croix quand l'user clique sur l'image
    var croix = image.nextElementSibling;
    croix.style.width = '40px';
    croix.style.height = '40px';
    croix.style.display = (croix.style.display === 'block') ? 'none' : 'block';

    
}
function supprimerPhoto(numero) {
    // Confirmation de suppression
    Swal.fire({
        icon: 'warning',
        title: 'Confirmation de suppression',
        text: 'Êtes-vous sûr de vouloir supprimer cette photo ?',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            // Suppression de la photo
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'modifierLogement/suppressionImage.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('numero=' + numero);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Suppression de la photo dans le DOM
                    var image = document.getElementById('image' + numero);
                    image.parentNode.removeChild(image);
                }
            }
        }
    }
    );
}