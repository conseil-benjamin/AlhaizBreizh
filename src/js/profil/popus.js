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
}
