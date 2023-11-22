function annulerResa() {
    let confirmation = false;

    swal({
        title: "Êtes-vous sur de vouloir annuler cette réservation",
        text: "Cette action est définitive",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((value) => { if (value) {
            confirmation = true
        }
        })
    if (confirmation) {
        fetch("../php/suppResaDB.php", {
            method: "POST",
            body: new URLSearchParams({numReservation: numReservation}),
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        })
            .then(function (response) {
                if (response.ok) {
                    return response.text();
                }
                throw new Error("Erreur lors de la suppression de la réservation.");
            })
            .then(function (message) {
                alert(message); // Affichez un message de confirmation ou d'erreur
                // Actualisez la liste des réservations si nécessaire
            })
            .catch(function (error) {
                alert(error.message);
            });
    }
}

