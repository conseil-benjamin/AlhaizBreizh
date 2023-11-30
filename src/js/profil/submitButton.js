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

// Gestion des champs vides
form.addEventListener('input', function (e) {
    e.target.classList.add('invalid');
    if (e.target.value == "") {
        e.target.setCustomValidity('Ce champ ne peut pas être vide');
    } else {
        e.target.setCustomValidity('');
        e.target.classList.remove('invalid');
    }
    e.target.reportValidity();
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
            input.setCustomValidity('Ce champ ne peut pas être vide');
            input.reportValidity();
            champsVides = true;
        
        // Gestion des champs trop longs
        } else if ((input.value.length > 50) && (["tel", "email"].includes(input.type) == false)) {
            input.classList.add('invalid');
            input.setCustomValidity('Ce champ ne peut pas contenir plus de 50 caractères');
            input.reportValidity();
            let message = document.createElement('p')
            message.innerHTML = "Ce champ ne peut pas contenir plus de 50 caractères";
            message.style.position = "absolute";
            message.style.color = "red";
            message.style.backgroundColor = "white";
            message.style.border = "1px solid red";
            message.style.fontSize = "12px";
            message.style.top = input.offsetTop + input.offsetHeight + "px";
            message.style.left = input.offsetLeft + "px";
            input.parentNode.appendChild(message);
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
                input.setCustomValidity("Veuillez utiliser au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.");
                input.reportValidity();
            } 
        }

        // Gestion du champ téléphone
        else if ((input.type == "tel") && (input.value != "")) {
            const regex = /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/;

            if (regex.test(input.value) == false){
                input.classList.add('invalid');
                invalid = true;
                input.setCustomValidity("Veuillez réessayer en s'assurant que le numéro de téléphone est au format français.");
                input.reportValidity();
            } 
        }

        // Gestion du champ email
        else if ((input.type == "email") && (input.value != "")) {
            const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if (input.value.length > 100) {
                input.classList.add('invalid');
                invalid = true;
                input.setCustomValidity("Ce champ ne peut pas contenir plus de 100 caractères");
                input.reportValidity();
                
            } else if (regex.test(input.value) == false){
                input.classList.add('invalid');
                invalid = true;
                promise = promise.then(() => popupInvalid("L'adresse email est incorrecte !", "Veuillez réessayer en s'assurant que l'adresse email est sous ce format: xxx@xxx.xxx"));
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