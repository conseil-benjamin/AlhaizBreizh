<?php
    session_start();
/*****************************************************************************/
/*****************************************************************************/
    //Vérifier si l'utilisateur est donné
    $user_set = true;
    if (!isset($_GET['user'])){
        header('Location: profil.php?user='.$_SESSION['id']);
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
        if ((isset($_SESSION['id'])) && ($user == $_SESSION['id']) && (!isset($_GET['public']))){
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
/*****************************************************************************/
/*****************************************************************************/
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

/*****************************************************************************/
/*****************************************************************************/
            //Récupère les logements de l'utilisateur
            try {
                $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
                $stmt = $pdo->prepare("SELECT numLogement,libelle,nbPersMax,tarifNuitees,LogementEnLigne,ville FROM ldc.Logement WHERE proprio = :user");
                $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        
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

            //Adapter les informations
            $infos['Date de Naissance'] = date('d/m/Y', strtotime($infos['Date de Naissance']));
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
    <link rel="icon" href="/public/logos/logo-black.svg">
    <title>Profil</title>
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); //Utilisation du get contents pour éviter d'avoir les mêmes variables ?> 
    <div class="<?php if ($page_personnelle) { echo 'monprofil'; } else { echo 'profil'; } ?>">

    <!-- Cas profil non trouvé -->
    <?php if ($user_set == false){ ?>

        <style> body{min-height:0;} </style>

        <div class="wrapper">
                    <video autoplay playsinline muted loop preload poster="http://i.imgur.com/xHO6DbC.png">
                        <source src="/public/videos/video-bretagne.mp4" />
                    </video>
                    <div class="container">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 285 80" preserveAspectRatio="xMidYMid slice">
                            <defs>
                                <mask id="mask" x="0" y="0" width="100%" height="100%">
                                    <rect x="0" y="0" width="100%" height="100%" />
                                    <!-- Texte principal -->
                                    <text x="50%" y="50%" text-anchor="middle" alignment-baseline="middle" font-family="NoirPro" font-weight="200" text-transform="uppercase" font-size="20">
                                        ALHaIZ Breizh
                                    </text>
                                </mask>
                            </defs>
                            <!-- Rectangle pour masquer le texte principal -->
                            <rect x="0" y="0" width="100%" height="100%" mask="url(#mask)" />
                        </svg>
                        <!-- Lien vers la page d'accueil avec un message d'erreur -->
                        <a class="lien" href="/">
                            <div>
                                Cette page est inexistante. Cliquez ici pour retourner à l'accueil.
                            </div>
                        </a>
                    </div>
                </div>           

    <?php } else{ ?>

    <!--Cas profil personnel -->
    <?php if (($page_personnelle) && (!isset($_GET['edit'])) && (!isset($_GET['public']))){ ?>  

        <div id="titre">
            <img src="<?php echo $image_user ?>">
            <h2>Bienvenue <?php echo $_SESSION['pseudo'] ?></h2>
        </div>
        
        <div id="info">
            <div>
            <?php 
                $infos = array_slice($infos, 0, (sizeof($infos)-1), true); //Enlève la notation moyenne
                $infos['Mot de Passe'] = preg_replace('/./u', '*', $infos['Mot de Passe']);
                $infos['Téléphone'] = str_split($infos['Téléphone'], 2);
                $infos['Téléphone'] = implode(' ', $infos['Téléphone']);
                
                foreach ($infos as $key => $value) {
                    echo '<ul><li>'.$key.'</li>';
                    echo '<li>'.$value.'</li></ul>';
                    if ($key != 'Mot de Passe'){
                        echo '<hr>';
                    }
                }
            ?>
            </div>
            <a class="boutton" href="profil.php?user=<?php echo $user ?>&edit"><img src="/public/icons/edit.svg" alt="Editer"></a>
        </div>

        <div id="options">
            <a class="boutton" href="profil.php?user=<?php echo $user ?>&public"><img src="/public/icons/user.svg" alt="">Profil public</a>
            <?php 
                if ($page_personnelle && $page_proprio){ ?>
                    <a class="boutton" href="./api"><img src="/public/icons/clef.svg" alt="">Mes clés API</a> <?php
                } ?>
            <a class="boutton" href="./ical"><img src="/public/icons/calendar.svg" alt="Icon de calendrier">Abonnement Ical</a>
            <a class="boutton" href="/src/php/connexion/login.php?deconnexion"><img id="img-disconnect" src="/public/icons/forward.svg" alt="">Déconnexion</a>
        </div>

    <!--Cas profil personnel en édition -->
    <?php } else if (($page_personnelle) && (isset($_GET['edit'])) && (!isset($_GET['public']))){ ?>
        
        <form id="form" action="saveModif.php?user=<?php echo $user ?>" method="post" enctype="multipart/form-data">
            <div id="titre">
                <input type="file" name="file" id="file" style="display: none;" accept=".png, .jpg, .jpeg" maxlength="10">
                <label for="file" class="customFileUpload"><img class="<?php if (isset($_GET['invalidFile'])) echo 'invalid'; ?>" id="photo" src="<?php echo $image_user; ?>"></label>
                <h2>Editer mon profil</h2>
            </div>
            
            <div id="info">
                <div>
                <?php 
                    $infos = array_slice($infos, 0, (sizeof($infos)-1), true); //Enlève la notation moyenne
                    foreach ($infos as $key => $value) {
                        $disabled = '';
                        $id = '';
                        $placeholder = $value;
                        echo '<ul><li>'.$key.'</li>';
                        if ($key == 'Mot de Passe'){
                            $type = 'password';
                            $placeholder = '********';
                            $id = 'password';
                        } else if ($key == 'Date de Naissance'){
                            $value = date('Y-m-d', mktime(0, 0, 0, substr($value, 3, 2), substr($value, 0, 2), substr($value, 6, 4)));
                            $type = 'date';
                            $disabled = 'disabled';
                        } else if ($key == 'Adresse Mail'){
                            $type = 'email';
                        } else if ($key == 'Téléphone'){
                            $type = 'tel';
                        } else{
                            $type = 'text';
                        }
                        echo '<li><input id="'.$id.'" '.$disabled.' required class="textfield" type="'.$type.'" placeholder="'.$placeholder.'" name="'.$key.'" value="'.$value.'"></li></ul>';
                        if ($key != 'Mot de Passe'){ ?>
                            <hr> <?php
                        }
                    }
                ?>
                </div>
                <div class="bouttons">
                    <button id="submitButton" class="boutton" type="submit"><img style="filter: none;" src="/public/icons/check.svg" alt="Valider"></button>
                    <a class="boutton" href="profil.php?user=<?php echo $user ?>"><img style="filter: none;" src="/public/icons/supprimer.svg" alt="Annuler"></a>
                </div>
            </div>  
        </form>   

    <!--Cas profil non-personnel -->
    <?php } else{ ?>

        <div class="infos-profil">
            <div class="image">
                <img src="<?php echo $image_user ?>" alt="Image de l'utilisateur">
                <?php if ($page_personnelle == false){ ?>
                    <a class="boutton" href="">Contacter</a>
                    <a class="boutton" href="">Signaler</a> <?php
                } else{?>
                    <a class="boutton" style="background-color: var(--col6); color: #000;" href="profil.php">Voir profil privé</a> <?php
                } ?>
            </div>
            <div class="infos">
                <h1><?php echo $infos['Pseudo'] ?></h1>
                <div class="notation">
                    <img src="/public/icons/star_fill.svg" alt="Etoile">
                    <h2><?php echo $infos['Notation Moyenne']; ?></h2>
                </div>
                <h3><?php echo $nbCommentairesEnvoyes ?> commentaire<?php if ($nbCommentairesEnvoyes > 1) { echo 's envoyés'; } else { echo ' envoyé'; } ?></h3>
                <div class="nav">
                    <a href="#commentaires" class="boutton">Commentaires</a>
                    <?php if ($page_proprio){ ?>
                        <a href="#logements" class="boutton">Logements</a>
                    <?php } ?>
                </div>
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
                                <a href="/src/php/profil/profil.php?user=<?php echo $commentaire['id'] ?>&public"><img src="<?php echo $image_avis ?>" alt="Image de l'utilisateur"></a>
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

        <!-- Cas profil propriétaire -->
        <?php if ($page_proprio){ ?>
            <div id="logements">
                <h2>Logements de <?php echo $infos['Pseudo']; ?></h2>
                <div id="logement-container">
                <?php
                    /*Créations de carte pour chaque logements*/

                    $nb_logements_inactifs = 0;
                    $logements = array_reverse($logements);
                    foreach ($logements as $logement) {
                        $actif = $logement[4];
                        if ($actif) {
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
                                    <div><img src="/public/icons/nb_personnes.svg"><?php echo $nombre_personnes ?> personnes</div> <!-- Nombre de personnes -->
                                    <div><img src="/public/icons/map.svg"><?php echo $localisation ?></div> <!-- Localisation -->
                                    <div><strong><?php echo $prix ?>€</strong> / nuit</div> <!-- Prix du logement -->
                                </div></a>
                            </div> 
                        <?php
                    } else{
                        $nb_logements_inactifs++;
                    }
                } 
                if ($nb_logements_inactifs == count($logements)){ ?>
                    <h3>Aucun logement pour le moment</h3><?php
                } ?>
                </div>             
            </div>
        <?php } ?>

    <?php } ?>
    </div>
   <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php');
} ?>
    <script src="/src/js/profil/editImage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/src/js/profil/popus.js"></script>
    <script src="/src/js/profil/submitButton.js"></script>
</body>
</html>