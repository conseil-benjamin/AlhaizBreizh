<?php 
    session_start(); 
    //connexion à la base de donnée
    try {
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $stmt = $pdo->prepare("SELECT numLogement,libelle,nbPersMax,tarifNuitees,LogementEnLigne,ville,note,typeLogement,adresse FROM ldc.Logement");

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

    //Tableau des adresses des logements id => "ville rue"
    $adresses = array();
    foreach ($logements as $logement) {
        $adresses[$logement[0]] = $logement[5].' '.$logement[8];
    }

    //Si erreur dans la récupération des coordonnées GPS
    if (in_array(null, $adresses)) {
        $erreurMap = true;
    } else {
        $erreurMap = false;
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
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <title>ALHaiz Breizh</title>
        
    </head>
    <body>
        <?php include $_SERVER['DOCUMENT_ROOT'] .'/src/php/header.php'; ?>

        <div class="map">
            <nav>
                <button id="bouttonResetMap" class="boutton"><img src="/public/icons/reset.svg" alt="Reset la vue"></button>
                <button id="bouttonCloseMap" class="boutton"><img src="/public/icons/croix.svg" alt="Fermer"></button>
            </nav>
            <div 
                <?php if (!($erreurMap)){ ?>
                    id="map"
                <?php } ?>>
                <?php if ($erreurMap) { ?>
                    <h2 class="error_map">Une erreur est survenue avec la carte :/</h2>
                <?php } ?>
            </div>
        </div>

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
                                <li <?php if ($tri=="ancien"){?> class="select"><a href="index.php#logements"> <?php }else{?> ><a href="index.php?tri=ancien#logements"><?php }?>Offre de la plus ancienne à la plus récente</li>
                                <li <?php if ($tri=="tarifmoins"){?> class="select"><a href="index.php#logements"> <?php }else{?> ><a href="index.php?tri=tarifmoins#logements"><?php }?>Tarif (- cher en premier)</li>
                                <li <?php if ($tri=="tarifplus"){?> class="select"><a href="index.php#logements"> <?php }else{?> ><a href="index.php?tri=tarifplus#logements"><?php }?>Tarif (+ cher en premier)</li>
                                <li <?php if ($tri=="notes"){?> class="select"><a href="index.php#logements"> <?php }else{?> ><a href="index.php?tri=notes#logements"><?php }?>Notes (meilleures en premier)</li>
                                <li <?php if ($tri=="avis"){?> class="select"><a href="index.php#logements"> <?php }else{?> ><a href="index.php?tri=avis#logements"><?php }?>Avis positifs (+ d'avis positifs)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="aucunLogementVisible">
                <h2>Aucun logement n'est visible sur la carte :/</h2>
            </div>
            <div id="conteneur_logements">

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
    
                        <div class="logement" id="logement<?php echo $logement[0] ?>">
                            <a href="<?php echo $lien ?>"><img src="<?php echo $img ?>"></a> <!-- Image du logement -->
                            <div data-information=<?php echo $logement[7]?>>
                            <button type="button"><img src="/public/icons/heart_white.svg"></button> <!-- Coeur pour liker -->
                                <?php if ($logement[6]!=NULL){ //Verifie que le logement a recu au moins une note?>                                
                                <div id="rating"><img src="/public/icons/star_fill.svg"><?php echo $logement[6]; ?></div> <!-- Notation -->
                                <?php } ?>
                            </div>   
                            <a id="description" href="<?php echo $lien ?>"><div id="resultat"> 
                                <h3 class="titre-logement"><?php echo $titre ?></h3> <!-- Titre du logement -->
                                <div><img src="/public/icons/nb_personnes.svg"><p class="nb-pers"><?php echo $nombre_personnes ?> personnes</p></div> <!-- Nombre de personnes -->
                                <div><img src="/public/icons/map.svg"><p class="localisation"><?php echo $localisation ?></p></div> <!-- Localisation -->
                                <div><p class="prix"><strong><?php echo $prix ?>€</strong> / nuit</p></div> <!-- Prix du logement -->
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
            <div class="bandeau">
                <div>
                    <button class="boutton" id="precedent"><img src="/public/icons/forward.svg" alt="Précédent"></button>
                    <button class="boutton" id="suivant"><img src="/public/icons/forward.svg" alt="Suivant"></button>
                </div>
                <button id="bouttonMap" class="boutton"><img src="/public/icons/map.svg" alt="Carte"></button>
            </div>
        </div>

        <?php include $_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'; ?>
        <script src="/src/js/side.js"></script>
        <script src="/src/js/accueilScroll.js"></script>
        <script>
            var adresses = <?php echo json_encode($adresses); ?>;
        </script>
        <script type="module" src="/src/js/map-accueil.js"></script>
    </body>
</html>