<?php
session_start();

if ($_SESSION['proprio'] != true){
    header('Location: /');
    exit();
} else{
    $id = $_SESSION['id'];

    do {
        $newApiKey = bin2hex(random_bytes(25));
        $droits = $_GET['rights'];
        $droitsAutorises = array('R', 'RU');
        if (!in_array($droits, $droitsAutorises)){
            header('Location: /src/php/profil/api/index.php');
            exit();
        }

        //Vérifier que la clé n'existe pas déjà
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $stmt = $pdo->prepare("SELECT apikey FROM ldc.APIkey WHERE apikey = :cle");
        $stmt->bindParam(':cle', $newApiKey);
        $stmt->execute();
        $pdo = null;

    } while ($stmt->rowCount() > 0);

    //Ajouter la clé API à la base de données
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $stmt = $pdo->prepare("INSERT INTO ldc.APIkey (id_proprio, apikey, droit, est_admin) VALUES (:id, :cle, :droits, FALSE)");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':cle', $newApiKey);
    $stmt->bindParam(':droits', $droits);
    $stmt->execute();
    $pdo = null;

    header('Location: /src/php/profil/api/index.php');
    exit();
}

