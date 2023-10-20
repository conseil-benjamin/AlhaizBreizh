<?php
session_start();
$header = "Location: /index.php";

if (isset($_GET['deconnexion'])) {
    session_unset();
    session_destroy();
} else {
    $identifiant = $_POST['identifiant'];
    $mdp = $_POST['mdp'];

    if ($identifiant == "gege" && $mdp == "1234") {
        $_SESSION['id'] = $identifiant;
        $_SESSION['proprio'] = false;
    } elseif ($identifiant == "propro" && $mdp == "azerty") {
        $_SESSION['id'] = $identifiant;
        $_SESSION['proprio'] = true;
    } elseif ($identifiant == "gege" || $identifiant == "propro") {
        $header = "Location: /src/php/connection.php?error=mdp";
    } else{
        $header = "Location: /src/php/connection.php?error=id";
    }
}

header($header);