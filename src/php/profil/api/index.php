<?php 
    session_start(); 

    if (!isset($_SESSION['id'])){
        header('Location: /');
        exit();
    } else if ($_SESSION['proprio'] != true){
        header('Location: /src/php/profil/profil.php');
        exit();
    } else{
        $id = $_SESSION['id'];
    }

    //Récupérer les clés API du propriétaire
    $droits = array('R' => 'Lecture', 'RU' => 'Lecture et update');
    $clesAPI = array();

    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $stmt = $pdo->prepare("SELECT apikey, droit FROM ldc.APIkey WHERE id_proprio = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['droit'] = $droits[$row['droit']];
        $clesAPI[$row['apikey']] = $row['droit'];
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
        <link rel="stylesheet" type="text/css" href="/src/styles/api.css">
        <link rel="icon" href="/public/logos/logo-black.svg">
        <title>ALHaiz Breizh</title>
    </head>
    <body>
        <?php include $_SERVER['DOCUMENT_ROOT'] .'/src/php/header.php'; ?>
        <div class="monprofil">
            <div id="titre">
                <img src="/public/icons/clef.svg" alt="">
                <h2>Mes clés API</h2>
            </div>
            
            <div id="info">
                <div>
                <?php    
                    if (empty($clesAPI)){ ?>
                        <p>Vous n'avez pas encore de clé API</p> <?php
                    }

                    foreach ($clesAPI as $key => $value) {
                        echo '<a><ul><li>'.$key.'</li>';
                        echo '<li>'.$value.'</li></ul>';
                        echo '<hr>';
                    }
                ?>
                </div>
                <a id="addButton" class="boutton" href="add.php"><img src="/public/icons/plus.svg" alt="Ajouter"></a>
            </div>

            <div id="options">
                <a class="boutton" href="/src/php/profil/profil.php">Retour</a>
            </div>
        </div>
        <form action="/src/php/profil/api/add.php" id="addForm"></form>
        <form action="/src/php/profil/api/remove.php" id="removeForm"></form>

        <?php include $_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="/src/js/profil/api/addApiKey.js"></script>
        <script src="/src/js/profil/api/copyRemoveApiKey.js"></script>
    </body>
</html>