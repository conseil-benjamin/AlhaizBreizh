<?php
session_start();
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
                    echo $localisation;
                }
                # gestion localisation specifique
                ?>
                Localisation specifique
                    </p>
                <ul class="infos_loge">
                    <p>
                        <a href="#comment" class="logo"><img src="../../public/icons/star_fill.svg" id="icone" alt="icone etoile"> Note</p></a>


                    <li><div><img src="../../public/icons/type_logement.svg" id="icone" alt="icone maison"> <?php echo $type_logement?></div></li>
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
                                    echo "Prénom";          
                                }
                                else {
                                    echo $prenom_proprio;
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
                                    <p1> par nuit</p1>
                                    </div>
                                    <div class="arrivee_depart">
                                        <form class="date_resa" id="date_arri">
                                            <label for="date_arrivee">Arrivée :</label>
                                            <input type="text" id="date_arrivee" name="date_arrivee">
                                        </form>
                                        <form class="date_resa">
                                            <label for="date_depart">Départ :</label>
                                            <input type="text" id="date_depart" name="date_depart">
                                        </form>
                                    </div>
                                    <div class="bouton_reserve">
                                        <a href="#">Réserver</a>
                                    </div>

                                </article>

                                <article class="contact">
                                    <div class="photo_profil_contact">
                                        <img src="../../public/icons/user.svg" id="photo_profil" alt="photo de profil du propriétaire">
                                        <div class="contact_nom_bouton">
                                            <p>Prénom Nom</p>
                                            <p><img src="../../public/icons/star_fill.svg" id="icone" alt="icone etoile"> Note</p>
                                            <div class="bouton_contact">
                                                <a href="#">Contacter</a>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    Langues parlées : 
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
