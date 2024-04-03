//Lorsque l'utilisateur appuie sur la touche "Entrée", fermer la sidebar
document.addEventListener('keydown', function(event) {
    let sidebar = document.getElementById("sidebar");
    if (event.key === 'Escape' && sidebar && sidebar.style.left === "0px") {
        event.preventDefault();
        abime();
    }
});