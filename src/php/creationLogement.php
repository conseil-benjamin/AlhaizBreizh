<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/styles.css">
    <link rel="stylesheet" type="text/css" href="../styles/styleCreationLogement.css">
    <script src="../js/addInputElement.js"></script>
    <title>Creation Logement</title>
</head>
<body>
    <?php
        include './header.php';
    ?>
    <h1>Création d’un nouveau logement</h1>
    <hr>
    <div class="container-main">
        <div class="container-left">
            <label for="">Titre de l'annonce (*)</label>
            <input type="text" id="title" name="title" size="50">
            <label for="description">Description de l'annonce (*)</label>
            <textarea name="description" id="description" cols="55" rows="20"></textarea>
            <label for="photos">Photos (*)</label>
            <input type="file" id="photos" name="photos" multiple>
            <div class="typeLogementDiv">
                <div>
                    <label for="typeLogement">Type de logement (*)</label>
                    <select id="typeLogement" name="typeLogement">
                    <option>Appartement</option>
                    <option>Maison</option>
                    <option>Villa</option>
                    <option>Gîte</option>
                    </select>
                </div>
                <div>
                    <label for="surface">Surface en m² (*)</label>
                    <input type="number" id="surface" name="surface">
                </div>
            </div>
            <div class="servicesElement">
                <label for="services">Services disponibles</label>
                <input type="text" id="services" name="services">
            </div>
            <button class="addButton" id="btnServices" type="button">Ajouter services disponibles</button>
            <div class="chambresElement">
            <div class="litsElement">
                <label for="lits">Chambre 1</label>
                <select id="lits" name="lits">
                <option selected="selected" >Lit double (140 * 190)</option>
                <option>Lit simple (90 * 190)</option>
                </select>
            </div>
            </div>
            <button class="addButton" id="btnAddLits" type="button">Ajouter un lit</button>
        </div>
        <div class="container-right">
            <label for="adresse">Adresse (*)</label>
            <input type="text" id="adresse" name="adresse" size="50">
            <div class="villeDiv">
                <div>
                    <label for="cdPostal">Code Postal (*)</label>
                    <input type="text" id="cdPostal" name="cdPostal">
                </div>
                <div>
                    <label for="ville">Ville (*)</label>
                    <input type="text" id="ville" name="ville">
                </div>
            </div>
            <label for="accroche">Phrase d'accroche</label>
            <textarea name="accroche" id="accroche" cols="55" rows="10"></textarea>
            <div class="nbChambreEtBainsDiv">
                <div>
                    <label for="nbChambres">Nombres de chambres (*)</label> 
                    <input type="number" id="nbChambres" name="nbChambres">
                </div>
                <div>
                    <label for="nbSalleBain">Nombres de salles de bain (*)</label>
                    <input type="number" id="nbSallesBain" name="nbSallesBain">
                </div>
            </div>
            <div class="nbPrixEtPersonnesDiv">
                <div>
                    <label for="nbMaxPers">Nombre de personnes max (*)</label>
                    <input type="number" id="nbMaxPers" name="nbMaxPers">
                </div>
                <div>
                    <label for="prixParNuit">Prix de base par nuit (*)</label>
                    <input type="number" id="prixParNuit" name="prixParNuit">
                </div>
            </div>

            <div class="installationsElement">
                <label for="installDispo">Installations disponibles</label>
                <input type="text" id="installDispo" name="installDispo">
            </div>
            <button class="addButton" id="btnInstallations" type="button">Ajouter installations disponibles</button>
            <div class="equipementsElement">
                <label for="equipementDispo">Equipements disponibles</label>
                <input type="text" id="equipementDispo" name="equipementDispo">
            </div>
            <button class="addButton" id="btnAddEquipements" type="button">Ajouter Equipements disponibles</button>
            <button class="addChambre" id="btnAddChambre" type="button">Ajouter une chambre</button>
    </div>
    <div class="bottom">
    <p>Les champs marqués par (*) sont obligatoire</p>
            <span class="conditionsGenerale">J'ai lu et j'accepte les Conditions Générales d'Utilisation, la Politique des données personnelles et les Conditions Générales de Ventes d’Alhaiz Breizh (*)</label>
            </span>
            <input type="checkbox" name="conditionsGenerale" id="conditionsGenerale">
            <label class="conditionsGenerale" for="conditionsGenerale">
        <button class="creerAnnonce" type="submit" id="creerAnnonce">Créer annonce</button>
    </div>
    </div>
</body>
</html>