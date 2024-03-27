export async function recupCoordGps(adresse, idLogement = null, approximation = false) {
    adresse = adresse.replace(/ /g, '+');
    let coordX = null;
    let coordY = null;

    //Récupérer les coordonnées dans la BDD
    if (idLogement != null) {
        [coordX, coordY] = await recupCoordGpsBDD(idLogement);
    }

    console.log(coordX, coordY);

    if (coordX == null || coordY == null){
        console.log("Coordonnées non trouvées dans la BDD, requête à l'API en cours...");
        try {
            const url = `https://nominatim.openstreetmap.org/search?q=${adresse}&format=json&polygon=1&addressdetails=1`;
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'User-Agent': 'MyApplication/1.0'
                }
            });
            const data = await response.json();
            if (data[0]) {
                coordX = data[0].lat;
                coordY = data[0].lon;

                //Enregistrer les coordonnées dans la BDD
                if (idLogement) {
                    await saveCoordGpsBDD(idLogement, coordX, coordY);
                }

                if (approximation) {
                    [coordX, coordY] = appoximationCoord(coordX, coordY);
                }
            }
        } catch (e) {
            console.error(e);
        }
    }
    
    return [coordX, coordY];
}

async function saveCoordGpsBDD(idLogement, coordX, coordY) {
    fetch('/src/php/logement/coords/insert.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            idLogement: idLogement,
            coordX: coordX,
            coordY: coordY
        })
    });
}

async function recupCoordGpsBDD(idLogement) {

    const response = await fetch("/src/php/logement/coords/getCoords.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            idLogement: idLogement
        })
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    if (response.headers.get('content-length') === '0') {
        return [null, null];
    }

    const data = await response.json();
    return [data.coordx, data.coordy];
}


export async function recupAllCoordGps(adresses, approximation = false) {
    const coords = [];
    adresses = Object.entries(adresses);

    for (const [id, adresse] of adresses) {
        let coord = await recupCoordGps(adresse);
        if (approximation) {
            coord = appoximationCoord(coord[0], coord[1]);
        }
        if (coord[0] != null && coord[1] != null) {
            coords[id] = coord;
        }
    }
    return coords;
}

export function appoximationCoord(coordX, coordY) {
    const random = Math.floor(Math.random() * 19 - 9) / 1000;
    coordX = parseFloat(coordX) + random;
    coordY = parseFloat(coordY) + random;
    return [coordX.toFixed(6), coordY.toFixed(6)];
}