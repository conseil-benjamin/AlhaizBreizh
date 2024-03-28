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
            // Envoi d'une requête Fetch pour la suppression
            fetch('supprimerImage.php?nomPhoto=' + numero, {
                method: 'DELETE',
            }).then((response) => {
                if (response.status === 200) {
                    // Suppression de l'image du DOM
                    var imageContainer = document.getElementsByClassName('photoContainer')[numero - 1];
                    imageContainer.parentNode.removeChild(imageContainer);
                    // Affichage d'un message de succès
                    Swal.fire({
                        icon: 'success',
                        title: 'Photo supprimée avec succès.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    // Affichage d'un message d'erreur
                    Swal.fire({
                        icon: 'error',
                        title: 'Échec de la suppression de la photo.',
                        text: 'Veuillez réessayer ultérieurement.',
                    });
                }
            });
        }
    });
}
