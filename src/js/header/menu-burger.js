let header = document.getElementById('header');

/**************************************************************************/
/*Fermer le menu burger lorsqu'on appuie un bouton du menu*/

var elements = header.getElementsByTagName("a"); 
for(let i=0; i<elements.length; i++){
    elements[i].onclick = function(){ 
        document.querySelector('input').checked = false;
    }
}

/**************************************************************************/
/*Ajouter le boutton Accueil dans la liste du menu*/

let headerLogo = header.querySelector('.logo');
let headerNav = header.querySelector('nav > ul');


headerLogo.addEventListener('click', function(){
    if (window.innerWidth >= 900){
        window.location.href = '/index.php';
    } else{
        // Ajouter le bouton accueil dans le menu burger
        if (!headerNav.querySelector('.retourAccueil')){
            let accueil = document.createElement('a');
            accueil.setAttribute('href', '/index.php');
            accueil.innerHTML = '<li>Accueil</li>';
            accueil.classList.add('retourAccueil');
            headerNav.insertBefore(accueil, headerNav.firstChild);
        }
    }
});

window.addEventListener('resize', function(){
    if ((window.innerWidth >= 900) && (!headerLogo.querySelector('.retourAccueil'))){
        headerNav.removeChild(headerNav.querySelector('.retourAccueil'));
    } else{
        // Ajouter le bouton accueil dans le menu burger
        if (!headerNav.querySelector('.retourAccueil')){
            let accueil = document.createElement('a');
            accueil.setAttribute('href', '/index.php');
            accueil.innerHTML = '<li>Accueil</li>';
            accueil.classList.add('retourAccueil');
            headerNav.insertBefore(accueil, headerNav.firstChild);
        }
    }
});