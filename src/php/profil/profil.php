<?php
    session_start();

    //Vérifier si l'utilisateur est donné
    $user_set = true;
    if (!isset($_GET['user'])){
        $user_set = false;
    } else{
        $user = $_GET['user'];
        
        //Récupère la photo de profil de l'utilisateur
        $image_user = '/public/img/photos_profil/'.$user.'.png';
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].$image_user)){
            $image_user = '/public/img/icons/user.svg';
        }

        //Vérifier si la page est la page personnelle de l'utilisateur
        $page_personnelle = false;
        if ($user == $_SESSION['pseudo']){
            $page_personnelle = true;
        }

        //Récupère les informations de l'utilisateur
        $infos = array();
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
            $stmt = $pdo->prepare('SELECT firstName,lastName,pseudoCompte,dateNaissance,mail,numeroTel,adressePostale,motDePasse FROM ldc.Client WHERE idCompte = :user');
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);

            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $infos[] = $row;
            }
            $pdo = null; 
        } catch (\Throwable $th) {
            $user_set = false;
        }
    }  
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/profil.css">
    <title>Profil</title>
</head>
<body>
    <?php //include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); //Utilisation du get contents pour éviter d'avoir les mêmes variables ?> 
    <div class="content">

    <!-- Cas profil non trouvé -->
    <?php if ($user_set == false){ ?>

        <div id="titre">
            <h2>Profil non trouvé</h2>
        </div>

    <?php } else{ echo 'non'; ?>

    <!--Cas profil personnel -->
    <?php if ($page_personnelle){ ?>  

        <div id="titre">
            <img src="<?php echo $image_user ?>">
            <h2>Bienvenue <?php echo $_SESSION['pseudo'] ?></h2>
        </div>
        <div id="info">
            <?php 
                for ($i=0; $i < sizeof($infos); $i++) { 
                    echo '<p>'.$infos[$i].'</p>';
                }
            ?>
        </div>

    <?php }} ?>
    <?php
        echo $user_set;
        echo $page_personnelle;
    ?>
    </div>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
</body>
</html>