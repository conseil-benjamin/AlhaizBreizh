<?php
$nom = $_SESSION["nom_bien"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="./styles/proposition_devis.css" rel="stylesheet" type="text/css">
    <link href="./styles/demande_devis.css" rel="stylesheet" type="text/css">
    <link href="./styles/styles.css" rel="stylesheet" type="text/css">
    <title>Demande de devis</title>
</head>
<body>
<header id="header">
    <a class="logo" href="/index.html"><img src="/public/logos/logo-grey.svg" alt="logo"></a>
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
        <form method="post" action="submitPropositionDevis.php">
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
                                       type="date" value="2023-10-19" readonly>
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
                                       type="date" value="2023-10-19" readonly>
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
                                       placeholder="nbpersonne" type="number" value="10" readonly>
                            </div>
                        </li>
                    </ul>
                    <div>
                        <label for="demande">Autre demande (1000 caractères maximum)</label>
                        <textarea id="demande" maxlength="1000" placeholder="Votre demande"
                                  spellcheck="true" name="demande" readonly></textarea>
                    </div>
                    <div id="upload">
                        <div id="uploadInput">
                            <label for="devis" id="devisLabel">Upload votre devis</label>
                            <input type="file" accept="application/pdf" name="devis" id="devis">
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
                <button type="submit">Envoyer le devis</button>
            </div>
        </form>
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
</body>
<script src="js/proposition_devis.js"></script>
</html>

