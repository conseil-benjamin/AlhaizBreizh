function confirmationAnnulerPopUp(numReservation, dateArr) {
    Swal.fire({
        title: `Êtes-vous sûr de vouloir annuler la réservation  ?`,
        text: "Cette action est définitive",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, je confirme",
        cancelButtonText: "Annuler",
        reverseButtons: true,
        dangerMode: true,
    })
    .then((value) => {
        if (value) {
            // Appel de la fonction annulerResa() avec le numéro de réservation
            annulerResa(numReservation, dateArr);
        }
    });
}


function annulerComplexPopUp(numReservation) { // Ajouter le paramètre numReservation
    Swal.fire({
        title: "Vous risquez de ne pas être totalement remboursé",
        text: "Voulez-vous quand même annuler la réservation ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Non',
        cancelButtonColor:"#7066E0"
    })
    .then((value) => {
        if (value.isConfirmed) {
            annulerReservation(numReservation); // Appeler la fonction annulerReservation si l'utilisateur confirme
        } else {
            console.log("Annulation de l'annulation de la réservation.");
        }
    })
}

function annulerImpossiblePopUp() {
    Swal.fire({
        title: "Attention",
        text: "Vous ne pouvez pas annuler une réservation 2 jours avant son début",
        icon: "error",
        button : "ok"
    })
}
// Fonctions de gestion des pop-up (suite)

function annulerSuccesPopUp(numReservation) {
    Swal.fire({
        title: "Réservation annulée",
        text: `La réservation n°${numReservation} a bien été supprimée`,
        icon: "success",
        button : "Revenir à la liste des réservations"
    }).then(() => {
        // TODO Changer avec l'url de la liste des réservation
            window.location.href = "/src/php/reservation/listeResa.php"
        }
    )
}

function annulerErreurPopUp() {
    Swal.fire({
        title: "Erreur",
        text: "Le serveur à rencontrer une erreur, réessayer plus tard",
        icon: "error",
        button : "ok"
    })
}

// Fonctions de gestion de la logique d'annulation

function annulerResa(numReservation, dateArr) {
    let dateDebutReservation = new Date(dateArr); // Convertir la date de début de réservation en objet Date
    let dateActuelle = new Date();
    let differenceTemps = dateDebutReservation.getTime() - dateActuelle.getTime();
    let differenceJours = differenceTemps / (1000 * 3600 * 24);

    if (differenceJours < 2) {
        annulerImpossiblePopUp();
        return; // Sortir de la fonction sans effectuer la requête
    }
    if (differenceJours < 14) {
        annulerComplexPopUp(numReservation); // Appeler la pop-up complexe avec le numéro de réservation
        return; // Sortir de la fonction sans effectuer la requête
    }

    annulerReservation(numReservation);
}

function annulerReservation(numReservation) {
    fetch("/src/php/reservation/supprimerResaDB.php?numReservation=" + encodeURIComponent(numReservation.toString()), {
        method: "GET",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
    })
    .then(function (response) {
        if (response.ok) {
            annulerSuccesPopUp(numReservation);
        } else {
            annulerErreurPopUp();
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
    Swal.fire({
      title: "Validation en cours...",
      text: "Veuillez patienter...",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
        setTimeout(() => {
          Swal.hideLoading();
          Swal.fire({
            title: "Paiement en cours...",
            text: "Vous allez être redirigé vers PayPal.",
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
              setTimeout(() => {
                Swal.hideLoading();
                // Redirection vers PayPal
                window.location.href = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=your_paypal_email&amount=100.00&currency_code=EUR&item_name=Reservation+Logement&return=https://www.votresite.com/confirmation/paiement";
              }, 1000);
            }
          });
        }, 1000);
      }
    });
  }
  


/**
 * Affiche le Pop-up pour confirmer la validation de la réservation
 */
function confirmationValiderPopUp() {
    Swal.fire({
      title: "Êtes-vous sûr de vouloir confirmer cette réservation ?",
      text: "Vous allez être redirigé vers la page de paiement.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Oui, je confirme",
      cancelButtonText: "Annuler",
      reverseButtons: true,
    }).then((result) => {
      if (result.value) {
        confirmerResa();
      }
    });
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
