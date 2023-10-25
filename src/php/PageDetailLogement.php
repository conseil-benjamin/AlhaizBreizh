
<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO("pgsql:host=servbdd;port=5432;dbname=pg_fnormand;user=fnormand;password=#");
    //$pdo = new PDO("pgsql:host=postgresdb;port=5432;dbname=sae;user=sae;password=Phiegoosequ9en9o");






} catch (PDOException $e) {
    $error_message = "Erreur de connexion à la base de données : " . $e->getMessage();
}

if (isset($_GET['numLogement'])) {
    $numLogement = $_GET['numLogement'];

    if (isset($pdo)) {
        // Vérifier si numLogement existe dans la base de données
        $sql = "SELECT COUNT(*) FROM ldc.Logement WHERE numLogement = $numLogement";
        $numLogementExists = getSingleValue($pdo, $sql);

        if ($numLogementExists) {
            // Fonction pour récupérer une valeur unique d'une colonne
        function getSingleValue($pdo, $sql) {
        $stmt = $pdo->query($sql);
        return $stmt ? $stmt->fetchColumn() : null;
        }

        $etat_logement = "SELECT LogementEnLigne FROM ldc.Logement WHERE numLogement = $numLogement";
        $etat_logement = $pdo->query($etat_logement)->fetchColumn();

        $proprio = getSingleValue($pdo, "SELECT proprio FROM ldc.Logement WHERE numLogement = $numLogement");

        if ($etat_logement || $_SESSION['id'] == $proprio) {
        
        // Récupération des données
        $localisation = getSingleValue($pdo, "SELECT ville FROM ldc.localisation WHERE numLogement = $numLogement");
        $localisation_speci = getSingleValue($pdo, "SELECT rue FROM ldc.localisation WHERE numLogement = $numLogement");

        $type_logement = getSingleValue($pdo, "SELECT natureLogement FROM ldc.Logement WHERE numLogement = $numLogement");
        $type_logement = ucfirst($type_logement);

        $nb_personnes = getSingleValue($pdo, "SELECT nbPersMax FROM ldc.Logement WHERE numLogement = $numLogement");
        $nb_chambres = getSingleValue($pdo, "SELECT nbChambres FROM ldc.Logement WHERE numLogement = $numLogement");
        $nb_sdb = getSingleValue($pdo, "SELECT nbSalleDeBain FROM ldc.Logement WHERE numLogement = $numLogement");


        $sql = "SELECT 
                    C.firstName AS prenom,
                    C.lastName AS nom,
                    P.languesParlees,
                    C.photoProfil AS photo_profil
                FROM ldc.LogementProprio LP
                JOIN ldc.Proprietaire P ON LP.idCompte = P.idCompte
                JOIN ldc.Client C ON LP.idCompte = C.idCompte
                WHERE LP.numLogement = $numLogement";

        $stmt = $pdo->query($sql);
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;

        $prenom_proprio = $row ? $row['prenom'] : null;
        $nom_proprio = $row ? $row['nom'] : null;
        $liste_langue_parle = $row ? $row['languesParlees'] : null;
        $photo_profil_proprio = $row ? $row['photo_profil'] : null;

        $sql = "SELECT installationsOffertes, equipementsProposes, servicesComplementaires
                FROM ldc.Services
                WHERE numLogement = $numLogement";

        $stmt = $pdo->query($sql);
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;

        $liste_installation = $row ? $row['installationsOffertes'] : null;
        $liste_equipements = $row ? $row['equipementsProposes'] : null;
        $liste_services = $row ? $row['servicesComplementaires'] : null;

        $prix = getSingleValue($pdo, "SELECT tarifNuitees FROM ldc.Tarification WHERE numDevis = (SELECT numDevis FROM ldc.Devis WHERE numLogement = $numLogement)");

        $titre_offre = getSingleValue($pdo, "SELECT libelle FROM ldc.Logement WHERE numLogement = $numLogement");

        $sql = "SELECT * FROM ldc.Logement WHERE numLogement = $numLogement";
        $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        $photo_logement = $row ? $row['photoCouverture'] : null;
        $surfaceHabitable = $row ? $row['surfaceHabitable'] : null;
        $detail_description = $row ? $row['description'] : null;
        $phrase_accroche = $row ? $row['accroche'] : null;
        $proprio = $row ? $row['proprio'] : null;
        $photoCouverture = $row ? $row['photoCouverture'] : null;
        $LogementEnLigne = $row ? $row['LogementEnLigne'] : null;
        $nbLitsSimples = $row ? $row['nbLitsSimples'] : null;
        $nbLitsDoubles = $row ? $row['nbLitsDoubles'] : null;
        $detailsLitsDispos = $row ? $row['detailsLitsDispos'] : null;
        }
        } else {
            $error_message = "Le numéro de logement spécifié n'existe pas.";
        }
    }
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
        <link rel="stylesheet" type="text/css" href="../styles/styles.css">
        <link rel="stylesheet" type="text/css" href="../styles/stylePageDetailLogement.css">
        <title>ALHaiz Breizh</title>
    </head>
    <body>
        <header id="header">
            <a href="../../index.php" class="logo"><img src="../../public/logos/logo-grey.svg"></a>
            <nav>
                <ul>
                    <li><a href="">Logements</a></li>
                    <li><a href="">Réservations</a></li>
                    <li><a href="">Qui sommes-nous</a></li>
                </ul>
            </nav>
            <a href="" class="compte">
                <img src="../../public/icons/arrow-blue.svg">
                <p>Se connecter</p>
                <?php 
                if ($_SESSION==null) {
                    echo " <img src=\"../../public/icons/user-blue.svg\">";
                }
                else{
                    echo $photo_profil;
                }
                
                ?>
            </a>
        </header>

        <main>

                <?php
//TODO gérer la mise en ligne/hors ligne de la page
                if (isset($_SESSION['id']) && isset($_GET['numLogement'])) {
                    if ($_SESSION['id'] == $proprio) {
                        // L'utilisateur est connecté et est le propriétaire du logement
                        if ($etat_logement) { 
                            echo "<p class=\"proprio\">
                            <hs>Le logement est en ligne</hs>
                            <a href=\"#\" class=\"bouton_modification\">Mettre l'annonce hors ligne</a>
                            <a href=\"#\" class=\"bouton_modification\">Modifier l'annonce</a>
                            <a href=\"#\" class=\"bouton_modification\">Supprimer l'annonce</a>
                            </p>";
                        }else {
                        
                        echo "<p class=\"proprio\">
                        <hs>Le logement est hors ligne</hs>
                        <a href=\"#\" class=\"bouton_modification\">Mettre l'annonce en ligne</a>
                        <a href=\"#\" class=\"bouton_modification\">Modifier l'annonce</a>
                        <a href=\"#\" class=\"bouton_modification\">Supprimer l'annonce</a>
                        </p>";
                        }
                    }
                }
                ?>
            <section class="tete_offre">
                <?php
                if ($photo_logement==null) {
                echo "<img src=\"../../public/img/maison.png\" alt=\"image maison\">";
                }
                else {
                    echo $photo_logement;
                }
                ?>
                <div>
                <p id="localisation_haut_page">
                <?php
                if ($localisation==null) {
                    echo "Localisation <br>";          
                }
                else {
                    echo "$localisation <br>";
                }



                # gestion localisation specifique
                if ($localisation_speci==null) {
                    echo "Localisation specifique";          
                }
                else {
                    if (isset($_SESSION['id']) && isset($_GET['numLogement'])) {
                        // L'utilisateur est connecté
                        if ($_SESSION['id'] == $proprio) {
                            // L'utilisateur est connecté et est le propriétaire du logement
                            echo $localisation_speci;
                        }
                    }
                }

                ?>
                    </p>
                <ul class="infos_loge">
                    <p>
                        <a href="#comment" class="logo"><img src="../../public/icons/star_fill.svg" id="icone" alt="icone etoile"> Note</p></a>


                    <li><div><img src="../../public/icons/type_logement.svg" id="icone" alt="icone maison"> <?php if ($type_logement==null) {
                                    echo "Type de logement";          
                                }
                                else {
                                    echo $type_logement;
                                }?></div></li>
                    <li><div><img src="../../public/icons/nb_personnes.svg" id="icone" alt="icone personnes">  <?php echo $nb_personnes?> Personnes</div></li>
                    
                    <li><div><a href="#infos_chambres"><img src="../../public/icons/double-bed.svg" id="icone" alt="icone lit"> <?php echo $nb_chambres?> Chambre(s)</a></div></li>
                    <li><div><img src="../../public/icons/salle_de_bains.svg" id="icone" alt="icone salle de bain"> <?php echo $nb_sdb?> Salles de bains</div></li>
                </ul>
                </div>
            </section>
            <div class="degrade"><br></div>
            <div class="corps">
                <div class="div_corps">
                    <section class="corps_offre">
                            <h1>
                                <?php
                                if ($titre_offre==null) {
                                    echo "Titre de l'offre";          
                                }
                                else {
                                    echo $titre_offre;
                                }
                                ?>
                            </h1>
                            <p>
                            <?php
                                if ($phrase_accroche==null) {
                                    echo "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eu turpis molestie, dictum est a, mattis tellus. Sed dignissim, metus nec fringilla accumsan";          
                                }
                                else {
                                    echo $phrase_accroche;
                                }
                                ?>
                            </p>
                            <br>
                            <h2>
                                Hôte
                            </h2>
                            <div id="hote">
                                <?php
                                if ($photo_profil_proprio==null) {
                                    echo "<img src=\"../../public/icons/user.svg\" id=\"photo_profil\" alt=\"photo de profil du propriétaire\">";          
                                }
                                else {
                                    echo $photo_profil_proprio;
                                }
                                ?>
                                <p><?php
                                if ($prenom_proprio==null) {
                                    echo "Prénom ";          
                                }
                                else {
                                    echo "$prenom_proprio ";
                                }
                                if ($nom_proprio==null) {
                                    echo "Nom";          
                                }
                                else {
                                    echo $nom_proprio;
                                }
                                ?>
                                </p>
                            </div>
                            <br>
                            <h2>
                                Détails de l'offre
                            </h2>
                            <p>
                            <?php
                                if ($detail_description==null) {
                                    echo "  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eu turpis molestie, dictum est a, mattis tellus. Sed dignissim, metus nec fringilla accumsan, risus sem sollicitudin lacus, ut interdum tellus elit sed risus. Maecenas eget condimentum velit, sit amet feugiat lectus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent auctor purus luctus enim egestas, ac scelerisque ante pulvinar. Donec ut rhoncus ex. Suspendisse ac rhoncus nisl, eu tempor urna. Curabitur vel bibendum lorem. Morbi convallis convallis diam sit amet lacinia. Aliquam in elementum tellus.
                                    Curabitur tempor quis eros tempus lacinia. Nam bibendum pellentesque quam a convallis. Sed ut vulputate nisi. Integer in felis sed leo vestibulum venenatis. Suspendisse quis arcu sem. Aenean feugiat ex eu vestibulum vestibulum. Morbi a eleifend magna. Nam metus lacus, porttitor eu mauris a, blandit ultrices nibh. Mauris sit amet magna non ligula vestibulum eleifend. Nulla varius volutpat turpis sed lacinia. Nam eget mi in purus lobortis eleifend. Sed nec ante dictum sem condimentum ullamcorper quis venenatis nisi. Proin vitae facilisis nisi, ac posuere leo.
                                    ";          
                                }
                                else {
                                    echo $detail_description;
                                }
                                
                                ?>
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
                                                echo "<img src='../../public/icons/single-bed.svg' id='icone' alt='icone'>";
                                            } elseif ($lit['type'] == 'double') {
                                                echo "<img src='../../public/icons/double-bed.svg' id='icone' alt='icone'>";
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
                            <h2>
                                Installations disponibles :
                            </h2>
                                <?php
                                if ($liste_installation==null || empty($liste_installation)) {
                                    echo "<p class='section_vide'>Cette section est vide.</p>";
                                } else {
                                    echo "<ul class='liste_corps'>";
                                    foreach ($liste_installation as $installation) {
                                        echo "<li>$installation</li>";
                                    }
                                    echo "</ul>";
                                }
                                ?>

                            <br>

                            <h2>
                                Equipements disponibles :
                            </h2>
                            <?php
                                if ($liste_equipements==null || empty($liste_installation)) {
                                    echo "<p class='section_vide'>Cette section est vide.</p>";
                                } else {
                                    echo "<ul class='liste_corps'>";
                                    foreach ($liste_equipements as $equipement) {
                                        echo "<li>$equipement</li>";
                                    }
                                    echo "</ul>";
                                }
                                ?>
                            <br>

                            <h2>
                            Services :
                            </h2>
                            <?php
                                if ($liste_services==null || empty($liste_installation)) {
                                    echo "<p class='section_vide'>Cette section est vide.</p>";
                                } else {
                                    echo "<ul class='liste_corps'>";
                                    foreach ($liste_services as $service) {
                                        echo "<li>$service</li>";
                                    }
                                    echo "</ul>";
                                }
                                ?>
                            </ul>
                            <br>
                    </section>
                        <section class="reserve_contact">
                            <div class="resa_colle">
                                <article class="reserve">
                                    <div id="prix_base">
                                    <p id="prix"><?php
                                        if ($prix==null) {
                                            echo "Prix ";          
                                        }
                                        else {
                                            echo $prix;
                                        }
                                ?></p>
                                    <p1>€ par nuit</p1>
                                    </div>
                                    <div class="arrivee_depart">
                                        <form class="date_resa" id="date_arri">
                                            <label for="date_arrivee">Nombre de nuit :</label>
                                            <input type="text" id="date_arrivee" name="date_arrivee">
                                        </form>
                                    </div>
                                    <div class="bouton_reserve">
                                        <a href="#">Réserver</a>
                                    </div>

                                </article>

                                <article class="contact">
                                    <div class="photo_profil_contact">
                                        <?php
                                            if ($photo_profil_proprio==null) {
                                                echo "<img src=\"../../public/icons/user.svg\" id=\"photo_profil\" alt=\"photo de profil du propriétaire\">";          
                                            }
                                            else {
                                                echo $photo_profil_proprio;
                                            }
                                            ?>                                        <div class="contact_nom_bouton">
                                            <p><?php
                                            if ($prenom_proprio==null) {
                                                echo "Prénom ";          
                                            }
                                            else {
                                                echo "$prenom_proprio ";
                                            }
                                            if ($nom_proprio==null) {
                                                echo "Nom";          
                                            }
                                            else {
                                                echo $nom_proprio;
                                            }
                                            ?></p>
                                            <p><img src="../../public/icons/star_fill.svg" id="icone" alt="icone etoile"> Note</p>
                                            <div class="bouton_contact">
                                                <a href="#">Contacter</a>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    Langues parlées :
                                        <?php
                                        //$liste_langue_parle = array("Français", "Anglais", "Espagnol"); 

                                        if (empty($liste_langue_parle)|| $liste_langue_parle == null){
                                            echo " non renseigné";
                                        } else {
                                            echo implode(', ', $liste_langue_parle);
                                        }
                                        ?>
                                </article>
                            </div>
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

        <footer id="footer">
            <img src="../../public/icons/wave.svg">
            <div>
                <div>
                    <div>
                        <h2>Assistance</h2>
                        <ul>
                            <li><a href="">Centre d'aide</a></li>
                            <li><a href="">Options d'annulation</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2>Accueil des voyageurs</h2>
                        <ul>
                            <li><a href="">Nous rejoindre</a></li>
                            <li><a href="">Mettez votre logement sur ALHaIZ Breizh</a></li>
                            <li><a href="">Confiance et sécurité</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2>ALHaIZ Breizh</h2>
                        <ul>
                            <li><a href="">Qui sommes-nous</a></li>
                            <li><a href="">Conditions générales d'utilisation</a></li>
                            <li><a href="">Mentions légales</a></li>
                        </ul>
                    </div>
                </div>
                <p>© 2023 ALHaIZ Breizh - Tous droits réservés</p>
            </div>
        </footer>
    </body>
</html> 
                    
