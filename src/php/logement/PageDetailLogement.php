<?php
session_start();

// Connexion à la base de données
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

if (isset($_GET['numLogement'])) {
    $numLogement = $_GET['numLogement'];
    //Récupérer l'image de couverture du logement
    $img = '/public/img/logements/'.$numLogement.'/1.png';

    if (isset($pdo)&&!empty($numLogement)) {
        // Vérifier si numLogement existe dans la base de données
        $stmt = $pdo->query("SELECT COUNT(*) FROM ldc.Logement WHERE numLogement = $numLogement");
        $numLogementExists = $stmt ? $stmt->fetchColumn() : null;

        if ($numLogementExists) {

            $etat_logement = "SELECT LogementEnLigne FROM ldc.Logement WHERE numLogement = $numLogement";
            $etat_logement = $pdo->query($etat_logement)->fetchColumn();

            // Récupération des informations de la chambre
            $stmt = $pdo->prepare("SELECT * FROM ldc.Logement WHERE numLogement = $numLogement");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {  
                $surfaceHabitable = isset($row[1]) ? $row[1] : null;
                $titre_offre = isset($row[2]) ? $row[2] : null;
                $phrase_accroche = isset($row[3]) ? $row[3] : null;
                $detail_description = isset($row[4]) ? $row[4] : null;
                $type_logement = ucfirst($row[5]);
                $proprio = isset($row[6]) ? $row[6] : null;
                $photo_logement = isset($row[7]) ? $row[7] : null;
                $photoCouverture = isset($row[7]) ? $row[7] : null;
                $LogementEnLigne = isset($row[8]) ? $row[8] : null;
                $nb_personnes = isset($row[9]) ? $row[9] : null;
                $nb_chambres = isset($row[10]) ? $row[10] : null;
                $nbLitsSimples = isset($row[11]) ? $row[11] : null;
                $nbLitsDoubles = isset($row[12]) ? $row[12] : null;
                $detailsLitsDispos = isset($row[13]) ? $row[13] : null;
                $nb_sdb = isset($row[14]) ? $row[14] : null;
                $prix = isset($row[15]) ? $row[15] : null;
            }

            // Récupération des listes: installations, équipements, services
            $stmt = $pdo->prepare("SELECT installationsOffertes, equipementsProposes, servicesComplementaires FROM ldc.Services WHERE numLogement = $numLogement");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)){
                $liste_installation = isset($row[0]) ? $row[0] : null;
                $liste_equipements = isset($row[1]) ? $row[1] : null;
                $liste_services = isset($row[2]) ? $row[2] : null;
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
            
            // Récupération de la localisation
            $stmt = $pdo->prepare("SELECT ville,rue FROM ldc.localisation WHERE numLogement = $numLogement");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $localisation = isset($row[0]) ? $row[0] : null;
                $localisation_speci = isset($row[1]) ? $row[1] : null;
            }
        } else {
            $error_message = "Le numéro de logement spécifié n'existe pas.";
        }
    } else {
        $numLogementExists = false;
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

/* Ce qui manque a afficher

    $liste_chambres, probleme base de donnees, il faut une liste de chambres avec un l'intérieur une liste pour chaque chambre pour specifier le nombre de lit simple et lit double/ par chambre
    $liste_installation,
    $liste_equipements
    $liste_services
    $liste_langue_parle,
    les images
    la localisation specifique (l'adresse) pour les clients connectés qui ont réservé ce logement
*/

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
    </head>
    <body>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
        <main>
            <?php
                if (isset($_SESSION['id']) && $numLogementExists) {
                    if ($_SESSION['id'] == $proprio) {
                        // L'utilisateur est connecté et est le propriétaire du logement
                        ?>
                        <p class="proprio">
                            <hs>Le logement est 
                                <?php if($etat_logement){ ?>en ligne
                                <?php } else { ?>hors ligne <?php } ?>
                            </hs>
                            <?php
                            if (!$etat_logement){ ?>
                                <a href="?action=activer&numLogement=<?php echo $numLogement ?>" class="bouton_modification">Mettre l'annonce en ligne</a>
                            <?php } else { ?>
                                <a href="?action=desactiver&numLogement=<?php echo $numLogement ?>" class="bouton_modification">Mettre l'annonce hors ligne</a>
                            <?php } ?>
                            <a href="#" class="bouton_modification">Modifier l'annonce</a>
                            <a href="#" class="bouton_modification">Supprimer l'annonce</a>
                        </p><?php 
                    }
                }
            ?>
        
            <section class="tete_offre">
                <?php
                if (!isset($photo_logement)) { ?>
                    <img src="/public/img/maison.png" alt="image maison"> <?php
                } else {?>
                    <img src="<?php echo $img ?>" alt="image maison"> <?php
                } ?>
                <div>
                    <ul class="infos_loge">
                        <li><h2 id="localisation_haut_page">
                            <?php
                            if (!isset($localisation)) {
                                echo "Localisation <br>";          
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
                        <li><div><a href="#comment" class="logo"><img src="/public/icons/star_fill.svg" id="icone" alt="icone etoile"> Note</a></div></li>
                        <li>
                            <div>
                                <img src="/public/icons/type_logement.svg" id="icone" alt="icone maison"> 
                                <?php echo $type_logement; ?>
                            </div>
                        </li>
                        <li><div><img src="/public/icons/nb_personnes.svg" id="icone" alt="icone personnes"><?php echo $nb_personnes?> Personne(s)</div></li>                
                        <li><div><a href="#infos_chambres"><img src="/public/icons/double-bed.svg" id="icone" alt="icone lit"><?php echo $nb_chambres?> Chambre(s)</a></div></li>
                        <li><div><img src="/public/icons/salle_de_bains.svg" id="icone" alt="icone salle de bain"><?php echo $nb_sdb?> Salle(s) de bains</div></li>
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
                        Chambres :
                        </h2>
                        <?php
                            /*
                            $liste_chambres = array(
                                array(
                                    'nom' => 'Chambre 1',
                                    'lits' => array(
                                        array('type' => 'simple', 'quantite' => 5),
                                        array('type' => 'double', 'quantite' => 2)
                                    )
                                ),
                                array(
                                    'nom' => 'Chambre 2',
                                    'lits' => array(
                                        array('type' => 'double', 'quantite' => 1)
                                    )
                                )
                            ); 
                            */
                            if (empty($liste_chambres)|| $liste_chambres == null) {
                                echo "<p> Cette section est vide.</p>";
                            } else {
                                echo "<ul class='chambres'>";
                                foreach ($liste_chambres as $chambre) {
                                    echo "<li>";
                                    echo "<ul class='chambre'>";
                                    echo "<p>" . $chambre['nom'] . " : </p>";
                                    foreach ($chambre['lits'] as $lit) {
                                        echo "<li>";
                                        if ($lit['type'] == 'simple') {
                                            echo "<img src='/public/icons/single-bed.svg' id='icone' alt='icone'>";
                                        } elseif ($lit['type'] == 'double') {
                                            echo "<img src='/public/icons/double-bed.svg' id='icone' alt='icone'>";
                                        }
                                        echo "</li>";
                                        echo "<li id='nb_lit'>";
                                        echo "<p>" . ($lit['type'] == 'simple' ? 'Lit simple' : 'Lit double') . "</p>";
                                        echo "<p>" . $lit['quantite'] . "</p>";
                                        echo "</li>";
                                    }
                                    echo "</ul>";
                                    echo "</li>";
                                }
                                echo "</ul>";
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
                    </section>
                    <section class="reserve_contact">
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
                            <div>
                                <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . $photo_profil_proprio)==false) { ?>
                                    <img src="/public/icons/user.svg" id="photo_profil" alt="photo de profil du propriétaire"> <?php          
                                } else { ?>
                                    <img src="<?php echo $photo_profil_proprio ?>" id="photo_profil" alt="photo de profil du propriétaire"> <?php
                                } ?>  
                                <p><?php echo $prenom_proprio .' '. $nom_proprio; ?></p>
                            </div>
                            <p><?php echo $liste_langue_parle; ?></p>
                            <p><img src="/public/icons/star_fill.svg" id="icone" alt="icone etoile"> Note</p>
                            <a class="boutton" href="#">Contacter</a>
                        </article>
                    </section>
                </div>
            </div>

            <section class="commentaires">
                <br>
                <div id="comment">
                    <h2>
                        Commentaires
                    </h2>
                    <p>Il n'y a pas encore d'avis pour cette annonce.</p>
                </div>
            </section>
        </main>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
    </body>
</html>