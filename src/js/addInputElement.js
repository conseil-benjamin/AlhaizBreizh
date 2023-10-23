let nbChambres = 1;
let nbLitsSimple = 0;
let nbLitsDoubles = 0;
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
  let chambresElement = document.querySelector(".container-left");

  let checkboxReglement = document.querySelector("#conditionsGenerale");
  let creerAnnonce = document.querySelector("#creerAnnonce");

  checkboxReglement.addEventListener("change", function () {
    if (this.checked) {
      creerAnnonce.disabled = false;
    } else {
      creerAnnonce.disabled = true;
    }
  });

  addInstallation.addEventListener("click", () => {
    const inputPlusIconeSupprimer = createInputWithIconSupprimer();
    installationsElement.appendChild(inputPlusIconeSupprimer);
  });

  addService.addEventListener("click", () => {
    const inputPlusIconeSupprimer = createInputWithIconSupprimer();
    servicesElement.appendChild(inputPlusIconeSupprimer);
  });

  addEquipements.addEventListener("click", () => {
    const inputPlusIconeSupprimer = createInputWithIconSupprimer();
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
    let titre = document.createElement("label");

    let btnAddLits = document.createElement("button");

    btnAddLits.textContent = "Ajouter lit";

    btnAddLits.addEventListener("click", () => {
      const addLits = addLitsFunction();
      chambresElement.appendChild(addLits);
    });

    titre.textContent = "Chambre " + nbChambres;
    let newElement = document.createElement("select");
    newElement.setAttribute("id", "nouvelleChambre");

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

    inputPlusIconeSupprimer.appendChild(titre);
    inputPlusIconeSupprimer.appendChild(iconSupprimer);

    chambresElement.appendChild(inputPlusIconeSupprimer);
    chambresElement.appendChild(newElement);
    chambresElement.appendChild(btnAddLits);
  });

  iconSupprimer.addEventListener("click", function (event) {
    event.preventDefault();

    const parentDiv = this.parentElement;

    parentDiv.remove();
  });

  function createInputWithIconSupprimer() {
    let newElement = document.createElement("input");
    newElement.setAttribute("type", "text");

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
    });

    return inputPlusIconeSupprimer;
  }

  function addLitsFunction() {
    let newElement = document.createElement("select");
    newElement.setAttribute("type", "text");

    var option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    var option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";
    newElement.appendChild(option2);

    newElement.addEventListener("change", function () {
      const selectedOption = newElement.value;

      // reste à gérer nombre de lits pour chaque categorie de lits
      if (selectedOption === option1.value) {
        console.log("Doubles " + nbLitsDoubles);
      } else {
        console.log("Simple " + nbLitsSimple);
      }
    });

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
    });

    return inputPlusIconeSupprimer;
  }
});
