<?php 
    require_once("../chargerMesLogements.php")
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
                            <img id="suppr" src="../../../public/icons/supprimer.png" alt="Icône Supprimer" onclick="abime()">
                            <div class="menu_tri">
                                <?php
                                    if (isset($_GET['tri'])){
                                        $tri=$_GET['tri'];
                                    }
                                    else{
                                        $tri=null;
                                    }
                                ?>
                                <button class="boutton">Trier</button>
                                <div class="menu_deroulant">
                                    <ul>
                                        <a class="item_tri select" onclick="num(event)">Ancienneté (Ordre décroissant)</a>
                                        <a  class="item_tri" onclick="unnum(event)">Ancienneté (Ordre décroissant)</a>
                                        <a  class="item_tri" onclick="tarif(event)">Tarif (Ordre croissant)</a>
                                        <a  class="item_tri" onclick="untarif(event)">Tarif (Ordre décroissant)</a>
                                        <a  class="item_tri" onclick="notes(event)">Notes</a>
                                        <a  class="item_tri" href="index.php?tri=avis#logements">Avis positifs</a>
                                    </ul>
                                </div>
                            </div>

                            <input id="side_recherche" class="textfield" type="text" placeholder="Rechercher..">
                            <h2>Ville</h2>
                            <select id="side_ville">
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
                            <h2>Type du logement</h2>
                                <select id="side_type">
                                    <option value="">---</option>
                                    <option value="appartement">Appartement</option>
                                    <option value="maison">Maison</option>
                                    <option value="villa">Villa</option>
                                </select>
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