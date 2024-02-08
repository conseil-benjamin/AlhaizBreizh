<?php 
    require_once("./src/php/chargerLogements.php")
?>
<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/public/logos/logo-black.svg">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/index.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="icon" href="/public/logos/logo-black.svg">
        <title>ALHaiz Breizh</title>
        
    </head>
    <body>
        <?php include $_SERVER['DOCUMENT_ROOT'] .'/src/php/header.php'; ?>
        <video id="background" autoplay loop muted>
            <source src="/public/videos/video-bretagne.mp4" type="video/mp4">
        </video>
        <div id="titre">
            <h1>Envie de découvrir la Bretagne ?</h1>
            <p>Nous avons tout pour vous mettre ALHaIZ</p>
        </div>


        <div id="logements">
            <h2>Les logements</h2>
            <div id="options">
                <div>
                    <div class="menu_filtre">
                        <div id="sidebar">
                            <img id="suppr" src="public/icons/supprimer.png" alt="Icône Supprimer" onclick="abime()">
                            <div class="menu_tri">
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
                            <h2>Plage de séjour</h2>
                                <div class="hell">
                                    <div class="select_filtr">
                                        <p>Date d'arrivée</p>
                                        <input class="input1" id="side_arrive" name="date_arrive" placeholder="JJ/MM/YYYY" type="date">
                                    </div>
                                    <div class="select_filtr">
                                        <p>Date de départ</p>
                                        <input class="input1" id="side_depart" name="date_depart" placeholder="JJ/MM/YYYY" type="date">
                                    </div>
                                </div>
                            <?php //<h2>Propriétaire</h2>
                            //<input class="textfield" type="text" placeholder="Nom du propriétaire..."> ?>
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
                </div>
            </div>
            <div id="contenur_logements">
                <?php

                /*Créations de carte pour chaque logements*/

                $nb_logements_inactifs = 0;

                foreach ($logements as $logement) {
                    $actif = $logement[4];
                    if ($actif) {
                        $lien = '/src/php/logement/PageDetailLogement.php?numLogement=' . $logement[0];
                        $img = '/public/img/logements/' . $logement[0] . '/1.png';

                        $titre = $logement[1];
                        $nombre_personnes = $logement[2];
                        $localisation = $logement[5];
                        $prix = $logement[3] ?>
    
                        <div class="logement">
                            <a href="<?php echo $lien ?>"><img src="<?php echo $img ?>"></a> <!-- Image du logement -->
                            <div data-information=<?php echo $logement[7]?> >
                            <button type="button"><img src="/public/icons/heart_white.svg"></button> <!-- Coeur pour liker -->
                                <?php if ($logement[6]!=NULL){ //Verifie que le logement a recu au moins une note?>                                
                                <div id="rating"><img src="/public/icons/star_fill.svg"><?php echo $logement[6]; ?></div> <!-- Notation -->
                                <?php } ?>
                            </div>   
                            <a id="description" href="<?php echo $lien ?>"><div id="resultat"> 
                                <h3><?php echo $titre ?></h3> <!-- Titre du logement -->
                                <div><img src="/public/icons/nb_personnes.svg"><p><?php echo $nombre_personnes ?> personnes</p></div> <!-- Nombre de personnes -->
                                <div><img src="/public/icons/map.svg"><p><?php echo $localisation ?></p></div> <!-- Localisation -->
                                <div><p><strong><?php echo $prix ?>€</strong> / nuit</p></div> <!-- Prix du logement -->
                            </div></a>
                        </div> <?php
                    } else{
                        $nb_logements_inactifs++;
                    }
                } 
                if ($nb_logements_inactifs == count($logements)){ ?>
                    <h2>Aucun logement n'est disponible pour le moment :/</h2><?php
                } ?>
            </div> 
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'; ?>
        <script src="/src/js/side.js"></script>
        <script src="/src/js/accueilScroll.js"></script>
        <script>
            //Si on a l'attribut index dans l'url, on scroll jusqu'au logement
            let index = null;
            if (index != null){
                let logement = document.getElementById("logements");
                logement.scrollIntoView();
            } else {
                let header = document.querySelector("header");
                header.scrollIntoView();
            }

        </script>
    </body>
</html>