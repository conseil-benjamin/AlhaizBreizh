const formHTMLelement = document.getElementById("formulaire")
const btnValiderHTMLelement = document.getElementById("valider")

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
    const photoProfil = document.forms["formulaire"]["photo_profil"].files[0];
    const acceptConditions = document.forms["formulaire"]["accept_conditions"].checked;
    let msgErreur = ""
    if (nom === "") {
        msgErreur += "Le champ 'Nom' est vide.\n"
    }
    if (prenom === "") {
        msgErreur += "Le champ 'Prenom' est vide.\n"
    }
    if (numTel === "") {
        msgErreur += "Le champ 'Numéro de téléphone' est vide.\n"
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
    if (mdp === "") {
        msgErreur += "Le champ 'Mot de passe' est vide.\n"
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
    console.log(mdp === confirmation)
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

function notifFormInvalide(err) {
    swal({
        title: "Attention formulaire invalide",
        text: err,
        icon: "warning",
    })
}

btnValiderHTMLelement.addEventListener("click", (e) => handleClickBtnValider(e))