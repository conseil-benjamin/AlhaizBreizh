<?php
session_start(); 
//connexion à la base de donnée
try {
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $num = isset($_GET['num']) ? $_GET['num'] : '';

    $stmt = $pdo->prepare("SELECT pd.datedebutplage, pd.datefinplage FROM ldc.plagededisponibilite pd JOIN ldc.calendrier c ON pd.numcal = c.numcal WHERE c.numlogement = $num");

    //Recherche des logements dans la base de données
    $stmt->execute();
    $logements = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        print_r($row);
    }

    $pdo = null;
} catch (PDOException $e) {
    $logements = array();
}
?>
