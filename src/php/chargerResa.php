<?php
session_start();

// Connexion à la base de données
try {
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Assurez-vous que la session 'id' est définie pour éviter des erreurs
if (isset($_SESSION['id'])) {
    function obtenirLogementsProprio($id) {
        $logements = array();
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
            $stmt = $pdo->prepare("SELECT DISTINCT 
            numLogement,
            libelle,
            dateDebut,
            dateFin,
            Reservation.numClient,
            Client.pseudoCompte,
            proprio,
            numReservation,
            proprio.firstName as prenom_proprio,
            proprio.lastName as nom_proprio,
            Logement.tarifNuitees,
            Logement.ville
            FROM ldc.Reservation
            NATURAL JOIN ldc.Logement 
            INNER JOIN ldc.Client on ldc.Reservation.numClient = idCompte 
            INNER JOIN ldc.Client as proprio ON proprio.idCompte = Logement.proprio 
        WHERE 
            Client.idCompte = $id;");

    //requete github 2h
    //$stmt = $pdo->prepare("SELECT DISTINCT numLogement,libelle,dateDebut,dateFin,idCompte, pseudoCompte,proprio,numReservation FROM ldc.Reservation NATURAL JOIN ldc.Logement NATURAL JOIN ldc.Client WHERE proprio = $id ");

        $stmt->execute();
        $logements = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $logements[] = $row;
            
        }

        $pdo = null;
    } catch (PDOException $e) {
        $logements = array();
    }
    return $logements;
}

} else {
    // Redirige l'utilisateur vers la page de connexion
    header("Location: /src/php/connexion/connexion.php");
    exit();
}

$reservations = obtenirLogementsProprio($_SESSION['id']);

    if(isset($_GET["json"])) {
        $json = json_encode($reservations);
        echo $json;
    }
    ?>