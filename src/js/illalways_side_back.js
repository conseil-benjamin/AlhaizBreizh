let charlie=Array.from(document.getElementsByClassName("logement"));

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

    liste.forEach(function (logement) {

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

        let pictprofil = document.createElement('a');
        pictprofil.href="/src/php/profil/profil.php?user="+logement[6];
        pictprofil.id='prof';
        let profil = document.createElement('div');
        profil.className= 'profile';
        let imageProf = document.createElement('img');
        imageProf.src = '/public/img/photos_profil/' + logement[6] + '.png';
        imageProf.alt='Photo de profil';
        nomProf = document.createElement('p');
        nomProf.textContent=logement[8]+" "+logement[9];
        profil.appendChild(imageProf);
        profil.appendChild(nomProf);
        pictprofil.appendChild(profil);

        res.appendChild(pictprofil);

        let boutons = document.createElement('nav');

        let voir = document.createElement('a');
        voir.classList.add("boutton");
        voir.href="/src/php/reservation/details_reservation.php?numReservation="+logement[7];
        voir.append("Voir Réservation");
        boutons.appendChild(voir);

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
        sidebar.style.left = "-22em";
        replier = !replier
    }
}

document.getElementById('menu-btn').addEventListener('click', function () {
    let sidebar = document.getElementById('sidebar');
    if (replier) {
      sidebar.style.left = "0";
      replier = !replier
    } else {
        sidebar.style.left = "-22em";
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