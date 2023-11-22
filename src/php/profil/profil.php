<?php
    session_start();

    //Vérifier si l'utilisateur est donné
    $user_set = true;
    if (!isset($_GET['user'])){
        $user_set = false;
    } else{
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $user = $_GET['user'];
        
        //Récupère la photo de profil de l'utilisateur
        $image_user = '/public/img/photos_profil/'.$user.'.png';
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].$image_user)){
            $image_user = '/public/img/icons/user.svg';
        }

        //Vérifier si la page est la page personnelle de l'utilisateur
        $page_personnelle = false;
        if ((isset($_SESSION['id'])) && ($user == $_SESSION['id'])){
            $page_personnelle = true;
        } 

        //Vérifier si la page est une page d'un propriétaire
        $page_proprio = false;
        try{
            $stmt = $pdo->prepare('SELECT idCompte FROM ldc.Proprietaire WHERE idCompte = :user');
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);

            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $page_proprio = true;
            }
        } catch (\Throwable $th) {
            //Erreur
        }

        //Récupère les informations de l'utilisateur
        $infos = array();
        $keys = array('Prénom','Nom','Pseudo','Date de Naissance','Adresse Mail','Téléphone','Adresse Postale','Mot de Passe','Notation Moyenne');
        try {
            $stmt = $pdo->prepare('SELECT firstName,lastName,pseudoCompte,dateNaissance,mail,numeroTel,adressePostale,motDePasse,notationMoyenne FROM ldc.Client WHERE idCompte = :user');
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);

            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                for ($i=0; $i < sizeof($row); $i++) { 
                    $infos[$keys[$i]] = $row[$i];
                }
            }

            if (sizeof($infos) == 0){
                $user_set = false;
            }
        } catch (\Throwable $th) {
            $user_set = false;
        }

        if ($user_set){
            $sommeNotationAvis = 0;

            //Définir le nombre de commentaires à afficher
            if (isset($_GET['nbCommentaires'])){
                $nbCommentaires = $_GET['nbCommentaires'];
            } else{
                $nbCommentaires = 2;
            }

            //Récupère le nombre de commentaire total envoyé par l'utilisateur
            $nbCommentairesEnvoyes = 0;
            try {
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM ldc.AvisClient WHERE idCompte = :user');
                $stmt->bindParam(':user', $user, PDO::PARAM_STR);

                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $nbCommentairesEnvoyes = $row[0];
                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            //Récupère les commentaires à destination de l'utilisateur
            $commentaires = array();
            $keys = array('id','notation','avis','pseudo');
            try {
                $stmt = $pdo->prepare('SELECT idCompte,nbEtoiles,contenuAvis FROM ldc.AvisClient INNER JOIN ldc.Avis ON idAvis = numAvis WHERE idDestinataire = :user ORDER BY idAvis DESC LIMIT :limit');
                $stmt->bindParam(':user', $user, PDO::PARAM_STR);
                $stmt->bindParam(':limit', $nbCommentaires, PDO::PARAM_INT);

                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $commentaire = array();
                    for ($i=0; $i < sizeof($row); $i++) { 
                        if ($keys[$i] == 'notation'){
                            // Arrondi à 1 chiffre après la virgule la notation
                            $row[$i] = number_format($row[$i], 1);
                            $sommeNotationAvis += $row[$i];
                        }
                        $commentaire[$keys[$i]] = $row[$i];
                    }
                    array_push($commentaires, $commentaire);
                }

                // Récupère les pseudos des utilisateurs ayant laissé un commentaire
                if (sizeof($commentaires) != 0){
                    $stmt = $pdo->prepare('SELECT pseudoCompte FROM ldc.Client WHERE idCompte = :user');
                    foreach ($commentaires as $key => $commentaire) {
                        $stmt->bindParam(':user', $commentaire['id'], PDO::PARAM_STR);

                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                            $commentaires[$key][$keys[(sizeof($keys)-1)]] = $row[0];
                        }
                    } 
                }
                
            } catch (\Throwable $th) {
                throw $th;
            }

            // Update la moyenne de notation de l'utilisateur
            /*
            if (sizeof($commentaires) != 0){
                $infos['Notation Moyenne'] = $sommeNotationAvis / sizeof($commentaires);
            } else{
                $infos['Notation Moyenne'] = 0;
            }
            try {   
                $stmt = $pdo->prepare('UPDATE ldc.Client SET notationMoyenne = :moy WHERE idCompte = :user');
                $stmt->bindParam(':moy', $infos['Notation Moyenne'], PDO::PARAM_DECIMAL, 1);
                $stmt->bindParam(':user', $user, PDO::PARAM_STR);

                $stmt->execute();
            } catch (\Throwable $th) {
                //throw $th;
            } */
            
            $pdo = null; 

            //Adapter les informations
            $infos['Date de Naissance'] = date('d/m/Y', strtotime($infos['Date de Naissance']));
            $infos['Mot de Passe'] = preg_replace('/./u', '*', $infos['Mot de Passe']);
            $infos['Notation Moyenne'] = number_format($infos['Notation Moyenne'], 1);
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
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); //Utilisation du get contents pour éviter d'avoir les mêmes variables ?> 
    <div class="<?php if ($page_personnelle) { echo 'monprofil'; } else { echo 'profil'; } ?>">

    <!-- Cas profil non trouvé -->
    <?php if ($user_set == false){ ?>

        <div id="titre">
            <h2>Profil non trouvé :/</h2>
        </div>

    <?php } else{ ?>

    <!--Cas profil personnel -->
    <?php if ($page_personnelle){ ?>  

        <div id="titre">
            <img src="<?php echo $image_user ?>">
            <h2>Bienvenue <?php echo $_SESSION['pseudo'] ?></h2>
        </div>
        
        <div id="info">
            <div>
            <?php 
                $infos = array_slice($infos, 0, (sizeof($infos)-1), true); //Enlève le notation moyenne
                foreach ($infos as $key => $value) {
                    echo '<ul><li>'.$key.'</li>';
                    echo '<li>'.$value.'</li></ul>';
                    if ($key != 'Mot de Passe'){
                        echo '<hr>';
                    }
                }
            ?>
            </div>
            <a class="boutton" href=""><img src="/public/icons/edit.svg" alt="Editer"></a>
        </div>

        <div id="options">
            <a class="boutton" href=""><img src="/public/icons/comment.svg" alt="">Ma messagerie</a>
            <a class="boutton" href=""><img src="/public/icons/type_logement.svg" alt="">
            <?php 
                if ($page_personnelle && $page_proprio){ ?>
                    Mes logements</a> <?php
                } else{ ?>
                    Devenir Propriétaire</a> <?php
                }
            ?>
            <a class="boutton" href="/src/php/connexion/login.php?deconnexion"><img id="img-disconnect" src="/public/icons/forward.svg" alt="">Déconnexion</a>
        </div>

    <!--Cas profil non-personnel -->
    <?php } else{ ?>

        <div class="infos-profil">
            <div class="image">
                <img src="<?php echo $image_user ?>" alt="Image de l'utilisateur">
                <a class="boutton" href="">Contacter</a>
                <a class="boutton" href="">Signaler</a>
            </div>
            <div class="infos">
                <h1><?php echo $infos['Pseudo'] ?></h1>
                <div class="notation">
                    <img src="/public/icons/star_fill.svg" alt="Etoile">
                    <h2><?php echo $infos['Notation Moyenne']; ?></h2>
                </div>
                <h3><?php echo $nbCommentairesEnvoyes ?> commentaire<?php if ($nbCommentairesEnvoyes > 1) { echo 's envoyés'; } else { echo ' envoyé'; } ?></h3>
            </div>
        </div>

        <div id="commentaires">
            <h2>Avis reçu pour <?php echo $infos['Pseudo']; ?></h2>
            <div id="commentaire-container">
                <?php
                    foreach ($commentaires as $commentaire) { ?>
                        <div class="commentaire">

                            <div class="user">
                                <?php 
                                    //Récupère la photo de profil de l'utilisateur
                                    $image_avis = '/public/img/photos_profil/'.$commentaire['id'].'.png';
                                    if (!file_exists($_SERVER['DOCUMENT_ROOT'].$image_avis)){
                                        $image_avis = '/public/img/icons/user.svg';
                                    }
                                ?>
                                <a href="/src/php/profil/profil.php?user=<?php echo $commentaire['id'] ?>"><img src="<?php echo $image_avis ?>" alt="Image de l'utilisateur"></a>
                                <h1><?php echo $commentaire['pseudo']; ?></h1>
                            </div>

                            <div class="notation">
                                <img src="/public/icons/star_fill.svg" alt="Etoile">
                                <h2><?php echo $commentaire['notation']; ?></h2>
                            </div>

                            <p><?php echo $commentaire['avis']; ?></p>

                            <?php if (!((isset($_SESSION['id'])) && ($_SESSION['id'] == $commentaire['id']))){ ?>
                                <a class="boutton" href="">Signaler</a>
                            <?php } ?>
                        </div>
                    <?php }
                ?>
            </div>

            <div class="infos">
                <?php if (sizeof($commentaires) == 0){ ?>
                    <h3 id="no-comment">Aucun commentaire pour le moment</h3>
                <?php } else{ ?>
                    <a class="boutton" href="gestionPages.php?user=<?php echo $user ?>&nbAvis=<?php echo count($commentaires) ?>">Plus de Commentaires</a>
                <?php } ?>
            </div>
        </div>

    <?php }} ?>
    </div>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
</body>
</html>