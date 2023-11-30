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
    // Utilisez des requêtes préparées pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM ldc.Reservation WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    
    // Vérifiez si l'utilisateur a des réservations
    if ($stmt->rowCount() > 0) {
        // Récupérez les réservations
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Ajustez en conséquence si l'utilisateur n'a pas de réservations
        $reservations = array();
    }

    // Utilisez une boucle foreach pour afficher les réservations
    foreach ($reservations as $reservation) {
        // Affichez les détails de la réservation
        echo "Numéro de logement: " . $reservation['numLogement'] . "<br>";
        echo "Date de début: " . $reservation['dateDebut'] . "<br>";
        echo "Date de fin: " . $reservation['dateFin'] . "<br>";
        // Ajoutez d'autres détails selon votre structure de base de données
    }
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
$reservations = [
    [
        'numReservation' => 1,
        'numLogement' => 1,
        'libelle' => 'Appartement Cozy',
        'dateDebut' => '2023-11-01',
        'dateFin' => '2023-11-07',
        'pseudoCompte' => 'Gege',
    ],
    [
        'numReservation' => 2,
        'numLogement' => 2,
        'libelle' => 'Cave Spacieuse',
        'dateDebut' => '2023-11-10',
        'dateFin' => '2023-11-15',
        'pseudoCompte' => 'Gege',
    ],
    // ... Ajoutez d'autres réservations ici
];
?>


</head>

<!-- Section En-tête -->
<header id="header">
    <a href="/index.php" class="logo"><img src="/public/logos/logo-grey.svg"></a>
    <nav>
        <ul>
            <li><a href="/index.php#logements">Logements</a></li>
            <li><a href="">Réservations</a></li>
            <li><a href="">À propos</a></li>
        </ul>
    </nav>

    <!-- Code PHP pour gérer la session utilisateur -->
    <?php
    if (isset($_SESSION['id'])) { ?>
        <!-- Affiche les informations utilisateur si connecté -->
        <div class="compte">
            <img src="/public/icons/arrow-blue.svg">
            <p><?php echo $_SESSION['id'] ?></p>
            <img src="<?php echo $image ?>">
        </div>
        <!-- Menu d'options utilisateur -->
        <div class="compte-options">
            <ul>
                <a href=""><li>Mon Profil</li></a>
                <a href=""><li>Ma Messagerie</li></a>
                <a href=""><li>Mes Réservations</li></a>
                <?php
                if ($_SESSION['proprio']) { ?>
                    <a href=""><li>Mes Logements</li></a> <?php
                } else { ?>
                    <a href=""><li>Devenir propriétaire</li></a> <?php
                } ?>
                <a href=""><li>Mes Favoris</li></a>
                <a href=""><li>Besoin d'Aide</li></a>
                <a href="/src/php/login.php?deconnexion" id="deconnexion"><li>Déconnexion</li></a>
            </ul>
        </div>
    <?php } else { ?>
        <!-- Affiche le lien de connexion si non connecté -->
        <a href="/src/php/connection.php" class="compte">
            <p>Se connecter</p>
            <img src="/public/icons/user-blue.svg">
        </a>
    <?php } ?>
</header>

<!-- Section Corps -->
<body>
    <?php 
    // Code PHP pour gérer la soumission du formulaire
    session_start(); 

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
                    <button class="boutton-tri">Date</button>
                    <button class="boutton-tri">Prix</button>
                </div>
            </div>
        </div>

      
        <!-- Affiche les cartes de réservation -->
        <?php foreach ($reservations as $reservation): ?>
            <div class="card-container">    
                <div class="reservation-card">
                    <div class="logement">
                        <img src="/public/img/logements/<?php echo $reservation['numLogement']; ?>/1.png" alt="Photo du logement">
                    </div>
                    <div class="infos">
                        <h2><?php echo $reservation['libelle']; ?></h2>
                        <div class="details">
                            <p>Date d'arrivée : <?php echo $reservation['dateDebut']; ?></p>
                            <p>Date de départ : <?php echo $reservation['dateFin']; ?></p>
                        </div>
                        <div class="profile">
                            <img src="/public/img/gege.png" alt="Photo de profil">
                            <p><?php echo $reservation['pseudoCompte']; ?></p>
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
                        <a href="#" class="button-resa">Voir Réservation</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Section Pied de page -->
    <footer id="footer">
        <img src="/public/icons/wave.svg">
        <div>
            <div>
                <div>
                    <h2>Assistance</h2>
                    <ul>
                        <li><a href="">Centre d'aide</a></li>
                        <li><a href="">Options d'annulation</a></li>
                    </ul>
                </div>
                <div>
                    <h2>Accueil des voyageurs</h2>
                    <ul>
                        <li><a href="">Nous rejoindre</a></li>
                        <li><a href="">Mettez votre logement sur ALHaIZ Breizh</a></li>
                        <li><a href="">Confiance et sécurité</a></li>
                    </ul>
                </div>
                <div>
                    <h2>ALHaIZ Breizh</h2>
                    <ul>
                        <li><a href="">Qui sommes-nous</a></li>
                        <li><a href="">Conditions générales d'utilisation</a></li>
                        <li><a href="">Mentions légales</a></li>
                    </ul>
                </div>
            </div>
            <p>© 2023 ALHaIZ Breizh - Tous droits réservés</p>
        </div>
    </footer>
</body>

</html>
