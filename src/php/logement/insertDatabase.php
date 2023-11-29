<?php 
session_start();
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    echo $id;
} else{
    echo "pas d'id trouvé";
}

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $photoCouverture = $_FILES['photos']['name'];
    $typeLogement = $_POST['typeLogement'];
    $surface = $_POST['surface'];
    $natureLogement = $_POST['natureLogement'];
    $photos = $_POST['photos'];
    $lits = $_POST['lits'];
    $adresse = $_POST['adresse'];
    $cp = $_POST['cdPostal'];
    $ville = $_POST['ville'];
    $accroche = $_POST['accroche'];
    $nbSalleDeBain = $_POST['nbSallesBain'];
    $nbMaxPers = $_POST['nbMaxPers'];
    $prixParNuit = $_POST['prixParNuit'];
    $nbPersMax = $_POST['nbMaxPers'];
    $proprio = $id;
    $logementEnLigne = 1;



    $installations=[];
    $installElement=$_POST['installDispo'];
    $i=0;
    while (isset($installElement)){
        $installations[$i]=$installElement;
        $i=$i+1;
        $installElement=$_POST['InstallDispo'.$i+1];
    }

    //EQUIPEMENT

    $equipements=[];
    $equipementElement=$_POST['equipement'];
    $x=0;
    while (isset($equipementElement)){
        $equipements[$i]=$equipementElement;
        $x=$x+1;
        $equipementElement=$_POST['equipement'.$x+1];
    }

    //SERVICE

    $services=[];
    $serviceElement=$_POST['service'];
    $j=0;
    while (isset($serviceElement)){
        $services[$i]=$serviceElement;
        $j=$j+1;
        $serviceElement=$_POST['service'.$j+1];
    }

    //CHAMBRES

    $chambres=[];
    $vessel=$_POST['1lits0'];
    $i=1;
    while (isset($vessel)){
        if ($vessel=='Lit simple (90 * 190)'){
            $chambres[$i][0]=$chambres[$i][0]+1;
            $chambres[$i][1]=0;
        }
        else{
            $chambres[$i][1]=$chambres[$i][1]+1;
            $chambres[$i][0]=0;
        }
        $j=1;
        $vessel=$_POST[$i."lits".$j];
        while (isset($vessel)){
            if ($vessel=='Lit simple (90 * 190)'){
                $chambres[$i][0]=$chambres[$i][0]+1;
            }
            else{
                $chambres[$i][1]=$chambres[$i][1]+1;
            }
            $j=$j+1;
            $vessel=$_POST[$i.'lits'.$j];
        }
        $i=$i+1;
        $j=0;
        $vessel=$_POST[$i.'lits'.$j];
    }
    $nbChambres=count($chambres);

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

            $id_logem = $pdo->lastInsertId();

            foreach ($chambres as $key => $value){
                $stmt = $pdo->prepare(
                    "INSERT INTO ldc.chambre VALUES ($key, $value[0], $value[1], $id_logem)"
                );
                $stmt->execute();
            }
            
            foreach($installations as $key => $value){
                $stmt = $pdo->prepare(
                    "INSERT INTO ldc.installation VALUES ($id_logem, $key, '$value')"
                );
                $stmt->execute();
            }
    
            foreach($equipements as $key => $value){
                $stmt = $pdo->prepare(
                    "INSERT INTO ldc.equipement VALUES ($id_logem, $key, '$value')"
                );
                $stmt->execute();
            }
    
            foreach($services as $key => $value){
                $stmt = $pdo->prepare(
                    "INSERT INTO ldc.service VALUES ($id_logem, $key, '$value')"
                );
                $stmt->execute();
            }

    $nom_dossier = $_SERVER['DOCUMENT_ROOT'] . "/public/img/logements/" . $id_logem;


if (!is_dir($nom_dossier)) {
    if (mkdir($nom_dossier)) {
        echo "crée dossier";
        // Vérifie si des fichiers ont été envoyés
        if (!empty($_FILES['photos']['name'][0])) {
            echo "fichier présent";
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
    }
//header('Location: /src/php/logement/mesLogements.php');
?>