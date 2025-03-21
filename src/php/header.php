<?php
    $image = '/public/icons/user-blue.svg';
    if ((isset($_SESSION['id'])) && (file_exists($_SERVER['DOCUMENT_ROOT'].$image))) { // Si l'utilisateur est connecté et qu'il a une photo de profil
        $image = '/public/img/photos_profil/'.$_SESSION['id'].'.png';
    }
?>
<header id="header">
    <a href="/index.php" class="logo"><img src="/public/logos/logo-grey.svg"></a>
    <nav>
        <ul>
            <li><a href="/index.php#logements">Logements</a></li>
            <li><a href="">Réservations</a></li>
            <li><a href="">À propos</a></li>
        </ul>
    </nav>
    <?php
        if (isset($_SESSION['id'])) { ?>
            <div class="compte">
                <img src="/public/icons/arrow-blue.svg">
                <p><?php echo $_SESSION['pseudo'] ?></p>
                <img src="<?php echo $image ?>">
            </div>
            <div class="compte-options">
                <ul>
                    <!--<a href=""><li>Mon Profil</li></a>-->
                    <!--<a href=""><li>Ma Messagerie</li></a>-->
                    <!--<a href=""><li>Mes Réservations</li></a>-->
                    <?php
                        if ($_SESSION['proprio']) { ?>
                            <a href="/src/php/logement/mesLogements.php"><li>Mes Logements</li></a> <?php
                        } else { ?>
                            <!--<a href=""><li>Devenir propriétaire</li></a>--> <?php
                        } ?>
                    <!--<a href=""><li>Mes Favoris</li></a>-->
                    <!--<a href=""><li>Besoin d'Aide</li></a>-->
                    <a href="/src/php/connexion/login.php?deconnexion" id="deconnexion"><li>Déconnexion</li></a>
                </ul>
            </div> <?php
        } else { ?>
            <a href="/src/php/connexion/connexion.php" class="compte">
                <p>Se connecter</p>
                <img src="/public/icons/user-blue.svg">
            </a> <?php
        }
    ?>
</header>
<script src="/src/js/compte-click.js"></script>
