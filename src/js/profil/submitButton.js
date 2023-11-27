//Gestion du boutton d'envoi du formulaire

/*****************************************************************/
//Fonctions

function popupInvalid(titre, message='') {
    //Fonction qui affiche un message d'erreur si le champ est invalide
    let popup = Swal.fire({
        icon: 'info',
        title: titre,
        text: message,
        showConfirmButton: true
    });
    return popup;
}

/*****************************************************************/
//Variables et constantes

const submitButton = document.getElementById('submitButton');
const form = document.getElementById('form');

/*****************************************************************/
//Listeners

//Enlèvement de la classe invalid lorsqu'on clique sur un champ
form.addEventListener('click', function (e) {
    if (e.target.classList.contains('invalid')) {
        e.target.classList.remove('invalid');
    }
});

//Envoi du formulaire
submitButton.addEventListener('click', function (e) {
    e.preventDefault();
    
    let invalid = false;
    let champsVides = false;
    let promise = Promise.resolve(); //Permet d'etre sur que les popups soient affichées dans l'ordre
  
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {

        // Gestion des champs vides
        if ((input.value == "") && (input.type != "file")) {
            input.classList.add('invalid');
            champsVides = true;

        // Gestion du champ file (photo)
        } else if ((input.type == "file") && (input.value != "")) {
            const fileExt = input.value.split('.').pop(); 
            const validExt = ['png', 'jpg', 'jpeg'];

            if ((validExt.includes(fileExt) == false) && (fileExt != "")){
                document.getElementById('photo').classList.add('invalid');
                invalid = true;
                promise = promise.then(() => popupInvalid("Le format du fichier est invalide !", "Veuillez réessayer en s'assurant que le fichier est au format png, jpg ou jpeg."));
            }

        // Gestion du champ mot de passe
        } else if ((input.type == "password") && (input.value != "")) {
            const charMaj = /[a-zA-Z]/;
            const charSpe = /[!@#$%^&*(),.?":{}|<>]/;
            const charNum = /[0-9]/;

            if ((charMaj.test(input.value) == false) || (charSpe.test(input.value) == false) || (charNum.test(input.value) == false) || (input.value.length < 8)){
                input.classList.add('invalid');
                invalid = true;
                promise = promise.then(() => popupInvalid("Le mot de passe actuel est incorrect !", "Veuillez réessayer en s'assurant que le mot de passe contient au moins 8 caractères, une majuscule, un chiffre et un caractère spécial."));
            } 
        }

        // Gestion du champ téléphone
        else if ((input.type == "tel") && (input.value != "")) {
            const regex = /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/;

            if (regex.test(input.value) == false){
                input.classList.add('invalid');
                invalid = true;
                promise = promise.then(() => popupInvalid("Le numéro de téléphone est incorrect !", "Veuillez réessayer en s'assurant que le numéro de téléphone est au format français."));
            } 
        }

        // Gestion du champ email
        else if ((input.type == "email") && (input.value != "")) {
            const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if (regex.test(input.value) == false){
                input.classList.add('invalid');
                invalid = true;
                promise = promise.then(() => popupInvalid("L'adresse email est incorrecte !", "Veuillez réessayer en s'assurant que l'adresse email est sous ce format: xxx@xxx.xxx"));
            } 
        }
    });
    if (champsVides) {
        promise = promise.then(() => popupInvalid("Certains champs sont vides !"));
    }
    
    if ((invalid == false) && (champsVides == false)){
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
    }
});