<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <title>ALHaiz Breizh</title>
    </head>
    <body>
        <?php include './src/php/header.php'; ?>
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
        <script src="/src/js/compte-click.js"></script>
    </body>
</html>