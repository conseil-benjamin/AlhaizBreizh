function afficherCroix(image) {
    // Affiche la croix quand l'user clique sur l'image
    var croix = image.nextElementSibling;
    croix.style.width = '40px';
    croix.style.height = '40px';
    croix.style.display = (croix.style.display === 'block') ? 'none' : 'block';  
}

function supprimerPhoto(numero) {
    // Confirme la suppression
    Swal.fire({
        icon: 'warning',
        title: 'Confirmation de suppression',
        text: 'Êtes-vous sûr de vouloir supprimer cette photo ?',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            // Compte nb photos
            var nbPhotos = document.getElementsByClassName('photoContainer').length;
            //Si y'a qu'une photo on affiche un message d'erreur
            if (nbPhotos === 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Suppression impossible',
                    text: 'Un logement doit avoir au minimum une photo.',
                });
            } else {
                fetch('supprimerImage.php?nomPhoto=' + numero + '&numLogement=' + sessionStorage.getItem('num_logement'), {
                    method: 'DELETE',
                }).then((response) => {
                    if (response.status === 200) {
                        // Suppression de l'image du DOM
                        var imageContainer = document.getElementsByClassName('photoContainer')[numero - 1];
                        imageContainer.parentNode.removeChild(imageContainer);
                        // pop up succès
                        Swal.fire({
                            icon: 'success',
                            title: 'Photo supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        console.error(response.status, response.statusText);

                        //pop up erreur
                        Swal.fire({
                            icon: 'error',
                            title: 'Échec de la suppression de la photo.',
                            text: 'Veuillez réessayer ultérieurement.',
                        });
                    }
                });
            }
        }
    });
}

