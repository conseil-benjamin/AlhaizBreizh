<?php 
    session_start(); 

    if (isset($_SESSION['id'])) {
        header("Location: /index.php");
    }
?>
<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/connexion.css">
        <title>ALHaiz Breizh</title>
    </head>
    <body>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
        <div id="content">
            <div id="connexion">
                <h1>Connexion</h1>
                <form id="connexion-form" action="login.php" method="post">
                    <div class="form">
                        <?php if (isset($_GET['error'])) { ?>
                            <p class="error">Identifiant ou mot de passe incorrect</p>
                        <?php } ?>
                        <div>
                            <label for="identifiant">Identifiant</label>
                            <input type="text" name="identifiant" id="identifiant" placeholder="Entrez votre identifiant"  required>
                        </div>
                        <div>
                            <label for="mdp">Mot de passe</label>
                            <input type="password" name="mdp" id="mdp" placeholder="Entrez votre mot de passe" required>
                            <a href="">Mot de passe oublié ?</a>
                        </div>
                        <input id="submitBtn" class="boutton" type="submit" value="Se connecter">
                    </div>
                </form>
            </div>
            <div id="inscription">
                <p>Pas encore inscrit ? Inscrivez-vous dès maintenant !</p>
                <a href="/src/php/inscription/inscription.php" class="boutton">S'inscrire</a>
            </div>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="/src/js/connexion/popup.js"></script>
    </body>
</html>