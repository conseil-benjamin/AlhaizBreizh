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
        $stmt = $pdo->prepare("SELECT numLogement,proprio,libelle,accroche,ville,tarifNuitees,note FROM ldc.Logement");

        //Recherche des logements dans la base de données
        $stmt->execute();
        $logements = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            if ($row[1] == $_SESSION['id']){
                $logements[] = $row;
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
                    <div class="menu_tri">
                        <button class="boutton">Trier</button>
                        <div class="menu_deroulant">
                        <ul>
                            <li <?php if ($_GET['tri']=="ancien"){?> class="select"><a href="mesLogements.php"> <?php }else{?> ><a href="mesLogements.php?tri=ancien"><?php }?>Offre de la plus ancienne à la plus récente</li>
                            <li <?php if ($_GET['tri']=="tarifmoins"){?> class="select"><a href="mesLogements.php"> <?php }else{?> ><a href="mesLogements.php?tri=tarifmoins"><?php }?>Tarif (- cher en premier)</li>
                            <li <?php if ($_GET['tri']=="tarifplus"){?> class="select"><a href="mesLogements.php"> <?php }else{?> ><a href="mesLogements.php?tri=tarifplus"><?php }?>Tarif (+ cher en premier)</li>
                            <li <?php if ($_GET['tri']=="notes"){?> class="select"><a href="mesLogements.php"> <?php }else{?> ><a href="mesLogements.php?tri=notes"><?php }?>Notes (meilleures en premier)</li>
                            <li <?php if ($_GET['tri']=="avis"){?> class="select"><a href="mesLogements.php"> <?php }else{?> ><a href="mesLogements.php?tri=avis"><?php }?>Avis positifs (+ d'avis positifs)</li>
                        </ul>
                        </div>
                    </div>
                </div>
                <a href="/src/php/logement/creationLogement.php" class="boutton">Ajouter un logement</a>
            </div>
            <div id="logements">
            <?php

                //Choix du tri

                function tarif($a, $b) {
                    return $a[5] - $b[5]; //fonction de tri par tarif le moins eleve
                }
                function note($a, $b) {
                    return $a[6] <=> $b[6]; //fonction de tri par note la plus elevee
                }
                function avis($a, $b) {
                    return $a[7] <=> $b[7]; //fonction de tri par le nombre d'avis le plus eleve
                }

                if ($_GET['tri']=="tarifmoins"){
                    usort($logements, 'tarif');
                }
                else if ($_GET['tri']=="tarifplus"){
                    usort($logements, 'tarif');
                    $logements = array_reverse($logements);
                }
                else if ($_GET['tri']=="notes"){ //On trie par ceux qui ont la meilleure note en premier
                    foreach ($logements as $logement){
                        $l_logement[]=$logement;
                        if (empty($logement[6])){
                            $l_logement[6]=2.5;
                        }
                    }
                    usort($l_logement, 'note');
                    $l_logement = array_reverse($l_logement);
                    $logements=unserialize(serialize($l_logement));
                    unset($logements[1]);
                }
                else if ($_GET['tri']=="avis"){ //fonction de tri par logement qui a le plus de commentaires superieur a 3 etoiles
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
                                $logement[7]=$logement[7]+1;
                            }
                        }
                    }

                    usort($l_logements, 'note'); //Cette categorie est sous triee par les notes les plus elevee
                    usort($l_logements, 'avis'); //puis le tri principal, par nombre d'avis superieur a 3 etoiles

                    $l_logements = array_reverse($l_logements);

                    $logements=unserialize(serialize($l_logements));
                    
                }
                else if ($_GET['tri']=="ancien"){
                    //option inverse du defaut : les plus anciens en premier
                }
                else{
                    $logements = array_reverse($logements); //option par defaut : les plus recents en premier
                }
                
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
                                <a class="boutton" href="/src/php/logement/modificationLogement.php?numLogement=<?php echo $logement[0]; ?>"><img src="/public/icons/edit.svg" alt="Editer">Editer</a>
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