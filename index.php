<?php 
    session_start(); 
    /***********************************************************************/
    /***********************************************************************/
    //Constantes
    $MAX_LOGEMENTS = 4;

    /***********************************************************************/
    /***********************************************************************/
    //Définition des fonctions
    function obtenirNombreLogements(){
        //Permet d'obtenir le nombre de logements dans la base de données
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM ldc.Logement WHERE LogementEnLigne = true");
            $stmt->execute();
            $nombreLogements = $stmt->fetch(PDO::FETCH_NUM)[0];

            $pdo = null;
        } catch (PDOException $e) {
            $nombreLogements = -1;
        }
        return $nombreLogements;
    }

    function obtenirLogementAPartirIndex($index) {
        //Permet d'obtenir 8 logement à partir d'un index
        global $MAX_LOGEMENTS;
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

            //Obtenir les 8 derniers logements ajoutés dans la base de donnée où LogementEnLigne = true
            $stmt = $pdo->prepare("SELECT numLogement,libelle,nbPersMax,tarifNuitees,LogementEnLigne,ville FROM ldc.Logement WHERE LogementEnLigne = true ORDER BY numLogement DESC LIMIT :maxlogements OFFSET :index");

            //Recherche des logements dans la base de données
            $stmt->bindParam(':maxlogements', $MAX_LOGEMENTS, PDO::PARAM_INT);
            $stmt->bindParam(':index', $index, PDO::PARAM_INT);
            $stmt->execute();
            $logements = array();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $logements[] = $row;
            }

            $pdo = null;
        } catch (PDOException $e) {
            $logements = array();
        }
        return $logements;
    }
    /***********************************************************************/
    /***********************************************************************/
    //Définition des variables
    if (isset($_GET['index']) && $_GET['index'] != null && $_GET['index'] != ""){
        $index = $_GET['index'];
    } else{
        $index = 0;
    }

    $nombreLogements = obtenirNombreLogements();
    if (($index > $nombreLogements) || ($index < 0)){
        header('Location: /index.php?index=0');
    }

    if ($nombreLogements != -1) {
        $logements = obtenirLogementAPartirIndex($index);
    } else {
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
        <script src="/src/js/loading.js"></script>
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
                if ($nombreLogements == -1){ ?>
                    <h2>Une erreur de connexion est survenue</h2> <?php
                } else if (($logements == null) || (count($logements) == 0)){ ?>
                    <h2>Votre recherche correspond à aucun logement :/</h2><?php
                } else{
                    foreach ($logements as $logement) {
                        $actif = $logement[4];
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
                    } 
                } ?>
            </div> 
            <nav>
                <a class="boutton" href="/index.php?index=<?php echo ($index-$MAX_LOGEMENTS) ?>">Précédent</a>
                <a class="boutton" href="/index.php?index=<?php echo ($index+$MAX_LOGEMENTS) ?>">Suivant</a>
            </nav>
        </div>   
        <?php include $_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'; ?>
        <script>
            //Si on a l'attribut index dans l'url, on scroll jusqu'au logement
            var index = <?php echo $index ?>;
            if (index != null){
                var logement = document.getElementById("logements");
                logement.scrollIntoView();
            }    

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