//Map avec leaftlet

var bzhLayer = L.tileLayer('https://tile.openstreetmap.bzh/br/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
});

var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
});

var topoLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenTopoMap contributors'
});

var baseMaps = {
    "Bretonne": bzhLayer,
    "OpenStreetMap": osmLayer,
    "Topographic": topoLayer
};

var pin = L.icon({
    iconUrl: '/public/icons/pin.svg',
    iconSize:     [38, 95], // size of the icon
    iconAnchor:   [20, 80], // point of the icon which will correspond to marker's location
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

import {recupCoordGps, appoximationCoord} from '/src/js/logement/recupCoordGps.js';
async function fetchCoordinates() {

    var [coordX, coordY] = await recupCoordGps(localisation);

    //On récupère seulement le nom de la ville
    localisation = localisation.split(' ')[0];

    if (!estProprio){
        [coordX, coordY] = appoximationCoord(coordX, coordY);
    }

    let map = L.map('map', {
        center: [coordX, coordY-100],
        zoom: 14,
        layers: [bzhLayer], // set the default layer
        closePopupOnClick: false,
        gestureHandling: true

    }).setView([coordX, coordY], 10);

    L.control.layers(baseMaps).addTo(map); // Add the layer control to the map

    let popup = L.popup()
        .setLatLng([coordX, coordY])
        .setContent("<h1>"+localisation+"</h1><a target='_blank' class='.boutton' href='https://www.google.com/maps/search/?api=1&query=" + coordX + "," + coordY + "'><strong>Voir sur Google Maps</strong></a>")
        .openOn(map);

    let marker = L.marker([coordX, coordY], {icon: pin}).addTo(map).bindPopup(popup);

    let circle = L.circle([coordX, coordY], {
        color: '#16A1EF',
        fillColor: '#16A1EF',
        fillOpacity: 0.5,
        radius: 1000
    }).addTo(map);

    function drawTownBoundary(town){
        let url = `https://nominatim.openstreetmap.org/search.php?city=${town}&polygon_geojson=1&format=jsonv2`
        fetch(url).then(function(response) {
            return response.json();
        })
        .then(function(json) {
            let geojsonFeature = json[0].geojson;
            L.geoJSON(geojsonFeature).addTo(map);
        });
    }

    drawTownBoundary(localisation);
}

fetchCoordinates();