<?php session_start(); ?>
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
        <?php include 'header.php'; ?>
        <div id="content">
            <div id="connexion">
                <h1>connexion</h1>
                
                <form id="connexion-form" action="login.php" method="post">
                    <div class="form">
                        <div>
                            <label <?php if (isset($_GET['error']) && $_GET['error'] == 'id') echo 'class="error"' ?> for="identifiant">Identifiant</label>
                            <input type="text" name="identifiant" id="identifiant" value="<?php if (isset($_GET['identifiant'])) echo $_GET['identifiant']; ?>" placeholder="Entrez votre identifiant"  required>
                        </div>
                        <div>
                            <label <?php if (isset($_GET['error']) && $_GET['error'] == 'mdp') echo 'class="error"' ?> for="mdp">Mot de passe</label>
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
        <?php include 'footer.php'; ?>
        <script src="/src/js/compte-click.js"></script>
    </body>
</html>