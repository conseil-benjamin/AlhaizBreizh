<?php
    unset($_SESSION["prixNuit"]);
    unset($_SESSION["nbNuit"]);
    unset($_SESSION["nomBien"]);
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <link href="/src/styles/demande_devis.css" rel="stylesheet" type="text/css">
        <link href="/src/styles/styles.css" rel="stylesheet" type="text/css">
        <title>Demande de devis</title>
    </head>
    <body>
        <?php include("php/header.php"); ?>
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
                <h2> Votre devis a bien été envoyée</h2>
            </section>
        </div>
        <?php include("php/footer.php"); ?>
    </body>
</html>