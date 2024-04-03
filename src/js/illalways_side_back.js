let charlie=Array.from(document.getElementsByClassName("logement"));

async function chargerAvis(nb01,nb02) {
    let rep =  await fetch('/src/php/chargerAvisLogement.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            json: 1,
            idcl: nb01,
            idlog: nb02
        })
    });
    let dataJson = await rep.json();
    return dataJson; // je vais me foutre en l air si ca marche pas
} 



async function chargerLogements() {
    let rep =  await fetch("/src/php/chargerResa.php?json=1")
    let dataJson = await rep.json()
    return dataJson
}

function switchClass(event){
    boutons=Array.from(document.getElementsByClassName("item_tri"));
    boutons.forEach(function(em){
        em.classList.remove("select")
    })
    let bal=event.target;
    if (bal){
        bal.classList.add("select")
    }
}

function num(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return b[6]-a[6];
        });
        trierLogements(logements);
    })
}

function unnum(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return b[6]-a[6];
        });
        trierLogements(logements.reverse());
    })
}

function tarif(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return b[10]-a[10];
        });
        trierLogements(logements);
    })
}

function untarif(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return a[10]-b[10];
        });
        trierLogements(logements);
    })
}

function date(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return new Date(a[2])- new Date(b[2]);
        });
        trierLogements(logements);
    })
}

function undate(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return new Date(b[3])- new Date(a[3]);
        });
        trierLogements(logements);
    })
}

function trierLogements(liste) {
    let cont=document.getElementById("logements");
    cont.innerHTML="";

    liste.forEach(async function (logement) {
        //C'est parti pour recreer toutes les etiquettes de logement
        let logementDiv = document.createElement('div');
        logementDiv.className = 'logement';

        let imageElement = document.createElement('img');
        imageElement.src = '/public/img/logements/' + logement[0] + '/1.png'; //num logement
        imageElement.alt="logement";
        imageElement.setAttribute("place",logement[11]);
        imageElement.setAttribute("data-information",logement[12]);
        logementDiv.appendChild(imageElement);

        let res = document.createElement('div');

        let titre = document.createElement('h2');
        titre.textContent=logement[1]; //titre
        res.appendChild(titre);

        let aa = document.createElement('a');
        aa.href="/src/php/afficherPlageDispo.php?dateDebut=2023-11-01&dateFin=2023-11-07";

        let texte = document.createElement('h4');
        texte.textContent = logement[2]; //description
        aa.appendChild(texte);

        loc_t = document.createElement('h4');
        loc_t.textContent=logement[3];
        aa.appendChild(loc_t);

        res.appendChild(aa);

        // let pictprofil = document.createElement('a');
        // pictprofil.href="/src/php/profil/profil.php?user="+logement[6];
        // pictprofil.id='prof';
        // let profil = document.createElement('div');
        // profil.className= 'profile';
        // let imageProf = document.createElement('img');
        // imageProf.src = '/public/img/photos_profil/' + logement[6] + '.png';
        // imageProf.alt='Photo de profil';
        // nomProf = document.createElement('p');
        // nomProf.textContent=logement[8]+" "+logement[9];
        // profil.appendChild(imageProf);
        // profil.appendChild(nomProf);
        // pictprofil.appendChild(profil);

        // res.appendChild(pictprofil);

        let boutons = document.createElement('nav');
        boutons.style="display: flex; align-items: center;";

        let voir = document.createElement('a');
        voir.classList.add("boutton");
        voir.href="/src/php/reservation/details_reservation.php?numReservation="+logement[7];
        voir.append("Voir Réservation");
        boutons.appendChild(voir);


        var etatElement = document.createElement('h4');

        etatReservation=logement[13];
        etatElement.classList.add('etat-reservation');

        // Définition du contenu en fonction de la valeur de $etatReservation
        if (etatReservation === "En attente de validation") {
            etatElement.textContent = etatReservation;
        } else if (etatReservation === "Annulée") {
            // Création d'un conteneur pour l'état "Annulée"
            var annuleContainer = document.createElement('div');
            annuleContainer.classList.add('etat-annule');

            // Ajout de l'icône "fa-ban"
            var banIcon = document.createElement('i');
            banIcon.classList.add('fas', 'fa-ban');
            annuleContainer.appendChild(banIcon);

            // Ajout de l'état "Annulée"
            var annuleText = document.createElement('h4');
            annuleText.textContent = etatReservation;
            annuleContainer.appendChild(annuleText);

            // Ajout du conteneur au document
            aa.appendChild(annuleContainer);
        } else if (etatReservation === "Confirmée") {
            // Création d'un conteneur pour l'état "Validée"
            var valideContainer = document.createElement('div');
            valideContainer.style="display: flex; align-items: center; background-color: #DCF5D3; width: 6em; border-radius: 5px; padding: 0.3em; margin: 0.3em 0 0 0";

            // Ajout de l'icône "fa-check"
            var checkIcon = document.createElement('i');
            checkIcon.classList.add('fas', 'fa-check');
            valideContainer.appendChild(checkIcon);

            // Ajout de l'état "Validée"
            var valideText = document.createElement('h4');
            valideText.textContent = etatReservation;
            valideText.style="margin: 0 0 0 0.5em;";
            valideContainer.appendChild(valideText);

            // Ajout du conteneur au document
            aa.appendChild(valideContainer);
        }
        else if (etatReservation === "Acceptée") {
            // Création d'un conteneur pour l'état "Validée"
            var valideContainer = document.createElement('div');
            valideContainer.style="display: flex; align-items: center; width: 6em; border-radius: 5px; padding: 0.3em; margin: 0.3em 0 0 0";

            // Ajout de l'état "Validée"
            var valideText = document.createElement('h4');
            valideText.textContent = etatReservation;
            valideText.style="margin: 0 0 0 0.5em;";
            valideContainer.appendChild(valideText);

            // Ajout du conteneur au document
            aa.appendChild(valideContainer);
        }
        var dateActuelle = new Date();
        var dateFinReservation = new Date(logement[3]);
        console.log(dateFinReservation+dateActuelle)
        if(logement[3]<dateActuelle){

        }

        if (dateFinReservation < dateActuelle) {
            var lienSupprimer = document.createElement('a');
            lienSupprimer.classList.add('boutton');
            lienSupprimer.setAttribute('href', '/src/php/reservation/supprimerResaDB.php?numReservation=' + logement[7]);
            lienSupprimer.textContent = 'Supprimer';
            boutons.appendChild(lienSupprimer);
        }

        result= await chargerAvis(logement[6],logement[0]);

        if (result){
            let nav = document.createElement('nav');
            nav.style="display: flex; justify-content: center; align-items: center; margin: 0 0 1em 1em;";
            let i = document.createElement('i');
            i.className='fas fa-check';
            let span = document.createElement('span');
            span.style="margin: 0.5em;";
            span.innerHTML = "Avis posté";
            nav.appendChild(i);
            nav.appendChild(span);
            boutons.appendChild(nav);
        }

        res.appendChild(boutons);
        logementDiv.appendChild(res);

        cont.appendChild(logementDiv);
        charlie=Array.from(document.getElementsByClassName("logement"));
    });
    enfer();
}

//Application des filtres
async function enfer() {

    charlie.forEach(logement => {
        if (
            filtre_recherche(logement.innerHTML)&&
            filtre_sej_deb(logement.innerHTML)&&
            filtre_sej_dep(logement.innerHTML)&&
            filtre_ville(logement.innerHTML)&&
            filtre_type(logement.innerHTML)
        ) {
            logement.style.display = "flex";
        } else {
            logement.style.display = "none";
        }
      });
      
    }

//Gestion de l'apprition du sidemenu

let replier = true

function abime() { //Degage le sidemenu sur la gauche
    let sidebar = document.getElementById('sidebar');
    if (sidebar.style.left === "0px") {
        sidebar.style.left = "-30em";
        replier = !replier
    }
}

document.getElementById('menu-btn').addEventListener('click', function () {
    let sidebar = document.getElementById('sidebar');
    if (replier) {
      sidebar.style.left = "0";
      replier = !replier
    } else {
        sidebar.style.left = "-30em";
        replier = !replier
    }
  });

document.body.addEventListener('click', function (event) {
    let sidebar = document.getElementById('sidebar');
    let menuBtn = document.getElementById('menu-btn');

    if (event.target !== sidebar && !sidebar.contains(event.target) && event.target !== menuBtn) {
        abime();
    }
});

function filtre_ville(contenu) {
    let doc = document.getElementById('side_ville');
    let filtre = doc.value.toLowerCase();
    let nb = contenu.match(/place="(.*?)"/);
    if (filtre!== ""){
        if (nb[1].toLowerCase()==filtre){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return true;
    }
}

function filtre_recherche(contenu) {
    let doc = document.getElementById('side_recherche');
    let filtre = doc.value.toLowerCase();
    let nb = contenu.match(/<h2>([\s\S]*?)<\/h2>/);
    if (nb[1].toLowerCase().includes(filtre)){
        return true;
    }
    else{
        return false;
    }
};

function filtre_type(contenu){
    let doc = document.getElementById('side_type');
    let filtre = doc.value.toLowerCase();
    let nb = contenu.match(/data-information="(.*?)"/);
    if (filtre!== ""){
        if (nb[1].toLowerCase()==filtre){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return true;
    }
}


function filtre_sej_deb(contenu){
    let doc = document.getElementById('side_arrive');
    let nb = contenu.match(/\d{4}-\d{2}-\d{2}/g);
    if (doc.value!== ""){
        let dateAVerifier = new Date(doc.value);
        if (dateAVerifier.getTime()==new Date(nb[2]).getTime()){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return true;
    }
}

function filtre_sej_dep(contenu){
    let doc = document.getElementById('side_depart');
    let nb = contenu.match(/\d{4}-\d{2}-\d{2}/g);
    if (doc.value!== ""){
        let dateAVerifier = new Date(doc.value);
        if (dateAVerifier.getTime()==new Date(nb[3]).getTime()){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return true;
    }
}

const arrivee = document.getElementById("side_arrive");
const depart = document.getElementById("side_depart");
src="https://cdn.jsdelivr.net/npm/sweetalert2@11";

depart.addEventListener("change", () => {
  const dateArrivee = new Date(arrivee.value);
  const dateDepart = new Date(depart.value);

  if (dateArrivee > dateDepart) {
    depart.value = ""; // Remet la date d'arrivée à vide si elle dépasse la date de départ
    Swal.fire({
        icon: "error",
        title: "La date d'arrivée doit être antérieure à la date de départ.",
        showConfirmButton: true,
        timer: 3000
    }); 
  }
});

arrivee.addEventListener("change", () => {
  const dateArrivee = new Date(arrivee.value);
  const dateDepart = new Date(depart.value);

  if (dateArrivee > dateDepart) {
    arrivee.value = ""; // Remet la date d'arrivée à vide si elle dépasse la date de départ
    Swal.fire({
        icon: "error",
        title: "La date d'arrivée doit être antérieure à la date de départ.",
        showConfirmButton: true,
        timer: 3000
    });
  }
});

document.getElementById('side_recherche').addEventListener('input',enfer);
document.getElementById('side_type').addEventListener('change',enfer);
document.getElementById('side_arrive').addEventListener('change',enfer);
document.getElementById('side_depart').addEventListener('change',enfer);
document.getElementById('side_ville').addEventListener('change',enfer);