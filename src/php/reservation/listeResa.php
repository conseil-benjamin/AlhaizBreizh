<?php
global $numLogement;
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
            $stmt = $pdo->prepare("SELECT DISTINCT numLogement,libelle,dateDebut,dateFin,Reservation.numClient,Client.pseudoCompte,proprio,numReservation,     proprio.firstName as prenom_proprio,
            proprio.lastName as nom_proprio FROM ldc.Reservation NATURAL JOIN ldc.Logement inner JOIN ldc.Client on ldc.Reservation.numClient=idCompte INNER JOIN
            ldc.Client as proprio ON proprio.idCompte = Logement.proprio WHERE Client.idCompte = $id;");

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

    // Affichez un message d'erreur
    echo '<div class="alert-danger"> <h1>Vous n\'êtes pas connecté. Veuillez vous connecter pour accéder à vos réservations.</h1> </div>';
     // Masquer la barre de recherche
     echo '<style>
     .recherche {
         display: none;
        }
        </style>';

        // Masquer les options de tri et de filtrage
        echo '<style>
            .options {
                display: none;
            }
        </style>';

}

$reservations = obtenirLogementsProprio($_SESSION['id']);
?>


<!DOCTYPE html>
<html lang="fr-fr">

<head>
    <!-- Balises meta pour le jeu de caractères, la compatibilité et la vue -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Feuilles de style -->
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/index.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/style_Liste_resa.css">

    <!-- Titre de la page -->
    <title>ALHaiz Breizh</title>
</head>

<!-- Section En-tête -->
<?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>

<!-- Section Corps -->
<body>
    <!-- Section Contenu -->
    <div class="content">
        <!-- Recherche et options de réservation -->
        <div class="recherche">
            <h1>Mes Réservations</h1>
            <div id="options">
                <div>
                    <input type="text" placeholder="Rechercher..">
                    <button class="boutton-tri">Date</button>
                    <button class="boutton-tri">Prix</button>
                </div>
            </div>
        </div>
  
      
        <!-- Affiche les cartes de réservation -->
        <?php foreach ($reservations as $reservation): ?>
            <div class="card-container">    
                <div class="reservation-card">
                    <a href="/src/php/logement/PageDetailLogement.php?numLogement=<?php echo $reservation[0] ?>">
                        <div class="logement">
                            <img src="/public/img/logements/<?php echo $reservation[0]; ?>/1.png" alt="Photo du logement">
                        </div>
                    </a>
                    <div class="infos">
                        <h2><?php echo $reservation[1]; ?></h2>
                        <div class="details">
                            <p>Date d'arrivée : <?php echo $reservation[2]; ?></p>
                            <p>Date de départ : <?php echo $reservation[3]; ?></p>
                        </div>
                        <a href="/src/php/profil/profil.php?user=<?php echo $reservation[6] ?>">
                            <div class="profile">
                                <img src="/public/img/photos_profil/<?php echo $reservation[6]; ?>.png" alt="Photo de profil">
                                <p><?php echo "$reservation[8]"." $reservation[9]"; ?></p>
                            </div>
                        </a>
                    </div>
                    <label class="button-etat" for="status">État Réservation</label>
                    <div>
                        <button class="rond-button">
                            <div class="contract">
                                <img src="/public/icons/contract.svg" alt="">
                            </div>
                        </button>
                    </div>
                    <div>
                    <a  href="/src/php/reservation/details_reservation.php?numReservation=<?php echo $reservation[7]?>" class="button-resa">Voir Réservation</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Section Pied de page -->
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
</body>

</html>
