const btnAbo = document.getElementById("aboBtn")
const formAbo = document.getElementById("aboForm")


btnAbo.addEventListener("click",(e) => {
    e.preventDefault()
    Swal.fire({
        icon: 'info',
        title: "Êtes-vous sûr de vous abonnez selon ces paramètre ?",
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Non'
    }).then((result) => {
        if (result.isConfirmed) {
            formAbo.submit()
        }
    })
})