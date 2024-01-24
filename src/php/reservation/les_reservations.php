<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant que propriétaire
if (!isset($_SESSION['id']) || !$_SESSION['proprio']) {
    header('Location: /src/php/connexion/connexion.php');
    exit(); // Assurez-vous de quitter le script après une redirection
}

// Connexion à la base de données
try {
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    
    $idProprio = $_SESSION['id']; // ID du propriétaire connecté

    $stmt = $pdo->prepare("SELECT
    r.numReservation,
    r.dateDebut,
    r.dateFin,
    r.nbPersonnes,
    l.numLogement,
    l.libelle,
    c.idCompte AS idClient,
    c.pseudoCompte,
    l.proprio
    FROM ldc.reservation r
    JOIN ldc.logement l ON r.numLogement = l.numLogement
    JOIN ldc.client c ON r.numClient = c.idCompte
    WHERE l.proprio = :idProprio;");


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
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr-fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/mesLogements.css">
    <link rel="icon" href="/public/logos/logo-black.svg">
    <title>ALHaiz Breizh</title>
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/header.php'); ?>

    <div id="content">
        <h2>Les réservations</h2>
        <div id="options">
            <div>
                <input class="textfield" type="text" placeholder="Search..">
                <button class="boutton">Filtrer</button>
                <button class="boutton">Trier</button>
            </div>
        </div>
        <div id="logements">
            <?php
            if (count($reservation) === 0) { ?>
                <h3>Vous n'avez aucune réservation pour le moment :/</h3> <?php
            } else {
                foreach ($reservation as $uneReservation) {?>                        
                        <div class="logement">
                            <img src="/public/img/logements/<?php echo $uneReservation['numlogement'] ?>/1.png" alt="logement">
                            <div>
                                <a href="/src/php/afficherPlageDispo.php?dateDebut=<?php echo $uneReservation['datedebut'] ?>&dateFin=<?php echo $uneReservation['datefin'] ?>">
                                    <h3><?php echo $uneReservation['datedebut'] ?></h3>
                                    <p><?php echo $uneReservation['datefin'] ?></p>
                                </a>
                                <a href="/src/php/profil/profil.php?user=<?php echo $uneReservation['idclient'] ?>">
                                        <div class="profile">
                                            <p><?php echo $uneReservation['pseudocompte']; ?></p>
                                        </div>
                                </a>
                                <nav>
                                    <a class="boutton" href="/src/php/reservation/supprimerResaDB.php?numReservation=<?php echo $uneReservation['numreservation']?>"class="button-resa">Supprimer</a>
                                    <a class="boutton" href="/src/php/reservation/details_reservation.php?numReservation=<?php echo $uneReservation['numreservation']?>" class="button-resa">Voir Réservation</a>
                                </nav>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/footer.php'); ?>
</body>
</html>
