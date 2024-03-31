<?php 
    require_once("../chargerMesLogements.php");

    if (isset($_GET['tri'])){
        $tri=$_GET['tri'];
    }
    else{
        $tri=null;
    }
?>
<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/mesLogements.css">
        <link rel="icon" href="/public/logos/logo-black.svg">
        <title>ALHaiz Breizh</title>
    </head>
    <body>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
        <div id="content">
            <h1>Mes logements</h1>
            <div id="options">
                <div>
                    <div class="menu_filtre">
                        <div id="sidebar">
                            <div class="filtrer-trier">
                                <h1>Filtrer et Trier</h1>
                                <img id="suppr" src="/public/icons/croix.svg" alt="Fermer" onclick="abime()">
                            </div>
                            <div class="group-tri-recherche">
                                <div class="menu_tri">
                                    <button class="boutton">Trier</button>
                                    <div class="menu_deroulant">
                                        <ul>
                                            <a class="item_tri select" onclick="num(event)"><li>Ancienneté (Ordre décroissant)</li></a>
                                            <a  class="item_tri" onclick="unnum(event)"><li>Ancienneté (Ordre décroissant)</li></a>
                                            <a  class="item_tri" onclick="tarif(event)"><li>Tarif (Ordre croissant)</li></a>
                                            <a  class="item_tri" onclick="untarif(event)"><li>Tarif (Ordre décroissant)</li></a>
                                            <a  class="item_tri" onclick="notes(event)"><li>Notes</li></a>
                                            <a  class="item_tri" href="index.php?tri=avis#logements"><li>Avis positifs</li></a>
                                        </ul>
                                    </div>
                                </div>
                                <input id="side_recherche" class="textfield" type="text" placeholder="Rechercher..">
                            </div>
                            <div>
                                <h2>Ville</h2>
                                <select id="side_ville" class="textfield">
                                    <option value="">---</option>
                                    <?php
                                        $tab=[];
                                        foreach ($logements as $logement) {
                                            $ville = $logement[4];
                                            if (!in_array($ville,$tab)){
                                                echo "<option value=\"{$logement[4]}\">{$logement[4]}</option>";
                                                $tab[]=$ville;
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <h2>Type du logement</h2>
                                <select id="side_type" class="textfield">
                                    <option value="">---</option>
                                    <option value="appartement">Appartement</option>
                                    <option value="maison">Maison</option>
                                    <option value="villa">Villa</option>
                                </select>
                            </div>
                        </div>

                        <button id="menu-btn" class="boutton">Filtrer et Trier</button>

                    </div>
                    <div>
                        <a href="/src/php/logement/creationLogement.php" class="boutton">Ajouter un logement</a>
                    </div>
                </div>
            </div>
            <div id="logements">
                <?php
                if (count($logements) === 0){ ?>
                    <h3>Vous n'avez aucun logement pour le moment :/</h3> <?php
                } else{
                    /*Créations de carte pour chaque logements*/
                    foreach ($logements as $logement) { ?>
                        <div class="logement">
                            <img src="/public/img/logements/<?php echo $logement[0] ?>/1.png" alt="logement" place=<?php echo $logement[4];?> data-information=<?php echo $logement[6]; ?>>
                            <div>
                                <h3><?php echo $logement[2] ?></h3>
                                <p><?php echo $logement[3] ?></p>
                                <div><img src="/public/icons/map.svg" alt="Map"><?php echo $logement[4] ?></div>
                                <nav>
                                    <a class="boutton" href="/src/php/logement/PageDetailLogement.php?numLogement=<?php echo $logement[0] ?>"><img src="/public/icons/type_logement.svg" alt="">Voir</a>
                                    <a class="boutton" href="/src/php/logement/modificationLogement.php?numLogement=<?php echo $logement[0]; ?>"><img src="/public/icons/edit.svg" alt="Editer">Editer</a>
                                </nav>
                            </div>
                        </div> <?php
                    }
                }
                ?>
            </div>
        </div>
        <script src="/src/js/side_bis.js"></script>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
    </body>
</html>