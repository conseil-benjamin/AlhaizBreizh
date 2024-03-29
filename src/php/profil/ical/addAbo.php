<?php
session_start();

if ($_SESSION['proprio'] != true){
    header('Location: /');
    exit();
} else{
    $id = $_SESSION['id'];

    do {
        try {
            $newToken = bin2hex(random_bytes(25));
        } catch (\Random\RandomException $e) {
            echo $e;
        }

        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $stmt = $pdo->prepare("SELECT token FROM ldc.tokenical WHERE token = :cle");
        //Vérifier que la clé n'existe pas déjà
        $stmt->bindParam(':cle', $newToken);
        $stmt->execute();
        $pdo = null;

    } while ($stmt->rowCount() > 0);


    $date_fin = "";
    $date_debut = "";
    $logements = array();
    foreach ($_GET as $cle => $value) {
        if ($cle == "date_fin") {
            $date_fin = $value;
        }
        elseif ($cle == "date_debut") {
            $date_debut = $value;
        }
        else {
            $logements[] = $cle;
        }
    }
    //Ajouter le token à la base de données
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $stmt = $pdo->prepare("INSERT INTO ldc.tokenICal (date_debut,date_fin,token,id_proprio) VALUES (:debut,:fin, :cle,:id)");
    $stmt->bindParam(':debut', $date_debut);
    $stmt->bindParam(':fin', $date_fin);
    $stmt->bindParam(':cle', $newToken);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    print_r($logements);
    foreach ($logements as $loge) {
        $stmt2 = $pdo->prepare("INSERT INTO ldc.logements_tokenical (token, num_logement) VALUES (:cle,:lg)");
        $stmt2->bindParam(':cle', $newToken);
        $stmt2->bindParam(':lg',$loge);
        $stmt2->execute();
    }
    $pdo = null;

    header('Location: /src/php/profil/ical/index.php');
    exit();
}
?>