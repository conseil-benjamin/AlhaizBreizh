/**
 * Fonction pour annuler la réservation
 */
function annulerResa() {
        let numReservation = 1;
    fetch("/src/php/reservation/supprimerResaDB.php?numReservation=" + encodeURIComponent(numReservation.toString()), {
        method: "GET",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        })
            .then(function (response) {
                if (response.ok) {
                    annulerSuccesPopUp()
                }
                else {
                    annulerErreurPopUp()
                }
            })
            .catch(function (error) {
                console.log(error.message);
            });
}

/**
 * Fonction pour simuler la confirmation de la réservation
 */
function confirmerResa() {
    if(Math.random() > 0.5 ) {
        confirmerSuccesPopUp()
    }
        confirmerErreurPopUp()
}

/**
 * Affiche le Pop-up pour confirmer de l'annulation de la réservation
 */
function confirmationAnnulerPopUp() {
    Swal.fire({
        title: "Êtes-vous sur de vouloir annuler cette réservation",
        text: "Cette action est définitive",
        icon: "warning",
        buttons: ['annuler',true],
        dangerMode: true,
    })
        .then((value) => {
            if (value) {
                annulerResa()
            }
        })
}

/**
 * Affiche le Pop-up pour confirmer la validation de la réservation
 */
function confirmationValiderPopUp() {
    Swal.fire({
        title: "Êtes-vous sur de vouloir confirmer cette réservation",
        text: "Vous allez être redirigé vers la page de paiment",
        icon: "warning",
        buttons: ['annuler',true],
        dangerMode: true,
    })
        .then((value) => {
            if (value) {
                confirmerResa()
            }
        })
}

/**
 * Pop-up du succès de l'annulation
 */
function annulerSuccesPopUp() {
    Swal.fire({
        title: "La réservation à bien été supprimée",
        icon: "success",
        button : "Revenir à la liste des réservations"
    }).then(() => {
        // TODO Changer avec l'url de la liste des réservation
            window.location.href = "/src/php/reservation/listeResa.php"
        }
    )
}


/**
 * Pop-up erreur de l'annulation
 */
function annulerErreurPopUp() {
    Swal.fire({
        title: "Erreur",
        text: "Le serveur à rencontrer un erreur, réessayer plus tard",
        icon: "error",
        button : "ok"
    })
}

/**
 * Pop-up du succès de la confirmation
 */
function confirmerSuccesPopUp() {
    Swal.fire({
        title: "La réservation à bien été confirmer",
        text : "l'équipe d'ALHaIZ vous souhaite agréable séjour !",
        icon: "success",
        button : "Revenir à la liste des réservations"
    }).then(() => {
        // TODO Changer avec l'url de la liste des réservation ou ne pas redirigé
        window.location.href = "../../../index.php"}
    )
}

/**
 * Pop-up erreur de la confirmation
 */
function confirmerErreurPopUp() {
    Swal.fire({
        title: "Erreur",
        text: "Le serveur à rencontrer un erreur, réessayer plus tard",
        icon: "error",
        button : "ok"
    })
}
