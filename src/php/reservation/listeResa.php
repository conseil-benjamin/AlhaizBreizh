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
            $stmt = $pdo->prepare("SELECT DISTINCT numLogement,libelle,dateDebut,dateFin, etatReservation, Reservation.numClient,Client.pseudoCompte,proprio,numReservation, proprio.firstName as prenom_proprio,
            proprio.lastName as nom_proprio, ville FROM ldc.Reservation NATURAL JOIN ldc.Logement inner JOIN ldc.Client on ldc.Reservation.numClient=idCompte INNER JOIN
            ldc.Client as proprio ON proprio.idCompte = Logement.proprio WHERE Client.idCompte = $id;");

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
            <!-- Lien vers la page d'accueil avec un message d'erreur -->
            <a class="lien" href="/index.php" target="_blank">
                <div>
                    Cette page est inexistante. Cliquez ici pour retourner à l'accueil.
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

        body {
            min-height: 0;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/mesLogements.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/style_Liste_resa.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="icon" href="/public/logos/logo-black.svg">
    <title>ALHaiz Breizh</title>
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
<div id="content">
    <h2>Mes réservations</h2>
    <div id="options">
                <div>
                    <div class="menu_filtre">
                        <div id="sidebar">
                            <div class="filtrer-trier">
                                <h1>Filtrer et Trier</h1>
                                <img id="suppr" src="/public/icons/croix.svg" alt="Fermer" onclick="abime()">
                            </div>
                            <div class="group-tri-recherche">
                                <div class="menu_tri">
                                    <button class="boutton">Trier</button>
                                    <div class="menu_deroulant">
                                        <ul>
                                            <a class="item_tri select" onclick="num(event)"><li>Date de Réservation (Ordre décroissant)</li></a>
                                            <a  class="item_tri" onclick="unnum(event)"><li>Date de Réservation (Ordre décroissant)</li></a>
                                            <a  class="item_tri" onclick="date(event)"><li>Date de Séjour (Ordre croissant)</li></a>
                                            <a  class="item_tri" onclick="undate(event)"><li>Date de Séjour (Ordre décroissant)</li></a>
                                            <a  class="item_tri" onclick="tarif(event)"><li>Tarif (Ordre croissant)</li></a>
                                            <a  class="item_tri" onclick="untarif(event)"><li>Tarif (Ordre décroissant)</li></a>
                                        </ul>
                                    </div>
                                </div>

                                <input id="side_recherche" class="textfield" type="text" placeholder="Rechercher..">
                            </div>
                            <div>
                                <h2>Plage de séjour</h2>
                                <div class="hell">
                                    <div class="select_filtr">
                                        <p>Date d'arrivée</p>
                                        <input class="input1 textfield" id="side_arrive" name="date_arrive" placeholder="JJ/MM/YYYY" type="date">
                                    </div>
                                    <div class="select_filtr">
                                        <p>Date de départ</p>
                                        <input class="input1 textfield" id="side_depart" name="date_depart" placeholder="JJ/MM/YYYY" type="date">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h2>Ville</h2>
                                <select id="side_ville" class="textfield">
                                    <option value="">---</option>
                                    <?php
                                        $tab=[];
                                        foreach ($reservations as $reservatio) {
                                            $ville = $reservatio[11];
                                            if (!in_array($ville,$tab)){
                                                echo "<option value=\"{$reservatio[11]}\">{$reservatio[11]}</option>";
                                                $tab[]=$ville;
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <h2>Type du logement</h2>
                                <select id="side_type" class="textfield">
                                    <option value="">---</option>
                                    <option value="appartement">Appartement</option>
                                    <option value="maison">Maison</option>
                                    <option value="villa">Villa</option>
                                </select>
                            </div> 
                        </div>
                        <button id="menu-btn" class="boutton">Filtrer et Trier</button>
                    </div>
                </div>
            </div>

    <div id="logements">
        <?php
        if (count($reservations) === 0) { ?>
            <h3>Vous n'avez aucune réservation pour le moment :/</h3> <?php
        } else {
            foreach ($reservations as $reservation){ 
                print_r($reservation);?>
            
                <div class="logement">
                <img src="/public/img/logements/<?php echo $reservation[0]; ?>/1.png" alt="Photo du logement" place=<?php echo $reservation[11];?> data-information=<?php echo $reservation[12]; ?>>
                    <div>
                        <h2><?php echo $reservation[1]; ?></h2>
                        <a href="/src/php/afficherPlageDispo.php?dateDebut=<?php echo $reservation[2] ?>&dateFin=<?php echo $reservation[3] ?>">
                        <?php
                        $etatReservation = $reservation[4];
                        ?>
                            <h4><?php echo $reservation[2]; ?></h4>
                            <h4><?php echo $reservation[3]; ?></h4>
                            <?php 
                                if ($etatReservation == "En attente de validation"){
                                    ?>
                                    <h4 style="margin: 0.3em 0 0 0;"><?= $etatReservation; ?></h4>
                                    <?php
                                } else if ($etatReservation == "Annulée"){
                                    ?>
                                    <div style="display: flex; align-items: center; background-color: #FCE8E8; width: 6em; border-radius: 5px; padding: 0.3em; margin: 0.3em 0 0 0">
                                        <i class="fas fa-ban"></i>                                    
                                        <h4 style="margin: 0 0 0 0.5em;"><?= $etatReservation; ?></h4>
                                    </div>
                                    <?php
                                } else if ($etatReservation == "Acceptée"){
                                    ?>
                                    <div style="display: flex; align-items: center; background-color: #F5F5F5; width: 6em; border-radius: 5px; padding: 0.3em; margin: 0.3em 0 0 0">
                                        <h4 style="margin: 0 0 0 0.5em;"><?= $etatReservation; ?></h4>
                                    </div>
                                    <?php
                                } else if ($etatReservation == "Confirmée"){
                                    ?>
                                    <div style="display: flex; align-items: center; background-color: #DCF5D3; width: 7.5em; border-radius: 5px; padding: 0.3em; margin: 0.3em 0 0 0">
                                        <i class="fas fa-check"></i>
                                        <h4 style="margin: 0 0 0 0.5em;"><?= $etatReservation; ?></h4>
                                    </div>
                                    <?php
                                }
                            ?>
                        </a>
                        <a href="/src/php/profil/profil.php?user=<?php echo $reservation[6] ?>">
                            <div class="profile">
                            </div>
                        </a>
                        <nav style="display: flex; align-items: center;">
                            <a class="boutton" href="/src/php/reservation/details_reservation.php?numReservation=<?php echo $reservation[8]?>">Voir Réservation</a>
                            <?php
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
                            $currentDate = date('Y-m-d');
                            $stmt = $pdo->prepare("SELECT idClient FROM ldc.AvisLogement WHERE idClient = :idClient AND idLogement = :idLogement");
                            $stmt->bindParam(':idClient', $reservation[5]);
                            $stmt->bindParam(':idLogement', $reservation[0]);
                            $stmt->execute();
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ($result) {
                                ?>
                                <nav style="display: flex; justify-content: center; align-items: center; margin: 0 0 1em 1em;">
                                    <i class='fas fa-check'></i>
                                    <span style="margin: 0.5em;">Avis posté</span>
                                </nav>
                                <?php
                            } else if ($currentDate > $reservation[3] && $etatReservation == "Confirmée"){
                                echo "<button class='boutton' onclick='deposerAvis($reservation[0])'>Laisser un avis</button>";
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

<script>
    function deposerAvis(idLogement) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        let notes = [];
        let moyenne = 0;
        let total = 0;
        let count = 0;
        Swal.fire({
            title: "<strong><u>Déposer un avis</u></strong>",
            html: `
                <h4>Propreté du logement</h4>
                <div class="div-input-avis">
                    <input type="radio" id="option1" name="ratingProprete" value="1">
                    <label for="option1">1</label><br>
                    <input type="radio" id="option2" name="ratingProprete" value="2">
                    <label for="option2">2</label><br>
                    <input type="radio" id="option3" name="ratingProprete" value="3">
                    <label for="option3">3</label><br>
                    <input type="radio" id="option4" name="ratingProprete" value="4">
                    <label for="option4">4</label><br>
                    <input type="radio" id="option5" name="ratingProprete" value="5">
                    <label for="option5">5</label><br>
                </div>
                <h4>Confort du logement</h4>
                <div class="div-input-avis">
                    <input type="radio" id="option1" name="ratingConfort" value="1">
                    <label for="option1">1</label><br>
                    <input type="radio" id="option2" name="ratingConfort" value="2">
                    <label for="option2">2</label><br>
                    <input type="radio" id="option3" name="ratingConfort" value="3">
                    <label for="option3">3</label><br>
                    <input type="radio" id="option4" name="ratingConfort" value="4">
                    <label for="option4">4</label><br>
                    <input type="radio" id="option5" name="ratingConfort" value="5">
                    <label for="option5">5</label><br>
                </div>
                <h4>Commodités du logement</h4>
                <div class="div-input-avis">
                    <input type="radio" id="option1" name="ratingCommodites" value="1">
                    <label for="option1">1</label><br>
                    <input type="radio" id="option2" name="ratingCommodites" value="2">
                    <label for="option2">2</label><br>
                    <input type="radio" id="option3" name="ratingCommodites" value="3">
                    <label for="option3">3</label><br>
                    <input type="radio" id="option4" name="ratingCommodites" value="4">
                    <label for="option4">4</label><br>
                    <input type="radio" id="option5" name="ratingCommodites" value="5">
                    <label for="option5">5</label><br>
                </div>
                <h4>Rapport qualité prix</h4>
                <div class="div-input-avis">
                    <input type="radio" id="option1" name="ratingQualitePrix" value="1">
                    <label for="option1">1</label><br>
                    <input type="radio" id="option2" name="ratingQualitePrix" value="2">
                    <label for="option2">2</label><br>
                    <input type="radio" id="option3" name="ratingQualitePrix" value="3">
                    <label for="option3">3</label><br>
                    <input type="radio" id="option4" name="ratingQualitePrix" value="4">
                    <label for="option4">4</label><br>
                    <input type="radio" id="option5" name="ratingQualitePrix" value="5">
                    <label for="option5">5</label><br>
                </div>
                <h4>Emplacement</h4>
                <div class="div-input-avis">
                    <input type="radio" id="option1" name="ratingEmplacement" value="1">
                    <label for="option1">1</label><br>
                    <input type="radio" id="option2" name="ratingEmplacement" value="2">
                    <label for="option2">2</label><br>
                    <input type="radio" id="option3" name="ratingEmplacement" value="3">
                    <label for="option3">3</label><br>
                    <input type="radio" id="option4" name="ratingEmplacement" value="4">
                    <label for="option4">4</label><br>
                    <input type="radio" id="option5" name="ratingEmplacement" value="5">
                    <label for="option5">5</label><br>
                </div>
                <h4>Commentaires sur le logement</h4>
                <textarea id="text-area-commentaires" name="story" rows="10" cols="40"></textarea>
            `,
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: `
                <i></i> Poster
            `,
            cancelButtonText: `
                <i>Annuler</i>
            `,
            preConfirm: () => {
                return [
                    document.querySelector('input[name="ratingProprete"]:checked').value !== null ?
                        document.querySelector('input[name="ratingProprete"]:checked').value : Swal.fire({
                            text: "Création avis annulé",
                            icon: "error"
                        }),
                    document.querySelector('input[name="ratingConfort"]:checked').value !== null ?
                        document.querySelector('input[name="ratingConfort"]:checked').value : Swal.fire({
                            text: "Création avis annulé",
                            icon: "error"
                        }),
                    document.querySelector('input[name="ratingCommodites"]:checked').value !== null ?
                        document.querySelector('input[name="ratingCommodites"]:checked').value : Swal.fire({
                            text: "Création avis annulé",
                            icon: "error"
                        }),
                    document.querySelector('input[name="ratingQualitePrix"]:checked').value !== null ?
                        document.querySelector('input[name="ratingQualitePrix"]:checked').value : Swal.fire({
                            text: "Création avis annulé",
                            icon: "error"
                        }),
                    document.querySelector('input[name="ratingEmplacement"]:checked').value !== null ?
                        document.querySelector('input[name="ratingEmplacement"]:checked').value : Swal.fire({
                            text: "Création avis annulé",
                            icon: "error"
                        }),
                ];
            }
        }).then((result) => {
            if (result.isConfirmed) {
                notes = result.value;
                moyenne = calculMoyenne();
                console.log(moyenne);
                let logement = idLogement;
                let contenusAvis = document.querySelector("#text-area-commentaires").value;
                console.log(idLogement);
                console.log(contenusAvis);
                $.ajax({
                    url: 'insertAvis.php',
                    type: 'POST',
                    data: {
                        moyenne: moyenne,
                        idLogement: logement,
                        contenusAvis: contenusAvis
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: "success",
                            title: "Avis posté avec succès."
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 3500);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de la suppression :', error);
                    }
                });
            } else {
                Toast.fire({
                    text: "Création avis annulé",
                });
            }
        });

        function calculMoyenne() {
            for (let note of notes) {
                total += parseInt(note);
                count++;
            }
            return total / notes.length;
        }
    }
</script>
<script src="/src/js/illalways_side_back.js"></script>

</body>
</html>
 
