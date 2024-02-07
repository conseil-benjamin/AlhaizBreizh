let charlie=Array.from(document.getElementsByClassName("logement"));

async function chargerLogements() {
    let rep =  await fetch("/src/php/chargerMesLogements.php?json=1")
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
    console.log(bal);
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
    let cont=document.getElementById("logements");
    cont.innerHTML="";

    liste.forEach(function (logement) {

        //C'est parti pour recreer toutes les etiquettes de logement
        let logementDiv = document.createElement('div');
        logementDiv.className = 'logement';

        let imageElement = document.createElement('img');
        imageElement.src = '/public/img/logements/' + logement[0] + '/1.png'; //num logement
        imageElement.alt="logement";
        logementDiv.appendChild(imageElement);

        let res = document.createElement('div');

        let titre = document.createElement('h3');
        titre.textContent=logement[2]; //titre
        res.appendChild(titre);

        let texte = document.createElement('p');
        texte.textContent = logement[3]; //description
        res.appendChild(texte);

        let loc = document.createElement('div');
        let loc_i = document.createElement('img');
        loc_i.src='/public/icons/map.svg';
        loc_t = document.createElement('p');
        loc_t.textContent=logement[4];
        loc.appendChild(loc_i);
        loc.appendChild(loc_t); //Ville
        res.appendChild(loc);

        let boutons = document.createElement('nav');

        let voir = document.createElement('a');
        voir.classList.add("boutton");
        voir.href="/src/php/logement/PageDetailLogement.php?numLogement="+logement[0];
        let pict = document.createElement('img');
        pict.src="/public/icons/type_logement.svg";
        pict.alt="voir";
        voir.appendChild(pict);
        voir.append("Voir");
        boutons.appendChild(voir);

        let modif = document.createElement('a');
        modif.classList.add("boutton");
        modif.href="/src/php/logement/modificationLogement.php?numLogement="+logement[0];
        let pictu = document.createElement('img');
        pictu.src="/public/icons/edit.svg";
        pictu.alt="editer";
        modif.appendChild(pictu);
        modif.append("Editer");
        boutons.appendChild(modif);

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
            filtre_nb(logement.innerHTML) &&
            filtre_max(logement.innerHTML) &&
            filtre_min(logement.innerHTML) &&
            filtre_recherche(logement.innerHTML) &&
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

//document.getElementById('side_nb').addEventListener('input',enfer);
//document.getElementById('side_max').addEventListener('input',enfer);
//document.getElementById('side_min').addEventListener('input',enfer);
//document.getElementById('side_recherche').addEventListener('input',enfer);
//document.getElementById('side_type').addEventListener('change',enfer);