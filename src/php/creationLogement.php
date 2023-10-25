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
            $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=root");
        
            // Préparez la requête SQL pour récupérer les données de la table "Client"
            $query = "SELECT * FROM ldc.Client";
        
            // Exécutez la requête
            $result = $pdo->query($query);
        
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                // Accédez aux colonnes de la table "Client"
                $idCompte = $row['idCompte'];
                $firstName = $row['firstName'];
                $lastName = $row['lastName'];
                $mail = $row['mail'];
                $numeroTel = $row['numeroTel'];
                $photoProfil = $row['photoProfil'];
                $civilite = $row['civilite'];
                $adressePostale = $row['adressePostale'];
                $pseudoCompte = $row['pseudoCompte'];
                $motDePasse = $row['motDePasse'];
                $dateNaissance = $row['dateNaissance'];
                $notationMoyenne = $row['notationMoyenne'];
            }

            echo "ID du Compte : $idCompte<br>";
            echo "Prénom : $firstName<br>";
            echo "Nom : $lastName<br>";
            echo "Email : $mail<br>";
            echo "Numéro de téléphone : $numeroTel<br>";
            echo "Photo de profil : $photoProfil<br>";
            echo "Civilité : $civilite<br>";
            echo "Adresse postale : $adressePostale<br>";
            echo "Pseudo du Compte : $pseudoCompte<br>";
            echo "Mot de passe : $motDePasse<br>";
            echo "Date de naissance : $dateNaissance<br>";
            echo "Notation moyenne : $notationMoyenne<br>";
        
            $pdo = null;
        } catch (PDOException $e) {
            // Gérez les erreurs de connexion ou d'exécution de la requête
            echo "Erreur : " . $e->getMessage();
        }
        
    ?>
    <h1>Création d’un nouveau logement</h1>
    <hr>
    <div class="container-main">
        <div class="container-left">
            <label for="title">Titre de l'annonce (*)</label>
            <input type="text" id="title" name="title" size="60" placeholder="Titre" maxlength="100">
            <label for="description">Description de l'annonce (*)</label>
            <textarea name="description" id="description" cols="46" rows="20" placeholder="Description" maxlength="500"></textarea>
            <label for="photos">Photos (*)</label>
            <input type="file" id="photos" name="photos" multiple>
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
                    <input type="number" id="surface" name="surface" maxlength="4" placeholder="Surface">
                </div>
            </div>
            <label for="natureLogement">Nature du logement (*)</label>
            <input type="text" id="natureLogementInput" size="60" placeholder="Nature du logement" maxlength="50">
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
                    <input type="text" id="ville" name="ville" placeholder="Ville">
                </div>
            </div>
            <label for="accroche">Phrase d'accroche</label>
            <textarea name="accroche" id="accroche" cols="45" rows="10" placeholder="Laisser une petite accroche"></textarea>
            <div class="nbChambreEtBainsDiv">
                <div>
                    <label for="nbChambres">Nombres de chambres (*)</label> 
                    <input type="number" id="nbChambres" name="nbChambres" placeholder="Nb Chambres">
                </div>
                <div>
                    <label for="nbSalleBain">Nombres de salles de bain (*)</label>
                    <input type="number" id="nbSallesBain" name="nbSallesBain" placeholder="Nb Salles de Bain">
                </div>
            </div>
            <div class="nbPrixEtPersonnesDiv">
                <div>
                    <label for="nbMaxPers">Nombre de personnes max (*)</label>
                    <input type="number" id="nbMaxPers" name="nbMaxPers" placeholder="Nb pers max">
                </div>
                <div>
                    <label for="prixParNuit">Prix de base par nuit (*)</label>
                    <br>
                    <input type="number" id="prixParNuit" name="prixParNuit" placeholder="Prix/Nuit">
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
        <button class="creerAnnonce" type="submit" id="creerAnnonce">Créer annonce</button>
    </div>
    </div>
    <?php include './footer.php'; ?>
</body>
</html>