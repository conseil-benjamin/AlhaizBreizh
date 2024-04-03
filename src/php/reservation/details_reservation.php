<?php
session_start();

try {
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (!isset($_SESSION['id'])) {
    header('Location: /src/php/connexion/connexion.php');
} else if ($_SESSION['proprio'] == false) {
    header('Location: /');
}

$numReservation=$_GET['numReservation'];

try {
    $stmt = $pdo->prepare( "SELECT
    Logement.proprio,
    proprio.firstName as prenom_proprio,
    proprio.lastName as nom_proprio,
    client.firstName as prenom_client,
    client.lastName as nom_client,
    logement.numLogement,
    logement.adresse,
    logement.ville,
    logement.libelle,
    Reservation.dateDebut,
    Reservation.dateFin,
    Reservation.dateDevis,
    Tarification.total,
    Devis.nbPersonnes,
    Devis.dateValid,
    Devis.dateDevis,
    Reservation.numClient
    FROM 
        ldc.Reservation
    INNER JOIN 
        ldc.Logement ON Reservation.numLogement = Logement.numLogement
    INNER JOIN 
        ldc.Devis ON Devis.numReservation = Reservation.numReservation
    INNER JOIN 
        ldc.Tarification ON Tarification.numDevis = Devis.numDevis
    INNER JOIN
        ldc.Client as proprio ON proprio.idCompte = Logement.proprio
    INNER JOIN
        ldc.Client as client ON client.idCompte = Reservation.numClient
    WHERE 
        Reservation.numReservation = $numReservation;");
    $stmt->execute();

    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reservation) {
        $numclient=$reservation['numclient'];
        $proprio=$reservation['proprio'];
        $prenom_proprio =$reservation['prenom_proprio'];
        $nom_proprio =$reservation['nom_proprio'];
        $prenom_client =$reservation['prenom_client'];
        $nom_client =$reservation['nom_client'];
        $localisationDetail = $reservation['adresse'];
        $localisation = $reservation['ville'];
        $titreLogement = $reservation['libelle'];
        $prixTotal = $reservation['total'];
        $nbPersonnes = $reservation['nbpersonnes'];
        $cheminPhoto = "/public/img/logements/".$reservation['numlogement']."/1.png";
        $cheminPhotoProprio="/public/img/photos_profil/".$proprio.".png";
        $cheminPhotoClient="/public/img/photos_profil/".$numclient.".png";
        $sqlResa = "SELECT * FROM ldc.reservation WHERE numreservation = :numReservation";
        $stmt = $pdo->prepare($sqlResa);
        $stmt->bindParam(':numReservation', $numReservation, PDO::PARAM_INT);
        $stmt->execute();
        $resultResa = $stmt->fetchAll();
        
        $dateArr = $resultResa[0]["datedebut"];
        $dateDep = $resultResa[0]["datefin"];
        $dateDevis = $resultResa[0]["datedevis"];
        $dateResa = $resultResa[0]["datereservation"];
        $etatReservation = $resultResa[0]["etatreservation"];
        //include("getDBResa.php");
    } else {
        echo "Reservation inexistante";
    }
} catch (PDOException $e) {

    echo "Database error: " . $e->getMessage();
}

// Gestion de la suppresion du logement

$resa_en_cours = false;
$date = new DateTime();
$dateDuJour = $date->format('Y-m-d');
 if ($dateDep > $dateDuJour) {
 $resa_en_cours = true;
 }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="/src/styles/details_reservation.css" rel="stylesheet" type="text/css">
    <link href="/src/styles/styles.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="/public/logos/logo-black.svg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/src/js/detailsReservation.js"></script>
    <title>Détails de la réservation</title>
 
</head>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/header.php'); ?>
<body>
    <div class="carte">
        <div id="titrephoto">
            <div id="photo">
            <figure>
                <a href="/src/php/logement/PageDetailLogement.php?numLogement=<?php echo $reservation['numlogement'] ?>">  <img class="imgLogement" src="<?= $cheminPhoto ?>" alt="Photo du logement"/> </a>
            </figure>
            </div>         
            </div>
            <div id="infosContainer">
                <div id="titreLogement" >
                            <h1> <?= $titreLogement ?></h1>
                        </div>
                <div class="container" id="localisationEtat">
                        <div id="localisation">
                            <img src="../../../public/icons/markerMap.svg" alt="logo" style="max-width: 50px; max-height: 50px;">
                            <h2><?= $localisationDetail ?>, <?= $localisation?></h2>
                        </div>
                        <div id="nbPersonne" class="center"> <img src="../../../public/icons/nb_personnes.svg" alt="nbPersonne" style="max-width: 48px; max-height: 48px;"> <h1><?= $nbPersonnes  ?> </h1></div>
                    </div>
                    <div id="dateDiv">
                        <h2 id="date"> Arrivée : <span id="dateArr"><?= $dateArr ?></span> <br> Départ : <span id="dateDep"><?= $dateDep ?></span></h2>
                    </div>
                    <div id="etatDevis" >
                    <a href='' download='devis.pdf'><img class="recu" src="/public/icons/contract.svg" alt="icon devis" style="max-width: 48px; max-height: 48px;"></a>
                        <?php
                        if ($_SESSION['id']==$numclient) {
                            echo "<h2>Devis reçu le <span id='dateDevis'>$dateDevis</span></h2></div>";
                        }
                        else {
                            echo "<h2>Demande de devis reçue le $dateResa</h2></div>";
                        }
                        ?>

                    <div id="prixDiv">
                        <h2>Total :<span id="prixSpan"><?= $prixTotal ?></span>€</h2>
                    </div>
                    <div class="container" style="flex-direction: column; align-items: end;">
                        <div id="annulerAccepter">
                            <?php
                                if ($_SESSION['id']== $numclient && $etatReservation == "Validée") {?>
                                <button class="boutton" onclick="confirmerSuccesPopUp()">Accepter le devis et payer</button>
                                <button class="boutton" onclick="confirmationAnnulerPopUp(<?php echo $numReservation; ?>, '<?php echo $dateArr; ?>')">Annuler ma réservation</button>
                            <?php }else if ($_SESSION['id'] !== $numclient && $etatReservation == "En attente de validation") {?>
                                <button class="boutton" onclick="accepterReservation()">Accepter demande réservation</button>
                                <button class="boutton" onclick="supprimerReservation()">Refuser la réservation</button>
                            <?php } else if ($_SESSION['id'] !== $numclient && $etatReservation == "Validée") {
                                echo "En attente de paiement";
                            }
                            ?>
                        </div>
                        <?php
                        if ($_SESSION['id']==$numclient) {?>
                        <p>Logement reservé le <span id="dateResa"><?= $dateResa ?></span></p>
                        <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/footer.php'); ?>
</body>
<script>
        function supprimerReservation() {
            <?php
                // Utilisation des valeurs PHP dans le script JavaScript
                echo "var resaEnCours = " . json_encode($resa_en_cours) . ";\n";
                echo "var numReservation = " . json_encode($numReservation) . ";\n";
            ?>
    if (!resaEnCours) {
        // Affiche une boîte de dialogue d'avertissement
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cette Réservation ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, je confirme",
            cancelButtonText: "Annuler",
        })
        .then((result) => {
            if (result.value) {
                //Changer l'url pour la suppression
                window.location.href = "/src/php/reservation/supprimerResaDB.php?numReservation=" + numReservation;
            }
        });
    } else {
        // Affichez un message à l'utilisateur si des réservations sont en cours
        Swal.fire({
            title: "Vous ne pouvez pas supprimé cette réservation, la réservation est en cours",
            icon: "error",
        });        }
}

function accepterReservation() {
            <?php
                echo "var numReservation = " . json_encode($numReservation) . ";\n";
            ?>

        Swal.fire({
            title: "Êtes-vous sûr de vouloir accecpter cette réservation ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, je confirme",
            cancelButtonText: "Annuler",
        })
        .then((result) => {
            if (result.value) {
                window.location.href = "/src/php/reservation/accepterResa.php?numReservation=" + numReservation;
            }
        });
    }

    function refuserReservation() {
            <?php
                echo "var numReservation = " . json_encode($numReservation) . ";\n";
            ?>

        Swal.fire({
            title: "Êtes-vous sûr de vouloir refuser cette réservation ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, je confirme",
            cancelButtonText: "Annuler",
        })
        .then((result) => {
            if (result.value) {
                window.location.href = "/src/php/reservation/annulerResa.php?numReservation=" + numReservation;
            }
        });
    }

    function confirmerSuccesPopUp() {
        <?php
                echo "var numReservation = " . json_encode($numReservation) . ";\n";
            ?>

        Swal.fire({
            title: "Êtes-vous sûr de vouloir payer cette réservation ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, je confirme",
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.value) {
            window.location.href = "/src/php/reservation/payerResa.php?numReservation=" + numReservation;   
        }});
    }

    function confirmationAnnulerPopUp() {
            <?php
                echo "var numReservation = " . json_encode($numReservation) . ";\n";
            ?>

        Swal.fire({
            title: "Êtes-vous sûr de vouloir annuler cette réservation ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, je confirme",
            cancelButtonText: "Annuler",
        })
        .then((result) => {
            if (result.value) {
                window.location.href = "/src/php/reservation/annulerResa.php?numReservation=" + numReservation;
            }
        });
    }
</script>
</html>
