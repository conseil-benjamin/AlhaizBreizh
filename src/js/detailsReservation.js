function annulerResa() {
        fetch("../php/suppResaDB.php", {
            method: "POST",
            body: new URLSearchParams({numReservation: numReservation}),
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        })
            .then(function (response) {
                if (response.ok) {

                }
                else {

                }
            })
            .catch(function (error) {
                console.log(error.message);
            });
}


function confirmationAnnulerPopUp() {
    swal({
        title: "Êtes-vous sur de vouloir annuler cette réservation",
        text: "Cette action est définitive",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((value) => {
            if (value) {

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