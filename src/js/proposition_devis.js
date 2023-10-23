const uploadInputHTMLelement = document.getElementById("uploadInput")
const ficUploadNomHTMLelement = document.getElementById("ficUploadNom")
const ficUploadHTMLelement = document.getElementById("ficUpload")
const fileInputHTMLelement = document.getElementById("devis")
const devisLabelHTMLelement = document.getElementById("devisLabel")
const uploadHTMLelement = document.getElementById("upload")

let uploaded = false

uploadHTMLelement.addEventListener("click", () => {
    if (!uploaded) {
        devisLabelHTMLelement.click()
    }
})

fileInputHTMLelement.addEventListener('change', function () {
    ficUploadNomHTMLelement.innerText = fileInputHTMLelement.files[0].name;
    uploadInputHTMLelement.style.display = "none"
    ficUploadHTMLelement.style.display = "block"
});