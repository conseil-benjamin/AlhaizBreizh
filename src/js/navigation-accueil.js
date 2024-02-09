var bouttonPrecedent = document.getElementById('precedent');
var bouttonSuivant = document.getElementById('suivant');
var contenurLogements = document.getElementById('conteneur_logements');

//Récupérer les logements
var logements = contenurLogements.getElementsByClassName('logement');
var index = 0;

//Fonction pour afficher le logement suivant
function logementSuivant() {
    logements[index].style.display = 'none';
    index = (index + 1) % logements.length;
    logements[index].style.display = 'block';
}

//Fonction pour afficher le logement précédent
function logementPrecedent() {
    logements[index].style.display = 'none';
    index = (index - 1 + logements.length) % logements.length;
    logements[index].style.display = 'block';
}

//Ajouter les événements aux boutons
bouttonSuivant.addEventListener('click', logementSuivant);
bouttonPrecedent.addEventListener('click', logementPrecedent);