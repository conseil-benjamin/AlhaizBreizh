<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/styles.css">
    <link rel="stylesheet" type="text/css" href="../styles/styleCreationLogement.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    <script src="../js/addInputElement.js"></script>
    <title>Creation Logement</title>
</head>
<body>
    <?php
        include './header.php';


        try {
            // Connexion à la base de données
            $pdo = new PDO("pgsql:host=postgresdb;port=5432;dbname=sae;user=sae;password=Phiegoosequ9en9o");
        
            // Préparez la requête SQL pour récupérer les données de la table "Client"
            $query = "SELECT * FROM ldc.Logement WHERE numLogement = 2 ";
        
            // Exécutez la requête
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
                $nbLitsSimples = $row[11];
                $nbLitsDoubles = $row[12];
                $detailsLitsDispos = $row[13];
                $nbSalleDeBain = $row[14];
                $tarifNuitees = $row[15];
            }

            $query = "SELECT * FROM ldc.localisation WHERE numLogement = 2 ";
            $result = $pdo->query($query);

            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $rue = $row[2];
                $cp = $row[3];
                $ville = $row[4];
            }

            // insert

            $pdo = null;

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    ?>
    <h1>Création d’un nouveau logement</h1>
    <hr>
    <form method="post">
    <div class="container-main">
        <div class="container-left">
            <label for="title">Titre de l'annonce (*)</label>
            <input type="text" id="title" name="title" size="60" placeholder="Titre" maxlength="100" value=<?php echo $libelle; ?>>
            
            <label for="description">Description de l'annonce (*)</label>
            <textarea name="description" id="description" cols="46" rows="20" placeholder="Description" maxlength="500"><?php echo $description; ?></textarea>
            
            <label for="photos">Photos (*)</label>
            <input type="file" id="photos" name="photos" multiple>
            
            <div class="typeLogementDiv">
                <div>
                    <label for="typeLogement">Type de logement (*)</label>

                    <select id="typeLogement" name="typeLogement">

                    <?php
                    $options=array("Appartement","maison","villa","cave");
                    foreach ($options as $option){
                        if ($option == $natureLogement){
                            echo "<option value='$option' selected>$option</option>";
                        }
                        else{
                            echo "<option value='$option'>$option</option>";
                        }
                    }
                    ?>
                    </select>
                </div>

                <div>
                    <label for="surface">Surface en m² (*)</label>
                    <input type="number" id="surface" name="surface" maxlength="4" placeholder="Surface" min="0" value=<?php echo $surfaceHabitable; ?>>
                </div>
            </div>

            <label for="natureLogement">Nature du logement (*)</label>
            <input type="text" id="natureLogementInput" name="natureLogement" size="60" placeholder="Nature du logement" maxlength="50" value=<?php echo $natureLogement; ?>>
            
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
            <input type="text" id="adresse" name="adresse" size="60" placeholder="Numéro et nom de la rue" value=<?php echo $rue; ?> >
            <div class="villeDiv">
                <div>
                    <label for="cdPostal">Code Postal (*)</label>
                    <input type="text" id="cdPostal" name="cdPostal" placeholder="Code Postal" maxlength="5" value=<?php echo $cp; ?>>
                    <span id="testValeurInputCdPostal"></span>
                </div>
                <div>
                    <label for="ville">Ville (*)</label>
                    <input type="text" id="ville" name="ville" placeholder="Ville" value=<?php echo $ville; ?>>
                </div>
            </div>
            <label for="accroche">Phrase d'accroche</label>
            <textarea name="accroche" id="accroche" cols="45" rows="10" placeholder="Laisser une petite accroche"><?php echo $accroche; ?></textarea>
            <div class="nbChambreEtBainsDiv">
                <div>
                    <label for="nbChambres">Nombres de chambres (*)</label> 
                    <input type="number" id="nbChambres" name="nbChambres" placeholder="Nb Chambres" min="1" value=<?php echo $nbChambres; ?>>
                </div>
                <div>
                    <label for="nbSalleBain">Nombres de salles de bain (*)</label>
                    <input type="number" id="nbSallesBain" name="nbSallesBain" placeholder="Nb Salles de Bain" min="1" value=<?php echo $nbSalleDeBain; ?>>
                </div>
            </div>
            <div class="nbPrixEtPersonnesDiv">
                <div>
                    <label for="nbMaxPers">Nombre de personnes max (*)</label>
                    <input type="number" id="nbMaxPers" name="nbMaxPers" placeholder="Nb pers max" min="1" value=<?php echo $nbPersMax; ?>>
                </div>
                <div>
                    <label for="prixParNuit">Prix de base par nuit (*)</label>
                    <br>
                    <input type="number" id="prixParNuit" name="prixParNuit" placeholder="Prix/Nuit" min="0" value=<?php echo $tarifNuitees; ?>>
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
            <label class="conditionsGenerale" for="conditionsGenerale">
            <button class="modifAnnonce" type="submit" id="modifAnnonce">Modifier l'annonce</button>
    </div>
    </div>
    </form>


    <?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $photos = $_POST['photos'];
    $typeLogement = $_POST['typeLogement'];
    $surface = $_POST['surface'];
    $natureLogement = $_POST['natureLogement'];
    // faire une boucle qui récupère tous les inputs services en récupérant la valeur de la variable nbServices
    // pour connaitre le nombre de services tapé et tous les récupérés
    $serviceLogement1 = $_POST['services'];
    $serviceLogement2 = $_POST['service2'];
    $lits = $_POST['lits'];
    $adresse = $_POST['adresse'];
    $codePostal = $_POST['cdPostal'];
    $ville = $_POST['ville'];
    $phraseAccroche = $_POST['accroche'];
    $nbChambres = $_POST['nbChambres'];
    $nbSalleDeBain = $_POST['nbSallesBain'];
    $nbPersMax = $_POST['nbMaxPers'];
    $prixParNuit = $_POST['prixParNuit'];
    $installationsDispo = $_POST['installDispo'];
    $equipementDispo = $_POST['equipementDispo'];

    $servicesLogement = $serviceLogement1 . ", " . $serviceLogement2;
    try {

        $pdo = new PDO("pgsql:host=postgresdb;port=5432;dbname=sae;user=sae;password=Phiegoosequ9en9o");
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare(
        //"UPDATE ldc.logement SET surfacehabitable=$surface, libelle=$title, accroche = $phraseAccroche, logement.description=$description,naturelogement=$typeLogement, photocouverture=$photos, nbpersmax=$nbPersMax, nbchambres=$nbChambres, nbsalledebain=$nbSalleDeBain WHERE logement.numLogement = $numLogement)"
        //"UPDATE ldc.logement SET surfacehabitable=$surface WHERE logement.numLogement = $numLogement"
        "UPDATE ldc.logement SET nbsalledebain=$nbSalleDeBain, surfacehabitable=$surface, nbchambres=$nbChambres, nbpersmax=$nbPersMax WHERE logement.numLogement = $numLogement"
    );
        $stmt->execute();
        $pdo = null;
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

}
    ?>
</body>
</html>