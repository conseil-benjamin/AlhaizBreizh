<?php
    if (isset($_SESSION)) {
        $nom = $_SESSION["nom_bien"];
        $nbNuit = $_SESSION["nbNuit"];
        $prixNuit = $_SESSION["prixNuit"];
    } else {
        $nom = "Superbe Maison au bord de la plage";
        $nbNuit = "6";
        $prixNuit = "6,8";
    }
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
                    Réservation de <?php echo $nom ?>
                </h1>
            </section>
            <section id="corpsTexte">
                <form method="post" action="submitDemandeDevis.php">
                    <div id="formulaire">
                        <div id="prixDiv">
                            <h2>Prix de base <span id="prixSpan"><?php echo $prixNuit ?></span>€/nuit - <span
                                        id="nbNuit"><?php echo $nbNuit ?></span>
                                nuit</h2>
                            <ul>
                                <li>
                                    <div class="labelPrix">
                                        <label for="arrivee">
                                            Date d'arrivée :
                                        </label>
                                    </div>
                                    <div>
                                        <input class="input1" id="arrivee" name="date_arrivee" placeholder="JJ/MM/YYYY"
                                            type="date">
                                    </div>
                                </li>
                                <li>
                                    <div class="labelPrix">
                                        <label for="depart">
                                            Date de depart :
                                        </label>
                                    </div>
                                    <div>
                                        <input class="input1" id="depart" name="date_depart" placeholder="JJ/MM/YYYY"
                                            type="date">
                                    </div>
                                </li>
                                <li>
                                    <div class="labelPrix">
                                        <label for="nbpersonne">
                                            Nombre de personnes :
                                        </label>
                                    </div>
                                    <div>
                                        <input class="input1" id="nbpersonne" max="10" min="0" name="nb_personne"
                                            placeholder="nbpersonne" type="number" value="0">
                                    </div>
                                </li>
                            </ul>
                            <div>
                                <label for="demande">Autre demande (1000 caractères maximum)</label>
                                <textarea id="demande" maxlength="1000" placeholder="Votre demande"
                                        spellcheck="true" name="demande"></textarea>
                            </div>
                        </div>
                        <div id="service">
                            <h2>Services complémentaires : (cocher les services que vous souhaitez)</h2>
                            <ul>
                                <?php
                                $MAX = 6;
                                for ($i = 1; $i <= $MAX; $i++) {
                                    if ($i === 1) {
                                        $classe = "supplement first";
                                    } else if ($i === $MAX) {
                                        $classe = "supplement last";
                                    } else {
                                        $classe = "supplement";
                                    }
                                    $id = 'checkBox' . $i;
                                    $name = 'service' . $i;
                                    echo "<li>
                                    <div class='$classe'>
                                        <input id='$id' type='checkbox' name='$name'>
                                        <label for='$id'>Service 01</label>
                                        <p class='prix''><span>66,6</span>€</p>
                                    </div>
                                </li>
                            ";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div id="total">
                        <h2> Total de la réservation : <span id="prixTotal">0</span>€</h2>
                        <button class="boutton" type="submit">Confirmer la réservation et demander un devis</button>
                    </div>
                </form>
            </section>
        </div>
        <?php include("php/footer.php"); ?>
    </body>
    <script src="js/devis.js"></script>
</html>