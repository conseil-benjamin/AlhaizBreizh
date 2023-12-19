<?php 
    session_start(); 
    if (!isset($_SESSION['id'])) {
        header('Location: /src/php/connexion/connexion.php');
    } else if ($_SESSION['proprio'] == false) {
        header('Location: /');
    }

    function obtenirLogementsProprio($id) {
        $logements = array();
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
            $stmt = $pdo->prepare("SELECT numLogement,proprio,libelle,accroche,ville FROM ldc.Logement ORDER BY numLogement DESC");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                if ($row[1] == $id){
                    $logements[] = $row;
                }
            }
            $pdo = null;
        } catch (PDOException $e) {
            $logements = array();
        }
        return $logements;
    }

    $logements = obtenirLogementsProprio($_SESSION['id']);
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
                    <input class="textfield" type="text" placeholder="Search..">
                    <div>
                        <button class="boutton">Filtrer</button>
                        <button class="boutton">Trier</button>
                    </div>
                </div>
                <div>
                    <a href="/src/php/logement/creationLogement.php" class="boutton">Ajouter un logement</a>
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
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
    </body>
</html>