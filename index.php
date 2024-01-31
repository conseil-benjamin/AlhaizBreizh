<?php 
    session_start(); 
    //connexion à la base de donnée
    try {
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $stmt = $pdo->prepare("SELECT numLogement,libelle,nbPersMax,tarifNuitees,LogementEnLigne,ville,note,typeLogement FROM ldc.Logement");

        //Recherche des logements dans la base de données
        $stmt->execute();
        $logements = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $logements[] = $row;
        }

        $pdo = null;
    } catch (PDOException $e) {
        $logements = array();
    }
?>
<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/index.css">
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
                                        <li <?php if ($tri==null){?> class="select"<?php }?>><a href="index.php#logements">Ancienneté (Ordre décroissant)</a></li>
                                        <li <?php if ($tri=="ancien"){?> class="select"<?php }?>><a href="index.php?tri=ancien#logements">Ancienneté (Ordre décroissant)</a></li>
                                        <li <?php if ($tri=="tarifmoins"){?> class="select"<?php }?>><a href="index.php?tri=tarifmoins#logements">Tarif (Ordre croissant)</a></li>
                                        <li <?php if ($tri=="tarifplus"){?> class="select"<?php }?>><a href="index.php?tri=tarifplus#logements">Tarif (Ordre décroissant)</a></li>
                                        <li <?php if ($tri=="notes"){?> class="select"<?php }?>><a href="index.php?tri=notes#logements">Notes</a></li>
                                        <li <?php if ($tri=="avis"){?> class="select"<?php }?>><a href="index.php?tri=avis#logements">Avis positifs</a></li>
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
                                    <option value="appart">Appartement</option>
                                    <option value="maison">Maison</option>
                                    <option value="villa">Villa</option>
                                </select>
                        </div>

                        <button id="menu-btn" class="boutton">Filtrer</button>

                    </div>
                </div>
            </div>
            <div id="contenur_logements">
                <?php

                //Choix du tri

                function tarif($a, $b) {
                    return $a[3] - $b[3]; //fonction de tri par tarif le moins eleve
                }
                function note($a, $b) {
                    return $a[6] <=> $b[6]; //fonction de tri par note la plus elevee
                }
                function avis($a, $b) {
                    return $a[7] <=> $b[7]; //fonction de tri par le nombre d'avis le plus eleve
                }

                if ($tri=="tarifmoins"){
                    usort($logements, 'tarif');
                }
                else if ($tri=="tarifplus"){
                    usort($logements, 'tarif');
                    $logements = array_reverse($logements);
                }
                else if ($tri=="notes"){ //On trie par ceux qui ont la meilleure note en premier
                    foreach ($logements as $logement){
                        $l_logement[]=$logement;
                        if (empty($logement[6])){
                            $l_logement[6]=2.5;
                        }
                    }
                    usort($l_logement, 'note');
                    $logements = array_reverse($l_logement);
                }
                else if ($tri=="avis"){ //fonction de tri par logement qui a le plus de commentaires superieur a 3 etoiles
                    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
                    $stmt = $pdo->prepare("SELECT nbetoiles,numlogement FROM ldc.Avis INNER JOIN ldc.AvisLogement ON ldc.Avis.numAvis = AvisLogement.idAvis WHERE nbetoiles>=3");

                    //Recherche des avis dans la base de données
                    $stmt->execute();
                    $avis = array();
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                        $avis[] = $row;
                    }
                    $pdo = null;
                    $l_logements=$logements;
                    foreach ($l_logements as &$logement){
                        $logement[]=0;
                    }
                    foreach ($avis as $avi){
                        foreach ($l_logements as &$logement){
                            if ($logement[0]==$avi[1]){
                                $logement[8]=$logement[8]+1;
                            }
                        }
                    }

                    usort($l_logements, 'note'); //Cette categorie est sous triee par les notes les plus elevee
                    usort($l_logements, 'avis'); //puis le tri principal, par nombre d'avis superieur a 3 etoiles

                    $l_logements = array_reverse($l_logements);

                    $logements=unserialize(serialize($l_logements));
                    
                }
                else if ($tri=="ancien"){
                    //option inverse du defaut : les plus anciens en premier
                }
                else{
                    $logements = array_reverse($logements); //option par defaut : les plus recents en premier
                }

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
                            <div data-information=<?php echo $logement[7]?>>
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
            let index = <?php echo $_GET["index"] ?>;
            console.log(index);
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