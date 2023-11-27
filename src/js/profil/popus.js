var currentUrl = window.location.href;
console.log(currentUrl); // Outputs the current URL

if (currentUrl.includes("save")) {
    Swal.fire({
        icon: 'success',
        title: 'Modifications enregistrées',
        showConfirmButton: false,
        timer: 1500
    });
} else if (currentUrl.includes("error")) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Une erreur est survenue !',
        showConfirmButton: true
    });
} else if (currentUrl.includes("invalidFile")){
    Swal.fire({
        icon: 'info',
        title: "L'extension du fichier n'est pas valide !",
        text: "Veuillez réessayer avec les extensions suivantes : 'png', 'jpg', 'jpeg'",
        showConfirmButton: true
    });
} else if (currentUrl.includes("invalidPassword")){
    Swal.fire({
        icon: 'info',
        title: "Le mot de passe actuel est incorrect !",
        text: "Veuillez réessayer",
        showConfirmButton: true
    });
}
