const uploadInputHTMLelement = document.getElementById("uploadInput")
const ficUploadNomHTMLelement = document.getElementById("ficUploadNom")
const ficUploadHTMLelement = document.getElementById("ficUpload")
const fileInputHTMLelement = document.getElementById("devis")
const devisLabelHTMLelement = document.getElementById("devisLabel")
const uploadHTMLelement = document.getElementById("upload")
const annulerFicUpload = document.getElementById("annulerFicUpload")

/**
 * Etat du bouton, par defaut normal
 * @type {boolean}
 */
let uploaded = false

/**
 * Permet de faire comme si la div était un bouton
 */
uploadHTMLelement.addEventListener("click", () => {
    if (!uploaded) {
        devisLabelHTMLelement.click()
    }
})


/**
 * Permet de réinitialiser le bouton d'upload
 */
annulerFicUpload.addEventListener("click", function (e) {
    fileInputHTMLelement.value = ""
    uploadInputHTMLelement.style.display = "block"
    ficUploadHTMLelement.style.display = "none"
    uploaded = false
    uploadHTMLelement.style.cursor = "pointer"
    e.stopImmediatePropagation()
})


/**
 * Permet de transformer le bouton vers son etat "uploader"
 */
fileInputHTMLelement.addEventListener('change', function () {
    ficUploadNomHTMLelement.innerText = fileInputHTMLelement.files[0].name;
    uploadInputHTMLelement.style.display = "none"
    ficUploadHTMLelement.style.display = "block"
    uploaded = true
    uploadHTMLelement.style.cursor = "default"
});


function notifErr() {
    swal({
        title: "Erreur",
        text: "Le serveur à rencontrer un erreur, réessayer plus tard",
        icon: "error",
        button : "Revenir à l\'acceuil"
    }).then(() => {
        window.location.href = "../../../index.php"}
    )
}

function notifSuccess() {
    swal({
        title: "Votre proposition de devis à bien été envoyer",
        text: "Le client va maintenant y répondre dans les plus bref délais",
        icon: "success",
        button : "Revenir à l\'acceuil"
    }).then(() => {
        window.location.href = "../../../index.php"}
    )
}