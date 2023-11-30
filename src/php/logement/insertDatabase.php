<?php 
    session_start(); 

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        echo "Id : " . $id . "<br>";
    } else{
        echo "pas d'id trouvé";
    }

    if(isset($_POST['nbChambres'])) {
        $nbChambres = $_POST['nbChambres'];
        echo $nbChambres;
    } else {
        echo "pas de nombre de chambres";
    }

    /* 
     if (isset($_SESSION['nbInstallations'])) {
        $nbInstallations = $_SESSION['nbInstallations'];
        echo $nbInstallations;
    } else{
        echo "pas d'installations trouvé";
    }
    */


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
    $nbSalleDeBain = $_GET['nbSallesBain'];
    $nbMaxPers = $_GET['nbMaxPers'];
    $prixParNuit = $_GET['prixParNuit'];
    $installationsDispo = $_GET['installDispo'];
    $equipementsDispo = $_GET['equipementDispo'];
    $nbPersMax = $_GET['nbMaxPers'];
    $proprio = $id;
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

    $idLogement = $pdo->lastInsertId();

    if ($idLogement) {
        echo "<br>";
        echo $idLogement;
    }
    else {
        echo "rien";
    }
    $nom_dossier = $_SERVER['DOCUMENT_ROOT'] . "/public/img/logements/" . $idLogement;


    // marche pas 
    $photos = $_FILES['photos'];

    // Parcourir chaque fichier
    foreach ($photos['tmp_name'] as $key => $tmp_name) {
        $file_name = $photos['name'][$key];
        $file_tmp = $photos['tmp_name'][$key];
        // Traitement des fichiers ou enregistrement dans un dossier
    }

if (!is_dir($nom_dossier)) {
    if (mkdir($nom_dossier)) {
        // Vérifie si des fichiers ont été envoyés
        if (!empty($_FILES['photos']['name'][0])) {
            // Boucle pour traiter chaque fichier envoyé
            foreach ($_FILES['photos']['name'] as $key => $name) {
                echo "dada";
                $tmp_name = $_FILES['photos']['tmp_name'][$key];    
                $fichierURL = $nom_dossier . "/" . $name;
                // Vérifier si le fichier existe déjà dans le dossier
                if (file_exists($fichierURL)) {
                    echo "Le fichier existe déjà : " . $name . "<br>";
                } else {
                    // Déplacer le fichier téléchargé dans le dossier
                    if (move_uploaded_file($tmp_name, $fichierURL)) {
                        echo "Fichier téléchargé avec succès : " . $name . "<br>";
                    } else {
                        echo "Erreur lors du téléchargement du fichier : " . $name . "<br>";
                    }
                }
            }
        } else {
            echo "Aucun fichier envoyé.";
        }
    } else {
        echo "Erreur lors de la création du dossier.";
    }
} else {
    echo "Le dossier existe déjà.";
}

    
    


} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$pdo = null;
//header('Location: /src/php/logement/mesLogements.php');
?>