let charlie=Array.from(document.getElementsByClassName("logement"));

function enfer(){ //Met à jour l'affichage de la liste des logements
    for (let cle in charlie){
    if ((filtre_nb(charlie[cle].innerHTML))&&(filtre_max(charlie[cle].innerHTML))&&(filtre_min(charlie[cle].innerHTML))&&(filtre_recherche(charlie[cle].innerHTML))){ //compare le dictionnaire fixe (charlie) au dictionnaire variable (sammy). Si une valeur n'est pas dans la liste variable, elle n'apparait pas dans la liste des recherches
        console.log("negra");
        charlie[cle].style.display="block";
      }
      else{
        charlie[cle].style.display="none";
        console.log(cle);
      }
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
    if (nb[1].toLowerCase()==filtre){
        return true;
    }
    else{
        return false;
    }
}

document.getElementById('side_nb').addEventListener('input',enfer);
document.getElementById('side_max').addEventListener('input',enfer);
document.getElementById('side_min').addEventListener('input',enfer);
document.getElementById('side_recherche').addEventListener('input',enfer);
document.getElementById('side_type').addEventListener('change',enfer);
document.getElementById('side_arrive').addEventListener('input',enfer);