<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/styleCreationLogement.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/src/js/addInputElement.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" href="/public/logos/logo-black.svg">

    <title>Creation Logement</title>
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
    <form id="myForm" method="post" action="insertDatabase.php" enctype="multipart/form-data">
    <div class="main">
        <div class="titre">
            <h1>Créer un logement</h1>
        </div>
        <div class="container-main">
            <div class="container-left">
                <label for="title">Titre de l'annonce (*)</label>
                <input class="textfield" type="text" id="title" name="title" size="60" placeholder="Titre" maxlength="100">
                <label for="description">Description de l'annonce (*)</label>
                <textarea class="textfield" name="description" id="description" cols="56" rows="20" placeholder="Description" maxlength="500"></textarea>
                <label for="photos" class="boutton">Ajouter photos</label>
                <input type="file" id="photos" name="photos" accept=".jpg, .jpeg, .png" multiple onchange="afficherNomsPhotos()">
                <div id="photosName"></div>
                <div class="typeLogementDiv">
                    <div>
                        <label for="typeLogement">Type de logement (*)</label>
                        <select class="textfield" id="typeLogement" name="typeLogement">
                        <option>Appartement</option>
                        <option>Maison</option>
                        <option>Villa</option>
                        </select>
                    </div>
                    <div>
                        <label for="surface">Surface en m² (*)</label>
                        <input class="textfield" type="number" id="surface" name="surface" maxlength="4" min="1" placeholder="Surface">
                    </div>
                </div>
                <label for="natureLogement">Nature du logement (*)</label>
                <input class="textfield" type="text" id="natureLogement" name="natureLogement" size="60" placeholder="Nature du logement" maxlength="50">
                <div class="servicesElement">
                    <label for="services">Services disponibles</label>
                    <input class="textfield" type="text" id="service" name="service" placeholder="Service disponible" size="60" maxlength="100">
                </div>
                <button class="addButton boutton" id="btnServices" type="button">Ajouter services disponibles</button>
                <div class="chambresElement">
                <div class="litsElement">
                    <label for="lits">Chambre 1</label>
                    <select class="textfield" id="lits" name="1lits0">
                    <option>Lit simple (90 * 190)</option>
                    <option selected="selected" >Lit double (140 * 190)</option>
                    </select>
                </div>
                <button class="addButton boutton" id="btnAddLits" type="button">Ajouter un lit</button>
                </div>
                <button class="addChambre boutton" id="btnAddChambre" type="button">Ajouter une chambre</button>    
            </div>
            <div class="container-right">
                <label for="adresse">Adresse (*)</label>
                <input class="textfield" type="text" id="adresse" name="adresse" size="60" placeholder="Numéro et nom de la rue">
                <div class="villeDiv">
                    <div>
                        <label for="cdPostal">Code Postal (*)</label>
                        <input class="textfield" type="number" id="cdPostal" name="cdPostal" placeholder="Code Postal" max="99999">
                        <span id="testValeurInputCdPostal"></span>
                    </div>
                    <div>
                        <label for="ville">Ville (*)</label>
                        <input class="textfield" type="text" id="ville" name="ville" placeholder="Ville" size="26">
                    </div>
                </div>
                <label for="accroche">Phrase d'accroche</label>
                <textarea class="textfield" name="accroche" id="accroche" cols="55" rows="10" placeholder="Laisser une petite accroche"></textarea>
                <div class="nbPrixEtPersonnesDiv">
                    <div class="div-label-nbPers">
                        <label for="nbMaxPers">Nombre de personnes max (*)</label>
                        <input class="textfield" type="number" id="nbMaxPers" name="nbMaxPers" placeholder="Nb pers max" min="1">
                    </div>
                    <div class="div-prix-nuit"> 
                        <label for="prixParNuit">Prix de base par nuit (*)</label>
                        <input class="textfield" type="number" id="prixParNuit" name="prixParNuit" placeholder="Prix/Nuit" min="1">
                    </div>
                </div>   
                <div class="div-nbSalleBain">
                    <label for="nbSalleBain">Nombres de salles de bain (*)</label>
                    <input class="textfield" type="number" id="nbSallesBain" name="nbSallesBain" min="1" placeholder="Nb Salles de Bain">
                </div>
                <div class="installationsElement">
                    <label for="installDispo">Installations disponibles</label>
                    <input class="textfield" type="text" id="installDispo" name="installDispo" placeholder="Installation disponible" size="60">
                </div>
                <button class="addButton boutton" id="btnInstallations" type="button">Ajouter installations disponibles</button>
                <div class="equipementsElement">
                    <label for="equipementDispo">Equipements disponibles</label>
                    <input class="textfield" type="text" id="equipement" name="equipement" placeholder="Equipement disponible" size="60">
                </div>
                <button class="addButton boutton" id="btnAddEquipements" type="button">Ajouter Equipements disponibles</button>
            </div>
            <div class="bottom">
                <p>Les champs marqués par (*) sont obligatoire</p>
                <span class="conditionsGenerale">J'ai lu et j'accepte les <a href="">Conditions Générales d'Utilisation</a>, la Politique des données personnelles et les Conditions Générales de Ventes d’Alhaiz Breizh (*)</span>
                <input class="textfield" type="checkbox" name="conditionsGenerale" id="conditionsGenerale">
                <button class="creerAnnonce boutton" type="submit" id="creerAnnonce">Créer annonce</button>
            </div>
        </div>     
    </div>
    </form>
    <?php include '../footer.php'; ?>
</body>
</html>