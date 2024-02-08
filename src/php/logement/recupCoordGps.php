<?php
$context = stream_context_create(
    array(
        'http' => array(
            'method' => 'GET',
            'header' => "User-Agent: MyApplication/1.0\r\n"
        )
    )
);
function recupCoordGps($adresse){
    $url = "https://nominatim.openstreetmap.org/search?q=".$adresse."&format=json&polygon=1&addressdetails=1";
    global $context;
    try {
        $data = file_get_contents($url, false, $context);
        $json = json_decode($data, true);
        if (isset($json[0])) {
            $coordX = $json[0]['lat'];
            $coordY = $json[0]['lon'];
        } else {
            $coordX = null;
            $coordY = null;
        }
    } catch (Exception $e) {
        $coordX = null;
        $coordY = null;
    }
    return [$coordX, $coordY];
}

function recupAllCoordGps($adresses, $approximation = false){
    $coords = [];
    foreach ($adresses as $adresse) {
        $coord = recupCoordGps($adresse);
        if ($approximation) {
            $coord = appoximationCoord($coord[0], $coord[1]);
        }
        if ($coord[0] != null && $coord[1] != null) {
            $coords[] = $coord;
        }
    }
    return $coords;
}

function appoximationCoord($coordX, $coordY){

    $random = mt_rand(-9, 9) / 1000;
    $coordX += $random;
    $random = mt_rand(-9, 9) / 1000;
    $coordY += $random;
    
    return [$coordX, $coordY];
}