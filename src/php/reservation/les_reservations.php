<?php
session_start();
// Vérifier si l'utilisateur est connecté en tant que propriétaire
if (!isset($_SESSION['id'])) {
    header('Location: /src/php/connexion/connexion.php');
} else if ($_SESSION['proprio'] == false) {
    header('Location: /');
}

// Connexion à la base de données
try {
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $stmt = $pdo->prepare("SELECT numlogement, libelle, datedebut, datefin, proprio FROM ldc.reservation NATURAL JOIN ldc.logement");
    

    // Recherche des réservations dans la base de données
    $stmt->execute();
    $reservation = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        if ($row[1] == $_SESSION['id']) {
            $reservation[] = $row;

            // Récupération du pseudo du client
        }
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
    <title>ALHaiz Breizh</title>
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/header.php'); ?>

    <div id="content">
        <h2>Reservations</h2>
        <div id="options">
            <div>
                <input class="textfield" type="text" placeholder="Search..">
                <button class="boutton">Filtrer</button>
                <button class="boutton">Trier</button>
            </div>
        </div>
        <div id="logements">
            <?php
            if (count($reservation) === 0) {
                ?>
                <h3>Vous n'avez aucune réservation pour le moment :/</h3>
                <?php
            } else {
                /*Création d'une carte pour chaque réservation*/
                foreach ($reservation as $uneReservation) {
                    ?>
                    <a href="/src/php/logement/PageDetailLogement.php?numLogement=<?php echo $uneReservation[0] ?>">
                        <div class="logement">
                            <img src="/public/img/logements/<?php echo $uneReservation[0] ?>/1.png" alt="logement">
                        </div>
                    </a>
                    <div>
                        <a href="/src/php/afficherPlageDispo.php?dateDebut=<?php echo $uneReservation[2] ?>&dateFin=<?php echo $uneReservation[3] ?>">
                            <h3><?php echo $uneReservation[2] ?></h3>
                            <!-- Affiche la date de début de la réservation -->
                            <p><?php echo $uneReservation[3] ?></p>
                            <!-- Affiche la date de fin de la réservation -->
                        </a>

                        <div>
                            <a href="/src/php/profil.php?pseudo=<?php echo $uneReservation[4] ?>">
                                <img src="/public/icons/user.svg" alt="Contact">
                                <?php echo $uneReservation[4] ?>
                            </a>
                        </div>
                        <a class="boutton" href="/src/php/reservation/supprimerResaDB.php?numLogement=<?php echo $uneReservation[0]; ?>">
                            <img src="/public/icons/croix.svg" alt="Supprimer">Supprimer
                        </a>
                    </div>
                <?php
                }
            }
            ?>
        </div>
    </div>
    
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/footer.php'); ?>
</body>
</html>
