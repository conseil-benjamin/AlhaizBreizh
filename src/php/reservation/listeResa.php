<?php
global $numLogement;
session_start();
?>
<?php
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

    // Redirige l'utilisateur vers la page de connexion
    header("Location: /src/php/connexion/connexion.php");
    exit();

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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <!-- Titre de la page -->
    <title>ALHaiz Breizh</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                    <?php
                        $currentDate = date('Y-m-d');
                        echo $currentDate;
                        $stmt = $pdo->prepare("SELECT idClient FROM ldc.AvisLogement WHERE idClient = :idClient AND idLogement = :idLogement");
                        //echo "idClient : $reservation[4], idLogement : $reservation[0]";
                        $stmt->bindParam(':idClient', $reservation[4]);
                        $stmt->bindParam(':idLogement', $reservation[0]);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($result) {
                            echo "<i class='fas fa-check'> Avis posté</i>";             
                        } else if($currentDate < $reservation[2]){
                        } else {
                            echo "<button id='deposer-avis' class='button-resa'>Laisser un avis</button>";
                        } 
                    ?>
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
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Section Pied de page -->
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
</body>
</html>
