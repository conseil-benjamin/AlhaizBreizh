<?php
global $photo_profil_proprio;
$id = 1;
$titreLogement = "Superbe maison au bord de la plage";
$cheminPhoto = "../../../public/img/logements/$id/$id.png";
$dateArr = '25/10/2004';
$dateDep = '25/10/2004';
$dateDevis = '25/22/2004';
$prixTotal = '200';
$dateResa = '25/22/2004';
$devisRecu = false;
$conlue = false;
$etatResa = 'En attente';
$prenom_proprio = "Albert";
$nom_proprio = "Einstaint";
include("getDBResa.php");
$nomProprio = "$prenom_proprio"." "."$nom_proprio";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la réservations</title>
    <link href="/src/styles/styles.css" rel="stylesheet" type="text/css">
    <link href="/src/styles/details_reservation.css" rel="stylesheet" type="text/css">
</head>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/header.php'); ?>
<body>
<div style="height: 75px"></div>
<div>
    <h1 id="titreLogement"> <?= $titreLogement ?></h1>
    <div id="infosContainer">
        <div id="photoEtContact">
            <figure>
                <img src="<?= $cheminPhoto ?>" alt="Photo du logement"/>
                <fieldset><a>En savoir plus</a></fieldset>
            </figure>
            <div id="contactPropio">
                <div id="carteProprio"><img src="<?= $photo_profil_proprio ?>" alt="Photo de profil"/> <?= $nomProprio ?></div>
                <button class="boutton gris" disabled>Contacter</button>
            </div>
        </div>
        <div id="autreInfo">
            <div class="container" id="localisationEtat">
                <div id="localisation">
                    <img src="../../../public/icons/markerMap.svg" alt="logo"/>
                    <h2>Location <br/> localisation détaillé</h2>
                </div>
                <div id="etatResa" class="center"><h2> <?= $etatResa ?> </h2></div>
            </div>
            <div id="dateDiv">
                <h2 id="date"><span id="dateArr"><?= $dateArr ?></span> - <span id="dateDep"><?= $dateDep ?></span></h2>
                <div id="nbPersonne" class="center"> <img src="../../../public/icons/nb_personnes.svg" alt="nbPersonne" /> <h1>7</h1></div>
            </div>
            <div id="etatDevis" class="<?php if ($devisRecu) { echo 'recu';} else { echo 'enattente';} ?>">
                <a href='' download='devis.pdf'><img src="" alt="icon devis"/></a> <h2>
                Devis reçu le <span id="dateDevis"> <?= $dateDevis ?></span></h2></div>

            <div id="prixDiv">
                <p>Total :</p>
                <h2><span id="prixSpan"><?= $prixTotal ?></span>€</h2>
            </div>
            <div class="container" style="flex-direction: column;
    align-items: end;">
                <div id="annulerAccepter">
                <?php
                if(!$conlue) {
                    echo '
                    <button class="boutton" onclick="confirmationValiderPopUp()">Accepter le devis et payer</button>
                    <button class="boutton" onclick="confirmationAnnulerPopUp()">Annuler ma réservation</button>
                ';
                }
                else {
                    echo '
                    <button class="boutton gris" disabled>Laisser un avis sur le logement</button>
                    <button class="boutton gris" disabled>Laisser un avis sur l\'hôte</button>';
                }
                ?>
                </div>
                <p>Logement reservé le <span id="dateResa"><?= $dateResa ?></span></p>
            </div>
        </div>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/footer.php'); ?>
</body>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <!-- Librairie pour les alertes -->
<script src="../../js/detailsReservation.js"></script>
</html>