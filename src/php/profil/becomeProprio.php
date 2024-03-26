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

            <label for="natureLogement">BIC (*)</label>
            <input class="textfield" type="text" id="bc" name="natureLogement" size="150" placeholder="ABCDEFGH" maxlength="8">

            <label for="iban0">IBAN (*)</label>
            <div class="propr">
                <input class="textfield" type="text" id="ibn1" name="natureLogement" size="60" placeholder="FR00" maxlength="4">
                <input class="textfield" type="text" id="ibn2" name="natureLogement" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" id="ibn3" name="natureLogement" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" id="ibn4" name="natureLogement" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" id="ibn5" name="natureLogement" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" id="ibn6" name="natureLogement" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" id="ibn7" name="natureLogement" size="60" placeholder="1234" maxlength="4">
                <input class="textfield" type="text" id="ibn8" name="natureLogement" size="60" placeholder="123" maxlength="3">
                </div>

            <div class="typeLogementDiv">
                    <div>
                        <label for="langue">Langue (*)</label>
                        <select class="textfield" id="typeLogement" name="typeLogement">
                        <option>Français</option>
                        <option>Maison</option>
                        <option>Villa</option>
                        </select>
                    </div>
                </div>
        </div>
        <button class="creerAnnonce boutton" type="submit" id="creerAnnonce">Devenir Proriétaire</button>     
    </div>
    </div>
    </form>
</body>
<?php include '../footer.php'; ?>
</html>