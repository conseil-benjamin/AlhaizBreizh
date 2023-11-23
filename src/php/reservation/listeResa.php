<!DOCTYPE html>
<html lang="fr-fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/index.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/style_Liste_resa.css">
    <title>ALHaiz Breizh</title>
    <?php
    session_start();

    if ((isset($_SESSION['id'])) && (file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/img/' . $_SESSION['id'] . '.png'))) {
        $image = '/public/img/' . $_SESSION['id'] . '.png';
    } else {
        $image = '/public/icons/user-blue.svg';
    }
    ?>
</head>

    <header id="header">
        <a href="/index.php" class="logo"><img src="/public/logos/logo-grey.svg"></a>
        <nav>
            <ul>
                <li><a href="/index.php#logements">Logements</a></li>
                <li><a href="">Réservations</a></li>
                <li><a href="">À propos</a></li>
            </ul>
        </nav>

        <?php
        if (isset($_SESSION['id'])) { ?>
            <div class="compte">
                <img src="/public/icons/arrow-blue.svg">
                    <p><?php echo $_SESSION['id'] ?></p>
                <img src="<?php echo $image ?>">
            </div>
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
            <a href="/src/php/connection.php" class="compte">
                <p>Se connecter</p>
                    <img src="/public/icons/user-blue.svg">
            </a>
        <?php } ?>
    </header>
    <body>
        <?php 
                session_start(); 
                //
                //if (!isset($_SESSION['id'])) {
                //    header('Location: /src/php/connection.php');
                //} else if ($_SESSION['proprio'] == true) {
                //     header('Location: /');
                // }


                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    try {
                        $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=root");
                        $stmt = $pdo->prepare("INSERT INTO ldc.Reservation (numLogement, dateDebut, dateFin) VALUES (:numLogement, :dateDebut, :dateFin)");

                        $numLogement = $_POST['numLogement'];
                        $dateDebut = $_POST['dateDebut'];
                        $dateFin = $_POST['dateFin'];

                        $stmt->bindParam(':numLogement', $numLogement, PDO::PARAM_INT);
                        $stmt->bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
                        $stmt->bindParam(':dateFin', $dateFin, PDO::PARAM_STR);

                        $stmt->execute();

                        $pdo = null;

                        // Redirige vers une page de confirmation ou autre si nécessaire
                        header('Location: confirmation.php');
                        exit();
                    } catch (PDOException $e) {
                        // Gère les erreurs de la base de données
                        echo "Erreur : " . $e->getMessage();
                    }
                }
            ?>

    <div class="content">
        <div class="recherche">
            <h1>Mes Réservations</h1>
                <div id="options">
                    <div>
                        <input type="text" placeholder="Search..">
                        <button class="boutton">Date</button>
                        <button class="boutton">Prix</button>
                    </div>
                </div>
        </div>

        <?php
        // Supposons que vous ayez déjà une variable $reservations récupérée de la base de données
        $reservations = [
            [
                'numReservation' => 1,
                'numLogement' => 1,
                'libelle' => 'Appartement cozy',
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
            [
                'numReservation' => 3,
                'numLogement' => 1,
                'libelle' => 'Appartement cozy',
                'dateDebut' => '2023-12-25',
                'dateFin' => '2024-01-1',
                'pseudoCompte' => 'Gege',
            ],
        ];
        ?>
        <?php foreach ($reservations as $reservation): ?>
            <div class="card-container">    
                <div class="reservation-card">
                    <div class="logement">
                        <img src="/public/img/logements/<?php echo $reservation['numLogement']; ?>/1.png" alt="Photo du logement">
                    </div>
                    <div class="infos">
                            <h2><?php echo $reservation['libelle']; ?></h2>
                        <div class="details">
                            <p>Date d'arrivée: <?php echo $reservation['dateDebut']; ?></p>
                            <p>Date de départ: <?php echo $reservation['dateFin']; ?></p>
                        </div>
                        <div class="profile">
                            <img src="/public/img/gege.png" alt="Photo de profil">
                            <p><?php echo $reservation['pseudoCompte']; ?></p>
                        </div>
                    </div>
                        <label class="button-etat" for="status">Etat Reservation</label>
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
