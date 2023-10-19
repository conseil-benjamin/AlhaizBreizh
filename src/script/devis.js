const inputDateDepart = document.getElementById('depart')
const inputDateArivee = document.getElementById('arrivee')
const services = document.getElementsByClassName('supplement')
const prixTotal = document.getElementById('prixTotal')
const prixHTMLelem = document.getElementById('prixSpan')
const prix = parseInt(prixHTMLelem.innerText,10)

const today = new Date();
// Format the date as YYYY-MM-DD
const yyyy = today.getFullYear();
const mm = (today.getMonth() + 1).toString().padStart(2, '0');
const dd = today.getDate().toString().padStart(2, '0');
const formattedDate = yyyy + '-' + mm + '-' + dd;

inputDateArivee.min = formattedDate
inputDateArivee.default = formattedDate


inputDateDepart.min = formattedDate
inputDateDepart.default = formattedDate


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

function compteJour(dateDepart,dateArriver) {
    const dateArr = new Date(dateArriver);
    const dateDep = new Date(dateDepart);

    const differenceEnMillisecondes =  dateDep-dateArr;
    return (differenceEnMillisecondes / (1000 * 60 * 60 * 24));
}

function updatePrix(dateArriver,dateDepart) {
    const nbjour = compteJour(dateArriver,dateDepart)
    prixTotal.innerHTML =  ((nbjour*prix)+getTotalService()).toFixed(2).toString().replace('.', ',')
}

function getTotalService() {
    let total = 0.0
    for (const service of services) {
        if (service.children[0].checked) {
            total += parseFloat(service.children[2].children[0].innerHTML.replace(',', '.'))
        }
    }
    return total
}
