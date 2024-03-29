<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/styleCreationLogement.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" href="/public/logos/logo-black.svg">

    <title>Creation Logement</title>
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
    <form id="myForm" method="post" action="insertProprio.php" enctype="multipart/form-data">
    <div class="main">
        <div class="titre">
            <h1>Devenir Propriétaire</h1>
        </div>
        <div class="container-main">
        <div class="container-left">
            <label for="photos">Validation d'identité</label>
            <label for="photos" class="boutton">Ajouter photos</label>
            <input type="file" id="photos" name="photos" accept=".jpg, .jpeg, .png" multiple onchange="afficherNomsPhotos()">
            <div id="photosName"></div>

            <label for="iban0">IBAN (*)</label>
            <div class="propr">
                <input class="textfield" type="text" name="ibn1" size="60" placeholder="FR00" maxlength="4">
                <input class="textfield" type="text" name="ibn2" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" name="ibn3" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" name="ibn4" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" name="ibn5" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" name="ibn6" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" name="ibn7" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" name="ibn8" size="60" placeholder="123" maxlength="3">
                </div>

            <div class="langues">
                    <div>
                        <label for="langue">Langue (*)</label>
                        <select class="textfield" id="languesparlees" name="languesparlees">
                        <option>Français</option>
                        <option>Anglais</option>
                        <option>Breton</option>
                        <option>Espagnol</option>
                        <option>Italien</option>
                        <option>Allemand</option>
                        <option>Mandarin</option>
                        <option>Hindi</option>
                        <option>Japonnais</option>
                        <option>Arabe</option>
                        <option>Russe</option>
                        <option>Ukrénien</option>
                        <option>Polonais</option>
                        <option>Portugais</option>
                        </select>
                    </div>
            </div>
            <div class="langages">
            <label for="langues" class="boutton" id="add_lg">Ajouter une langue</label>
            </div>
        </div>
        <button class="creerAnnonce boutton" type="submit" id="creerAnnonce">Devenir Proriétaire</button>     
    </div>
    </div>
    </form>
</body>
<script src="/src/js/profil/ajoutLangues.js"></script>
<?php include '../footer.php'; ?>
</html>