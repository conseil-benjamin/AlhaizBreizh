let charlie=Array.from(document.getElementsByClassName("logement"));

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
                result1 &&
                result2
            ) {
                charlie[cle].style.display = "block";
            } else {
                charlie[cle].style.display = "none";
            }
        }
    } catch (prob) {
        console.error("Ca marche pas", prob);
    }
}

document.getElementById('menu-btn').addEventListener('click', function () {
    let sidebar = document.getElementById('sidebar');
    if (sidebar.style.left === "-22em") {
      sidebar.style.left = "0";
    } else {
      sidebar.style.left = "-22em";
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

document.getElementById('side_nb').addEventListener('input',enfer);
document.getElementById('side_max').addEventListener('input',enfer);
document.getElementById('side_min').addEventListener('input',enfer);
document.getElementById('side_recherche').addEventListener('input',enfer);
document.getElementById('side_type').addEventListener('change',enfer);
document.getElementById('side_arrive').addEventListener('input',enfer);
document.getElementById('side_depart').addEventListener('input',enfer);