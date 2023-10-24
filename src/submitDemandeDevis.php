<?php
unset($_SESSION["prixNuit"]);
unset($_SESSION["nbNuit"]);
unset($_SESSION["nomBien"]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="./styles/demande_devis.css" rel="stylesheet" type="text/css">
    <link href="./styles/styles.css" rel="stylesheet" type="text/css">
    <title>Demande de devis</title>
</head>
<body>
<header id="header">
    <a class="logo" href="/index.html"><img src="/public/logos/logo-grey.svg"></a>
    <nav>
        <ul>
            <li><a href="">Logements</a></li>
            <li><a href="">Réservations</a></li>
            <li><a href="">Qui sommes-nous</a></li>
        </ul>
    </nav>
    <a class="compte" href="">
        <img alt="icon" src="/public/icons/arrow-blue.svg">
        <p>Se connecter</p>
        <img alt="icon" src="/public/icons/user-blue.svg">
    </a>
</header>
<div style="height: 75px"></div>
<div id="fond">
    <section id="entete">
        <h1>
            Réservation de "Superbe maison au bord de la plage"
        </h1>
    </section>
    <section id="corpsTexte">
    <?php
    //include("php/submitDevisDB.php");
    ?>
        <h2 id="succesDevis"> Votre devis a bien été envoyé</h2>
    </section>
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