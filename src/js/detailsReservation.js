function annulerResa() {
        let numReservation = 1;
        fetch("../php/reservation/supprimerResaDB.php", {
            method: "POST",
            body: numReservation.toString(),
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

function confirmerResa() {
    if(Math.random() > 0.5 ) {
        confirmerSuccesPopUp()
    }
        confirmerErreurPopUp()
}

function confirmationAnnulerPopUp() {
    swal({
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

function confirmationValiderPopUp() {
    swal({
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

function annulerSuccesPopUp() {
    swal({
        title: "La réservation à bien été supprimée",
        icon: "success",
        button : "Revenir à la liste des réservations"
    }).then(() => {
        // TODO Changer avec l'url de la liste des réservation
        window.location.href = "../../../index.php"}
    )
}

function annulerErreurPopUp() {
    swal({
        title: "Erreur",
        text: "Le serveur à rencontrer un erreur, réessayer plus tard",
        icon: "error",
        button : "ok"
    })
}

function confirmerSuccesPopUp() {
    swal({
        title: "La réservation à bien été confirmer",
        text : "l'équipe d'ALHaIZ vous souhaite agréable séjour !",
        icon: "success",
        button : "Revenir à la liste des réservations"
    }).then(() => {
        // TODO Changer avec l'url de la liste des réservation ou ne pas redirigé
        window.location.href = "../../../index.php"}
    )
}

function confirmerErreurPopUp() {
    swal({
        title: "Erreur",
        text: "Le serveur à rencontrer un erreur, réessayer plus tard",
        icon: "error",
        button : "ok"
    })
}
