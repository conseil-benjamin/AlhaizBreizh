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
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
            
            $idProprio = $_SESSION['id']; // ID du propriétaire connecté

            $stmt = $pdo->prepare(
            "SELECT
            r.numReservation,
            r.dateDebut,
            r.dateFin,
            r.nbPersonnes,
            l.numlogement,
            l.libelle,
            c.idCompte AS idClient,
            c.firstname,
            c.lastname,
            c.pseudoCompte,
            l.proprio,
            l.ville,
            l.typeLogement,
            l.tarifNuitees
            FROM ldc.reservation r
            JOIN ldc.logement l ON r.numlogement = l.numlogement
            JOIN ldc.client c ON r.numClient = c.idCompte
            WHERE l.proprio = :idProprio;"
            );


            // Liaison de la variable :idProprio
            $stmt->bindParam(':idProprio', $idProprio, PDO::PARAM_INT);

            // Recherche des réservations dans la base de données
            $stmt->execute();
            $reservation = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Ajouter la logique nécessaire ici
                $reservation[] = $row;
            }

            $pdo = null;
    } catch (PDOException $e) {
        $reservation = array();
    }
    return $reservation;
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