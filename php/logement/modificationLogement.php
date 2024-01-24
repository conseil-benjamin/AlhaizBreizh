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
    <script src="/src/js/modifierLogement/addInputElement.js"></script>
    <title>Modification Logement</title>
</head>
<body>
    <?php
        include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php');
        if (isset($_GET['numLogement']) && !empty($_GET['numLogement'])) {
            try {
                $numLogement=$_GET['numLogement'];
                // Connexion à la base de données
                $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
            
                // Préparez la requête SQL pour récupérer les données de la table "Logement"
                $query = "SELECT * FROM ldc.Logement WHERE numLogement = $numLogement ";
            
                // Exécutez la requête
                $result = $pdo->query($query);
            
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $surface = $row['surfacehabitable'];
                    $title = $row['libelle'];
                    $accroche = $row['accroche'];
                    $description = $row['descriptionlogement'];
                    $natureLogement = $row['naturelogement'];
                    $proprio = $row['proprio'];
                    $LogementEnLigne = $row['logementenligne'];
                    $nbPersMax = $row['nbpersmax'];
                    $nbChambres = $row['nbchambres'];
                    $nbSalleDeBain = $row['nbsalledebain'];
                    $adresse = $row['adresse'];
                    $cp = $row['cp'];
                    $ville = $row['ville'];
                    $prixParNuit = $row['tarifnuitees'];
                }
    
                $numChambre=[];
                $i=0;
    
                $query = "SELECT * FROM ldc.chambre WHERE numLogement = $numLogement";
                $result = $pdo->query($query);
    
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    $numChambre[$i][0] = $row[2]; //Doubles
                    $numChambre[$i][1] = $row[1]; //Simples
                    $i=$i+1;
                }
    
                $resting="[";//Chaine de caractere interpretee par le js
                foreach ($numChambre as $value){
                    $resting=$resting."[".$value[0].",".$value[1]."],";
                }
                $resting=substr($resting,0,-1);
                $resting=$resting."]";
    
                //INSTALLATIONS
                $query = "SELECT * FROM ldc.installation WHERE numLogement = $numLogement ";
                $result = $pdo->query($query);
    
                $installations="";
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    $installations = $installations.','.$row[2];
                }
    
                //EQUIPEMENTS
                $query = "SELECT * FROM ldc.equipement WHERE numLogement = $numLogement ";
                $result = $pdo->query($query);
    
                $equipements="";
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    $equipements = $equipements.','.$row[2];
                }
    
                //SERVICES
                $query = "SELECT * FROM ldc.service WHERE numLogement = $numLogement ";
                $result = $pdo->query($query);
    
                $services="";
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    $services = $services.','.$row[2];
                }
    
                $pdo = null;
    
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        ?>
        <h1>Modification d’un logement</h1>
        <hr>
        <form id="myForm" method="post" action="update_logement_bdd.php">
        <div class="container-main">
            <div class="container-left">
                <input type="hidden" name="id_logem" value="<?php echo $numLogement ?>">

                <label for="title" >Titre de l'annonce (*)</label>
                <input type="text" id="title" name="title" size="60" placeholder="Titre" maxlength="100" value="<?php echo $title; ?>" >
                
                <label for="description">Description de l'annonce (*)</label>
                <textarea name="description" id="description" cols="46" rows="20" placeholder="Description" maxlength="500" required><?php echo $description; ?></textarea>
                
                <div class="custom-file-input">
                    Ajouter photos
                    <input type="file" id="photos" name="photos" accept=".jpg, .jpeg, .png" onchange="afficherNomsPhotos()" multiple>
                </div>
                <div id="photosName">Noms Photos :</div>
    
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
                        <input type="number" id="surface" name="surface" maxlength="4" placeholder="Surface" min="1" value=<?php echo $surface; ?>>
                    </div>
                </div>
    
                <label for="natureLogement">Nature du logement (*)</label>
                <input type="text" id="natureLogementInput" name="natureLogement" size="60" placeholder="Nature du logement" maxlength="50" value="<?php echo $natureLogement; ?>">
                
                <div class="servicesElement">
                    <label for="services">Services disponibles</label>
                    <input type="text" id="service" name="service" placeholder="Service disponible" size="60" maxlength="100" value="<?php echo $services; ?>" >
                </div>
                <button class="addButton" id="btnServices" type="button">Ajouter services disponibles</button>
                <div class="chambresElement" id="chambresElement" value=<?php echo $resting?>>
                <div class="litsElement" id="Chambre0">
                    <label for="lits">Chambre 1</label>
                    <select name="1lits0">
                    <option id="option2">Lit double (140 * 190)</option>
                    <option id="option1">Lit simple (90 * 190)</option>
                    </select>
                </div>
                <button class="addButton" id="btnAddLits" type="button">Ajouter un lit</button>
                </div>
            </div>
            
            <div class="container-right">
                <label for="adresse">Adresse (*)</label>
                <input type="text" id="adresse" name="adresse" size="60" placeholder="Numéro et nom de la adresse" value="<?php echo $adresse; ?>">
                <div class="villeDiv">
                    <div>
                        <label for="cdPostal">Code Postal (*)</label>
                        <input type="text" id="cdPostal" name="cdPostal" placeholder="Code Postal" maxlength="5" value="<?php echo $cp; ?>">
                        <span id="testValeurInputCdPostal"></span>
                    </div>
                    <div>
                        <label for="ville">Ville (*)</label>
                        <input type="text" id="ville" name="ville" placeholder="Ville" value="<?php echo $ville; ?>">
                    </div>
                </div>
                <label for="accroche">Phrase d'accroche</label>
                <textarea name="accroche" id="accroche" cols="45" rows="10" placeholder="Laisser une petite accroche"><?php echo $accroche; ?></textarea>
                <div class="nbChambreEtBainsDiv">
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
                        <input type="number" id="prixParNuit" name="prixParNuit" placeholder="Prix/Nuit" min="0" value=<?php echo $prixParNuit; ?>>
                    </div>
                </div>
    
                <div class="installationsElement">
                    <label for="installDispo!">Installations disponibles</label>
                    <input type="text" id="installDispo" name="installDispo" placeholder="Installation disponible" size="55" value="<?php echo $installations; ?>" >
                </div>
                <button class="addButton" id="btnInstallations" type="button">Ajouter installations disponibles</button>
    
                <div class="equipementsElement">
                    <label for="equipementDispo">Equipements disponibles</label>
                    <input type="text" id="equipement" name="equipement" placeholder="Equipement disponible" size="55" value="<?php echo $equipements; ?>" >
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
                <button class="creerAnnonce" type="submit" id="creerAnnonce">Modifier annonce</button>
        </div>
        </div>
        </form>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php';
        } else{
            echo "pas d'id trouvé";
        }
        ?>
</body>
</html>