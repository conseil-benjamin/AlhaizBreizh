<?php

    session_start(); 
    //connexion à la base de donnée
    try {
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $currentDate = date('Y-m-d');
        $stmt = $pdo->prepare("SELECT idClient FROM ldc.AvisLogement WHERE idClient = :idClient AND idLogement = :idLogement");
        $stmt->bindParam(':idClient', $_POST['idcl']);
        $stmt->bindParam(':idLogement', $_POST['idlog']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = null;
    } catch (PDOException $e) {
        $result = null;
    }

    $json = json_encode($result);
    echo $json;

?>