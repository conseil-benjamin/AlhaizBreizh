const inputDateDepart = document.getElementById('depart')
const inputDateArivee = document.getElementById('arrivee')
const services = document.getElementsByClassName('supplement')
const prixTotal = document.getElementById('prixTotal')
const prixHTMLelement = document.getElementById('prixSpan')
const nbpersonne = document.getElementById('nbpersonne')
const nbNuitHTMLelement = document.getElementById('nbNuit')
const prixTotalHTMLelement = document.getElementById("prixTotalInput")
const btnEnvoyerHTMLelement = document.getElementById("envoyer")
const formHTMLelement = document.getElementById("form")

const PRIX = parseFloat(prixHTMLelement.innerText.replace(",", "."))
const NBNUIT = parseInt(nbNuitHTMLelement.innerText, 10)
const AUJ = new Date();
const DATEFORMAT_ARR = formaterDate(AUJ,1);
const DATEFORMAT_DEP = formaterDate(AUJ,NBNUIT + 6)

inputDateArivee.min = DATEFORMAT_ARR
inputDateArivee.value = DATEFORMAT_ARR

inputDateDepart.min = DATEFORMAT_DEP
inputDateDepart.value = DATEFORMAT_DEP

function nbJourDansLeMois(annee, mois) {
    const dernierJourDuMois = new Date(annee, mois, 0)
    return dernierJourDuMois.getDate()
}


/**
 * Permet de formater une date afin de l'utiliser comme valeur par default pour un input date
 * @param date la date à partir de laquelle faire le décalage
 * @param decalage le nombre de jours de decalage avec la date d'aujourd'hui
 * @returns {string} la date formatter
 */
function formaterDate(date,decalage) {
    const fullYear = date.getFullYear();
    const MOIS_DATE = (date.getMonth() + 1).toString().padStart(2, '0');
    const JOUR = date.getDate() + decalage;
    const DECALAGEMOIS = 1 + Math.floor(JOUR / nbJourDansLeMois(fullYear, MOIS_DATE))
    const MM = (date.getMonth() + DECALAGEMOIS).toString().padStart(2, '0');
    const DD = (JOUR % nbJourDansLeMois(fullYear, MOIS_DATE)).toString().padStart(2, '0');
    return fullYear + '-' + MM + '-' + DD;
}

btnEnvoyerHTMLelement.addEventListener("click", () => {
    swal({
        title: "Êtes-vous sur ?",
        text: "Cela enverras une demande de devis au propriétaire",
        icon: "warning",
        buttons: [
            'No, cancel it!',
            'Yes, I am sure!'
        ],
        dangerMode: true,
    }).then(function(isConfirm) {
        if (isConfirm) {
            formHTMLelement.submit()
        }
    })

})

nbpersonne.addEventListener('change',()=> {
    if (this.value > this.max) {
        this.value = this.max
    }
})

/**
 * Réagir au changement de la date de départ
 */
inputDateDepart.addEventListener("change", function () {
    const dateDepart = this.value;
    const dateArrivee = inputDateArivee.value
    updatePrix(dateDepart,dateArrivee)
    updateNBNuit()
});

/**
 * Réagir au changement de la date d'arrivée
 */
inputDateArivee.addEventListener("change", function () {
    const dateArrivee = this.value;
    let dateDepart = inputDateDepart.value
    if (dateArrivee >= dateDepart) {
        inputDateDepart.value = formaterDate(new Date(dateArrivee),1);
        dateDepart = inputDateDepart.value;
    }
    updatePrix(dateDepart,dateArrivee)
    updateNBNuit()
});

/**
 * Pour réagir au changement d'état de chaque service
 */
for (const service of services) {
    // children[0] : la checkbox
    service.children[0].addEventListener("change",function () {
        updatePrix(inputDateDepart.value,inputDateArivee.value);
    })
}

/**
 * Permet de mettre à jour le nombre de nuits
 */
function updateNBNuit() {
    const dateInput1 = new Date(inputDateArivee.value);
    const dateInput2 = new Date(inputDateDepart.value);
    const timeDifference = Math.abs(dateInput1 - dateInput2);
    const daysDifference = timeDifference / (1000 * 3600 * 24);
    nbNuitHTMLelement.innerText = daysDifference.toString();
}

/**
 *  Compte le nombre de jours du séjour
 * @param dateDepart la date de départ
 * @param dateArriver la date d'arriver
 * @returns {number} le nombre de jours entre les deux dates
 */
function compteJour(dateDepart,dateArriver) {
    const dateArr = new Date(Date.parse(dateArriver));
    const dateDep = new Date(Date.parse(dateDepart));
    const differenceEnMillisecondes =  (dateDep-dateArr)-1;
    return Math.ceil((differenceEnMillisecondes / (1000 * 60 * 60 * 24)));
}

/**
 * Met à jour le prix total estimé
 * @param dateArriver
 * @param dateDepart
 */
function updatePrix(dateArriver,dateDepart) {
    const nbjour = compteJour(dateArriver,dateDepart)
    let prix
    if (nbjour === 0) {
        prix = 0
        prixTotal.innerText = "0,00"
        prixTotalHTMLelement.value = prix
    } else {
        prix = ((nbjour * PRIX) + getTotalService()).toFixed(2)
        prixTotal.innerText = prix.toString().replace('.', ',')
        prixTotalHTMLelement.value = prix
    }
}

/**
 * Revoie le prix total de tous les services sélectionnés
 * @returns {number}
 */
function getTotalService() {
    let total = 0.0
    for (const service of services) {
        if (service.children[0].checked) {
            // children[2] le paragraphe du prix , children[2].children[0] : le span conteant le prix sans "€"
            total += parseFloat(service.children[2].children[0].innerHTML.replace(',', '.'))
        }
    }
    return total
}

/**
 * Permet d'afficher la notification de succès
 */
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

/**
 * Permet d'afficher la notification de succès
 */
function notifSuccess() {
    swal({
        title: "Votre demande de devis à bien été envoyer",
        text: "Le propriétaire va maintenant y répondre dans les plus bref délais",
        icon: "success",
        button : "Revenir à l\'acceuil"
    }).then(() => {
        window.location.href = "../../../index.php"}
    )
}

updatePrix(inputDateDepart.value,inputDateArivee.value)
