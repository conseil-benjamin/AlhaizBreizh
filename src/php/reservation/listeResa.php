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
                            <nav style="display: flex; align-items: center;">
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
                        $currentDate = date('Y-m-d');
                        $stmt = $pdo->prepare("SELECT idClient FROM ldc.AvisLogement WHERE idClient = :idClient AND idLogement = :idLogement");
                        //echo "idClient : $reservation[4], idLogement : $reservation[0]";
                        $stmt->bindParam(':idClient', $reservation[4]);
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
                        } else if($currentDate < $reservation[3]){
                        } else {
                            echo "<button id='deposer-avis' class='boutton'>Laisser un avis</button>";
                        } 
                    ?>
                            </nav>
                        </div>
                    </div>
                    <script>
                    document.addEventListener('DOMContentLoaded', (event) => {
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
                let buttonDeposerAvis = document.querySelector("#deposer-avis");
                buttonDeposerAvis.addEventListener("click", async (event) => {
                    const { value: formValues } = await Swal.fire({
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
                    });
                    if (formValues) {
                        notes = formValues;
                        moyenne = calculMoyenne();
                        console.log(moyenne);
                        let idLogement = <?php echo json_encode($reservation[0]); ?>; 
                        let contenusAvis = document.querySelector("#text-area-commentaires").value;
                        console.log(idLogement);
                        console.log(contenusAvis);
                        $.ajax({
                            url: 'insertAvis.php', 
                            type: 'POST',
                            data: {
                                moyenne: moyenne,
                                idLogement: idLogement,
                                contenusAvis: contenusAvis
                            },
                            success: function(response) {
                                Toast.fire({
                                    icon: "success",
                                    title: "Avis posté avec succès."
                                });
                                setTimeout(()=> {
                                    location.reload();
                                }, 3500);                            
                            },
                            error: function(xhr, status, error) {
                                console.error('Erreur lors de la suppression :', error);
                            }
                        });
                    } else{
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
});
                </script>
                <?php
                }
            }
            ?>
        </div>
    </div>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
</body>
</html>
