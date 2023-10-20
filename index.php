<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/index.css">
        <title>ALHaiz Breizh</title>
    </head>
    <body>
        <?php include './src/php/header.php'; ?>
        <img id="background" src="/public/backgrounds/bg1.png">
        <div id="titre">
            <h1>Envie de découvrir la Bretagne ?</h1>
            <p>Nous avons tout pour vous mettre ALHaIZ</p>
        </div>
        <div id="logements">
            <h2>Les logements</h2>
            <div>
                <?php
                /*Créations de carte pour chaque logements*/
                $dir = './public/img/logements';
                $folders = array_diff(scandir($dir), array('..', '.'));

                if ($folders == null) { ?>
                    <h2>Aucun logement n'est disponible pour le moment :/</h2> <?php
                }

                foreach ($folders as $folder) {
                    $img = $dir . '/' . $folder . '/1.png'; ?>

                    <div class="logement">
                        <a href=""><img src="<?php echo $img ?>"></a>
                        <button type="button"><img src="/public/icons/heart.svg"></button>
                        <a href=""><div>
                            <h3>Maison à Plestin les grèves</h3>
                            <div id="rating">4.9<img src="/public/icons/star_fill.svg"></div>
                            <div><img src="/public/icons/nb_personnes.svg">6 personnes</div>
                            <div><strong>999€</strong> / nuit</div>
                        </div></a>
                    </div> <?php
                }

                ?>
            </div> 
        </div>   
        <footer id="footer">
            <img src="/public/icons/wave.svg">
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
        <script src="/src/js/compte-click.js"></script>
        <script>
            window.addEventListener("scroll", () => {
                var header = document.querySelector("header");
                
                if (window.scrollY > 0) {
                    header.classList.add("scrolled");
                } else{
                    header.classList.remove("scrolled");
                }
            });
        </script>
    </body>
</html>