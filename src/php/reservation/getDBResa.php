<?php
$numReservation = $_GET["numReservation"];
global $pdo;
include("../connect.php");

try {
    // Requête pour récupérer les informations de la réservation
    $sqlResa = "SELECT * FROM ldc.reservation WHERE numreservation = :numReservation";
    $stmt = $pdo->prepare($sqlResa);
    $stmt->bindParam(':numReservation', $numReservation, PDO::PARAM_INT);
    $stmt->execute();
    $resultResa = $stmt->fetchAll();

    // Vérifier si la réservation a été trouvée
    if (count($resultResa) > 0) {
        $dateArr = $resultResa[0]["datedebut"];
        $dateDep = $resultResa[0]["datefin"];
        $dateDevis = $resultResa[0]["datedevis"];
        $dateResa = $resultResa[0]["datereservation"];
        $numLogement = $resultResa[0]["numlogement"];
        
        // Requête pour récupérer les informations du logement
        $sqlLogement = "SELECT libelle, proprio FROM ldc.devis INNER JOIN ldc.logement l ON devis.numlogement = l.numlogement WHERE devis.numreservation = :numReservation";
        $stmt = $pdo->prepare($sqlLogement);
        $stmt->bindParam(':numReservation', $numReservation, PDO::PARAM_INT);
        $stmt->execute();
        $resultLogement = $stmt->fetchAll();

        // Vérifier si le logement a été trouvé
        if (count($resultLogement) > 0) {
            $titreLogement = $resultLogement[0]["libelle"];
            $proprio = $resultLogement[0]["proprio"];

            // Requête pour récupérer les informations du propriétaire
            $sqlProprietaire = "SELECT firstName, lastName
                                FROM ldc.Proprietaire P
                                NATURAL JOIN ldc.Client C
                                WHERE idCompte = :proprio";
            $stmt = $pdo->prepare($sqlProprietaire);
            $stmt->bindParam(':proprio', $proprio, PDO::PARAM_INT);
            $stmt->execute();
            $resultProprietaire = $stmt->fetchAll();

            // Vérifier si le propriétaire a été trouvé
            if (count($resultProprietaire) > 0) {
                $prenom_proprio = $resultProprietaire[0]["firstName"];
                $nom_proprio = $resultProprietaire[0]["lastName"];
                $photo_profil_proprio = '/public/img/photos_profil/'.$proprio.'.png';
            }
        }
    }

    $pdo = null;
} catch (PDOException $e) {
    // En cas d'erreur, affichez un message d'erreur
    echo "Erreur : " . $e->getMessage();
    die();
}
?>
