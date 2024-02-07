<?php
session_start();
    if (isset($_SESSION)) {
        $nomBien = $_SESSION["nom_bien"];
    } else {
        $nomBien = "Superbe Maison à la plage";
        $dateArrivee = "2023-10-19";
        $dateDepart = "2023-10-19";
        $nbPersonne = 10;
        $demande = "Petit déjeuner au lit";
    }
    $tabServices = [];
    require "propositionGetDb.php";
?>
<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <link href="/src/styles/proposition_devis.css" rel="stylesheet" type="text/css">
        <link href="/src/styles/demande_devis.css" rel="stylesheet" type="text/css">
        <link href="/src/styles/styles.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="/public/logos/logo-black.svg">
        <title>Proposition de devis</title>
    </head>
    <body>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
        <div style="height: 75px"></div>
        <div id="fond">
            <section id="entete">
                <h1>
                    <?= $nomBien ?>
                </h1>
            </section>
            <section id="corpsTexte">
                <form method="post" enctype="application/x-www-form-urlencoded">
                    <div id="formulaire">
                        <div id="prixDiv">
                            <ul>
                                <li>
                                    <div class="labelPrix">
                                        <label for="arrivee">
                                            Date d'arrivée :
                                        </label>
                                    </div>
                                    <div>
                                        <input class="input1" id="arrivee" name="date_arrivee"
                                            type="date" value="<?php echo $dateArrivee ?>"" readonly>
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
                                            type="date" value="<?php echo $dateDepart ?>" readonly>
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
                                            placeholder="nbpersonne" type="number" value="<?php echo $nbPersonne ?>"
                                            readonly>
                                    </div>
                                </li>
                            </ul>
                            <div>
                                <label for="demande">Autre demande</label>
                                <textarea id="demande" maxlength="1000" content="<?php echo $demande ?>"
                                        spellcheck="true" name="demande" readonly></textarea>
                            </div>
                            <div id="upload">
                                <div id="uploadInput">
                                    <label for="devis" id="devisLabel">Upload votre devis</label>
                                    <input type="file" accept=".pdf" name="devis" id="devis">
                                </div>
                                <div id="ficUpload">
                                    <button id="annulerFicUpload" type="button">Annuler</button>
                                    <label id="ficUploadNom"></label></div>
                            </div>
                        </div>
                        <div id="service">
                            <h2>Services complémentaires :</h2>
                            <ul>
                                <?php
                                $MAX = sizeof($tabServices);
                                for ($i = 1; $i <= $MAX; $i++) {
                                    $service = $tabServices[$i];
                                    if ($i === 1) {
                                        $classe = "supplement first";
                                    } else if ($i === $MAX) {
                                        $classe = "supplement last";
                                    } else {
                                        $classe = "supplement";
                                    }
                                    $id = 'checkBox' . $i;
                                    $nom = $service['nom'];
                                    echo "<li>
                                    <div class='$classe'>
                                        <label for='$id'>$nom</label>
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
                        <button class="boutton" type="submit" id="envoyer">Envoyer le devis</button>
                    </div>
                </form>
            </section>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
    </body>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <!-- Librairie pour les alertes -->
    <script src="../../js/proposition_devis.js"></script>
    <?php
    if(isset($_POST['devis'])) {
        require("submitPropositionDevisDB.php");
    }
    ?>
</html>