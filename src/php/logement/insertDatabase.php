<?php 
    session_start(); 

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
    } else{
        echo "pas d'id trouvé";
    }

     if (isset($_SESSION['nbInstallations'])) {
        $nbInstallations = $_SESSION['nbInstallations'];
        echo $nbInstallations;
    } else{
        echo "pas d'installations trouvé";
    }


    $title = $_GET['title'];
    $description = $_GET['description'];
    $photoCouverture = $_FILES['photos']['name'];
    $typeLogement = $_GET['typeLogement'];
    $surface = $_GET['surface'];
    $natureLogement = $_GET['natureLogement'];
    $services = $_GET['services'];
    $lits = $_GET['lits'];
    $adresse = $_GET['adresse'];
    $cp = $_GET['cdPostal'];
    $ville = $_GET['ville'];
    $accroche = $_GET['accroche'];
    $nbChambres = $_GET['nbChambres'];
    $nbSalleDeBain = $_GET['nbSallesBain'];
    $nbMaxPers = $_GET['nbMaxPers'];
    $prixParNuit = $_GET['prixParNuit'];
    $installationsDispo = $_GET['installDispo'];
    $equipementsDispo = $_GET['equipementDispo'];
    $nbPersMax = $_GET['nbMaxPers'];
    $proprio = 1;
    $logementEnLigne = 1;

    try {
        $pdo = require($_SERVER['DOCUMENT_ROOT'].'/src/php/connect.php');

        $stmt = $pdo->prepare("INSERT INTO ldc.Logement (surfaceHabitable, libelle, accroche, descriptionLogement, natureLogement, adresse, cp, ville ,proprio, photoCouverture, LogementEnLigne, nbPersMax, nbChambres, nbSalleDeBain, tarifNuitees) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bindParam(1, $surface);
            $stmt->bindParam(2, $title);
            $stmt->bindParam(3, $accroche);
            $stmt->bindParam(4, $description);
            $stmt->bindParam(5, $natureLogement);
            $stmt->bindParam(6, $adresse);
            $stmt->bindParam(7, $cp);
            $stmt->bindParam(8, $ville);
            $stmt->bindParam(9, $proprio);
            $stmt->bindParam(10, $photoCouverture);
            $stmt->bindParam(11, $logementEnLigne);
            $stmt->bindParam(12, $nbPersMax);
            $stmt->bindParam(13, $nbChambres);
            $stmt->bindParam(14, $nbSalleDeBain);
            $stmt->bindParam(15, $prixParNuit);
            
            $stmt->execute();

            //Ajouter les services

    $last_inserted_id = $pdo->lastInsertId();

    if ($last_inserted_id) {
        echo "<br>";
        echo $last_inserted_id;
    }
    else {
        echo "rien";
    }
    $nom_dossier = $_SERVER['DOCUMENT_ROOT'] . "/public/img/logements/" . $last_inserted_id;

    if (!is_dir($nom_dossier)) { // Vérifie si le dossier n'existe pas déjà
        if (mkdir($nom_dossier)) { // Crée le dossier
            foreach ($_FILES["photos"]["error"] as $key => $error) {
                $i = 1;
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["photos"]["tmp_name"][$key];
                        $fichiers = scandir($$nom_dossier);
                        foreach ($fichiers as $fichier) {
                            if (!is_dir($nom_dossier . "/" . $fichier)) {
                                // Le fichier est un fichier, pas un dossier
                                if (file_exists($i)) {
                                    echo "Le fichier $fichier existe.";
                                } else {
                                    echo "Le fichier $fichier n'existe pas.";
                                    move_uploaded_file($tmp_name, "$nom_dossier/$i");
                                }
                                }
                                echo $fichier . "<br>";
                            }
                        }
                        closedir($handle);
                    }
            }
        } else {
            echo "erreur";
        }


} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

    
// créé un Dossier au nom du dernier logement créé
// ajouter les images dans ce dossier ci
// pareil pour service et tout le reste


$pdo = null;
//header('Location: /src/php/logement/mesLogements.php');
?>