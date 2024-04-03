let langues=[]
let mech = document.getElementById('add_lg');

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
    
    // Effacer d'abord le contenu de divNomsPhotos
    divNomsPhotos.innerHTML = '';

    for (let i = 0; i < photos.length; i++) {
        const nomPhoto = photos[i].name;
        let photo = document.createElement("p");
        photo.textContent = "Photo n°" + (i + 1) + ":" + nomPhoto;
        divNomsPhotos.appendChild(photo);
    }
}

input.addEventListener("change", afficherNomsPhotos);

mech.addEventListener('click', function() {
    let contenu = document.getElementById('languesparlees').value
    if (contenu){
        const divElement = document.createElement('div');
        divElement.classList.add('inputplusLangue');
    
        const iconSupprimer = document.createElement("img");
        iconSupprimer.src = "/public/icons/supprimer.svg";
        iconSupprimer.alt = "Icone supprimer";
        iconSupprimer.id = "iconSupprimer";
    
        let optioness = (document.getElementById('languesparlees').options)
        for(let i=optioness.length - 1;i>=0;i--){
            if (optioness[i].textContent.includes(contenu)){
                document.getElementById('languesparlees').remove(i)
            }
        }
    
        iconSupprimer.addEventListener('click', function() {
            divElement.parentNode.removeChild(divElement);
            optioness = (document.getElementById('languesparlees').options)
            let new_option = document.createElement("option");
            new_option.text=contenu
            new_option.value=contenu
            document.getElementById('languesparlees').add(new_option)
        });
    
        divElement.textContent = contenu;
        divElement.appendChild(iconSupprimer);
    
        this.parentNode.appendChild(divElement);
    }
})

