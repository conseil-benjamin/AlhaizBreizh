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
            proprio.lastName as nom_proprio, ville FROM ldc.Reservation NATURAL JOIN ldc.Logement inner JOIN ldc.Client on ldc.Reservation.numClient=idCompte INNER JOIN
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
    ?><!DOCTYPE html>
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
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
                    <div class="wrapper">
                        <video autoplay playsinline muted loop preload poster="http://i.imgur.com/xHO6DbC.png">
                            <source src="/public/videos/video-bretagne.mp4" />
                        </video>
                        <div class="container">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 285 80" preserveAspectRatio="xMidYMid slice">
                                <defs>
                                    <mask id="mask" x="0" y="0" width="100%" height="100%">
                                        <rect x="0" y="0" width="100%" height="100%" />
                                        <!-- Texte principal -->
                                        <text x="50%" y="50%" text-anchor="middle" alignment-baseline="middle" font-family="NoirPro" font-weight="200" text-transform="uppercase" font-size="20">
                                            ALHaIZ Breizh
                                        </text>
                                    </mask>
                                </defs>
                                <!-- Rectangle pour masquer le texte principal -->
                                <rect x="0" y="0" width="100%" height="100%" mask="url(#mask)" />
                            </svg>
                            <!-- Lien vers la page d&#39;accueil avec un message d&#39;erreur -->
                            <a class="lien" href="/accueil" target="_blank">
                                <div>
                                    Cette page est inexistante. Cliquez ici pour retourner à l&#39;accueil.
                                </div>
                            </a>
                        </div>
                    </div>

        <style>
     .recherche {
         display: none;
        }
        .options {
                display: none;
            }
    body{
        min-height:0;
    }
        </style>
    </body>
<?php
}

$reservations = obtenirLogementsProprio($_SESSION['id']);
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
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
    <div id="content">
        <h2>Mes réservations</h2>
        <div id="options">
            <div>
                <input class="textfield" type="text" placeholder="Rechercher..">
                <button class="boutton">Filtrer</button>
                <button class="boutton">Trier</button>
            </div>
        </div>
        <div id="logements">
            <?php 
            if (count($reservations) === 0) { ?>
                <h3>Vous n'avez aucune réservation pour le moment :/</h3> <?php
            } else {
                foreach ($reservations as $reservation){ ?>
                    <div class="logement">
                            <img src="/public/img/logements/<?php echo $reservation[0]; ?>/1.png" alt="Photo du logement">
                        <div>
                            <h2><?php echo $reservation[1]; ?></h2>
                            <a href="/src/php/afficherPlageDispo.php?dateDebut=<?php echo $reservation[2] ?>&dateFin=<?php echo $reservation[3] ?>">
                                <h4><?php echo $reservation[2]; ?></h4>
                                <h4><?php echo $reservation[3]; ?></h4>
                            </a>
                            <a href="/src/php/profil/profil.php?user=<?php echo $reservation[6] ?>">
                                <div class="profile">
                                    <p><?php echo "$reservation[8]"." $reservation[9]"; ?></p>
                                </div>
                            </a>
                            <nav>
                                <a class="boutton" href="/src/php/reservation/details_reservation.php?numReservation=<?php echo $reservation[7]?>">Voir Réservation</a>
                                <?php foreach ($reservations as $reservation) {
                                        // Convertir la date de fin de la réservation en objet DateTime
                                        $dateFinReservation = new DateTime($reservation[3]);
                                        // Obtenir la date actuelle
                                        $dateActuelle = new DateTime();
                                        // Vérifier si la date de fin est passée
                                        if ($dateFinReservation < $dateActuelle) {
                                            ?>
                                    <a class="boutton" href="/src/php/reservation/supprimerResaDB.php?numReservation=<?php echo $reservation[7]; ?>">Supprimer</a>
                                    <?php
                                }
                            }
                            ?>
                            </nav>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
        </div>
    </div>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
</body>
</html>
