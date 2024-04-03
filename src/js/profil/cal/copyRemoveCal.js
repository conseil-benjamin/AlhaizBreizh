const tabLi = document.querySelectorAll("#info > ul > li")
const rmForm = document.getElementById("rmForm")
const rmFormInput = document.getElementById("rmFormInput")

tabLi.forEach(
    (li) => {
        li.addEventListener("click",() => {
            copieSuppClef(li.textContent)
        })
    }
)

function copieSuppClef(clef ) {
    Swal.fire({
        icon: 'info',
        title: "Copier l'url ou supprimer ce Token ?",
        showCancelButton: true,
        confirmButtonText: 'Copier',
        cancelButtonText: 'Annuler',
        showDenyButton: true,
        denyButtonText: 'Supprimer',
    }).then((result) => {
        if (result.isConfirmed) {
            // Copie de la clé
            navigator.clipboard.writeText(window.location.host + "/src/php/icalator.php?token=" + clef);
            Swal.fire({
                icon: 'success',
                title: "l'url d'abonnement a été copiée !",
                showConfirmButton: false,
                timer: 1500
            });
        } else if (result.isDenied) {
            Swal.fire({
                icon: 'warning',
                title: "Êtes-vous sûr de vouloir supprimer cette clé API ?",
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non',
                confirmButtonColor: '#dc3741',

            }).then((deleteResult) => {
                if (deleteResult.isConfirmed) {
                    rmFormInput.setAttribute('value', clef);
                    rmForm.submit()
                }
            })
        }
    })
}
