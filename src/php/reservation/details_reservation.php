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
    <title>Détails de la réservations</title>
    <link href="/src/styles/styles.css" rel="stylesheet" type="text/css">
    <link href="/src/styles/details_reservation.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="/public/logos/logo-black.svg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/src/js/detailsReservation.js"></script>
</head>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/header.php'); ?>
<body>
<div style="height: 75px"></div>
<div>
    <h1 id="titreLogement"> <?php $reservation ?></h1>
    <div id="infosContainer">
        <div id="photoEtContact">
            <figure>
                <img src="<?= $cheminPhoto ?>" alt="Photo du logement"/>
                <fieldset><a>En savoir plus</a></fieldset>
            </figure>
            <div id="contactPropio">
                <div id="carteProprio">
                    <?php if ($_SESSION['id']==$numclient) {?>
                        <div>
                            <img src="<?= $cheminPhotoProprio ?>" alt="Photo de profil"/>
                        </div>
                        <?= $prenom_proprio . " " .$nom_proprio ?></div>
                        <a id="contactLien" href="/src/php/profil/profil.php?user=<?php echo $proprio ?>"><?php $prenom_proprio?> Contacter</a>

                <?php }else {?>
                        <div>
                            <img src="<?= $cheminPhotoClient ?>" alt="Photo de profil"/>
                        </div>
                        <?= $prenom_client . " " .$nom_client ?></div>
                        <a id="contactLien" href="/src/php/profil/profil.php?user=<?php echo $numclient ?>">Contacter</a>                <?php } ?>
            </div>
        </div>
        <div id="autreInfo">
            <div class="container" id="localisationEtat">
                <div id="localisation">
                    <img src="../../../public/icons/markerMap.svg" alt="logo"/>
                    <h2><?= $localisation?> <br/> <?= $localisationDetail ?></h2>
                </div>
            </div>
            <div id="dateDiv">
                <h2 id="date"><span id="dateArr"><?= $dateArr ?></span> - <span id="dateDep"><?= $dateDep ?></span></h2>
                <div id="nbPersonne" class="center"> <img src="../../../public/icons/nb_personnes.svg" alt="nbPersonne" /> <h1><?= $nbPersonnes  ?> </h1></div>
            </div>
            <div id="etatDevis" class="recu">
                <a href='' download='devis.pdf'><img src="/public/icons/contract.svg" alt="icon devis"/></a>
                <?php
                if ($_SESSION['id']==$numclient) {
                    echo "<h2>Devis reçu le <span id='dateDevis'>$dateDevis</span></h2></div>";
                }
                else {
                    echo "<h2>Demande de devis reçue le $dateResa</h2></div>";
                }
                ?>

            <div id="prixDiv">
                <h2>Total :</h2>
                <h2><span id="prixSpan"><?= $prixTotal ?></span>€</h2>
            </div>
            <div class="container" style="flex-direction: column;
    align-items: end;">
                <div id="annulerAccepter">
                <?php
                    if ($_SESSION['id']==$numclient) {?>

                    <button class="boutton" onclick="confirmationValiderPopUp()">Accepter le devis et payer</button>
                    <button class="boutton" onclick="confirmationAnnulerPopUp(<?php echo $numReservation; ?>, '<?php echo $dateArr; ?>')">Annuler ma réservation</button>
                <?php }else {?>
                    <button class="boutton" onclick="supprimerReservation()">Supprimer la réservation</button>
                <?php }?>
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

</script>
</html>
