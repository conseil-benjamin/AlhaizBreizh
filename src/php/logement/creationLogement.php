<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../styles/styles.css">
    <link rel="stylesheet" type="text/css" href="../../styles/styleCreationLogement.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/addInputElement.js"></script>
    <title>Creation Logement</title>
</head>
<body>
    <?php
        include '../header.php';
        
        /*
            $query = "SELECT * FROM ldc.Logement WHERE numLogement = 2 ";
            
            $result = $pdo->query($query);
        
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $numLogement = $row[0];
                $surfaceHabitable = $row[1];
                $libelle = $row[2];
                $accroche = $row[3];
                $description = $row[4];
                $natureLogement = $row[5];
                $proprio = $row[6];
                $photoCouverture = $row[7];
                $LogementEnLigne = $row[8];
                $nbPersMax = $row[9];
                $nbChambres = $row[10];
                $detailsLitsDispos = $row[11];
                $nbSalleDeBain = $row[12];
                $tarifNuitees = $row[13];
            }   

            $query2 = "SELECT * FROM ldc.Chambre WHERE numLogement = 1";

            $result2 = $pdo->query($query2);

            while ($row2 = $result2->fetch(PDO::FETCH_NUM)) {
                $numLogementAssocieChambre = $row2[0];
                $numChambre = $row2[1];
                $nbLitsSimples = $row2[2];
                $nbLitsDoubles = $row2[3];
            }

            echo "Numéro de Logement : " . $numLogement . "<br>";
            echo "Surface Habitable : " . $surfaceHabitable . "<br>";
            echo "Libellé : " . $libelle . "<br>";
            echo "Accroche : " . $accroche . "<br>";
            echo "Description : " . $description . "<br>";
            echo "Nature du Logement : " . $natureLogement . "<br>";
            echo "Propriétaire : " . $proprio . "<br>";
            echo "Photo de Couverture : " . $photoCouverture . "<br>";
            echo "Logement en Ligne : " . $LogementEnLigne . "<br>";
            echo "Nombre de Personnes Max : " . $nbPersMax . "<br>";
            echo "Nombre de Chambres : " . $nbChambres . "<br>";
            echo "Détails des Lits Disponibles : " . $detailsLitsDispos . "<br>";
            echo "Nombre de Salles de Bain : " . $nbSalleDeBain . "<br>";
            echo "Tarif des Nuitées : " . $tarifNuitees . "<br>";

            echo "<br>";

            echo "Num logement :" . $numLogementAssocieChambre . "<br>";
            echo "Num chambre : " . $numChambre . "<br>";
            echo "Nombres lits simples : " . $nbLitsSimples . "<br>";
            echo "Nombres lits doubles : " . $nbLitsDoubles . "<br>"; 

            $query5 = "INSERT INTO ldc.Chambre (numLogement, numChambre, nbLitsSimples, nbLitsDoubles) VALUES (2, $numChambre, $nbLitsSimples, $nbLitsDoubles)";

            $stmt3 = $pdo->prepare($query5);
            $stmt3->execute();

            $query3 = "INSERT INTO ldc.LogementProprio (numLogement,idCompte) VALUES (2, 2)";

            $stmt= $pdo->prepare($query3);
            $stmt->execute();

            

            
            $stmt2 = $pdo->prepare($query4);
            $stmt2->execute();
            */
        
    ?>
    <h1>Création d’un nouveau logement</h1>
    <hr>
<form method="post" action="javascript:void(0);" onsubmit="submitForm()">
    <div class="container-main">
        <div class="container-left">
            <label for="title">Titre de l'annonce (*)</label>
            <input type="text" id="title" name="title" size="60" placeholder="Titre" maxlength="100">
            <label for="description">Description de l'annonce (*)</label>
            <textarea name="description" id="description" cols="46" rows="20" placeholder="Description" maxlength="500"></textarea>
            <div class="custom-file-input">
                Ajouter photos
                <input type="file" id="photos" name="photos" accept=".jpg, .jpeg, .png" onchange="afficherNomsPhotos()" multiple>
            </div>
            <div id="photosName">Noms Photos :</div>
            <div class="typeLogementDiv">
                <div>
                    <label for="typeLogement">Type de logement (*)</label>
                    <select id="typeLogement" name="typeLogement">
                    <option>Appartement</option>
                    <option>Maison</option>
                    <option>Villa</option>
                    </select>
                </div>
                <div>
                    <label for="surface">Surface en m² (*)</label>
                    <input type="number" id="surface" name="surface" maxlength="4" min="1" placeholder="Surface">
                </div>
            </div>
            <label for="natureLogement">Nature du logement (*)</label>
            <input type="text" id="natureLogementInput" name="natureLogement" size="60" placeholder="Nature du logement" maxlength="50">
            <div class="servicesElement">
                <label for="services">Services disponibles</label>
                <input type="text" id="services" name="services" placeholder="Service disponible" size="60" maxlength="100">
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
            <button class="addButton" id="btnAddLits" type="button">Ajouter un lit</button>
            </div>
        </div>
        <div class="container-right">
            <label for="adresse">Adresse (*)</label>
            <input type="text" id="adresse" name="adresse" size="60" placeholder="Numéro et nom de la rue">
            <div class="villeDiv">
                <div>
                    <label for="cdPostal">Code Postal (*)</label>
                    <input type="text" id="cdPostal" name="cdPostal" placeholder="Code Postal" maxlength="5">
                    <span id="testValeurInputCdPostal"></span>
                </div>
                <div>
                    <label for="ville">Ville (*)</label>
                    <input type="text" id="ville" name="ville" placeholder="Ville" size="26">
                </div>
            </div>
            <label for="accroche">Phrase d'accroche</label>
            <textarea name="accroche" id="accroche" cols="45" rows="10" placeholder="Laisser une petite accroche"></textarea>
            <div class="nbChambreEtBainsDiv">
                <div>
                    <label for="nbChambres">Nombres de chambres (*)</label> 
                    <input type="number" id="nbChambres" name="nbChambres" placeholder="Nb Chambres" min="1">
                </div>
                <div>
                    <label for="nbSalleBain">Nombres de salles de bain (*)</label>
                    <input type="number" id="nbSallesBain" name="nbSallesBain" min="1" placeholder="Nb Salles de Bain">
                </div>
            </div>
            <div class="nbPrixEtPersonnesDiv">
                <div>
                    <label for="nbMaxPers">Nombre de personnes max (*)</label>
                    <input type="number" id="nbMaxPers" name="nbMaxPers" placeholder="Nb pers max" min="1">
                </div>
                <div> 
                    <label for="prixParNuit">Prix de base par nuit (*)</label>
                    <br>
                    <input type="number" id="prixParNuit" name="prixParNuit" placeholder="Prix/Nuit" min="1">
                </div>
            </div>

            <div class="installationsElement">
                <label for="installDispo">Installations disponibles</label>
                <input type="text" id="installDispo" name="installDispo" placeholder="Installation disponible" size="55">
            </div>
            <button class="addButton" id="btnInstallations" type="button">Ajouter installations disponibles</button>
            <div class="equipementsElement">
                <label for="equipementDispo">Equipements disponibles</label>
                <input type="text" id="equipementDispo" name="equipementDispo" placeholder="Equipement disponible" size="55">
            </div>
            <button class="addButton" id="btnAddEquipements" type="button">Ajouter Equipements disponibles</button>
            <button class="addChambre" id="btnAddChambre" type="button">Ajouter une chambre</button>
    </div>
    <div class="bottom">
    <p>Les champs marqués par (*) sont obligatoire</p>
            <span class="conditionsGenerale">J'ai lu et j'accepte les Conditions Générales d'Utilisation, la Politique des données personnelles et les Conditions Générales de Ventes d’Alhaiz Breizh (*)</label>
            </span>
            <input type="checkbox" name="conditionsGenerale" id="conditionsGenerale">
            <button class="creerAnnonce" type="submit" id="creerAnnonce">Créer annonce</button>
    </div>
    </div>
    </form>
    <?php include '../footer.php'; ?>
</body>
</html>