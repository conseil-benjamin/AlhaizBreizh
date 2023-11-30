<!DOCTYPE html>
<html lang="fr-fr">

<head>
    <!-- Balises meta pour le jeu de caractères, la compatibilité et la vue -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Feuilles de style -->
    <link rel="stylesheet" type="text/css" href="/src/styles/titre.css">

    <!-- Titre de la page -->
    <title>ALHaiz Breizh</title>

    <!-- Code PHP pour vérifier la session utilisateur et définir l'image de profil -->

</head>
<body>

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
            <a class="lien" href="/accueil" target="_blank">
                <div>
                    Cette page est inexistante. Cliquez ici pour retourner à l'accueil.
                </div>
            </a>
        </div>
    </div>
</body>
</html>
