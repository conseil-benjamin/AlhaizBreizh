function trierLogements(liste) {
    console.log(liste);
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
    });
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