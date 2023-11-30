/*******************************************************/
//Variables et constantes

const submitBtn = document.getElementById('submitBtn');
const form = document.getElementById('connexion-form');
const currentUrl = window.location.href;
const inputs = form.querySelectorAll('input');

/*******************************************************/
//Listners

inputs.forEach(input => {
    if (input.type == "submit") return;
    input.addEventListener('input', function (e) {
        if (e.target.classList.contains('invalid')) {
            e.target.classList.remove('invalid');
        }
    });
});

submitBtn.addEventListener('input', function (e) {
    e.preventDefault();
    
    //Vérifier si les champs sont remplis
    let isValid = true;

    inputs.forEach(input => {
        
        if (input.value == "") {
            input.classList.add('invalid');
            input.setCustomValidity('Ce champ ne peut pas être vide');
            isValid = false;
        } else {
            input.setCustomValidity('');
        }
    });

    //Envoyer le formulaire
    if (isValid) {
        form.submit();
    } else {
        form.reportValidity();
    }
});

/*******************************************************/
//Popup d'erreur

if (currentUrl.includes("error")) {
    Swal.fire({
        icon: 'info',
        title: 'Oops...',
        text: 'Mauvais identifiant ou mot de passe !',
    });
    inputs.forEach(input => {
        if (input.type == "submit") return;
        input.classList.add('invalid');
    });
} 