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
        l.typeLogement
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
                    <div class="menu_filtre">
                        <div id="sidebar">
                        <img id="suppr" src="../../../public/icons/supprimer.png" alt="Icône Supprimer" onclick="abime()">
                            <div class="menu_tri">
                                <button class="boutton">Trier</button>
                                <div class="menu_deroulant">
                                    <ul>
                                        <a class="item_tri select" onclick="num(event)">Date de Réservation (Ordre décroissant)</a>
                                        <a  class="item_tri" onclick="unnum(event)">Date de Réservation (Ordre décroissant)</a>
                                        <a  class="item_tri" onclick="date(event)">Date de Séjour (Ordre croissant)</a>
                                        <a  class="item_tri" onclick="undate(event)">Date de Séjour (Ordre décroissant)</a>
                                        <a  class="item_tri" onclick="tarif(event)">Tarif (Ordre croissant)</a>
                                        <a  class="item_tri" onclick="untarif(event)">Tarif (Ordre décroissant)</a>
                                    </ul>
                                </div>
                            </div>

                            <input id="side_recherche" class="textfield" type="text" placeholder="Rechercher..">
                            <h2>Plage de séjour</h2>
                                <div class="hell">
                                    <div class="select_filtr">
                                        <p>Date d'arrivée</p>
                                        <input class="input1" id="side_arrive" name="date_arrive" placeholder="JJ/MM/YYYY" type="date">
                                    </div>
                                    <div class="select_filtr">
                                        <p>Date de départ</p>
                                        <input class="input1" id="side_depart" name="date_depart" placeholder="JJ/MM/YYYY" type="date">
                                    </div>
                                </div>
                            <h2>Ville</h2>
                            <select id="side_ville">
                                <option value="">---</option>
                                <?php
                                    $tab=[];
                                    foreach ($reservation as $reservatio) {
                                        $ville = $reservatio["ville"];
                                        if (!in_array($ville,$tab)){
                                            echo "<option value=\"{$reservatio["ville"]}\">{$reservatio["ville"]}</option>";
                                            $tab[]=$ville;
                                        }
                                    }
                                ?>
                            </select>
                            <h2>Type du logement</h2>
                                <select id="side_type">
                                    <option value="">---</option>
                                    <option value="appartement">Appartement</option>
                                    <option value="maison">Maison</option>
                                    <option value="villa">Villa</option>
                                </select>
                        </div>

                        <button id="menu-btn" class="boutton">Filtrer et Trier</button>

                    </div>
                </div>
            </div>
        <div id="logements">
            <?php
            if (count($reservation) === 0) { ?>
                <h3>Vous n'avez aucune réservation pour le moment :/</h3> <?php
                } else {
                    foreach ($reservation as $uneReservation) {?>                        
                            <div class="logement">
                                <img src="/public/img/logements/<?php echo $uneReservation['numlogement'] ?>/1.png" alt="logement" place=<?php echo $uneReservation["ville"];?> data-information=<?php echo $uneReservation["typelogement"]; ?>>
                                <div>
                                    <h2><?php echo $uneReservation['libelle']; ?></h2>
                                        <h4><?php echo $uneReservation['datedebut'] ?></h4>
                                        <h4><?php echo $uneReservation['datefin'] ?></h4>
                                    <a href="/src/php/profil/profil.php?user=<?php echo $uneReservation['idclient'] ?>">
                                            <div class="profile">
                                            <p><?php echo $uneReservation['firstname'] . " " . $uneReservation['lastname']; ?></p>

                                            </div>
                                    </a>
                                    <nav>
                                        <a class="boutton" href="/src/php/reservation/supprimerResaDB.php?numReservation=<?php echo $uneReservation['numreservation']?>">Supprimer</a>
                                        <a class="boutton" href="/src/php/reservation/details_reservation.php?numReservation=<?php echo $uneReservation['numreservation']?>" >Voir Réservation</a>
                                        <a class="boutton" href="/src/php/logement/PageDetailLogement.php?numLogement=<?php echo $uneReservation['numlogement'] ?>" > Voir Logement</a>
                                    </nav>
                                </div>
                            </div>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
    <script src="/src/js/side_back.js"></script>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/footer.php'); ?>
</body>
</html>
