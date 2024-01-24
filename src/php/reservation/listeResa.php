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
    // Utilisez des requêtes préparées pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT DISTINCT numLogement,libelle,dateDebut,dateFin,pseudoCompte,proprio FROM ldc.Reservation NATURAL JOIN ldc.Logement NATURAL JOIN ldc.Client WHERE proprio = :id AND pseudoCompte = :pseudo");
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->bindParam(':pseudo', $_SESSION['pseudo'], PDO::PARAM_STR);
    $stmt->execute();
    
    // Vérifiez si l'utilisateur a des réservations
    if ($stmt->rowCount() > 0) {
        // Récupérez les réservations
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $reservations = array();
    }
    // Si l'utilisateur n'est pas le propriétaire, obtenir ses reservations
    $stmt = $pdo->prepare("SELECT DISTINCT numLogement,libelle,dateDebut,dateFin,pseudoCompte,proprio FROM ldc.Reservation NATURAL JOIN ldc.Logement NATURAL JOIN ldc.Client WHERE numClient = :id AND proprio != :id");
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $reservations + $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Utilisez une boucle foreach pour afficher les réservations
    /*foreach ($reservations as $reservation) {
        // Affichez les détails de la réservation
        echo "Numéro de logement: " . $reservation['numLogement'] . "<br>";
        echo "Date de début: " . $reservation['dateDebut'] . "<br>";
        echo "Date de fin: " . $reservation['dateFin'] . "<br>";
        // Ajoutez d'autres détails selon votre structure de base de données
    }*/
} else {
    // Ajustez en conséquence si l'utilisateur n'est pas connecté
    echo "Utilisateur non connecté";
}
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

    <!-- Code PHP pour vérifier la session utilisateur et définir l'image de profil -->
    <?php
// Supposons que vous ayez déjà une variable $reservations récupérée de la base de données

?>


</head>

<!-- Section En-tête -->
<?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>

<!-- Section Corps -->
<body>
    <?php 
    // Code PHP pour gérer la soumission du formulaire

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Connexion à la base de données et préparation de la requête
            $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=root");
            $stmt = $pdo->prepare("INSERT INTO ldc.Reservation (numLogement, dateDebut, dateFin) VALUES (:numLogement, :dateDebut, :dateFin)");

            // Récupération des données du formulaire
            $numLogement = $_POST['numLogement'];
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];

            // Liaison des paramètres et exécution de la requête
            $stmt->bindParam(':numLogement', $numLogement, PDO::PARAM_INT);
            $stmt->bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
            $stmt->bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
            $stmt->execute();

            $pdo = null;

            // Redirection vers la page de confirmation
            header('Location: confirmation.php');
            exit();
        } catch (PDOException $e) {
            // Gestion des erreurs de la base de données
            echo "Erreur : " . $e->getMessage();
        }
    }
    ?>



    <!-- Section Contenu -->
    <div class="content">
        <!-- Recherche et options de réservation -->
        <div class="recherche">
            <h1>Mes Réservations</h1>
            <div id="options">
                <div>
                    <input type="text" placeholder="Rechercher..">
                    <div class="menu_tri">
                        <button class="boutton">Trier</button>
                        <div class="menu_deroulant">
                        <ul>
                            <li <?php if ($_GET['tri'] == "date") { ?>class="select" > <a  href="listeResa.php">Date (plus ancien)</a><?php } else { ?> > <a href="listeResa.php?tri=date">Date (plus ancien)</a><?php } ?></li>
                            <li <?php if ($_GET['tri'] == "date_e") { ?>class="select" > <a class="select" href="listeResa.php">Date (plus récent)</a><?php } else { ?> > <a href="listeResa.php?tri=prix">Date (plus récent)</a><?php } ?></li>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      
        <!--Trie des reserv -->
        <?php

        function dater($a, $b) {
            return $a[5] - $b[5]; //fonction de tri par tarif le moins eleve
        }

        function dater_envers($a, $b) {
            return $a[5] - $b[5]; //fonction de tri par tarif le moins eleve
            $reservations = array_reverse($reservations);
        }

        if ($_GET['tri']=="date"){
            usort($reservations, 'dater');
        }
        else if ($_GET['tri']=="date_e"){
            usort($reservations, 'dater_envers');
        }

        ?>

        <!-- Affiche les cartes de réservation -->
        <?php foreach ($reservations as $reservation): ?>
            <div class="card-container">    
                <div class="reservation-card">
                    <div class="logement">
                        <img src="/public/img/logements/<?php echo $reservation['numlogement']; ?>/1.png" alt="Photo du logement">
                    </div>
                    <div class="infos">
                        <h2><?php echo $reservation['libelle']; ?></h2>
                        <div class="details">
                            <p>Date d'arrivée : <?php echo $reservation['datedebut']; ?></p>
                            <p>Date de départ : <?php echo $reservation['datefin']; ?></p>
                        </div>
                        <div class="profile">
                            <img src="/public/img/photos_profil/<?php echo $reservation['proprio']; ?>.png" alt="Photo de profil">
                            <p><?php echo $reservation['pseudocompte']; ?></p>
                        </div>
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
                        <a href="details_reservation.php?numLogement=<?=$reservation['numlogement']?>" class="button-resa">Voir Réservation</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Section Pied de page -->
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
</body>

</html>
