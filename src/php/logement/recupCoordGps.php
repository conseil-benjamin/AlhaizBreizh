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
    $data = file_get_contents($url, false, $context);
    $json = json_decode($data, true);

    if (isset($json[0])) {
        $coordX = $json[0]['lat'];
        $coordY = $json[0]['lon'];
    } else {
        $coordX = null;
        $coordY = null;
    }
    return [$coordX, $coordY];
}

function appoximationCoord($coordX, $coordY){

    $random = mt_rand(-9, 9) / 1000;
    $coordX += $random;
    $random = mt_rand(-9, 9) / 1000;
    $coordY += $random;
    
    return [$coordX, $coordY];
}