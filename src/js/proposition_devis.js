const uploadInputHTMLelement = document.getElementById("uploadInput")
const ficUploadNomHTMLelement = document.getElementById("ficUploadNom")
const ficUploadHTMLelement = document.getElementById("ficUpload")
const fileInputHTMLelement = document.getElementById("devis")
const devisLabelHTMLelement = document.getElementById("devisLabel")
const uploadHTMLelement = document.getElementById("upload")
const annulerFicUpload = document.getElementById("annulerFicUpload")

let uploaded = false

uploadHTMLelement.addEventListener("click", () => {
    if (!uploaded) {
        devisLabelHTMLelement.click()
    }
})

annulerFicUpload.addEventListener("click", function (e) {
    fileInputHTMLelement.value = ""
    uploadInputHTMLelement.style.display = "block"
    ficUploadHTMLelement.style.display = "none"
    uploaded = false
    uploadHTMLelement.style.cursor = "pointer"
    e.stopImmediatePropagation()
})

fileInputHTMLelement.addEventListener('change', function () {
    ficUploadNomHTMLelement.innerText = fileInputHTMLelement.files[0].name;
    uploadInputHTMLelement.style.display = "none"
    ficUploadHTMLelement.style.display = "block"
    uploaded = true
    uploadHTMLelement.style.cursor = "default"
});