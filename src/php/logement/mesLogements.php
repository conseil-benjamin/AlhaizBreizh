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
                            <h2>Plage de tarif</h2>
                                <div class="hell">
                                    <div class="select_filtr">
                                        <p>Minimum</p>
                                        <input id="side_min" class="number" placeholder="En Euros" min="0" style="width: 120px;">
                                    </div>
                                    <div class="select_filtr">
                                        <p>Maximum</p>
                                        <input id="side_max" class="number" placeholder="En Euros" min="0" style="width: 120px;">
                                    </div>
                                </div>
                            <h2>Nombre de personnes</h2>
                                <input id="side_nb" type="number" min="1">
                            <?php //<h2>Propriétaire</h2>
                            //<input class="textfield" type="text" placeholder="Nom du propriétaire..."> ?>
                            <h2>Type du logement</h2>
                                <select id="side_type">
                                    <option value="">---</option>
                                    <option value="appart">Appartement</option>
                                    <option value="maison">Maison</option>
                                    <option value="villa">Villa</option>
                                </select>
                        </div>

                        <button id="menu-btn" class="boutton">Filtrer et Trier</button>

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
                            <img src="/public/img/logements/<?php echo $logement[0] ?>/1.png" alt="logement">
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