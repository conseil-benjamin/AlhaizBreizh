let charlie=Array.from(document.getElementsByClassName("logement"));

async function chargerLogements() {
    let rep =  await fetch("/src/php/chargerLesResa.php?json=1")
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
            console.log(a,b);
            return a["numreservation"]-b["numreservation"];
        });
        trierLogements(logements);
    })
}

function unnum(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            console.log(a,b);
            return b["numreservation"]-a["numreservation"];
        });
        trierLogements(logements);
    })
}

function tarif(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return a["tarifnuitees"]-b["tarifnuitees"];
        });
        trierLogements(logements);
    })
}

function untarif(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return b["tarifnuitees"]-a["tarifnuitees"];
        });
        trierLogements(logements);
    })
}

function date(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return new Date(a["datedebut"])- new Date(b["datedebut"]);
        });
        trierLogements(logements);
    })
}

function undate(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return new Date(b["datefin"])- new Date(a["datefin"]);
        });
        trierLogements(logements);
    })
}

function trierLogements(liste) {
    let cont=document.getElementById("logements");
    cont.innerHTML="";

    liste.forEach(function (logement) {
        //C'est parti pour recreer toutes les etiquettes de logement
        let logementDiv = document.createElement('div');
        logementDiv.className = 'logement';
        let imageElement = document.createElement('img');
        imageElement.src = '/public/img/logements/' + logement["numlogement"] + '/1.png'; //num logement
        imageElement.alt="logement";
        imageElement.setAttribute("place",logement["ville"]);
        imageElement.setAttribute("data-information",logement["typelogement"]);
        logementDiv.appendChild(imageElement);

        let res = document.createElement('div');

        let titre = document.createElement('h2');
        titre.textContent=logement['libelle']; //titre
        res.appendChild(titre);

        let texte = document.createElement('h4');
        texte.textContent = logement["datedebut"]; //description
        res.appendChild(texte);

        loc_t = document.createElement('h4');
        loc_t.textContent=logement["datefin"];
        res.appendChild(loc_t);

        let pictprofil = document.createElement('a');
        pictprofil.href="/src/php/profil/profil.php?user="+logement["idclient"];
        pictprofil.id='prof';
        let profil = document.createElement('div');
        profil.className= 'profile';
        let imageProf = document.createElement('img');
        imageProf.src = '/public/img/photos_profil/' + logement["idclient"] + '.png';
        imageProf.alt='Photo de profil';
        nomProf = document.createElement('p');
        nomProf.textContent=logement["firstname"]+" "+logement["lastname"];
        profil.appendChild(imageProf);
        profil.appendChild(nomProf);
        pictprofil.appendChild(profil);

        res.appendChild(pictprofil);

        let boutons = document.createElement('nav');

        let supprimer = document.createElement('a');
        supprimer.classList.add("boutton");
        supprimer.href="/src/php/reservation/supprimerResaDB.php?numReservation="+logement["numreservation"];
        supprimer.append("Supprimer");
        boutons.appendChild(supprimer);

        let voir = document.createElement('a');
        voir.classList.add("boutton");
        voir.href="/src/php/reservation/details_reservation.php?numReservation="+logement["numreservation"];
        voir.append("Voir Réservation");
        boutons.appendChild(voir);

        let log = document.createElement('a');
        log.classList.add("boutton");
        log.href="/src/php/logement/PageDetailLogement.php?numLogement="+logement["numlogement"];
        log.append("Voir Logement");
        boutons.appendChild(log);

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
        if (dateAVerifier.getTime()==new Date(nb[0]).getTime()){
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
        if (dateAVerifier.getTime()==new Date(nb[1]).getTime()){
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