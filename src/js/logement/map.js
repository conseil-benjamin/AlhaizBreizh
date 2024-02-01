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

//Map avec leaftlet

//Approximation des coordonnées gps précises
coordX = parseFloat(coordX);
coordY = parseFloat(coordY);

//nombre random entre -9 et 9
let random = Math.random() * (9 - (-9)) + (-9);
coordX = coordX + (random / 1000);
random = Math.random() * (9 - (-9)) + (-9);
coordY = coordY + (random / 1000);

limites = [
    [limites[0], limites[2]], // coin inférieur gauche
    [limites[0], limites[3]], // coin inférieur droit
    [limites[1], limites[3]], // coin supérieur droit
    [limites[1], limites[2]]  // coin supérieur gauche
];

//Caluler le radius en fonction de la distance entre les deux points
let distance = Math.sqrt(Math.pow((parseFloat(limites[1][0])-parseFloat(limites[0][0])), 2) + Math.pow((parseFloat(limites[3][1])-parseFloat(limites[2][1])), 2));
let radius = distance * 1000000 / 2;

let mapContent = document.getElementById('map');
let map = L.map('map').setView([coordX, coordY], 10);
let popup = L.popup()
    .setLatLng([coordX, coordY])
    .setContent("<h1>"+localisation+"</h1><a target='_blank' class='.boutton' href='https://www.google.com/maps/search/?api=1&query=" + coordX + "," + coordY + "'><strong>Voir sur Google Maps</strong></a>")
    .openOn(map);

var pin = L.icon({
    iconUrl: '/public/icons/pin.svg',
    iconSize:     [38, 95], // size of the icon
    iconAnchor:   [20, 80], // point of the icon which will correspond to marker's location
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});
let marker = L.marker([coordX, coordY], {icon: pin}).addTo(map).bindPopup(popup);

drawTownBoundary(localisation);


let circle = L.circle([coordX, coordY], {
    color: '#16A1EF',
    fillColor: '#16A1EF',
    fillOpacity: 0.5,
    radius: 1000
}).addTo(map);

L.control.scale().addTo(map);

// Ajout d'une tuile
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);
