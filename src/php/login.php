<?php
session_start();
$header = "Location: /index.php";

if (isset($_GET['deconnexion'])) {
    session_unset();
    session_destroy();
} else {
    $identifiant = $_POST['identifiant'];
    $mdp = $_POST['mdp'];

    $error_id = true;
    $error_mdp = true;

    $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=root");
    $stmt = $pdo->prepare("SELECT * FROM ldc.Client");

    $stmt->execute();

    //Recherche de l'identifiant et du mdp dans la base de données
    while ($row = $stmt->fetch()) {
        if ($row[8] == $identifiant){ //$row[8] correspond au pseudo
            $error_id = false;
            if ($row[9] == $mdp){ //$row[9] correspond au mdp
                $error_mdp = false;
                $id = $row[0];
            }
            break;
        } 
    }

    //Recherche si l'identifiant est un propriétaire
    $_SESSION['proprio'] = false;
    if (($error_id == false) && ($error_mdp == false)) {
        $stmt = $pdo->prepare("SELECT * FROM ldc.Proprietaire");

        $stmt->execute();

        while ($row = $stmt->fetch()) {
            if ($row[0] == $id){ //$row[0] correspond à l'id du propriétaire
                $_SESSION['proprio'] = true;
                break;
            } 
        }
    }

    $pdo = null;

    if ($error_id) {
        $header = "Location: /src/php/connexion.php?error=id";
    } elseif ($error_mdp){
        $header = "Location: /src/php/connexion.php?error=mdp&identifiant=".$identifiant;
    } else {
        $_SESSION['id'] = $id;
        $_SESSION['pseudo'] = $identifiant;
    }  
}
header($header);