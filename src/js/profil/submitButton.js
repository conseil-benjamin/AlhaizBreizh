//Gestion du boutton d'envoi du formulaire

const submitButton = document.getElementById('submitButton');
const form = document.getElementById('form');

submitButton.addEventListener('click', function (e) {
    e.preventDefault();

    //Récupérer le mot de passe actuel
    const password = document.getElementById('password').value;
    

    //Demande de confirmation avant envoi du formulaire
    Swal.fire({
        icon: 'info',
        title: "Êtes-vous sûr de vouloir enregistrer les modifications ?",
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Non'
    }).then((result) => {
        if (result.isConfirmed) {

            //Demande de confirmation du mot de passe avant envoi du formulaire

            Swal.fire({
                icon: 'info',
                title: "Veuillez confirmer votre mot de passe",
                input: 'password',
                showCancelButton: true,
                confirmButtonText: 'Valider',
                cancelButtonText: 'Annuler',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Veuillez saisir votre mot de passe'
                    } else if (value != password) {
                        return 'Mot de passe incorrect'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    //Envoi du formulaire
                    form.submit();
                }
            });
        }
    });
});