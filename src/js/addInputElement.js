let nbChambres = 1;

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

  addInstallation.addEventListener("click", () => {
    let newElement = document.createElement("input");
    newElement.setAttribute("type", "text");
    installationsElement.appendChild(newElement);
  });

  addService.addEventListener("click", () => {
    let newElement = document.createElement("input");
    newElement.setAttribute("type", "text");
    servicesElement.appendChild(newElement);
  });

  addEquipements.addEventListener("click", () => {
    let newElement = document.createElement("input");
    newElement.setAttribute("type", "text");
    equipementsElement.appendChild(newElement);
  });

  addLits.addEventListener("click", () => {
    let newElement = document.createElement("select");

    newElement.className = "litsElement"; // Set the 'id' attribute

    var option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    var option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";
    newElement.appendChild(option2);
    litsElement.appendChild(newElement);
  });

  addChambre.addEventListener("click", () => {
    nbChambres++;
    let titre = document.createElement("label");

    titre.textContent = "Chambre " + nbChambres;
    let newElement = document.createElement("select");
    newElement.setAttribute('id', 'nouvelleChambre');

    var option1 = document.createElement("option");
    option1.value = "Lit double (140 * 190)";
    option1.text = "Lit double (140 * 190)";
    newElement.appendChild(option1);

    var option2 = document.createElement("option");
    option2.value = "Lit simple (90 * 190)";
    option2.text = "Lit simple (90 * 190)";
    newElement.appendChild(option2);

    titre.setAttribute('for', 'nouvelleChambre');
    chambresElement.appendChild(titre);
    chambresElement.appendChild(newElement);
  });
});
