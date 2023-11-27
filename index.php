<?php 
    session_start(); 
    //connexion à la base de donnée
    try {
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $stmt = $pdo->prepare("SELECT numLogement,libelle,nbPersMax,tarifNuitees,LogementEnLigne FROM ldc.Logement");

        //Recherche des logements dans la base de données
        $stmt->execute();
        $logements = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $logements[] = $row;
        }

        //Obtenir la localisation de chaque logement
        if (count($logements) !== 0) {
            $stmt = $pdo->prepare("SELECT numLogement,ville FROM ldc.Localisation WHERE numLogement = ?");
            foreach ($logements as $logement) {
                $stmt->execute([$logement[0]]);
                $ville = $stmt->fetch(PDO::FETCH_NUM)[1];
                $logements[($logement[0] - 1)][] = $ville;
            }
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
            <div>
                <?php
                /*Créations de carte pour chaque logements*/

                $nb_logements_inactifs = 0;
                $logements = array_reverse($logements);
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
                            <div>
                                <div id="rating"><img src="/public/icons/star_fill.svg">4.9</div> <!-- Notation -->
                                <button type="button"><img src="/public/icons/heart_white.svg"></button> <!-- Coeur pour liker -->
                            </div>   
                            <a id="description" href="<?php echo $lien ?>"><div> 
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
        <script>
            window.addEventListener("scroll", () => {
                var header = document.querySelector("header");
                
                if (window.scrollY > 0) {
                    header.classList.add("scrolled");
                } else{
                    header.classList.remove("scrolled");
                }
            });
        </script>
    </body>
</html>