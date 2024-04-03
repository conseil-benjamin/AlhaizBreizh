/*******************************************************/
/*Afficher/Fermer la carte*/

//Récupérer la carte
let mapDiv = document.querySelector('.map');
mapDiv.style.display = "none";
var chargementDiv = mapDiv.querySelector('.chargement-carte');
var texteChargement = chargementDiv.querySelector('p');
var loadingComplete = document.querySelector(".map > h2");
var pins = [];
var coordonnees = [];

var bretagne = [48.202047, -3.832382];
var mapX = bretagne[0];
var mapY = bretagne[1];

//Boutton pour afficher la carte
let bouttonOpenMap = document.getElementById('bouttonMap');
bouttonOpenMap.addEventListener('click', function() {

    markers = allMarkers;

    mapDiv.style.display = "block";
    if (loadingComplete.style.left === "1em") {
        loadingComplete.style.display = "none";
        loadingComplete.style.left = "-10em";
    }
    //Animation pour afficher la carte

    setTimeout(() => {
        mapDiv.style.transform = "translateY(0) translateX(0)";
        document.body.style.overflow = "hidden";
        map.invalidateSize();
        adaptMarkersVisibilityOnFilters();
    }, 100);
});

//Boutton pour cacher la carte
function closeMap() {
    
    adaptMarkersOnZoomAndMove();

    mapDiv.style.transform = "translate(200%, 100%)";

    setTimeout(() => {
        mapDiv.style.display = "none";
        adaptMarkersVisibilityOnFilters();
    }, 500);
    
    document.body.style.overflowY = "auto";
    document.getElementById('logements').scrollIntoView();
}

document.getElementById('bouttonCloseMap').addEventListener('click', closeMap);
//Si clic sur logements fermer la carte
document.querySelector("#header > nav > ul > a").addEventListener("click", closeMap);

/*******************************************************/
/*Initialisation de la carte*/

//Map avec leaftlet
let layerList = [];
let bzhLayer = L.tileLayer('https://tile.openstreetmap.bzh/br/{z}/{x}/{y}.png');
let osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
let topoLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png');
layerList.push(["Bretonne", bzhLayer]);
layerList.push(["OpenStreetMap", osmLayer]);
layerList.push(["Topographic", topoLayer]);

let baseMaps = {
    "Bretonne": bzhLayer,
    "OpenStreetMap": osmLayer,
    "Topographic": topoLayer
};

if (!baseMaps.hasOwnProperty(localStorage.getItem('layer'))){
    console.log("Layer non trouvé");
    localStorage.setItem('layer', 'Bretonne');
}

let map = L.map('map', {
    layers: [layerList.find(layer => layer[0] === localStorage.getItem('layer'))[1]],
    closePopupOnClick: false,
    zoom: 9,
    invalidateSize: true,
    minZoom: 8,
});

map.setView([mapX, mapY], 8);
L.control.layers(baseMaps).addTo(map); // Add the layer control to the map

var pinIcon = L.icon({
    iconUrl: '/public/icons/pin.svg',
    iconSize:     [38, 95], // size of the icon
    iconAnchor:   [20, 80], // point of the icon which will correspond to marker's location
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

var markers = L.markerClusterGroup({
    spiderfyOnMaxZoom: false,
    showCoverageOnHover: false,
    zoomToBoundsOnClick: true,
    disableClusteringAtZoom: 14,
    iconCreateFunction: function() {
		return pinIcon;
	}
});

var allMarkers = markers;

/*******************************************************/
/*Récupérer les coordonnees des logements*/
import {recupCoordGps} from '/src/js/logement/recupCoordGps.js';
async function fetchCoordinates() {

    var logements = document.querySelectorAll('.logement');
    let i = 1;
    let pinsLoaded = false;
    let pinsStorage = JSON.parse(sessionStorage.getItem('pins'));

    //Vérifier si la variable de session pins existe
    if (sessionStorage.getItem('pins') && (pinsStorage.length === logements.length)){
        if (pinsStorage.length === logements.length) {
            for (let pin of pinsStorage) {
                let marker = L.marker([pin.lat, pin.lng], {icon: pinIcon});
                marker.bindPopup(pin.popup, {autoClose: false});
                marker.on('click', closePopupOnClick.bind(null, marker));
                markers.addLayer(marker);
                pins.push(marker);
                coordonnees[i] = [pin.lat, pin.lng];
                i++;
            }
            map.addLayer(markers);
        }
        pinsLoaded = true;
        console.log("Pins chargés depuis le sessionStorage");
    }
    //Sinon, récupérer les coordonnées des logements
    if ((pinsLoaded == false) || (pinsStorage.length !== logements.length)){  
        for (let logement of logements) {
            let id = logement.id.substring(8);
            let titre = logement.querySelector('.titre-logement').innerText;
            let personnes = logement.querySelector('.nb-pers').innerText;
            let localisation = logement.querySelector('.localisation').innerText;
            let prix = logement.querySelector('.prix').innerText;

            let coords = await recupCoordGps(localisation, id, true);

            texteChargement.innerText = i+"/"+logements.length;

            if (coords[0] != null && coords[1] != null) {
                coordonnees[id] = coords;
                let marker = L.marker(coords, {icon: pinIcon})
                markers.addLayer(marker);
                map.addLayer(markers);
                marker.bindPopup("<h3>"+titre+"</h3>"+personnes+"<br>"+prix+"<br><a href='/src/php/logement/PageDetailLogement.php?numLogement="+id+"'><strong>Voir le Logement</strong></a>", {autoClose: false});
                //Fermer les popups si on clique sur un autre marker
                marker.on('click', closePopupOnClick.bind(null, marker));

                pins.push(marker);
            }
            i++;   
        }
        saveStoragePins(pins);
        console.log("Pins chargés depuis l'API");
    }
    loadingMapComplete();
}

function closePopupOnClick(marker) {
    map.eachLayer(function (layer) {
        if (layer instanceof L.Marker && layer.isPopupOpen() && layer !== marker) {
            layer.closePopup();
        }
    });
}

function saveStoragePins(pins) {
    let simplePins = pins.map(marker => {
        return {
            lat: marker.getLatLng().lat, 
            lng: marker.getLatLng().lng, 
            popup: marker.getPopup().getContent()
        };
    });
    
    sessionStorage.setItem('pins', JSON.stringify(simplePins));
}

function loadingMapComplete() {
    chargementDiv.style.display = "none";
    loadingComplete.style.display = "block";
    setTimeout(() => {
        loadingComplete.style.left = "1em";
        setTimeout(() => {
            loadingComplete.style.left = "-10em";
        }, 2000);
    }, 100);
}

/*******************************************************/
/*Afficher les logements suivant le zoom*/

//Vérifier les markers qui ne sont pas visibles suite à un zoom
function adaptMarkersOnZoomAndMove(){
    mapX = map.getCenter().lat;
    mapY = map.getCenter().lng;
    Object.keys(coordonnees).forEach(id => {
        let adresse = coordonnees[id];
        //Vérifier si le marker n'est pas visible
        let point = map.latLngToContainerPoint(adresse);
        let size = map.getSize();
        let logement = document.getElementById('logement'+id);
        if(point.x < 0 || point.y < 0 || point.x > size.x || point.y > size.y) {
            logement.classList.add('filtremap');
        } else {
            logement.classList.remove('filtremap');
        }
    });
    enfer();
}

function adaptMarkersVisibilityOnFilters() {

    for (let marker of pins) {
        let id = marker._popup._content.split('numLogement=')[1].split('\'')[0];
        if (isLogementFiltered(id)) {
            markers.removeLayer(marker);
        } else {
            markers.addLayer(marker); 
        }
    }
}

function isLogementFiltered(id){
    return document.getElementById('logement'+id).classList.contains('filtredefaut');
}

fetchCoordinates();

/*******************************************************/
/*Reset la vue sur la carte*/

let bouttonResetMap = document.getElementById('bouttonResetMap');
bouttonResetMap.addEventListener('click', function() {
    map.setView(bretagne, 8);
});

/*******************************************************/
/*Sauvegarder en localStorage le layer sélectionné*/

map.on('baselayerchange', function(e) {
    localStorage.setItem('layer', e.name);
});

/*******************************************************/

window.addEventListener('resize', function(){
    map.invalidateSize();
});

window.addEventListener('reset', function(){
    map.invalidateSize();
});