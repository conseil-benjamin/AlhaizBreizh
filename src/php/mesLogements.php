<?php 
    session_start(); 
    if (!isset($_SESSION['id'])) {
        header('Location: /src/php/connection.php');
    } else if ($_SESSION['proprio'] == false) {
        header('Location: /');
    }
    //Connection à la base de donnée
    try{
        $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=root");
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
            $stmt = $pdo->prepare("SELECT numLogement,ville FROM ldc.Localisation");
            $stmt->execute();
            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                if ($row[0] === $logements[$i][0]) {
                    $logements[$i][] = $row[1];
                }
                if ($i == count($logements)-1) {
                    break;
                }
                $i++;
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
        <?php include 'header.php'; ?> 
        <div id="content">
            <h2>Mes logements</h2>
            <div id="options">
                <div>
                    <input type="text" placeholder="Search..">
                    <button class="boutton">Filtrer</button>
                    <button class="boutton">Trier</button>
                </div>
                <a href="/src/php/creationLogement.php" class="boutton">Ajouter un logement</a>
            </div>
            <div id="logements">
                <?php
                if (count($logements) === 0){ ?>
                    <h3>Vous n'avez aucun logement pour le moment :/</h3> <?php
                } else{
                    /*Créations de carte pour chaque logements*/
                    foreach ($logements as $logement) { ?>
                        <a href="/src/php/PageDetailLogement.php?numLogement=<?php echo $logement[0] ?>"><div class="logement">
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
        <?php include 'footer.php'; ?>
    </body>
</html>