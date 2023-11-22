<?php
$id = 1;
$titreLogement = "Superbe maison au bord de la plage";
$cheminPhoto = "../../../public/img/logements/$id/$id.png";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la réservations</title>
    <link href="/src/styles/details_reservation.css" rel="stylesheet" type="text/css">
    <link href="/src/styles/styles.css" rel="stylesheet" type="text/css">
</head>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/header.php'); ?>
<body>
<div style="height: 75px"></div>
<div>
    <h1> <?= $titreLogement ?></h1>
    <div id="infosContainer">
        <div id="photoEtContact">
            <figure>
                <img src="<?= $cheminPhoto ?>" alt="Photo du logement"/>
                <fieldset><a>En savoir plus</a></fieldset>
            </figure>
            <div id="contactPropio">
                <div id="carteProprio"><img src="" alt="Photo de profil"/> Pseudo</div>
                <button class="boutton">Contacter</button>
            </div>
        </div>
        <div id="autreInfo">
            <div class="container">
                <div id="localisation">
                    <img src="" alt="logo"/>
                    <p>Localtion localisation détaillé</p>
                </div>
                <div id="etatResa">En attente du devis</div>
            </div>
            <div id="dateDiv">
                <h2 id="date"><span id="dateArr">Date 1</span> - <span id="dateDep">Date 2</span></h2>
                <div id="nbPersonne">7</div>
            </div>
            <div id="devisRecu">
                <button><img src="" alt="icon devis"/></button>
                Devis reçu le <span id="dateDevis"></span></div>

            <div id="prixDiv"><p>Total :</p>
                <h2><span id="prixSpan">200</span>€</h2></div>
        </div>
    </div>
    <div class="container">
        <div class="container">
            <div id="annulerAccepter">
                <button class="boutton">Accepter le devis et payer</button>
                <button class="boutton">Annuler ma réservation</button>
            </div>
            <p>Logement reservé le <span id="dateResa"></span></p>
        </div>
    </div>
</div>
</body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/footer.php'); ?>
</html>