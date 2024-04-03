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
        <link rel="icon" href="/public/logos/logo-black.svg">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/index.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/loading.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="icon" href="/public/logos/logo-black.svg">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
        <title>ALHaiz Breizh</title>      
    </head>
    <body>
        <?php include $_SERVER['DOCUMENT_ROOT'] .'/src/php/header.php'; ?>

        <div class="map">
            <h2>Chargement terminé !</h2>
            <div class="chargement-carte">
                <p>0/0</p>
                <div class="cssload-container">
                    <div class="cssload-zenith"></div>
                </div>
            </div>
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
            <a href="#logements" class="boutton" id="see-logements"><img src="/public/icons/arrow.svg">Voir les logements</a>
        </div>
        <div id="wave1">
            <img src="/public/icons/wave-white.svg">  
        </div>     
        <div id="logements">
            <h2>Les logements</h2>
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
                                <a class="item_tri" onclick="unnum(event)"><li>Ancienneté (Ordre décroissant)</li></a>
                                <a class="item_tri" onclick="tarif(event)"><li>Tarif (Ordre croissant)</li></a>
                                <a class="item_tri" onclick="untarif(event)"><li>Tarif (Ordre décroissant)</li></a>
                                <a class="item_tri" onclick="notes(event)"><li>Notes</li></a>
                                <a class="item_tri" href="index.php?tri=avis#logements"><li>Avis positifs</li></a>
                            </ul>
                        </div>
                    </div>
                    <input id="side_recherche" class="textfield" type="text" placeholder="Rechercher..">
                </div>
                <div>
                    <h2>Plage de tarif</h2>
                    <div class="hell">
                        <div class="select_filtr">
                            <p>Minimum</p>
                            <input id="side_min" class="number textfield" placeholder="En Euros" min="0" style="width: 120px;">
                        </div>
                        <div class="select_filtr">
                            <p>Maximum</p>
                            <input id="side_max" class="number textfield" placeholder="En Euros" min="0" style="width: 120px;">
                        </div>
                    </div>
                </div>
                <div>
                    <h2>Nombre de personnes</h2>
                    <input id="side_nb" class="textfield" type="number" min="1">
                </div>
                <div>
                    <h2>Plage de séjour</h2>
                    <div class="hell">
                        <div class="select_filtr">
                            <p>Date d'arrivée</p>
                            <input class="input1 textfield"  id="side_arrive" name="date_arrive" placeholder="JJ/MM/YYYY" type="date">
                        </div>
                        <div class="select_filtr">
                            <p>Date de départ</p>
                            <input class="input1 textfield" id="side_depart" name="date_depart" placeholder="JJ/MM/YYYY" type="date">
                        </div>
                    </div>    
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
            <div id="aucunLogementVisible">
                <h2>Aucun logement n'est visible sur la carte<br>ou votre recherche ne correpond à aucun logement :/</h2>
            </div>
            <button id="menu-btn" class="boutton" title="Filtrer et Trier"><img src="/public/icons/arrow.svg" alt="Filtrer et Trier"></button>
            <div id="conteneur_logements">

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
    
                        <div class="logement" id="logement<?php echo $logement[0] ?>">
                            <a href="<?php echo $lien ?>"><img src="<?php echo $img ?>" alt="Image du logement: <?= $titre ?>"></a> <!-- Image du logement -->
                            <div data-information=<?php echo $logement[7]?> >
                            <button type="button" class="like"><img src="/public/icons/heart_white.svg"></button> <!-- Coeur pour liker -->
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
                <button id="bouttonMap" class="boutton"><img src="/public/icons/map.svg" alt="Carte"></button>
            </div>
        </div>
        <img src="/public/icons/wave-white.svg" id="wave2">       
        <?php include $_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'; ?>
        <script src="/src/js/pressEnter.js"></script>
        <script src="/src/js/side.js"></script>
        <script src="/src/js/accueilScroll.js"></script>
        <script>

            //Vérifier si le navigateur connait la fonction css view()
            if (!CSS.supports('animation-timeline', 'view()')) {
                let logements = document.querySelectorAll(".logement");
                for (let i = 0; i < logements.length; i++) {
                    logements[i].style.opacity = "1";
                    logements[i].style.scale = "1.1";
                }
            }

            var adresses = <?php echo json_encode($adresses); ?>;
            var adaptFilter = false;

            let seeLogements = document.getElementById("see-logements");
            seeLogements.addEventListener("click", function() {
                event.preventDefault();
                document.getElementById("logements").scrollIntoView({behavior: "smooth"});
            });
        </script>
        <script src="/src/js/side.js" defer></script>
        <script type="module" src="/src/js/map-accueil.js" defer></script>
    </body>
</html>