//Gestion du boutton d'envoi du formulaire

/*****************************************************************/
//Fonctions

function popupInvalid(titre, message='') {
    //Fonction qui affiche un popup d'erreur si le champ est invalide
    let popup = Swal.fire({
        icon: 'info',
        title: titre,
        text: message,
        showConfirmButton: true
    });
    return popup;
}

function alertOverlay(elem, text){
    //Fonction qui affiche un message d'erreur si le champ est invalide
    elem.classList.add('invalid');
    let message = document.createElement('p')
    message.innerHTML = text;
    message.classList.add('alert');
    message.style.top = elem.offsetTop + elem.offsetHeight + "px";
    message.style.left = elem.offsetLeft + "px";
    message.style.width = "calc("+elem.offsetWidth + "px - 2em"+")";
    message.style.zIndex = "100";
    elem.parentNode.appendChild(message);
    setTimeout(function() {
        elem.parentNode.removeChild(elem.parentNode.querySelector('.alert'));
    }, 3000);
}

function removeAlert(elem){
    //Fonction qui supprime le message d'erreur
    elem.parentNode.querySelector('.invalid').classList.remove('invalid');
    elem.parentNode.removeChild(elem.parentNode.querySelector('.alert'));
}

/*****************************************************************/
//Variables et constantes

const submitButton = document.getElementById('submitButton');
const form = document.getElementById('form');

/*****************************************************************/
//Listeners

// Retirer les messages d'erreur quand on resize la fenêtre
window.addEventListener('resize', function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.parentNode.removeChild(alert);
    });
});

// Gestion des champs vides
form.addEventListener('input', function (e) {
    if ((e.target.value == "") && (e.target.type != "file")) {
        alertOverlay(e.target, "Ce champ ne peut pas être vide");
    } else {
        removeAlert(e.target);
    }
});

// Retirer les messages d'erreur quand on clique sur le champ
form.addEventListener('focusin', function (e) {
    if (e.target.classList.contains('invalid')) {
        removeAlert(e.target);
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
            champsVides = true;
        
        // Gestion des champs trop longs
        } else if ((input.value.length > 50) && (["tel", "email"].includes(input.type) == false)) {
            input.classList.add('invalid');
            alertOverlay(input, "Ce champ ne peut pas contenir plus de 50 caractères");
            invalid = true;

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
                alertOverlay(input, "Veuillez utiliser au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.");
            } 
        }

        // Gestion du champ téléphone
        else if ((input.type == "tel") && (input.value != "")) {
            const regex = /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/;

            if (regex.test(input.value) == false){
                input.classList.add('invalid');
                invalid = true;
                alertOverlay(input, "Veuillez réessayer en s'assurant que le numéro de téléphone est au format français.");
            } 
        }

        // Gestion du champ email
        else if ((input.type == "email") && (input.value != "")) {
            const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if (input.value.length > 100) {
                input.classList.add('invalid');
                invalid = true;
                alertOverlay(input, "Ce champ ne peut pas contenir plus de 100 caractères");
                
            } else if (regex.test(input.value) == false){
                input.classList.add('invalid');
                invalid = true;
                alertOverlay(input, "Veuillez utiliser ce format: xxx@xxx.xxx");
            } 
        } else {
            input.setCustomValidity('');
        }
    });
    
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