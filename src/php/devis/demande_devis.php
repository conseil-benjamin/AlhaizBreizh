<?php
session_start();
if (isset($_SESSION["id"])) {
        $nom = $_SESSION["nom_bien"];
        $nbNuit = $_POST["date_arrivee"];
        $prixNuit = $_SESSION["prixNuit"];
        $nbPersonne = $_SESSION["nbPersonneMax"];
        $numlogement = $_SESSION["numLogement"];
    } else {
    header("Location: /src/php/connexion/connexion.php");
}
global $pdo;
try {
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare(
        "SELECT nom,prix FROM ldc.Service where numlogement=$numlogement"
    );
    $stmt->execute();
    $tabServices = $stmt->fetchAll();
} catch (PDOException $e) {

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
        <link rel="icon" href="/public/logos/logo-black.svg">
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <!-- Librairie pour les alertes -->
        <title>Demande de devis</title>
    </head>
    <body>

        <?php
        if(isset($_POST["nb_personne"])) {
            require("submitDevisDB.php");
        }
        include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
        <div style="height: 75px"></div>
        <div id="fond">
            <section id="entete">
                <h1>
                    <?php echo $nom ?>
                </h1>
            </section>
            <section id="corpsTexte">
                <form method="post" id="form">
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
                                        <input class="input1" id="nbpersonne" max="<?=$nbPersonne?>" min="1" name="nb_personne"
                                               placeholder="nbpersonne" type="number" value="1">
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
                                $MAX = sizeof($tabServices);
                                if ($MAX == 0) {
                                    echo "<li> <h1> Aucun Services supplémentaire </h1> </li>";
                                }
                                for ($i = 0; $i < $MAX; $i++) {
                                    $service = $tabServices[$i];
                                    if ($i === 0) {
                                        $classe = "supplement first";
                                    } else if ($i === $MAX - 1) {
                                        $classe = "supplement last";
                                    } else {
                                        $classe = "supplement";
                                    }
                                    $id = 'checkBox' . $i;
                                    $name = 'service' . $i;
                                    echo "<li>
                                    <div class='$classe'>
                                        <input id='$id' type='checkbox' name='$name'>
                                        <label for='$id'>
                                        $service[nom]
                                        </label>
                                        <p class='prix''><span>$service[prix]</span>€</p>
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
                        <input type="hidden" name="total" value="0" id="prixTotalInput">
                        <button class="boutton" id="envoyer">Envoyer la demande de devis</button>
                    </div>
                </form>
            </section>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
    </body>
    <script src="../../js/devis.js"></script>

    <?php
    unset($_SESSION["nom_bien"]);
    unset($_POST["date_arrivee"]);
    unset($_SESSION["prixNuit"]);
    unset($_SESSION["nbPersonneMax"]);
    unset($_SESSION["numLogement"]);
    ?>
</html>

