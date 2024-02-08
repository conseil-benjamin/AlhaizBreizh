export async function recupCoordGps(adresse) {
    const url = `https://nominatim.openstreetmap.org/search?q=${adresse}&format=json&polygon=1&addressdetails=1`;
    let coordX = null;
    let coordY = null;
    try {
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
        }
    } catch (e) {
        console.error(e);
    }
    return [coordX, coordY];
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

function appoximationCoord(coordX, coordY) {
    const random = Math.floor(Math.random() * 19 - 9) / 1000;
    coordX = parseFloat(coordX) + random;
    coordY = parseFloat(coordY) + random;
    return [coordX.toFixed(6), coordY.toFixed(6)];
}