const inputDateDepart = document.getElementById('depart')
const inputDateArivee = document.getElementById('arrivee')
const services = document.getElementsByClassName('supplement')
const prixTotal = document.getElementById('prixTotal')
const prixHTMLelement = document.getElementById('prixSpan')
const nbpersonne = document.getElementById('nbpersonne')
const nbNuitHTMLelement = document.getElementById('nbNuit')
const PRIX = parseInt(prixHTMLelement.innerText,10)
const NBNUIT = parseInt(nbNuitHTMLelement.innerText, 10)

function nbJourDansLeMois(annee, mois) {
    const dernierJourDuMois = new Date(annee, mois, 0);
    return dernierJourDuMois.getDate();
}


const DATE_MIN = new Date();

function formaterDate(decalage) {
    const YYYY = DATE_MIN.getFullYear();
    const MOIS_DATE = (DATE_MIN.getMonth() + 1).toString().padStart(2, '0');
    const JOUR = DATE_MIN.getDate() + decalage;
    console.log(JOUR)
    const DECALAGEMOIS = 1 + Math.floor(JOUR / nbJourDansLeMois(YYYY, MOIS_DATE))
    const MM = (DATE_MIN.getMonth() + DECALAGEMOIS).toString().padStart(2, '0');
    console.log(decalage)
    const DD = (JOUR % nbJourDansLeMois(YYYY, MM)).toString().padStart(2, '0');
    return YYYY + '-' + MM + '-' + DD;
}

const DATEFORMAT_DEP = formaterDate(5);
const DATEFORMAT_ARR = formaterDate(NBNUIT + 5)

nbpersonne.addEventListener('change',()=> {
    if (this.value > this.max) {
        this.value = this.max
    }
})

inputDateArivee.min = DATEFORMAT_DEP
inputDateArivee.value = DATEFORMAT_DEP

inputDateDepart.min = DATEFORMAT_ARR
inputDateDepart.value = DATEFORMAT_ARR


for (const service of services) {
    service.children[0].addEventListener("change",function () {
        updatePrix(inputDateDepart.value,inputDateArivee.value);
    })
}

inputDateDepart.addEventListener("change", function () {
    const dateDepart = this.value;
    const dateArrivee = inputDateArivee.value
    updatePrix(dateDepart,dateArrivee)
});

inputDateArivee.addEventListener("change", function () {
    const dateArrivee = this.value;
    const dateDepart = inputDateDepart.value
    updatePrix(dateDepart,dateArrivee)

});

/**
 *  Compte le nombre de jours du séjour
 * @param dateDepart la date de départ
 * @param dateArriver la date d'arriver
 * @returns {number} le nombre de jours entre les deux dates
 */
function compteJour(dateDepart,dateArriver) {
    const dateArr = new Date(dateArriver);
    const dateDep = new Date(dateDepart);

    const differenceEnMillisecondes =  dateDep-dateArr;
    return (differenceEnMillisecondes / (1000 * 60 * 60 * 24));
}

/**
 * Met à jour le prix total estimé
 * @param dateArriver
 * @param dateDepart
 */
function updatePrix(dateArriver,dateDepart) {
    const nbjour = compteJour(dateArriver,dateDepart)
    prixTotal.innerHTML =  ((nbjour*PRIX)+getTotalService()).toFixed(2).toString().replace('.', ',')
}

/**
 * Revoie le prix total de tous les services seléctionnés
 * @returns {number}
 */
function getTotalService() {
    let total = 0.0
    for (const service of services) {
        if (service.children[0].checked) {
            total += parseFloat(service.children[2].children[0].innerHTML.replace(',', '.'))
        }
    }
    return total
}
