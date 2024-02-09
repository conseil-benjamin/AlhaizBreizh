let charlie=Array.from(document.getElementsByClassName("logement"));

async function chargerLogements() {
    let rep =  await fetch("/src/php/chargerLogements.php?json=1")
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
            return b[1]-a[1];
        });
        trierLogements(logements);
    })
}

function unnum(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return b[1]-a[1];
        });
        trierLogements(logements.reverse());
    })
}

function tarif(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            console.log(a);
            return b[3]-a[3];
        });
        trierLogements(logements);
    })
}

function untarif(event){
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value
        logements.sort(function(a, b) {
            return a[3]-b[3];
        });
        trierLogements(logements);
    })
}

function notes(event){ // A TESTER
    switchClass(event);
    chargerLogements().then((value) => {
        logements=value;
        l_logement=[];
        for (let i = 0; i < logements.length; i++) {
            let logement = logements[i];
            l_logement.push(logement);
            if (logement[6] === null || logement[6] === '') {
                logement[6] = 2.5;
            }
        }
        l_logement.sort(function(a, b) {
            return a[6] < b[6] ? -1 : (a[6] > b[6] ? 1 : 0);
        });
        trierLogements(l_logement.reverse());
    })
}

function trierLogements(liste) {
    let cont=document.getElementById("contenur_logements");
    cont.innerHTML="";

    liste.forEach(function (logement) {

        //C'est parti pour recreer toutes les etiquettes de logement
        let logementDiv = document.createElement('div');
        logementDiv.className = 'logement';

        let imageLink = document.createElement('a');
        imageLink.href = '/src/php/logement/PageDetailLogement.php?numLogement=' + logement[0];
        let imageElement = document.createElement('img');
        imageElement.src = '/public/img/logements/' + logement[0] + '/1.png'; //num logement
        imageLink.appendChild(imageElement);
        logementDiv.appendChild(imageLink);

        let divType = document.createElement('div');
        divType.setAttribute("data-information",logement[11]);
        let boutlike = document.createElement('button');
        let determination = document.createElement('img');
        determination.src='/public/icons/heart_white.svg';
        boutlike.appendChild(determination);
        //let boutnote = document.createElement('div');
        //boutnote.id="rating";
        //let star = document.createElement('img');
        //star.src='/public/icons/star_fill.svg';
        divType.appendChild(boutlike);
        logementDiv.appendChild(divType);

        let barInfo = document.createElement('a');
        barInfo.id="description";
        barInfo.href="/src/php/logement/PageDetailLogement.php?numLogement=1";
        let res = document.createElement('div');
        res.id="resultat";

        let titre = document.createElement('h3');
        titre.textContent=logement[1]; //titre
        res.appendChild(titre);

        let pers = document.createElement('div');
        let pers_i = document.createElement('img');
        pers_i.src='/public/icons/nb_personnes.svg';
        let pers_t = document.createElement('p');
        pers_t.textContent = logement[2] + " personnes"; //nb de personnes
        pers.appendChild(pers_i);
        pers.appendChild(pers_t);
        res.appendChild(pers);

        let loc = document.createElement('div');
        let loc_i = document.createElement('img');
        loc_i.src='/public/icons/map.svg';
        let loc_t = document.createElement('p');
        loc_t.textContent = logement[5]; //nb de personnes
        loc.appendChild(loc_i);
        loc.appendChild(loc_t);
        res.appendChild(loc);

        let prix = document.createElement('div');
        let prix_t = document.createElement('p');
        let price = document.createElement('strong');
        price.textContent=logement[3]+"€";
        prix_t.appendChild(price);
        prix_t.appendChild(document.createTextNode(" /nuits"));
        prix.appendChild(prix_t);
        res.appendChild(prix);

        barInfo.appendChild(res);
        logementDiv.appendChild(barInfo);

        cont.appendChild(logementDiv);
        charlie=Array.from(document.getElementsByClassName("logement"));
    });
    enfer();
}

//Application des filtres
async function enfer() {
    const promises = [];

    for (let cle in charlie) {
        promises.push(interrogerBDD(cle)); 
        promises.push(dateBDD(cle));
    }

    try {
        const results = await Promise.all(promises);
        for (let i = 0; i < results.length; i += 2) {
            const result1 = results[i];
            const result2 = results[i + 1];
            const cle = i / 2;

            if (
                filtre_nb(charlie[cle].innerHTML) &&
                filtre_max(charlie[cle].innerHTML) &&
                filtre_min(charlie[cle].innerHTML) &&
                filtre_recherche(charlie[cle].innerHTML) &&
                filtre_type(charlie[cle].innerHTML) &&
                filtre_map(charlie[cle])&&
                result1 &&
                result2
            ) {
                charlie[cle].style.display = "flex";
            } else {
                charlie[cle].style.display = "none";
            }
        }
    } catch (prob) {
        console.error("Ca marche pas", prob);
    }
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


function filtre_nb(contenu) {
    let doc = document.getElementById('side_nb');
    let filtre = doc.value.toLowerCase();
    let nb = contenu.match(/\d+/g);
    if (parseInt(nb[nb.length-2])<parseInt(filtre)){
        return false;
    }
    else{
        return true;
    }
};

function filtre_max(contenu) {
    let doc = document.getElementById('side_max');
    let filtre = doc.value.toLowerCase();
    let nb = contenu.match(/\d+/g);
    if (parseInt(nb[nb.length-1])>parseInt(filtre)){
        return false;
    }
    else{
        return true;
    }
};

function filtre_min(contenu) {
    let doc = document.getElementById('side_min');
    let filtre = doc.value.toLowerCase();
    let nb = contenu.match(/\d+/g);
    if (parseInt(nb[nb.length-1])<parseInt(filtre)){
        return false;
    }
    else{
        return true;
    }
};

function filtre_recherche(contenu) {
    let doc = document.getElementById('side_recherche');
    let filtre = doc.value.toLowerCase();
    let nb = contenu.match(/<h3>([\s\S]*?)<\/h3>/);
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

function filtre_map(contenu){
    if (contenu.classList.contains('filtremap')){
        return false;
    }
    else{
        return true;
    }
}

function interrogerBDD(num) {
    return new Promise((resolve, reject) => {
        let doc = document.getElementById('side_arrive');
        if (doc.value !== "") {
            let dateAVerifier = new Date(doc.value);
            // Créer un objet XMLHttpRequest
            let xhr = new XMLHttpRequest();

            // Configurer la requête
            xhr.open("GET", "src/php/search_date.php?num=" + num, true);

            // Définir la fonction de rappel pour gérer la réponse
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    let channel = xhr.responseText.match(/\d{4}-\d{2}-\d{2}/g);
                    let tab = channel || [];
                    let flag = false;

                    if (xhr.responseText !== "") {
                        for (let i = 0; i < tab.length; i += 2) {
                            let dateDebut = new Date(tab[i]);
                            let dateFin = new Date(tab[i + 1]);
                            if (dateAVerifier >= dateDebut && dateAVerifier <= dateFin) {
                                flag = true;
                                break;
                            }
                        }
                    } else {
                        flag=true;
                    }

                    resolve(flag);
                }
            };

            // Envoyer la requête
            xhr.send();
        } else {
            // Si doc.value est vide, résoudre avec true
            resolve(true);
        }
    });
}

function dateBDD(num) {
    return new Promise((resolve, reject) => {
        let doc = document.getElementById('side_depart');
        if (doc.value !== "") {
            let dateAVerifier = new Date(doc.value);
            // Créer un objet XMLHttpRequest
            let xhr = new XMLHttpRequest();

            // Configurer le truc
            xhr.open("GET", "src/php/search_date.php?num=" + num, true);

            // Définir la fonction de rappel pour gérer la réponse
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    let channel = xhr.responseText.match(/\d{4}-\d{2}-\d{2}/g);
                    let tab = channel || [];
                    let flag = false;

                    if (xhr.responseText !== "") {
                        for (let i = 0; i < tab.length; i += 2) {
                            let dateDebut = new Date(tab[i]);
                            let dateFin = new Date(tab[i + 1]);
                            if (dateAVerifier >= dateDebut && dateAVerifier <= dateFin) {
                                flag = true;
                                break;
                            }
                        }
                    } else {
                        flag=true;
                    }

                    resolve(flag);
                }
            };

            // Envoyer la requête
            xhr.send();
        } else {
            // Si doc.value est vide, résoudre avec true
            resolve(true);
        }
    });
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

document.getElementById('side_nb').addEventListener('input',enfer);
document.getElementById('side_max').addEventListener('input',enfer);
document.getElementById('side_min').addEventListener('input',enfer);
document.getElementById('side_recherche').addEventListener('input',enfer);
document.getElementById('side_type').addEventListener('change',enfer);
document.getElementById('side_arrive').addEventListener('change',enfer);
document.getElementById('side_depart').addEventListener('change',enfer);