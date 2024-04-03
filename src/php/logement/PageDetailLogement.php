<?php
session_start();

// Connexion à la base de données
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

if (isset($_GET['numLogement'])) {
    $numLogement = $_GET['numLogement'];
    $_SESSION['num_logement'] = $numLogement;
    if (isset($pdo)&&!empty($numLogement)) {
        // Vérifier si numLogement existe dans la base de données
        $stmt = $pdo->query("SELECT COUNT(*) FROM ldc.Logement WHERE numLogement = $numLogement");
        $numLogementExists = $stmt ? $stmt->fetchColumn() : null;

        if ($numLogementExists) {
            $etat_logement = "SELECT LogementEnLigne FROM ldc.Logement WHERE numLogement = $numLogement";
            $etat_logement = $pdo->query($etat_logement)->fetchColumn();

                // Récupération des informations du logement
                $stmt = $pdo->prepare("SELECT * FROM ldc.Logement WHERE numLogement = $numLogement");
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {  
                    $surfaceHabitable = isset($row[1]) ? $row[1] : null;
                    $titre_offre = isset($row[2]) ? $row[2] : null;
                    $phrase_accroche = isset($row[3]) ? $row[3] : null;
                    $detail_description = isset($row[4]) ? $row[4] : null;
                    $type_logement = ucfirst($row[5]);
                    $localisation = isset($row[8]) ? $row[8] : null;
                    $localisation_speci = isset($row[6]) ? $row[6] : null;
                    $proprio = isset($row[9]) ? $row[9] : null;
                    $photo_logement = isset($row[10]) ? $row[10] : null;
                    $photoCouverture = isset($row[10]) ? $row[10] : null;
                    $LogementEnLigne = isset($row[11]) ? $row[11] : null;
                    $nb_personnes = isset($row[12]) ? $row[12] : null;
                    $nb_chambres = isset($row[13]) ? $row[13] : null;
                    $nb_sdb = isset($row[14]) ? $row[14] : null;
                    $prix = isset($row[15]) ? $row[15] : null;
                    $note = isset($row[16]) ? $row[16] : null;
                    $note = isset($row[16]) ? $row[16] : null;
                    $_SESSION["nom_bien"] = $titre_offre;
                    $_SESSION["prixNuit"] = $prix;
                    $_SESSION["nbPersonneMax"] = $nb_personnes;
                    $_SESSION["numLogement"] = $numLogement;
                }

                // le nom pour la demande de devis

                // Récupération des chambres
                $numChambre=[];
                $i=0;
    
                $query = "SELECT * FROM ldc.chambre JOIN ldc.logementchambre l on chambre.numchambre = l.numchambre WHERE l.numlogement = $numLogement";
                $stmt = $pdo->query($query);
    
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $numChambre[$i][0] = $row[1]; //Simples
                    $numChambre[$i][1] = $row[2]; //Doubles
                    $i=$i+1;
                }
                if ($i > 0) {
                    $nb_chambres = $i;
                }

                if ($etat_logement || (isset($_SESSION['id']) && $_SESSION['id'] == $proprio)){
                    // Récupération des services
                    $stmt = $pdo->prepare("SELECT nom FROM ldc.Service WHERE numLogement = $numLogement");
                    $stmt->execute();
                    $liste_services = '';
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)){
                        $liste_services .= isset($row[0]) ? $row[0] : '';
                        $liste_services .= ", ";

                    }

                    // Récupération des equipements
                    $stmt = $pdo->prepare("SELECT nom FROM ldc.Equipement WHERE numLogement = $numLogement");
                    $stmt->execute();
                    $liste_equipements = '';
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)){
                        $liste_equipements .= isset($row[0]) ? $row[0] : '';
                        $liste_equipements .= ", ";

                    }

                    // Récupération des installations
                    $stmt = $pdo->prepare("SELECT nom FROM ldc.Installation WHERE numLogement = $numLogement");
                    $stmt->execute();
                    $liste_installation = '';
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)){
                        $liste_installation .= isset($row[0]) ? $row[0] : '';
                        $liste_installation .= ", ";

                    }

                    // Récupérations des informations concernant le propriétaire
                    $stmt = $pdo->prepare("SELECT firstName,lastName,languesParlees
                                            FROM ldc.Proprietaire P
                                            NATURAL JOIN ldc.Client C
                                            WHERE idCompte = $proprio");
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                        $prenom_proprio = isset($row[0]) ? $row[0] : null;
                        $nom_proprio = isset($row[1]) ? $row[1] : null;
                        $liste_langue_parle = isset($row[2]) ? $row[2] : null;
                    }
                    $photo_profil_proprio = '/public/img/photos_profil/'.$proprio.'.png'; 

                    $img = '/public/img/logements/'.$numLogement.'/1.png';
                        //Récupérer les images logement
                        $chemin_photos = $_SERVER['DOCUMENT_ROOT'] . '/public/img/logements/' . $numLogement;
                        if (!is_dir($chemin_photos)) {
                            mkdir($chemin_photos);
                        }
                        $liste_photos = scandir($chemin_photos);
                        $nombre_fichiers = 0;
                        foreach ($liste_photos as $fichier) {
                            $chemin_fichier = $chemin_photos .'/'. $fichier;
                            if (is_file($chemin_fichier)) {
                                $nombre_fichiers++;
                            }
                        }
                        $chemin_photos = '/public/img/logements/'.$numLogement;

                        for ($i = 1; $i <= $nombre_fichiers; $i++) {
                            ${"img".$i}='/public/img/logements/'.$numLogement.'/'.$i.'.png';
                        }
                    

                }elseif (!$etat_logement && isset($_SESSION['id']) && $_SESSION['id'] != $proprio) {
                    $surfaceHabitable = null;
                    $titre_offre = null;
                    $phrase_accroche = null;
                    $detail_description = null;
                    $type_logement = null;
                    $photo_logement = null;
                    $photoCouverture = null;
                    $LogementEnLigne = null;
                    $nb_personnes = null;
                    $nb_chambres = null;
                    $nbLitsSimples = null;
                    $nbLitsDoubles = null;
                    $detailsLitsDispos = null;
                    $nb_sdb = null;
                    $prix =null;
                }

        } else {
            $error_message = "Le numéro de logement spécifié n'existe pas.";
        }
    }
} else {
    $error_message = "Aucun numéro de logement spécifié.";
}

// Gestion de la mise en ligne/hors ligne du logement
if (isset($_GET['action']) && isset($_GET['numLogement'])) {
    $numLogement = $_GET['numLogement'];

    if ($_GET['action'] == 'activer') {
        // Mettre l'annonce en ligne (changer $etat_logement à true)
        $sql = "UPDATE ldc.Logement
                SET LogementEnLigne = true
                WHERE numLogement = :numLogement";

        // Préparez la requête
        $stmt = $pdo->prepare($sql);

        // Exécutez la requête avec les paramètres
        $stmt->execute(array(':numLogement' => $numLogement));

        // Changer $etat_logement
        $etat_logement = true;
    } elseif ($_GET['action'] == 'desactiver') {
        // Mettre l'annonce hors ligne (changer $etat_logement à false)
        $sql = "UPDATE ldc.Logement
                SET LogementEnLigne = false
                WHERE numLogement = :numLogement";

        // Préparez la requête
        $stmt = $pdo->prepare($sql);

        // Exécutez la requête avec les paramètres
        $stmt->execute(array(':numLogement' => $numLogement));

        // Changer $etat_logement
        $etat_logement = false;
    }
    // Rediriger vers la page de détails du logement
    header("Location: /src/php/logement/PageDetailLogement.php?numLogement=$numLogement");
    exit; // Assurez-vous de terminer le script après la redirection
}

// Gestion de la suppresion du logement
$nb_resa = "select count(*) from ldc.Reservation where numLogement=$numLogement";
$nb_resa = $pdo->query($nb_resa)->fetchColumn();

$resa_en_cours = false;


$date = new DateTime();
$dateDuJour = $date->format('Y-m-d');


$stmt = $pdo->prepare("select dateFin from ldc.Reservation where numLogement=$numLogement");
$stmt->execute();
$liste_dateFin_resa = '';
while ($row = $stmt->fetch(PDO::FETCH_NUM)){
    $liste_dateFin_resa .= isset($row[0]) ? $row[0] : '';
    $liste_dateFin_resa .= ", ";
}

if ($nb_resa !== 0) {

    // Divise la chaîne en un tableau en utilisant la virgule comme séparateur
    $array_dateFin_resa = explode(', ', $liste_dateFin_resa);
    array_pop($array_dateFin_resa);

    foreach ($array_dateFin_resa as $dateFin) {
        if ($dateFin > $dateDuJour) {
            $resa_en_cours = true;
        }
    }
}

// Définir les valeurs par défaut si elles ne sont pas définies
if (!isset($type_logement)) {
    $type_logement = 'Type de logement';
}
if (!isset($nb_personnes)) {
    $nb_personnes = 1;
}
if (!isset($nb_chambres)) {
    $nb_chambres = 1;
}
if (!isset($nb_sdb)) {
    $nb_sdb = 1;
}
if (!isset($titre_offre)) {
    $titre_offre = 'Titre de l\'offre';
}
if (!isset($phrase_accroche)) {
    $phrase_accroche = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eu turpis molestie, dictum est a, mattis tellus. Sed dignissim, metus nec fringilla accumsan';
}
if (!isset($detail_description)) {
    $detail_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eu turpis molestie, dictum est a, mattis tellus. Sed dignissim, metus nec fringilla accumsan, risus sem sollicitudin lacus, ut interdum tellus elit sed risus. Maecenas eget condimentum velit, sit amet feugiat lectus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent auctor purus luctus enim egestas, ac scelerisque ante pulvinar. Donec ut rhoncus ex. Suspendisse ac rhoncus nisl, eu tempor urna. Curabitur vel bibendum lorem. Morbi convallis convallis diam sit amet lacinia. Aliquam in elementum tellus.
    Curabitur tempor quis eros tempus lacinia. Nam bibendum pellentesque quam a convallis. Sed ut vulputate nisi. Integer in felis sed leo vestibulum venenatis. Suspendisse quis arcu sem. Aenean feugiat ex eu vestibulum vestibulum. Morbi a eleifend magna. Nam metus lacus, porttitor eu mauris a, blandit ultrices nibh. Mauris sit amet magna non ligula vestibulum eleifend. Nulla varius volutpat turpis sed lacinia. Nam eget mi in purus lobortis eleifend. Sed nec ante dictum sem condimentum ullamcorper quis venenatis nisi. Proin vitae facilisis nisi, ac posuere leo.';
}
if (!isset($prix)) {
    $prix = 0;
}
if (!isset($photo_profil_proprio)){
    $photo_profil_proprio = '/public/icons/user.svg';
}
if (!isset($prenom_proprio)) {
    $prenom_proprio = 'Prénom';
}
if (!isset($nom_proprio)) {
    $nom_proprio = 'Nom';
}
if (!isset($liste_langue_parle)) {
    $liste_langue_parle = 'Non renseigné';
}
if (!isset($note)){
    $note="Note";
}

?>
<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/stylePageDetailLogement.css">
        <title>ALHaiz Breizh</title>
        <link rel="icon" href="/public/logos/logo-black.svg">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <link rel="stylesheet" href="//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" type="text/css">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script src="//unpkg.com/leaflet-gesture-handling"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
        <main>
            <?php
            if (($numLogementExists && $etat_logement) || ($numLogementExists && isset($_SESSION['id']) && $_SESSION['id'] == $proprio && !$etat_logement) || ($etat_logement && isset($_SESSION['id']) && $_SESSION['id'] != $proprio)) {
                if (isset($_SESSION['id']) && $numLogementExists && $_SESSION['id'] == $proprio) { ?>
                    <section class="tete_offre_proprio"> <?php
                }else { ?>
                    <section class="tete_offre">
                <?php }
                ?> <div id="options-images"> <?php
                    if ((isset($_SESSION['id'])) && ($_SESSION['id'] == $proprio)) {
                        // L'utilisateur est connecté et est le propriétaire du logement
                        ?>
                        <div class="proprio">
                            <p> 
                                <?php if($etat_logement){ ?>
                                    <div id="icone_en_ligne"> <img id="icone" src="/public/icons/rond_vert.svg"></div>
                                    Le logement est en ligne
                                <?php } else { ?>
                                    <div id="icone_en_ligne"><img id="icone" src="/public/icons/rond_rouge.svg"></div> 
                                    Le logement est hors ligne 
                                <?php } ?>
                            </p>
                            <div>
                                <?php
                                if (!$etat_logement){ ?>
                                    <a href="?action=activer&numLogement=<?php echo $numLogement ?>" class="boutton">Mettre en ligne</a>
                                <?php } else { ?>
                                    <a href="?action=desactiver&numLogement=<?php echo $numLogement ?>" class="boutton">Mettre hors ligne</a>
                                <?php } 
                                $_SESSION['numLogement']=$numLogement?>
                                <a href="/src/php/logement/modificationLogement.php?numLogement=<?php echo $numLogement ?>" class="boutton">Modifier</a>
                                <a href="#" class="boutton" onclick="supprimerAnnonce()">Supprimer</a>
                            </div>
                        </div><?php 
                    }
                    if ($nombre_fichiers <= 0) {?>
                        <h2>Aucune photo n'a été trouvée pour ce logement<h2><?php
                    } else {?>
                        <div class="carousel">
                            <?php if ($nombre_fichiers > 1): ?>
                            <button id="prevButton"><img src="/public/icons/arrow.svg" alt="Précédent"></button>
                            <button id="nextButton"><img src="/public/icons/arrow.svg" alt="Suivant"></button>
                            <?php endif; ?>
                            <div class="carousel-dots">
                                <?php for ($i = 1; $i <= $nombre_fichiers; $i++ ): ?>
                                    <span class="carousel-dot" data-index="<?php echo ($i - 1) ?>"></span>
                                <?php endfor; ?>
                            </div>
                            <div class="conteneur-images">
                                <?php for ($i = 1; $i <= $nombre_fichiers; $i++ ): ?>
                                    <img src="<?php echo ($chemin_photos.'/'.$i.'.png') ?>" alt="Image<?php echo $i ?>">
                                <?php endfor; ?>
                            </div>
                        </div><?php
                    } ?>
                </div>
                <div>
                    <ul class="infos_loge">
                        <li><h2 id="localisation_haut_page">
                            <?php
                            if (!isset($localisation)) {
                                echo "$etat_logement Localisation <br>";          
                            } else {
                                echo "$localisation <br>";
                            }
                            # gestion localisation specifique
                            if (!isset($localisation_speci)) {
                                echo "Localisation specifique";          
                            } else {
                                if (isset($_SESSION['id']) && isset($_GET['numLogement'])) {
                                    // L'utilisateur est connecté
                                    if ($_SESSION['id'] == $proprio) {
                                        // L'utilisateur est connecté et est le propriétaire du logement
                                        echo $localisation_speci;
                                    }
                                }
                            } ?>
                        </h2></li>
                        <li>
                        <div>
                            <a href="#comment" class="logo">
                                <img src="/public/icons/star_fill.svg" id="icone" alt="icone etoile"> 
                                <?php echo $note === null ? "Aucun avis" : $note ?>
                            </a>
                        </div>
                    </li>                         
                    <li>
                            <div>
                                <img src="/public/icons/type_logement.svg" id="icone" alt="icone maison"> 
                                <?php echo $type_logement; ?>
                            </div>
                        </li>
                        <li><div><img src="/public/icons/nb_personnes.svg" id="icone" alt="icone personnes"><?php echo $nb_personnes ?> Personne<?php echo ($nb_personnes > 1) ? 's' : '' ?></div></li>
                        <li><div><a href="#infos_chambres"><img src="/public/icons/double-bed.svg" id="icone" alt="icone lit"><?php echo $nb_chambres?> Chambre<?php echo ($nb_chambres > 1) ? 's' : '' ?></a></div></li>
                        <li><div><img src="/public/icons/salle_de_bains.svg" id="icone" alt="icone salle de bain"><?php echo $nb_sdb?> Salle<?php echo ($nb_sdb > 1) ? 's' : '' ?> de bains</div></li>
                    </ul>
                </div>
            </section>
            <div class="corps">
                <div class="div_corps">
                    <section class="corps_offre">
                        <h1><?php echo $titre_offre; ?></h1>
                        <p><?php echo $phrase_accroche; ?></p>
                        <br>
                        <h2>Détails de l'offre</h2>
                        <p>
                            <?php echo $detail_description; ?>
                        </p>
                        <ul class="infos_chambres"></ul>
                        <h2 id="infos_chambres">
                        Chambre<?php echo ($nb_chambres > 1) ? 's' : '' ?> :
                        </h2>
                        <?php
                            if (empty($numChambre)|| $numChambre == null) {
                                    echo "<p> Cette section est vide.</p>";
                                } else {
                                    echo "<div class=\"chambres\">";
                                    foreach($numChambre as $key => $chambre){
                                        if ($key==3){
                                            echo "<span id='plus'>";
                                        }
                                        echo "<div class='etiquette_chambre'>";
                                        echo "<img src='/public/icons/single-bed.svg' id='icone' alt='icone'>";
                                        echo "X ".$chambre[0];
                                        echo "<div>";
                                        echo "</div>";
                                        echo "<img src='/public/icons/double-bed.svg' id='icone' alt='icone'>";
                                        echo "X ".$chambre[1];
                                        echo "<div>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "<br>";
                                        if (($key>=3)&&($key+1==sizeof($numChambre))){
                                            echo "</span>";
                                            echo "<div id='frd'>";
                                            echo '<button onclick="AfficherPlus()" id="myBtn">Afficher plus</button>';
                                            echo "</div>";
                                        }
                                    }
                                    echo "</div>";
                                }
                        ?>
                        <br>
                        <h2>Installations disponibles :</h2>
                            <?php
                            if (empty($liste_installation) || $liste_installation == null) {
                                echo "<p class='section_vide'>Cette section est vide.</p>";
                            } else {
                                // Divise la chaîne en un tableau en utilisant la virgule comme séparateur
                                $installation_array = explode(', ', $liste_installation);
                                array_pop($installation_array);
                            
                                echo "<ul class='liste_corps'>";
                                foreach ($installation_array as $installation) {
                                    $installation = ucfirst($installation); 
                                    echo "<li>$installation</li>";
                                }
                                echo "</ul>";
                            }
                            ?>
                        <h2>
                            Equipements disponibles :
                        </h2>
                        <?php
                            if ( empty($liste_equipements) || $liste_equipements == null) {
                                echo "<p class='section_vide'>Cette section est vide.</p>";
                            } else {
                                $equipements_array = explode(', ', $liste_equipements);
                                array_pop($equipements_array);

                            
                                echo "<ul class='liste_corps'>";
                                foreach ($equipements_array as $equipement) {
                                    $equipement = ucfirst($equipement); 
                                    echo "<li>$equipement</li>";
                                }
                                echo "</ul>";
                            }
                            ?>
                        <h2>Services :</h2>
                        <?php
                            if (empty($liste_services) || $liste_services == null) {
                                echo "<p class='section_vide'>Cette section est vide.</p>";
                            } else {
                                $services_array = explode(', ', $liste_services);
                                array_pop($services_array);

                            
                                echo "<ul class='liste_corps'>";
                                foreach ($services_array as $service) {
                                    $service = ucfirst($service); 
                                    echo "<li>$service</li>";
                                }
                                echo "</ul>";
                            }
                            ?>
                        </ul>
                        <br>
                        <?php $_SESSION['numLogement']=$numLogement; ?>
                        <a href="/src/php/profil/profil.php?user=<?php echo $proprio ?>">

                        <a href="/src/php/calendrier/afficherPlageDispo.php?numLogement=<?php echo $numLogement ?>" class="boutton">Voir les disponibilitées du logement</a>
                    </section>
                    <?php if ((isset($_SESSION['id']) == false) || ($_SESSION['id'] != $proprio)): ?>
                    <section class="reserve_contact">
                        <div class="resa_colle">
                            <article class="reserve">
                                <div>
                                    <p><strong><?php echo $prix; ?>€</strong> / nuit</p>
                                </div>
                                <form class="date_resa" id="date_arri" action="/src/php/devis/demande_devis.php" method="post">
                                    <div>
                                        <div>
                                            <input class="textfield" min="0" max="999" type="number" id="date_arrivee" name="date_arrivee" content required>
                                            <label for="date_arrivee">/ nuit(s)</label>
                                        </div>
                                        <div>
                                            <input class="boutton" type="submit"></input>
                                        </div>
                                    </div>
                                </form>
                            </article>

                            <article class="contact">
                                <div class="profil">
                                    <a href="/src/php/profil/profil.php?user=<?php echo $proprio ?>">
                                        <?php
                                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $photo_profil_proprio)==false) { ?>
                                            <img src="/public/icons/user.svg" id="photo_profil" alt="photo de profil du propriétaire"> <?php          
                                        } else { ?>
                                            <img src="<?php echo $photo_profil_proprio ?>" id="photo_profil" alt="photo de profil du propriétaire"> <?php
                                        } ?>    
                                        <p><?php echo $prenom_proprio .' '. $nom_proprio; ?></p>
                                    </a>                                    
                                </div>
                                <div class="contact_nom_bouton">
                                    <p><img src="/public/icons/star_fill.svg" id="icone" alt="icone etoile"> Note</p>
                                    <a class="boutton" href="#">Contacter</a>
                                </div>
                                <p>
                                    Langues parlées :
                                    <?php //$liste_langue_parle = array("Français", "Anglais", "Espagnol"); 
                                    echo $liste_langue_parle; ?>
                                </p>
                            </article>
                        </div>
                    </section>
                    <?php endif; ?>
                </div>
            </div>

            <section class="map">
                <div id="map"></div>
            </section>

           <section class="commentaires">
                <h2>Avis sur le logement</h2>
                <div>
                <?php 
                    $getAvis = $pdo->prepare("SELECT * FROM ldc.AvisLogement WHERE idLogement = $numLogement");
                    $getAvis->execute();
                    while ($row = $getAvis->fetch(PDO::FETCH_NUM)) {
                    $contenusAvis = isset($row[1]) ? $row[1] : null;
                    $nbEtoiles = isset($row[2]) ? $row[2] : null;
                    $idClient = isset($row[3]) ? $row[3] : null;
                    $getNameClient = $pdo->prepare("SELECT pseudocompte FROM ldc.Client WHERE idcompte = :idClient");
                    $getNameClient->bindParam(':idClient', $idClient);
                    $getNameClient->execute();
                    while ($inner_row = $getNameClient->fetch(PDO::FETCH_ASSOC)) {
                        $pseudoClient = isset($inner_row['pseudocompte']) ? $inner_row['pseudocompte'] : null;
                    }
                    ?>
                    <div class="div-avis">
                        <div class="header-avis-logement-main">
                        <div class="header-avis-logement-left">
                            <?php
                                $imagePath = "/public/img/photos_profil/" . $idClient . ".png";
                            ?>
                            <a>
                                <a href="/src/php/profil/profil.php?user=<?php echo $idClient ?>">
                            <img src="<?php echo $imagePath; ?>" alt="Photo profil utilisateur" width="50" height="50">
                            </a>
                            <p><?php echo $pseudoClient?></p>
                        </div>
                        <div class="notation-logement-commentaire">
                            <p><img src="/public/icons/star_fill.svg" class="icone" alt="icone etoile"><?php echo $nbEtoiles?></p>
                        </div>
                        </div>
                        <p><?php echo $contenusAvis?></p>
                        <?php 
                            if(strlen($contenusAvis) == 0){
                                ?>
                                <p>Aucun commentaire n'a été laissé par cet utilisateur.</p>
                                <?php
                            }
                        ?>
                    </div>
                    <?php
                }                    
                ?>
                </div>
            </section>
            </main>
            <?php
            include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>

            <?php }else {?>
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
            </main>
        <?php
        }; ?>  
        <script src="/src/js/carrousel.js"></script>      
        <script>
            var ville = <?= json_encode($localisation); ?>;
            var rue = <?= json_encode($localisation_speci); ?>;
            var estProprio = <?= json_encode(isset($_SESSION['id']) && $_SESSION['id'] == $proprio); ?>;
        </script>
        <script type="module" src="/src/js/logement/map.js"></script>
        <script>
        function supprimerAnnonce() {
            <?php
                // Utilisation des valeurs PHP dans le script JavaScript
                echo "var resaEnCours = " . json_encode($resa_en_cours) . ";\n";
                echo "var numLogement = " . json_encode($numLogement) . ";\n";
            ?>

    if (!resaEnCours) {
        // Affiche une boîte de dialogue d'avertissement
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce logement ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, je confirme",
            cancelButtonText: "Annuler",
        })
        .then((result) => {
            if (result.value) {
                // Lance la suppression de l'annonce
                fetch('suppressionLogement.php?numLogement=' + numLogement, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: "success",
                        title: `${data.message}`,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(() => {
                        window.location.href = "/src/php/logement/mesLogements.php";
                    }, 2000);
                })
                .catch(error => {
                    Swal.fire({
                        title: "Erreur : Le logement n'a pas pu être supprimé",
                        icon: "error",
                    });
                });
            }
        });
    } else {
        // Affichez un message à l'utilisateur si des réservations sont en cours
        Swal.fire({
            title: "Vous ne pouvez pas supprimer votre logement",
            text: "Des réservations sont en cours pour ce logement.",
            icon: "warning",
        });        }
}

</script>
    </body>
</html>
