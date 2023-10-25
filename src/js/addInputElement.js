let nbChambres = 1;
let nbLits = 1;
let nbLitsSimple = 0;
let nbLitsDoubles = 0;
let nbInstallations = 1;
let nbServices = 1;
let nbEquipements = 1;

const iconSupprimer = document.createElement("img");
iconSupprimer.src = "../../public/icons/supprimer34.svg";
iconSupprimer.alt = "Icone supprimer";

document.addEventListener("DOMContentLoaded", function () {
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
  let creerAnnonce = document.querySelector("#creerAnnonce");
  creerAnnonce.disabled = true;

  const form = document.querySelector("form");

  /**
   * * Si réglement pas accepté, bouton creerAnnonce désactivé
   */
  checkboxReglement.addEventListener("change", function () {
    if (this.checked) {
      creerAnnonce.disabled = false;
    } else {
      creerAnnonce.disabled = true;
    }
  });

  /**
   * * Déclaration de tous les inputs ayant un *
   */
let cdPostalInput = document.querySelector("#cdPostal");
let villeInput = document.querySelector("#ville");
let adresseInput = document.querySelector("#adresse");
let titreInput = document.querySelector("#title");
let descriptionInput = document.querySelector("#description");
let natureLogementInput = document.querySelector("#natureLogementInput");
let nbChambresInput = document.querySelector("#nbChambres");
let nbSallesBainInput = document.querySelector("#nbSallesBain");
let nbMaxPersInput = document.querySelector("#nbMaxPers");
let prixParNuitInput = document.querySelector("#prixParNuit");
let surfaceInput = document.querySelector("#surface");
let photosInput = document.querySelector("#photos");

/**
 * * Vérifie si les champs sont tous correctement remplis
 */

function checkFormValidity() {
    let cdPostalValue = cdPostalInput.value;
    let villeValue = villeInput.value;
    let adresseValue = adresseInput.value;
    let titreValue = titreInput.value;
    let descriptionValue = descriptionInput.value;
    let natureLogementValue = natureLogementInput.value;
    let nbChambresValue = nbChambresInput.value;
    let nbSallesBainValue = nbSallesBainInput.value;
    let nbMaxPersValue = nbMaxPersInput.value;
    let prixParNuitValue = prixParNuitInput.value;
    let surfaceValue = surfaceInput.value;
    
    /**
     * * Return true uniquement si tout les tests suivants sont corrects
     */
    return (
        cdPostalValue.length >= 5 &&
        villeValue.length > 0 &&
        adresseValue.length > 0 &&
        titreValue.length > 0 &&
        descriptionValue.length > 0 && 
        natureLogementValue.length > 0 &&
        surfaceValue.length > 0 &&
        photosInput.files.length > 0 &&
        (nbChambresValue.length > 0 && nbChambresValue >= 0) &&
        (nbSallesBainValue.length > 0 && nbChambresValue >= 0) &&
        (nbMaxPersValue.length > 0 && nbMaxPersValue >= 0) && 
        (prixParNuitValue.length > 0 && prixParNuitValue >= 0)
    );
}

    cdPostalInput.addEventListener("input", checkFormValidity);
    villeInput.addEventListener("input", checkFormValidity);
    adresseInput.addEventListener("input", checkFormValidity);
    titreInput.addEventListener("input", checkFormValidity);
    descriptionInput.addEventListener("input", checkFormValidity);

checkFormValidity();

/**
 * * Listener sur le bouton creerAnnonce qui
 * * vérifie si tous les champs ont correctement été remplis
 */


form.addEventListener("submit", function (event) {
  event.preventDefault(); // Empêche la soumission du formulaire
  // Vous pouvez placer le code de soumission du formulaire PHP ici si nécessaire
  if (checkFormValidity()) {
    Swal.fire({
        title: "Logement bien créé",
        text: "Succès",
        icon: "success"
    });
} else {
    Swal.fire({
        title: "Veuillez remplir correctement tous les champs dotés d'un *",
        text: "Erreur",
        icon: "error"
    });
}
});
    /**
     * * Permet au clic du bouton Ajouter installation,
     * * d'ajouter un nouvel input pour renseigner une nouvelle installation
     * * Pareil pour les services, équipements, lits et chambres
     */
  addInstallation.addEventListener("click", () => {
    nbInstallations++;
    let nameInput = "InstallDispo" + nbInstallations;
    const inputPlusIconeSupprimer = createInputWithIconSupprimer("Installation disponible", nameInput);
    installationsElement.appendChild(inputPlusIconeSupprimer);
  });

  addService.addEventListener("click", () => {
    nbServices++;
    let nameInput = "service" + nbServices;
    const inputPlusIconeSupprimer = createInputWithIconSupprimer("Service disponible", nameInput);
    servicesElement.appendChild(inputPlusIconeSupprimer);
  });

  addEquipements.addEventListener("click", () => {
    nbEquipements++;
    let nameInput = "equipement" + nbEquipements;
    const inputPlusIconeSupprimer = createInputWithIconSupprimer("Equipement disponible", nameInput);
    equipementsElement.appendChild(inputPlusIconeSupprimer);
  });

  addLits.addEventListener("click", () => {
    let newElement = document.createElement("select");

    newElement.className = "litsElement";

    var option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    var option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";
    newElement.appendChild(option2);

    const iconSupprimer = addLitsFunction();

    litsElement.appendChild(iconSupprimer);
  });

  addChambre.addEventListener("click", () => {
    nbChambres++;
    nbLits ++;
    console.log(nbChambres);
    let titre = document.createElement("label");

    let btnAddLits = document.createElement("button");

    btnAddLits.textContent = "Ajouter lit";
    btnAddLits.className = "btnAddLits";

    titre.textContent = "Chambre " + nbChambres;
    let newElement = document.createElement("select");
    newElement.setAttribute("id", "nouvelleChambre");
    newElement.id = "lits";

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
    iconSupprimer.src = "../../public/icons/supprimer34.svg";
    iconSupprimer.alt = "Icone supprimer";
    iconSupprimer.id = "iconSupprimer";

    const inputPlusIconeSupprimer = document.createElement("div");
    inputPlusIconeSupprimer.classList.add("inputPlusIconeSupprimer");

    const divChambre = document.createElement("div");   

    inputPlusIconeSupprimer.appendChild(titre);
    inputPlusIconeSupprimer.appendChild(iconSupprimer);

    divChambre.appendChild(inputPlusIconeSupprimer);
    divChambre.appendChild(newElement);
    divChambre.appendChild(btnAddLits);

    chambresElement.appendChild(divChambre);

    /**
     * * Même principe que pour supprimer un lit sauf qu'on vise son parent,
     * * c'est à dire directement la chambre. 
     */
    iconSupprimer.addEventListener("click", function () {
      const parentDiv = this.parentElement;
      const parentDivUp = parentDiv.parentElement;
      parentDivUp.remove();
      nbChambres --;
      console.log(nbChambres);
    });

    btnAddLits.addEventListener("click", () => {
      const addLits = addLitsFunction();
      divChambre.appendChild(addLits);
    });
  });

  /**
   * * Fonction qui crée un input ainsi qu'une image, 
   * * les assemble dans une div, et return la div
   */
  function createInputWithIconSupprimer(placeholder, name) {
    let newElement = document.createElement("input");
    newElement.setAttribute("type", "text");
    newElement.setAttribute("size", "60");
    newElement.setAttribute("placeholder", placeholder);
    newElement.setAttribute("name", name);

    console.log(name);

    const iconSupprimer = document.createElement("img");
    iconSupprimer.src = "../../public/icons/supprimer34.svg";
    iconSupprimer.alt = "Icone supprimer";
    iconSupprimer.id = "iconSupprimer";

    const inputPlusIconeSupprimer = document.createElement("div");
    inputPlusIconeSupprimer.classList.add("inputPlusIconeSupprimer");

    inputPlusIconeSupprimer.appendChild(newElement);
    inputPlusIconeSupprimer.appendChild(iconSupprimer);

    iconSupprimer.addEventListener("click", function () {
        const parentDiv = this.parentElement;
        parentDiv.remove();
        if(placeholder === "Installation disponible"){
          nbInstallations --;
        } else if(placeholder === "Service disponible"){
          nbServices--;
        } else if(placeholder === "Equipement disponible"){
          nbEquipements--;
        }
      });

    return inputPlusIconeSupprimer;
  }

  /**
   * * Permet d'ajouter un select donnant le choix d'un lit,
   * * et ensuite ajoute le select suivit de son image de supression 
   * * dans une div, cette div est intégré dans son élément parent qui est sa chambre
   * * 
   */
  function addLitsFunction() {
    event.preventDefault();
    nbLits++;
    console.log(nbLits);
    let newElement = document.createElement("select");
    newElement.setAttribute("type", "text");
    newElement.id = "lits";

    var option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    var option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";
    newElement.appendChild(option2);

    const iconSupprimer = document.createElement("img");
    iconSupprimer.src = "../../public/icons/supprimer34.svg";
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
