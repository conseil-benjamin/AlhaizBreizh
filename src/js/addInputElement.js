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
      photo.textContent =  nomPhoto;
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
  let surfaceInput = document.querySelector("#surface");
  //let photosInput = document.querySelector("#photos");
  //let photosValues = photosInput.value;

  /**
   * * Permet de limiter le nombre de chiffre à 5 pour le code postal
   */
  surfaceInput.addEventListener("input", function () {
    let surface = this.value;
    if (surface.length > 4) {
      this.value = surface.slice(0, 4);
    }
  });
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
      title: "Titre d'annonce non renseigné",
      description: "Description du logement non renseigné",
      surface: "Surface du logement non renseigné",
      natureLogement: "Nature du logement non renseigné",
      photos: "0 photos ou plus de 6 photos ajouté",
      adresse: "Adresse du logement non renseigné",
      cdPostal: "Code postal non renseigné",
      ville: "Ville non renseignée",
      nbSallesBain: "Nombres de salles de bain non renseigné",
      nbMaxPers: "Nombre maximum personne non renseigné",
      prixParNuit: "Prix par nuit non renseigné",
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
        title: "Code postal doit être composer de 5 chiffres",
        icon: "warning",
      });
      return false;
    } else {
      return true; // Le formulaire est valide
    }
  }

  function submitForm(event) {
    event.preventDefault();
    if (checkFormValidity()) {
      form.submit();
    }
  }
  
  myForm.addEventListener("submit", submitForm);

  /**
   * * Permet au clic du bouton Ajouter
   * * d'ajouter un nouvel input pour renseigner une nouvelle vignette
   * * Marche pour les equipements, services, Ã©quipements, lits et chambres
   */

  function createLogement(placeholder, name, val) {
    let newElement = document.createElement("input");
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

    newElement.className = "litsElement";

    let option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    let option2 = document.createElement("option");
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
    btnAddLits.className = "btnAddLits";

    titre.textContent = "Chambre " + nbChambres;
    let newElement = document.createElement("select");
    newElement.name = nbChambres + "lits" + 0;

    let option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    let option2 = document.createElement("option");
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
    newElement.name = nChambre + "lits" + ella[nChambre];

    let option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    let option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";
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
