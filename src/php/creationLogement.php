<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/styleCreationLogement.css">
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
            <input type="text" id="title" name="title">
            <label for="description">Description de l'annonce (*)</label>
            <input type="text" id="description" name="description">
            <label for="photos">Photos (*)</label>
            <input type="file" id="photos" name="photos">
            <label for="typeLogement">Type de logement (*)</label>
            <select id="typeLogement" name="typeLogement">
            <option>Appartement</option>
            <option>Maison</option>
            <option>Villa</option>
            <option>Gîte</option>
            </select>
            <label for="surface">Surface en m² (*)</label>
            <input type="number" id="surface" name="surface">
            <label for="services">Services disponibles</label>
            <input type="text" id="services" name="services">
            <button class="addButton" type="button">Ajouter services disponibles</button>
            <label for="lits">Chambre 1</label>
            <select id="lits" name="lits">
            <option selected="selected" >Lit double (140 * 190)</option>
            <option>Lit simple (90 * 190)</option>
            </select>
            <button class="addButton" type="button">Ajouter un lit</button>
        </div>
        <div class="container-right">
            <label for="adresse">Adresse (*)</label>
            <input type="text" id="adresse" name="adresse">
            <label for="cdPostale">Code Postal (*)</label>
            <input type="text" id="cdPostal" name="cdPostal">
            <label for="ville">Ville (*)</label>
            <input type="text" id="ville" name="ville">
            <label for="accroche">Phrase d'accroche</label>
            <textarea name="accroche" id="accroche" cols="30" rows="10"></textarea>
            <label for="nbChambres">Nombres de chambres (*)</label>
            <input type="number" id="nbChambres" name="nbChambres">
            <label for="nbSalleBain">Nombres de salles de bain (*)</label>
            <input type="number" id="nbSallesBain" name="nbSallesBain">
            <label for="nbMaxPers">Nombres de personnes max (*)</label>
            <input type="number" id="nbMaxPers" name="nbMaxPers">
            <label for="prixParNuit">Prix de base par nuit (*)</label>
            <input type="number" id="prixParNuit" name="prixParNuit">
            <label for="installDispo">Installations disponibles</label>
            <input type="number" id="installDispo" name="installDispo">
            <button class="addButton" type="button">Ajouter installations disponibles</button>
            <label for="equipementDispo">Equipements disponibles</label>
            <input type="number" id="equipementDispo" name="equipementDispo">
            <button class="addButton" type="button">Ajouter Equipementss disponibles</button>
            <button class="addChambre" type="button">Ajouter une chambre</button>
            <p>Les champs marqués par (*) sont obligatoire</p>
            <input type="checkbox" name="conditionsGenerale" id="conditionsGenerale">
            <label for="conditionsGenerale">J'ai lu et j'accepte les Conditions Générales d'Utilisation, la Politique des données personnelles et les Conditions Générales de Ventes d’Alhaiz Breizh (*)</label>
        </div>
        <button class="creerAnnonce" type="submit">Créer annonce</button>
    </div>
</body>
</html>