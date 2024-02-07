/*******************************************************/
/*Fonctions*/



/*******************************************************/
/*Afficher/Fermer la carte*/

//Récupérer la carte
let mapDiv = document.querySelector('.map');
mapDiv.style.display = "none";
var mapAffichee = false;

var bretagne = [48.202047, -3.832382];
var mapX = bretagne[0];
var mapY = bretagne[1];

//Boutton pour afficher la carte
let bouttonOpenMap = document.getElementById('bouttonMap');
bouttonOpenMap.addEventListener('click', function() {
    mapDiv.style.display = "block";
    mapAffichee = true;
    document.body.style.overflow = "hidden";
    map.invalidateSize();
});

//Boutton pour cacher la carte
let bouttonCloseMap = document.getElementById('bouttonCloseMap');
bouttonCloseMap.addEventListener('click', function() {
    mapDiv.style.display = "none";
    mapAffichee = false;
    document.body.style.overflowY = "auto";
    document.getElementById('logements').scrollIntoView();
});

/*******************************************************/
/*Initialisation de la carte*/

//Map avec leaftlet
let bzhLayer = L.tileLayer('https://tile.openstreetmap.bzh/br/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
});

let osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
});

let topoLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenTopoMap contributors'
});

let baseMaps = {
    "Bretonne": bzhLayer,
    "OpenStreetMap": osmLayer,
    "Topographic": topoLayer
};

let map = L.map('map', {
    layers: [bzhLayer], // set the default layer
    closePopupOnClick: false,
    zoom: 9,
    invalidateSize: true,
    minZoom: 8,
});

map.setView([mapX, mapY], 8);
L.control.layers(baseMaps).addTo(map); // Add the layer control to the map

var pin = L.icon({
    iconUrl: '/public/icons/pin.svg',
    iconSize:     [38, 95], // size of the icon
    iconAnchor:   [20, 80], // point of the icon which will correspond to marker's location
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

//Pour chaque adresse, on ajoute un marqueur
Object.keys(adresses).forEach(id => {
    let adresse = adresses[id];
    console.log(adresse);

    let popup = L.popup()
        .setLatLng(adresse)
        .setContent("<h1>"+localisations[id]+"</h1><a target='_blank' class='.boutton' href='/src/php/logement/PageDetailLogement.php?numLogement="+id+"'><strong>Voir logement</strong></a>")
        .openOn(map);

    let marker = L.marker(adresse, {icon: pin}).addTo(map);
});

/*******************************************************/
/*Afficher les logements suivant le zoom*/

var logements = document.querySelectorAll('.logement');

//Vérifier les markers qui ne sont pas visibles suite à un zoom
function adaptMarkersOnZoomAndMove(){
    if (mapDiv.style.display === "none") {
        return; // Si la carte n'est pas affichée, on ne fait rien
    }
    mapX = map.getCenter().lat;
    mapY = map.getCenter().lng;
    Object.keys(adresses).forEach(id => {
        let adresse = adresses[id];
        //Vérifier si le marker n'est pas visible
        let point = map.latLngToContainerPoint(adresse);
        let size = map.getSize();
        let logement = document.getElementById('logement'+id);
        if(point.x < 0 || point.y < 0 || point.x > size.x || point.y > size.y) {
            console.log("logement"+id+" n'est pas visible");
            logement.style.display = "none";
        } else {
            console.log("logement"+id+" est visible");
            logement.style.display = "flex";
        }
    });
    testAucunLogementVisible();
}

function testAucunLogementVisible() {
    //Afficher un message si aucun logement n'est visible
    var aucunLogementVisible = true;
    Object.keys(adresses).forEach(id => {
        let logement = document.getElementById('logement'+id);
        if (logement.style.display === "flex") {
            aucunLogementVisible = false;
        }
    });
    if (aucunLogementVisible) {
        document.getElementById('aucunLogementVisible').style.display = "block";
    } else {
        document.getElementById('aucunLogementVisible').style.display = "none";
    }
}

map.on('zoomend', function() {
    adaptMarkersOnZoomAndMove();
});

map.on('moveend', function() {
    adaptMarkersOnZoomAndMove();
});

/*******************************************************/
/*Resset la vue sur la carte*/

let bouttonResetMap = document.getElementById('bouttonResetMap');
bouttonResetMap.addEventListener('click', function() {
    map.setView(bretagne, 8);
});