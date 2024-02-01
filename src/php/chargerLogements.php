<?php
    session_start(); 
    //connexion à la base de donnée
    try {
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $stmt = $pdo->prepare("SELECT numLogement,libelle,nbPersMax,tarifNuitees,LogementEnLigne,ville,note,typeLogement FROM ldc.Logement");

        //Recherche des logements dans la base de données
        $stmt->execute();
        $logements = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $logements[] = $row;
        }

        $pdo = null;
    } catch (PDOException $e) {
        $logements = array();
    }

    $json = json_encode($logements);
        echo $json;

    if(isset($_GET["json"])) {
        $json = json_encode($logements);
        echo $json;
    }
    ?>