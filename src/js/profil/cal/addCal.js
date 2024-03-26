const btnAbo = document.getElementById("aboBtn")
const formAbo = document.getElementById("aboForm")


btnAbo.addEventListener("click",(e) => {
    e.preventDefault()

    swal({
        title: "Abonnement Enregistré",
        text: "Vous retrouverez votre lien d'abonnement sur cette page",
        icon: "success",
        button: "Compris !",
    });
})