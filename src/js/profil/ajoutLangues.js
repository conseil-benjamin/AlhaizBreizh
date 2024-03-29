let langues=[]
let mech =document.getElementById('add_lg')
mech.addEventListener('click', function() {
    let contenu = document.getElementById('languesparlees').value
    const divElement = document.createElement('div');
    divElement.classList.add('inputplusLangue');

    const iconSupprimer = document.createElement("img");
    iconSupprimer.src = "/public/icons/supprimer.svg";
    iconSupprimer.alt = "Icone supprimer";
    iconSupprimer.id = "iconSupprimer";
    iconSupprimer.addEventListener('click', function() {
        divElement.parentNode.removeChild(divElement);
    });

    divElement.textContent = contenu;
    divElement.appendChild(iconSupprimer);

    this.parentNode.appendChild(divElement);
})

