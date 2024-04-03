<?php

    session_start(); 
    //connexion à la base de donnée
    try {
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $currentDate = date('Y-m-d');
        $stmt = $pdo->prepare("SELECT idClient FROM ldc.AvisLogement WHERE idClient = :idClient AND idLogement = :idLogement");
        $stmt->bindParam(':idClient', $reservation[5]);
        $stmt->bindParam(':idLogement', $reservation[0]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = null;
    } catch (PDOException $e) {
        $result = null;
    }

    if(isset($_GET["json"])) {
        $json = json_encode($result);
        echo $json;
    }

?>