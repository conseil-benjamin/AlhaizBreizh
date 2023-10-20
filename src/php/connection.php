<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/connection.css">
        <title>ALHaiz Breizh</title>
    </head>
    <body>
        <?php include 'header.php'; ?>
        <div id="content">
            <div id="connection">
                <h1>Connection</h1>
                
                <form id="connection-form" action="login.php" method="post">
                    <div class="form">
                        <div>
                            <label for="identifiant">Identifiant</label>
                            <input type="text" name="identifiant" id="identifiant" placeholder="Entrez votre identifiant" required>
                        </div>
                        <div>
                            <label for="mdp">Mot de passe</label>
                            <input type="password" name="mdp" id="mdp" placeholder="Entrez votre mot de passe" required>
                            <a href="">Mot de passe oublié ?</a>
                        </div>
                        <input id="submit" class="boutton" type="submit" value="Se connecter">
                    </div>
                </form>
            </div>
            <div id="inscription">
                <p>Pas encore inscrit ? Inscrivez-vous dès maintenant !</p>
                <a href="/src/php/inscription.php" class="boutton">S'inscrire</a>
            </div>
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
        <script src="/src/js/compte-click.js"></script>
    </body>
</html>