document.addEventListener("DOMContentLoaded", function () {
  let nbChambres = 1;
  let nbLits = 1;
  let nbLitsSimple = 0;
  let nbLitsDoubles = 0;
  let nbInstallations = 1;
  let nbServices = 1;
  let nbEquipements = 1;

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
   * * Fonction permettant d'afficher le nom de tous les fichiers sélectioner par le propriétaire.
   */
  function afficherNomsPhotos() {
    const photos = input.files;

    for (let i = 0; i < photos.length; i++) {
      const nomPhoto = photos[i].name;
      let photo = document.createElement("p");
      photo.textContent = nomPhoto;
      divNomsPhotos.appendChild(photo);
    }
  }

  input.addEventListener("change", afficherNomsPhotos);

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

  /**
   * * Permet de limiter le nombre de chiffre à 5 pour le code postal
   */
  cdPostalInput.addEventListener("input", function () {
    let postalCode = this.value;
    if (postalCode.length > 5) {
      this.value = postalCode.slice(0, 5);
    }
  });

  let cdPostalValue = cdPostalInput.value;
  //let photosValues = photosInput.value;

  /**
   * * Vérifie si les champs sont tous correctement remplis
   */

  function checkFormValidity() {
    // Récupère le formulaire HTML et ses balises
    const formData = new FormData(document.getElementById("myForm"));
    let errors = [];

    // Vérification des champs requis et stockage des messages d'erreur
    const requiredFields = {
      title: "Titre d'annonce non renseigné",
      description: "Description du logement non renseignée",
      surface: "Surface du logement non renseigné",
      photos: "Aucune photo télécharger",
      natureLogement: "Nature du logement non renseigné",
      adresse: "Adresse du logement non renseigné",
      cdPostal: "Code postal non renseigné",
      ville: "Ville non renseigné",
      nbSallesBain: "Nombres de salles de bain non renseigné",
      nbMaxPers: "Nombre max. pers non renseigné",
      prixParNuit: "Prix par nuit non renseigné",
    };

    /**
     * * Vérifie si le champ sélectionner à une valeur, si non, on retourne l'erreur associé à ce champ
     */
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
    } else {
      /*
    else if (cdPostalValue.length < 5) {
      console.log(cdPostalValue);
      Swal.fire({
        title: "Code postal doit être composer de 5 chiffres",
        icon: "warning",
      });
      return false;

    } 
        */
      return true; // Le formulaire est valide
    }
  }

  /**
   * * Vérifie si le formulaire est bien valide, si oui on affiche un message de succès
   * * Après avoir fermer l'alert de succès on renvoie vers une page qui s'occupe d'insérer les éléments du client dans la bdd
   * * On récupère les informations de la création de logement via la méthode : formData.entries() et on les ajoute à l'url de destination
   * @param {*} event
   */
  function submitForm(event) {
    event.preventDefault();
    if (checkFormValidity()) {
      Swal.fire({
        title: "Logement bien créé",
        icon: "success",
      }).then((result) => {
        if (result.isConfirmed) {
          const formData = new FormData(document.getElementById("myForm"));
          let url = "/src/php/logement/insertDatabase.php?";

          for (const [key, value] of formData.entries()) {
            url += `${key}=${encodeURIComponent(value)}&`;
          }

          // Rediriger vers la page avec les données du formulaire ajoutées à l'URL
          window.location.href = url;
        }
      });
    }
  }
  myForm.addEventListener("submit", submitForm);

  /**
   * * Permet au clic du bouton Ajouter installation,
   * * d'ajouter un nouvel input pour renseigner une nouvelle installation
   * * Pareil pour les services, équipements, lits et chambres
   */
  addInstallation.addEventListener("click", () => {
    nbInstallations++;
    let nameInput = "InstallDispo" + nbInstallations;
    let id = "installNumero" + nbInstallations;
    const inputPlusIconeSupprimer = createInputWithIconSupprimer(
      "Installation disponible",
      nameInput,
      id
    );
    installationsElement.appendChild(inputPlusIconeSupprimer);
  });

  addService.addEventListener("click", () => {
    nbServices++;
    let nameInput = "service" + nbServices;
    const inputPlusIconeSupprimer = createInputWithIconSupprimer(
      "Service disponible",
      nameInput
    );
    servicesElement.appendChild(inputPlusIconeSupprimer);
  });

  addEquipements.addEventListener("click", () => {
    nbEquipements++;
    let nameInput = "equipement" + nbEquipements;
    const inputPlusIconeSupprimer = createInputWithIconSupprimer(
      "Equipement disponible",
      nameInput
    );
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

  checkboxReglement.addEventListener("change", function () {
    if (this.checked) {
      creerAnnonce.disabled = false;
    } else {
      creerAnnonce.disabled = true;
    }
  });

  /**
   * * Permet d'ajouter une chambre au clic sur le bouton "Ajouter une chambre"
   */
  addChambre.addEventListener("click", () => {
    let nbLitsChambreNumero;
    nbChambres++;
    nbLits++;
    let numeroChambre = "Chambre" + nbChambres;

    console.log(nbChambres);
    let titre = document.createElement("label");
    let btnAddLits = document.createElement("button");

    btnAddLits.textContent = "Ajouter lit";
    btnAddLits.className = "btnAddLits";

    titre.textContent = "Chambre " + nbChambres;
    let newElement = document.createElement("select");
    newElement.setAttribute("name", numeroChambre);
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
    iconSupprimer.src = "/public/icons/supprimer.svg";
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
     * Permet de supprimer une chambre ainsi que ses lits associés
     */
    iconSupprimer.addEventListener("click", function () {
      const parentDiv = this.parentElement;
      const parentDivUp = parentDiv.parentElement;
      parentDivUp.remove();
      nbChambres--;
      console.log(nbChambres);
    });

    btnAddLits.addEventListener("click", () => {
      const addLits = addLitsFunction();
      // Fais en sorte que le bouton "ajouter lit" soit toujours le dernier élément de la chambre
      divChambre.insertBefore(addLits, btnAddLits);
    });
  });

  /**
   * * Fonction qui crée un input ainsi qu'une image,
   * * les assemble dans une div, et return la div
   * @param {*} placeholder
   * @param {*} name
   * @param {*} id
   */
  function createInputWithIconSupprimer(placeholder, name, id) {
    let newElement = document.createElement("input");
    newElement.setAttribute("type", "text");
    newElement.setAttribute("size", "60");
    newElement.setAttribute("placeholder", placeholder);
    newElement.setAttribute("name", name);
    newElement.setAttribute("id", id);
    console.log(id);
    console.log(name);

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
