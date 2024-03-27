<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /');
    exit();
} else {
    $id = $_SESSION['id'];
}

try {
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $stmt = $pdo->prepare("SELECT libelle,LogementEnLigne,numlogement FROM ldc.Logement");

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

$pdo = null;
?>
<!DOCTYPE html>
<html lang="fr-fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/profil.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/ical.css">
    <link rel="icon" href="/public/logos/logo-black.svg">
    <title>ALHaiz Breizh</title>
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/src/php/header.php'; ?>
<div class="monprofil">
    <div id="titre">
        <img src="/public/icons/calendar.svg" alt="">
        <h2>Mes Abonnements Ical</h2>
    </div>

    <div id="info">
        <!--TODO La liste des ses abonnements  -->
        <div>
            <p>Vous n'avez pas encore d'abonnement</p>
        </div>


        <form id="aboForm" enctype="application/x-www-form-urlencoded" action="./ical/addAbo.php" method="get">
            <div id="dateAbonnement">
                <label for="debut">
                    S'abonner du :
                </label>
                <input class="input1" id="debut" name="date_debut" placeholder="JJ/MM/YYYY"
                       type="date">
                <label for="fin">
                    Au :
                </label>
                <input class="input1" id="fin" name="date_fin" placeholder="JJ/MM/YYYY"
                       type="date">
            </div>
            <div id="listeLogement">
                <ul>
                    <?php
                    $partition = sizeof($logements) / 3;
                    foreach ($logements as $cle => $logement) {
                        $actif = $logement[1];
                        if (($cle == $partition) or ($cle == $partition * 2)) {
                            echo '</ul>';
                            echo '<ul>';
                        }
                        if ($actif) {
                            echo "<li> <input type='checkbox' id='bien$cle' name='$logement[2]'/> <label for='bien$cle'> $logement[0] </label></li>";
                        }
                    }
                    ?>
                </ul>
            </div>
            <button type="submit" class="boutton" >test</button>
        </form>
        <button class="boutton" id="aboBtn"> S'abonner </button>
    </div>
    <!--<a id="addButton" class="boutton"><img src="/public/icons/plus.svg" alt="Ajouter"></a>-->
    <div id="options">
        <a class="boutton" href="/src/php/profil/profil.php">Retour</a>
    </div>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/src/php/footer.php'; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/src/js/profil/cal/addCal.js"></script>
</html>