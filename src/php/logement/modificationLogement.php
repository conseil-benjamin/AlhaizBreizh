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
    <link rel="icon" href="/public/logos/logo-black.svg">


    <title>Modification Logement</title>
</head>
<body>
    <?php
        include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php');
        if (isset($_SESSION['num_logement']) || (isset($_GET['numLogement']) && !empty($_GET['numLogement']))) {
            try {
                if (!empty($_GET['numLogement'])) {
                    $numLogement=$_GET['numLogement'];
                }else {
                    $numLogement=$_SESSION['num_logement'];
                }
                
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
        <form id="myForm" method="post" action="update_logement_bdd.php">
        <div class="main">
            <div class="titre">
                <h1>Modifier un logement</h1>
            </div>
            <div class="container-main">
                <div class="container-left">
                    <label for="title" >Titre de l'annonce (*)</label>
                    <input class="textfield" type="text" id="title" name="title" size="60" placeholder="Titre" maxlength="100" value="<?php echo $title; ?>" >
                    
                    <label for="description">Description de l'annonce (*)</label>
                    <textarea class="textfield" name="description" id="description" cols="46" rows="20" placeholder="Description" maxlength="500" required><?php echo $description; ?></textarea>
                    
                    <label for="photos" class="boutton">Ajouter photos</label>
                    <input class="textfield" type="file" id="photos" name="photos" accept=".jpg, .jpeg, .png" multiple onchange="afficherNomsPhotos()">
                    <div id="photosName"></div>
        
                    <div class="typeLogementDiv">
                        <div>
                            <label for="typeLogement">Type de logement (*)</label>
                            <select class="textfield" id="typeLogement" name="typeLogement">
        
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
                            <input class="textfield" type="number" id="surface" name="surface" maxlength="4" placeholder="Surface" min="1" value=<?php echo $surface; ?>>
                        </div>
                    </div>
        
                    <label for="natureLogement">Nature du logement (*)</label>
                    <input class="textfield" type="text" id="natureLogementInput" name="natureLogement" size="60" placeholder="Nature du logement" maxlength="50" value="<?php echo $natureLogement; ?>">
                    
                    <div class="servicesElement">
                        <label for="services">Services disponibles</label>
                        <input class="textfield" type="text" id="service" name="service" placeholder="Service disponible" size="60" maxlength="100" value="<?php echo $services; ?>" >
                    </div>
                    <button class="addButton boutton" id="btnServices" type="button">Ajouter services disponibles</button>
                    <div class="chambresElement" id="chambresElement" value=<?php echo $resting?>>
                        <div class="litsElement" id="Chambre0">
                            <label for="lits">Chambre 1</label>
                            <select class="textfield" name="1lits0">
                            <option id="option2">Lit double (140 * 190)</option>
                            <option id="option1">Lit simple (90 * 190)</option>
                            </select>
                        </div>
                        <button class="addButton boutton" id="btnAddLits" type="button">Ajouter un lit</button>    
                    </div>
                    <button class="addChambre boutton" id="btnAddChambre" type="button">Ajouter une chambre</button>
                </div>
                
                <div class="container-right">
                    <label for="adresse">Adresse (*)</label>
                    <input class="textfield" type="text" id="adresse" name="adresse" size="60" placeholder="Numéro et nom de la adresse" value="<?php echo $adresse; ?>">
                    <div class="villeDiv">
                        <div>
                            <label for="cdPostal">Code Postal (*)</label>
                            <input class="textfield" type="text" id="cdPostal" name="cdPostal" placeholder="Code Postal" maxlength="5" value="<?php echo $cp; ?>">
                            <span id="testValeurInputCdPostal"></span>
                        </div>
                        <div>
                            <label for="ville">Ville (*)</label>
                            <input class="textfield" type="text" id="ville" name="ville" placeholder="Ville" value="<?php echo $ville; ?>">
                        </div>
                    </div>
                    <label for="accroche">Phrase d'accroche</label>
                    <textarea class="textfield" name="accroche" id="accroche" cols="45" rows="10" placeholder="Laisser une petite accroche"><?php echo $accroche; ?></textarea>
                    <div class="nbPrixEtPersonnesDiv">
                        <div class="div-label-nbPers">
                            <label for="nbMaxPers">Nombre de personnes max (*)</label>
                            <input class="textfield" type="number" id="nbMaxPers" name="nbMaxPers" placeholder="Nb pers max" min="1" value="<?php echo $nbPersMax ?>">
                        </div>
                        <div class="div-prix-nuit"> 
                            <label for="prixParNuit">Prix de base par nuit (*)</label>
                            <input class="textfield" type="number" id="prixParNuit" name="prixParNuit" placeholder="Prix/Nuit" min="1" value="<?php echo $prixParNuit ?>">
                        </div>
                    </div>   
                    <div class="div-nbSalleBain">
                        <label for="nbSalleBain">Nombres de salles de bain (*)</label>
                        <input class="textfield" type="number" id="nbSallesBain" name="nbSallesBain" min="1" placeholder="Nb Salles de Bain" value="<?php echo $nbSalleDeBain ?>">
                    </div>
        
                    <div class="installationsElement">
                        <label for="installDispo!">Installations disponibles</label>
                        <input class="textfield" type="text" id="installDispo" name="installDispo" placeholder="Installation disponible" size="55" value="<?php echo $installations; ?>" >
                    </div>
                    <button class="addButton boutton" id="btnInstallations" type="button">Ajouter installations disponibles</button>
        
                    <div class="equipementsElement">
                        <label for="equipementDispo">Equipements disponibles</label>
                        <input class="textfield" type="text" id="equipement" name="equipement" placeholder="Equipement disponible" size="55" value="<?php echo $equipements; ?>" >
                    </div>
                    <button class="addButton boutton" id="btnAddEquipements" type="button">Ajouter Equipements disponibles</button>
                </div>
                <div class="bottom">
                    <p>Les champs marqués par (*) sont obligatoire</p>
                    <span class="conditionsGenerale">J'ai lu et j'accepte les <a href="">Conditions Générales d'Utilisation</a>, la Politique des données personnelles et les Conditions Générales de Ventes d’Alhaiz Breizh (*)</span>
                    <input class="textfield" type="checkbox" name="conditionsGenerale" id="conditionsGenerale">
                    <button class="creerAnnonce boutton" type="submit" id="creerAnnonce">Modifier annonce</button>
                </div>
            </div>
        </div>
        </form>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php';
        } else{?>
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
                        <a class="lien" href="/">
                            <div>
                                Cette page est inexistante. Cliquez ici pour retourner à l'accueil.
                            </div>
                        </a>
                    </div>
                </div>
        <?php }
        ?>
</body>
</html>
