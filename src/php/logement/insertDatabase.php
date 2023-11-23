<?php session_start(); 

    if (isset($_SESSION['nbInstallations'])) {
        $id = 'id';
    } else{
        echo "pas d'id trouvé";
    }

     if (isset($_SESSION['nbInstallations'])) {
        $nbInstallations = 'nbInstallations';
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
    $detailsLitsDispos = $_GET[''];
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
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$last_inserted_id = $pdo->lastInsertId();
echo $last_inserted_id;
/*

                $nom_dossier = $_SERVER['DOCUMENT_ROOT'] . "/public/img/logements/" . 5;

                if (!is_dir($nom_dossier)) { // Vérifie si le dossier n'existe pas déjà
                    if (mkdir($nom_dossier)) { // Crée le dossier
                        echo "Le dossier $nom_dossier a été créé avec succès.";
                    } else {
                        echo "Erreur : Impossible de créer le dossier $nom_dossier.";
                    }
                } else {
                    echo "Le dossier $nom_dossier existe déjà.";
                }
*/

// créé un Dossier au nom du dernier logement créé
// ajouter les images dans ce dossier ci
// pareil pour service et tout le reste


$pdo = null;
header('Location: /src/php/logement/mesLogements.php');
?>