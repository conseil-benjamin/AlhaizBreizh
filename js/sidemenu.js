let list=Array.from(document.getElementsByClassName("logement"));
let charlie ={};
let sammy={};
list.forEach((valeur,index) => {
  charlie[index]=valeur;});
  list.forEach((valeur,index) => {
    sammy[index]=valeur;});

function enfer(){ //Met à jour l'affichage de la liste des logements
  for (let cle in charlie){
    if (Object.values(sammy).includes(charlie[cle])){ //compare le dictionnaire fixe (charlie) au dictionnaire variable (sammy). Si une valeur n'est pas dans la liste variable, elle n'apparait pas dans la liste des recherches
      charlie[cle].style.display="block";
    }
    else{
      charlie[cle].style.display="none";
    }
}
}

function paradis(liste){
  sammy=liste;
}

document.getElementById('menu-btn').addEventListener('click', function () {
    let sidebar = document.getElementById('sidebar');
    if (sidebar.style.left === "-22em") {
      sidebar.style.left = "0";
    } else {
      sidebar.style.left = "-22em";
    }
  });

document.getElementById('side_nb').addEventListener('input', function () {
  let filtre = this.value.toLowerCase();

  for (let cle in charlie){
    let contenu = charlie[cle].innerHTML;
    let nb = contenu.match(/\d+/g);
    if (parseInt(nb[nb.length-2])<parseInt(filtre)){
      delete sammy[cle];
    }
    else{
      sammy[cle]=charlie[cle];
    }
  }
  enfer();
});

document.getElementById('side_max').addEventListener('input', function () {
  let filtre = this.value.toLowerCase();

  for (let cle in charlie){
    let contenu = charlie[cle].innerHTML;
    let nb = contenu.match(/\d+/g);
    if (parseInt(nb[nb.length-1])<parseInt(filtre)){
      delete sammy[cle];
    }
    else{
      sammy[cle]=charlie[cle];
    }
  }
  enfer();
});

document.getElementById('side_min').addEventListener('input', function () {

  let filtre = this.value.toLowerCase();
  let liste={};
  list.forEach((valeur,index) => {
    liste[index]=valeur;});

  for (let cle in charlie){
    let contenu = charlie[cle].innerHTML;
    let nb = contenu.match(/\d+/g);
    if (parseInt(nb[nb.length-1])<parseInt(filtre)){
      console.log(liste);
      delete liste[cle];
    }
  }
  paradis(liste);
});

/*
document.getElementById('side_type').addEventListener('input', function () {
  let filtre = this.value.toLowerCase();
  liste.forEach(item => {
    let contenu = item.innerHTML;
    let nb = contenu.match(/<h1>([\s\S]*?)<\h1>/);
    if (nb.includes(filtre)){
      item.style.display = "block";
    }
    else{
      item.style.display = 'display';
    }
  })
});
*/

document.getElementById('side_recherche').addEventListener('input', function () {
  let filtre = this.value.toLowerCase();
  list.forEach(item => {
    let contenu = item.innerHTML;
    let nb = contenu.match(/<h3>([\s\S]*?)<\/h3>/);
    console.log(filtre,nb[1],nb[1].toLowerCase().includes(filtre));
    if (nb[1].toLowerCase().includes(filtre)){
      //item.style.display = "block";
    }
    else{
      list.splice(i,1)
    }
  })
})