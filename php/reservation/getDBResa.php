<?php
$numReservation = $_GET["numLogement"] ?? 1;
global $pdo;
include("../connect.php");
try {
    $sqlResa = "SELECT * FROM ldc.reservation where numreservation=$numReservation";
    $sqlLogement ="SELECT libelle,devis.numLogement,l.proprio FROM ldc.devis INNER JOIN ldc.logement l on devis.numlogement = l.numlogement";
    $stmt = $pdo->prepare($sqlResa);
    $stmt->execute();
    $resultResa = $stmt->fetchAll();
    $dateArr = $resultResa[0]["datedebut"];
    $dateDep = $resultResa[0]["datefin"];
    $dateDevis = $resultResa[0]["datedevis"];
    $dateResa = $resultResa[0]["datereservation"];
    $stmt = $pdo->prepare($sqlLogement);
    $stmt->execute();
    $resultLogement = $stmt->fetchAll();
    $titreLogement = $resultLogement[0]["libelle"];
    $numLogement = $resultLogement[0]["numlogement"];
    $proprio = $resultLogement[1]["proprio"];

    $stmt = $pdo->prepare("SELECT firstName,lastName,languesParlees
                                    FROM ldc.Proprietaire P
                                    NATURAL JOIN ldc.Client C
                                    WHERE idCompte = $proprio");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $prenom_proprio = $row[0] ?? null;
        $nom_proprio = $row[1] ?? null;
    }
    $photo_profil_proprio = '/public/img/photos_profil/'.$proprio.'.png';
    $pdo = null;
} catch (PDOException $e) {
    // En cas d'erreur, affichez un message d'erreur
    echo "Erreur : " . $e->getMessage();
    die();
}

?>