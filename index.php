<?php 
    session_start(); 
    //Connection à la base de donnée
    try {
        $pdo = new PDO("pgsql:host=postgresdb;port=5432;dbname=sae;user=sae;password=Phiegoosequ9en9o");
        $stmt = $pdo->prepare("SELECT * FROM ldc.Logement");

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
        <?php include './src/php/header.php'; ?>
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

                if (count($logements) === 0) { ?>
                    <h2>Aucun logement n'est disponible pour le moment :/</h2> <?php
                } else{
                    $dir = './public/img/logements';
                    $logements = array_reverse($logements); //Obtenir les derniers logements ajoutés en premier

                    $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=root");
                    $stmt = $pdo->prepare("SELECT numLogement,ville FROM ldc.Localisation");

                    //Recherche des localisations dans la base de données
                    $stmt->execute();
                    $localisations = array();
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                        $localisations[] = $row;
                    }
                    $pdo = null;

                    foreach ($logements as $logement) {
                        $lien = '/src/php/PageDetailLogement.php?numLogement=' . $logement[0];
                        $localisation = $localisations[$logement[0] - 1][1];
                        $img = $dir . '/' . $logement[0] . '/1.png'; ?>
    
                        <div class="logement">
                            <a href="<?php echo $lien ?>"><img src="<?php echo $img ?>"></a> <!-- Image du logement -->
                            <div>
                                <div id="rating"><img src="/public/icons/star_fill.svg">4.9</div> <!-- Notation -->
                                <button type="button"><img src="/public/icons/heart_white.svg"></button> <!-- Coeur pour liker -->
                            </div>   
                            <a id="description" href="<?php echo $lien ?>"><div> 
                                <h3><?php echo $logement[2] ?></h3> <!-- Titre du logement -->
                                <div><img src="/public/icons/nb_personnes.svg"><?php echo $logement[9] ?> personnes</div> <!-- Nombre de personnes -->
                                <div><img src="/public/icons/map.svg"><?php echo $localisation ?></div> <!-- Localisation -->
                                <div><strong><?php echo $logement[15] ?>€</strong> / nuit</div> <!-- Prix du logement -->
                            </div></a>
                        </div> <?php
                    } 
                } ?>
            </div> 
        </div>   
        <?php include './src/php/footer.php'; ?>
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