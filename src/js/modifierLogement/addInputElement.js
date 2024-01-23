document.addEventListener("DOMContentLoaded", function () {
  let nbChambres = 1;
  let nbLits = 1;
  let nbInstallations = 1;
  let nbServices = 1;
  let nbEquipements = 1;
  let ella = []; //Va servir de tableaux pour stoquer le nombre de lits doubles et lits simples par chambre sous forme -> ex :[[0,1],[5,6]]
  ella[1] = 0; //declare la premiere chambre

  //Recupere toutes les infos des champs texte du php
  let addInstallation = document.querySelector("#btnInstallations");
  let installationsElement = document.querySelector(".installationsElement");

  let addService = document.querySelector("#btnServices");
  let servicesElement = document.querySelector(".servicesElement");

  let addEquipements = document.querySelector("#btnAddEquipements");
  let equipementsElement = document.querySelector(".equipementsElement");

  let addLits = document.querySelector("#btnAddLits");
  let litsElement = document.querySelector(".litsElement");

  let addChambre = document.querySelector("#btnAddChambre");
  let chambresElement = document.querySelector(".chambresElement");

  let checkboxReglement = document.querySelector("#conditionsGenerale");
  let myForm = document.querySelector("#myForm");
  let creerAnnonce = document.querySelector("#creerAnnonce");
  creerAnnonce.disabled = true;

  /*
  *
  *
  *  MODIFICATION DES VIGNETTES
  * 
  */

  /* GESTION DES INSTALLATIONS */
    
  let firstInstall = document.querySelector("#installDispo");

  contentInstall = firstInstall.value.split(',');
  if (contentInstall[1]!=null){ //verifie que le contenu existe
    firstInstall.value=contentInstall[1];
  }

  for (let i = 2; i < contentInstall.length; i++) {
    IniInstall(contentInstall[i]);
  }

  // GESTION DES EQUIPEMENTS

  let firstEquip = document.querySelector("#equipement");

  contentEquip = firstEquip.value.split(',');
  if (contentEquip[1]!=null){
    firstEquip.value=contentEquip[1];
  }

  for (let i = 2; i < contentEquip.length; i++) {
    InitiEquip(contentEquip[i]);
  }

  // GESTION DES SERVICES

  let firstServ = document.querySelector("#service");

  contentServ = firstServ.value.split(',');
  if (contentServ[1]!=null){
    firstServ.value=contentServ[1];
  }

  for (let i = 2; i < contentServ.length; i++) {
    InitiServic(contentServ[i]);
  }

  /* GESTION DES CHAMBRES */

  let firstRoom = document.querySelector("#chambresElement");  

  if (firstRoom!=null){
    console.log(firstRoom.getAttribute("value"));
    let contentChambres = JSON.parse(firstRoom.getAttribute("value"));

  spl=contentChambres[0][0];
  dbl=contentChambres[0][1];

  //Selectionne la bonne option dans le premier select

  let selspl=document.querySelector("#option2");
  let seldbl=document.querySelector("#option1");
  if (spl>0){
    spl=spl-1;
    selspl.selected=("selected");
  }
  else if (dbl>0){
    dbl=dbl-1;
    seldbl.selected=("selected");
  }

  //Ajout premiere chambre
  let divChambre=document.querySelector("#Chambre0");
  nbRoom=1;
  ella[1]=0;

  for (i=0;i<spl;i++){
    const addLits = addLitsFunction(nbRoom,0);
    divChambre.appendChild(addLits);
  }

  for (i=0;i<dbl;i++){
    const addLits = addLitsFunction(nbRoom,1);
    divChambre.appendChild(addLits);
  }

  //Gestion des autres chambres

  for (let i=1; i < contentChambres.length;i++){
    addChambrePre(contentChambres[i][0],contentChambres[i][1]);
  }

  }


  
  const form = document.querySelector("form");

  const iconSupprimer = document.createElement("img");
  iconSupprimer.src = "/public/icons/supprimer.png";
  iconSupprimer.alt = "Icone supprimer";

  let divNomsPhotos = document.getElementById("photosName");
  const input = document.getElementById("photos");

  /**
   * * Fonction permettant d'afficher le nom de tous les fichiers sÃ©lectioner par le propriÃ©taire.
   */
  function afficherNomsPhotos() {
    const photos = input.files;

    for (let i = 0; i < photos.length; i++) {
      const nomPhoto = photos[i].name;
      let photo = document.createElement("p");
      photo.textContent = "Photo n°" + (i + 1) + ":" + nomPhoto;
      divNomsPhotos.appendChild(photo);
    }
  }

  input.addEventListener("change", afficherNomsPhotos);

  /**
   * * Si rÃ©glement pas acceptÃ©, bouton creerAnnonce dÃ©sactivÃ©
   */
  checkboxReglement.addEventListener("change", function () {
    if (this.checked) {
      creerAnnonce.disabled = false;
    } else {
      creerAnnonce.disabled = true;
    }
  });

  /**
   * * DÃ©claration de tous les inputs ayant un *
   */
  let cdPostalInput = document.querySelector("#cdPostal");
  //let photosInput = document.querySelector("#photos");
  //let photosValues = photosInput.value;

  /**
   * * Permet de limiter le nombre de chiffre à 5 pour le code postal
   */
  cdPostalInput.addEventListener("input", function () {
    let postalCode = this.value;
    if (postalCode.length > 5) {
      this.value = postalCode.slice(0, 5);
    }
  });

  /**
   * * VÃ©rifie si les champs sont tous correctement remplis
   */

  function checkFormValidity() {
    const formData = new FormData(document.getElementById("myForm"));
    let errors = [];

    let cdPostalValue = cdPostalInput.value;

    // VÃ©rification des champs requis et stockage des messages d'erreur
    const requiredFields = {
      title: "Titre d'annonce non renseignÃ©",
      description: "Description du logement non renseignÃ©e",
      surface: "Surface du logement non renseignÃ©",
      natureLogement: "Nature du logement non renseignÃ©",
      adresse: "Adresse du logement non renseignÃ©",
      cdPostal: "Code postal non renseignÃ©",
      ville: "Ville non renseignÃ©",
      nbSallesBain: "Nombres de salles de bain non renseignÃ©",
      nbMaxPers: "Nombre max. pers non renseignÃ©",
      prixParNuit: "Prix par nuit non renseignÃ©",
    };

    for (const [fieldName, errorMessage] of Object.entries(requiredFields)) {
      if (!formData.get(fieldName)) {
        errors.push(errorMessage);
      }
    }

    // Affichage des messages d'erreur
    if (errors.length > 0) {
      const errorMessage = errors.join("<br>");
      Swal.fire({
        title: errorMessage,
        icon: "warning",
      });
      return false; // Le formulaire n'est pas valide
    } else if (cdPostalInput.value.length < 5) {
      console.log(cdPostalValue);
      Swal.fire({
        title: "Code postal doit Ãªtre composer de 5 chiffres",
        icon: "warning",
      });
      return false;
    } else {
      return true; // Le formulaire est valide
    }
  }

  /**
   * * Permet au clic du bouton Ajouter
   * * d'ajouter un nouvel input pour renseigner une nouvelle vignette
   * * Marche pour les equipements, services, Ã©quipements, lits et chambres
   */

  function createLogement(placeholder, name, val) {
    let newElement = document.createElement("input");
    newElement.className = "textfield";
    newElement.setAttribute("type", "text");
    newElement.setAttribute("size", "60");
    newElement.setAttribute("placeholder", placeholder);
    newElement.setAttribute("value", val);
    newElement.setAttribute("name", name);
    newElement.setAttribute("id", name);

    const iconSupprimer = document.createElement("img");
    iconSupprimer.src = "/public/icons/supprimer.svg";
    iconSupprimer.alt = "Icone supprimer";
    iconSupprimer.id = "iconSupprimer";

    const inputPlusIconeSupprimer = document.createElement("div");
    inputPlusIconeSupprimer.classList.add("inputPlusIconeSupprimer");

    inputPlusIconeSupprimer.appendChild(newElement);
    inputPlusIconeSupprimer.appendChild(iconSupprimer);

    iconSupprimer.addEventListener("click", function () {
      const parentDiv = this.parentElement;
      parentDiv.remove();
      if (placeholder === "Installation disponible") {
        nbInstallations--;
      } else if (placeholder === "Service disponible") {
        nbServices--;
      } else if (placeholder === "Equipement disponible") {
        nbEquipements--;
      }
    });
    return inputPlusIconeSupprimer;
  }

  function IniInstall(valeur) {
    nbInstallations++;
    let nameInput = "InstallDispo" + nbInstallations;
    const inputPlusIconeSupprimer = createLogement(
      "Installation disponible",
      nameInput,
      valeur
    );
    installationsElement.appendChild(inputPlusIconeSupprimer);
  }

  addInstallation.addEventListener("click", function () {
    IniInstall("");
  });

  function InitiEquip(valeur) {
    nbEquipements++;
    let nameInput = "equipement" + nbEquipements;
    const inputPlusIconeSupprimer = createLogement(
      "Equipement disponible",
      nameInput,
      valeur
    );
    equipementsElement.appendChild(inputPlusIconeSupprimer);
  }

  addEquipements.addEventListener("click", function () {
    InitiEquip("");
  });

  function InitiServic(valeur) {
    nbServices++;
    let nameInput = "service" + nbServices;
    const inputPlusIconeSupprimer = createLogement(
      "Service disponible",
      nameInput,
      valeur
    );
    servicesElement.appendChild(inputPlusIconeSupprimer);
  }
  addService.addEventListener("click", function () {
    InitiServic("");
  });

  /* 
La fonction 
*/
  addLits.addEventListener("click", () => {
    let newElement = document.createElement("select");

    newElement.className = "litsElement textfield";

    var option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    var option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";
    newElement.appendChild(option2);

    const iconSupprimer = addLitsFunction(1);

    litsElement.appendChild(iconSupprimer);
  });

  checkboxReglement.addEventListener("change", function () {
    if (this.checked) {
      creerAnnonce.disabled = false;
    } else {
      creerAnnonce.disabled = true;
    }
  });

  // NON FONCTIONNEL POUR L INSTANT!!!
  if (nbChambres >= 20) {
    addChambre.disabled = true;
  } else {
    null;
  }
  // !!!

  /* Ajoute une chambre lorsqu'on clique sur le bouton ajouter chambre*/
  addChambre.addEventListener("click", () => {
    nbChambres++;
    let titre = document.createElement("label");
    let btnAddLits = document.createElement("button");

    console.log(nbChambres);

    btnAddLits.textContent = "Ajouter lit";
    btnAddLits.className = "btnAddLits boutton";

    titre.textContent = "Chambre " + nbChambres;
    let newElement = document.createElement("select");
    newElement.name = nbChambres + "lits" + 0;
    newElement.className = "textfield";

    var option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    var option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";
    newElement.appendChild(option2);

    titre.setAttribute("for", "nouvelleChambre");

    const iconSupprimer = document.createElement("img");
    iconSupprimer.src = "/public/icons/supprimer.svg";
    iconSupprimer.alt = "Icone supprimer";
    iconSupprimer.id = "iconSupprimer";

    const inputPlusIconeSupprimer = document.createElement("div");
    inputPlusIconeSupprimer.classList.add("inputPlusIconeSupprimer");

    const divChambre = document.createElement("div");

    divChambre.id = nbChambres;
    ella[nbChambres] = 0;

    inputPlusIconeSupprimer.appendChild(titre);
    inputPlusIconeSupprimer.appendChild(iconSupprimer);

    divChambre.appendChild(inputPlusIconeSupprimer);
    divChambre.appendChild(newElement);
    divChambre.appendChild(btnAddLits);

    chambresElement.appendChild(divChambre);

    /**
     * * Ajout du bouton pour supprimer la chambre
     */
    iconSupprimer.addEventListener("click", function () {
      const parentDiv = this.parentElement;
      const parentDivUp = parentDiv.parentElement;
      parentDivUp.remove();
      nbChambres--;
      console.log(nbChambres);
    });

    /**
     * * Ajout du bouton pour ajouter un lit
     */
    btnAddLits.addEventListener("click", () => {
      nbRoom = divChambre.id;
      const addLits = addLitsFunction(nbRoom);
      divChambre.insertBefore(addLits, btnAddLits);
    });
  });


  function addChambrePre(spl=0,dbl=0) {
    nbChambres++;
    let titre = document.createElement("label");
  
    let btnAddLits = document.createElement("button");
  
    btnAddLits.textContent = "Ajouter lit";
    btnAddLits.className = "btnAddLits boutton";
  
    titre.textContent = "Chambre " + nbChambres;
    let newElement = document.createElement("select");
    newElement.className = "textfield";
    newElement.name = nbChambres+"lits"+0;
  
    var option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
  
    var option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";

    if (spl>0){
      spl=spl-1;
      option1.selected=true;
    }
    else if (dbl>0){
      dbl=dbl-1;
      option2.selected=true;
    }

    newElement.appendChild(option1);
    newElement.appendChild(option2);
  
    titre.setAttribute("for", "nouvelleChambre");


  
    const iconSupprimer = document.createElement("img");
    iconSupprimer.src = "/public/icons/supprimer.svg";
    iconSupprimer.alt = "Icone supprimer";
    iconSupprimer.id = "iconSupprimer";
  
    const inputPlusIconeSupprimer = document.createElement("div");
    inputPlusIconeSupprimer.classList.add("inputPlusIconeSupprimer");
  
    const divChambre = document.createElement("div");   
  
    divChambre.id=nbChambres;
    ella[nbChambres]=0;
  
    inputPlusIconeSupprimer.appendChild(titre);
    inputPlusIconeSupprimer.appendChild(iconSupprimer);
  
    divChambre.appendChild(inputPlusIconeSupprimer);
    divChambre.appendChild(newElement);
    divChambre.appendChild(btnAddLits);
  
    chambresElement.appendChild(divChambre);
  
    for (i=0;i<spl;i++){
      nbRoom=divChambre.id;
      const addLits = addLitsFunction(nbRoom,0);
      divChambre.insertBefore(addLits,btnAddLits);
    }

    for (i=0;i<dbl;i++){
      nbRoom=divChambre.id;
      const addLits = addLitsFunction(nbRoom,1);
      divChambre.insertBefore(addLits,btnAddLits);
    }

    /**
     * * Même principe que pour supprimer un lit sauf qu'on vise son parent,
     * * c'est à dire directement la chambre. 
     */
    iconSupprimer.addEventListener("click", function () {
      const parentDiv = this.parentElement;
      const parentDivUp = parentDiv.parentElement;
      parentDivUp.remove();
      nbChambres --;
    });
  
    btnAddLits.addEventListener("click", () => {
      nbRoom=divChambre.id;
      const addLits = addLitsFunction(nbRoom);
      divChambre.insertBefore(addLits, btnAddLits)
    });
  
  

  }
  

  /**
   * * Permet d'ajouter un select donnant le choix d'un lit,
   * * et ensuite ajoute le select suivit de son image de supression
   * * dans une div, cette div est intÃ©grÃ© dans son Ã©lÃ©ment parent qui est sa chambre
   * *
   */
  function addLitsFunction(nChambre, typ = 0) {
    event.preventDefault();
    ella[nChambre]++;

    let newElement = document.createElement("select");
    newElement.className = "textfield";
    newElement.setAttribute("type", "text");
    newElement.name = nChambre + "lits" + ella[nChambre];

    var option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";

    var option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";

    if (typ==0){
      option1.selected="selected";
    }
    else{
      option2.selected="selected";
    }

    newElement.appendChild(option1);
    newElement.appendChild(option2);

    const iconSupprimer = document.createElement("img");
    iconSupprimer.src = "/public/icons/supprimer.svg";
    iconSupprimer.alt = "Icone supprimer";
    iconSupprimer.id = "iconSupprimer";

    const inputPlusIconeSupprimer = document.createElement("div");
    inputPlusIconeSupprimer.classList.add("inputPlusIconeSupprimer");

    inputPlusIconeSupprimer.appendChild(newElement);
    inputPlusIconeSupprimer.appendChild(iconSupprimer);

    /**
     * * Permet au click sur l'icon supprimer, de supprimer
     * * de la chambre en question le lit en question.
     */
    iconSupprimer.addEventListener("click", function () {
      const parentDiv = this.parentElement;
      parentDiv.remove();
      nbLits--;
      console.log(nbLits);
    });

    return inputPlusIconeSupprimer;
  }
});
