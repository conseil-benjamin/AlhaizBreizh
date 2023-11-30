<?php 
    session_start(); 
    if (!isset($_SESSION['id'])) {
        header('Location: /src/php/connexion/connexion.php');
    } else if ($_SESSION['proprio'] == false) {
        header('Location: /');
    }
    //Connection à la base de donnée
    try{
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $stmt = $pdo->prepare("SELECT numLogement,proprio,libelle,accroche FROM ldc.Logement");

        //Recherche des logements dans la base de données
        $stmt->execute();
        $logements = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            if ($row[1] == $_SESSION['id']){
                $logements[] = $row;
            }
        }

        //Récupération des localisations
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
        <link rel="stylesheet" type="text/css" href="/src/styles/mesLogements.css">
        <title>ALHaiz Breizh</title>
    </head>
    <body>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
        <div id="content">
            <h2>Mes logements</h2>
            <div id="options">
                <div>
                    <input class="textfield" type="text" placeholder="Search..">
                    <button class="boutton">Filtrer</button>
                    <button class="boutton">Trier</button>
                </div>
                <a href="/src/php/logement/creationLogement.php" class="boutton">Ajouter un logement</a>
            </div>
            <div id="logements">
                <?php
                if (count($logements) === 0){ ?>
                    <h3>Vous n'avez aucun logement pour le moment :/</h3> <?php
                } else{
                    /*Créations de carte pour chaque logements*/
                    foreach ($logements as $logement) { ?>
                        <a href="/src/php/logement/PageDetailLogement.php?numLogement=<?php echo $logement[0] ?>"><div class="logement">
                            <img src="/public/img/logements/<?php echo $logement[0] ?>/1.png" alt="logement">
                            <div>
                                <h3><?php echo $logement[2] ?></h3>
                                <p><?php echo $logement[3] ?></p>
                                <div><img src="/public/icons/map.svg" alt="Map"><?php echo $logement[4] ?></div>
                            </div>   
                        </div></a> <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
    </body>
</html>