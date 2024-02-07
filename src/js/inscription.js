const formHTMLelement = document.getElementById("formulaire")
const btnValiderHTMLelement = document.getElementById("valider")
const numTelInputHTMLelement = document.getElementById("num_tel")
const codePostalHTMLelement = document.getElementById("code_postal")
const villeHTMLelement = document.getElementById("ville")
const dateNaissanceInputHTMLelement = document.getElementById("date_naissance")

function setMaxDateNaissanceAuj() {
    const dateAuj = new Date();
    const dateMax = new Date(dateAuj.getFullYear() - 18, dateAuj.getMonth(), dateAuj.getDate());
    dateNaissanceInputHTMLelement.max = dateMax.toISOString().split('T')[0];}

function validerForm() {
    const nom = document.forms["formulaire"]["nom"].value;
    const prenom = document.forms["formulaire"]["prenom"].value;
    const numTel = document.forms["formulaire"]["num_tel"].value;
    const civilite = document.forms["formulaire"]["civilite"].value;
    const dateNaissance = document.forms["formulaire"]["date_naissance"].value;
    const adresse = document.forms["formulaire"]["adresse"].value;
    const codePostal = document.forms["formulaire"]["code_postal"].value;
    const ville = document.forms["formulaire"]["ville"].value;
    const identifiant = document.forms["formulaire"]["identifiant"].value;
    const email = document.forms["formulaire"]["email"].value;
    const mdp = document.forms["formulaire"]["mdp"].value;
    const confirmerMdp = document.forms["formulaire"]["confirmer_mdp"].value;
    const photoProfil = document.forms["formulaire"]["file"].files[0];
    const acceptConditions = document.forms["formulaire"]["accept_conditions"].checked;
    let msgErreur = ""
    if (nom === "") {
        msgErreur += "Le champ 'Nom' est vide.\n"
    }
    if (prenom === "") {
        msgErreur += "Le champ 'Prénom' est vide.\n"
    }
    if (numTel === "") {
        msgErreur += "Le champ 'Numéro de téléphone' est vide.\n"
    }
    else if (!estValideNumTel(numTel)) {
        msgErreur += "Le numéro de Téléphone n'est pas valide.\n"
    }
    if (civilite === "") {
        msgErreur += "Le champ 'Civilité' est vide.\n"
    }
    if (dateNaissance === "") {
        msgErreur += "Le champ 'Date de naissance' est vide.\n"
    }
    if (adresse === "") {
        msgErreur += "Le champ 'Adresse' est vide.\n"
    }
    else if (!estValideAdr(adresse)) {
        msgErreur += "L'adresse spécifié est invalide.\n"
    }
    if (codePostal === "") {
        msgErreur += "Le champ 'Code postal' est vide.\n"
    }
    if (ville === "") {
        msgErreur += "Le champ 'Ville' est vide.\n"
    }
    if (identifiant === "") {
        msgErreur += "Le champ 'Identifiant' est vide.\n"
    }
    if (email === "") {
        msgErreur += "Le champ 'Email' est vide.\n"
    }
    else if (!estValideMail(email)) {
        msgErreur += "Votre Email est invalide. (ex : admin@example.com)\n"
    }
    if (mdp === "") {
        msgErreur += "Le champ 'Mot de passe' est vide.\n"
    }
    else if (mdp.size < 10) {
        msgErreur += "Votre mot de passe est trop court.\n"
    }
    if (confirmerMdp === "") {
        msgErreur += "Le champ 'Confirmer le mot de passe' est vide.\n"
    }
    if (photoProfil === null || !verifierImg(photoProfil)) {
        msgErreur += "Le fichier choisi pour l'image de profil n'est pas valide. (trop lourd ou mauvais Format)\n"
    }
    if (!acceptConditions) {
        msgErreur += "Vous devez accepter les conditions générales d'utilisation.\n"
    }
    if (!verifierMDP(mdp, confirmerMdp)) {
        msgErreur += "Le champ 'Mot de passe' et 'Confirmer le mot de passe' ne sont pas identique.\n";
    }

    return msgErreur
}

function verifierMDP(mdp, confirmation) {
    return mdp === confirmation;
}

function verifierImg(file) {
    if (file === undefined) {
        return true
    } else {
        if (!file.type.match(/^image\/(jpeg|jpg|png)$/i)) {
            return false;
        }
        return file.size <= 6000000;
    }

}


function handleClickBtnValider(e) {
    e.preventDefault()
    const err = validerForm()
    if (err === "") {
        formHTMLelement.submit();
    } else {
        notifFormInvalide(err)
    }
}

function formatTelephone(input) {
    const valPropre = input.value.replace(/\D/g, '');

    if (valPropre.length > 2) {
        // Ajouter des espaces entre chaque paire de chiffres
        input.value = valPropre.replace(/(\d{2})(?=\d)/g, '$1 ');
    } else {
        input.value = valPropre;
    }
}

function formatCodePostal(input) {
    input.value = input.value.replace(/\D/g, '')
}

function formatVille(input) {
    input.value = input.value.replace(/\d/g,'')
}

function estValideNumTel(numTel) {
    const regexTelephone = /^(0)[\s.-]*([1-9](?:[\s.-]*\d{2}){4})$/
    return regexTelephone.test(numTel)
}

function estValideMail(mail) {
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regexEmail.test(mail)
}

/**
 * Permet de valide le code postal (a voir si on en a vraiment besoins)
 * @param codePostal
 * @returns {boolean}
 */
function estValideCodePostal(codePostal) {
    const regexCodePostal = /^\d{5}$/
    return regexCodePostal.test(codePostal)
}

/**
 * Permet de valider l'adresse (le num et le nom de rue, pas l'adresse complete)
 * @param adr l'adresse à valider
 * @returns {boolean}
 */
function estValideAdr(adr) {
    const regexAdr = /^[0-9]+\s[A-Za-z\s]+/
    return regexAdr.test(adr)
}

function notifFormInvalide(err) {
    swal({
        title: "Attention formulaire invalide",
        text: err,
        icon: "warning",
    })
}

function notifErreurInscription() {
    swal({
        title: "Une erreur est survenu lors de l'inscription",
        text: "Pas de panique, ce n'est pas de votre faute",
        icon: "error",
    })
}

function notifSucessInscription() {
    swal({
        title: "Vous avez bien été inscrit",
        text: "Bienvenue sur notre Site",
        icon: "success",
        button : "Aller à l'écran de connexion"
    }).then(() => {
        window.location.href = "../connexion/connexion.php"}
    )
}

btnValiderHTMLelement.addEventListener("click", (e) => handleClickBtnValider(e))
numTelInputHTMLelement.addEventListener("input", function () {formatTelephone(this)})

codePostalHTMLelement.addEventListener("input", function () {formatCodePostal(this)})
villeHTMLelement.addEventListener("input", function () {formatVille(this)})
setMaxDateNaissanceAuj()