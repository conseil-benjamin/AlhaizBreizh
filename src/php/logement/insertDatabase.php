<?php 
    session_start(); 
    error_reporting(E_ALL & ~E_WARNING);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php  

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else{
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = htmlspecialchars(strip_tags($_POST['title']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $description = htmlspecialchars(strip_tags($_POST['description']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $photoCouverture = $_FILES['photos']['name'];
        $typeLogement = htmlspecialchars(strip_tags($_POST['natureLogement']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $surface = htmlspecialchars(strip_tags($_POST['surface']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $natureLogement = htmlspecialchars(strip_tags($_POST['natureLogement']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $photos = $_POST['photos']; // No filtering required for $_POST['photos']
        $lits = isset($_POST['lits']) ? htmlspecialchars(strip_tags($_POST['lits']), ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';
        $adresse = htmlspecialchars(strip_tags($_POST['adresse']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cp = htmlspecialchars(strip_tags($_POST['cdPostal']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $ville = htmlspecialchars(strip_tags($_POST['ville']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $accroche = htmlspecialchars(strip_tags($_POST['accroche']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $nbSalleDeBain = htmlspecialchars(strip_tags($_POST['nbSallesBain']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $nbMaxPers = htmlspecialchars(strip_tags($_POST['nbMaxPers']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $prixParNuit = htmlspecialchars(strip_tags($_POST['prixParNuit']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $nbPersMax = htmlspecialchars(strip_tags($_POST['nbMaxPers']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $proprio = $id; // No filtering required for $id
        $logementEnLigne = 0; // No filtering required for this value
        $installations=[];
    $hollow=$_POST['installDispo'];
    $i=0;
    while (isset($hollow)) {
        if (!($hollow == "")) {
            array_push($installations, htmlspecialchars(strip_tags($hollow), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }
        $i=$i+1;
        $hollow = $_POST['InstallDispo'.$i+1];
        //$hollow= htmlspecialchars(strip_tags($_POST['InstallDispo'.$i+1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

//EQUIPEMENT

    $equipements = [];
    $equipementElement = $_POST['equipement'];
    $j = 0;

    while (isset($equipementElement)) {
        if (!($equipementElement == "")) {
            array_push($equipements, htmlspecialchars(strip_tags($equipementElement), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }
        $j=$j+1;
        $equipementElement= $_POST['equipement'.$i+1];
        //$equipementElement=htmlspecialchars(strip_tags($_POST['equipement'.$i+1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    //SERVICE

    $services=[];
    $prices=[];
    $serviceElement=$_POST['service'];
    $servicePrix=$_POST['prixService'];
    $k=0;

    while (isset($serviceElement)){
        if (!($serviceElement=="")){
            $serviceElement = htmlspecialchars(strip_tags($serviceElement), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            array_push($services, $serviceElement);
            if ($servicePrix==""){
                $servicePrix=0;
            }
            else{
                $servicePrix=floatval($servicePrix);
            }
            array_push($prices, $servicePrix);
        }
        $k=$k+1;
        $serviceElement=$_POST['service'.$k+1];
        //$serviceElement=htmlspecialchars(strip_tags($_POST['service'.$k+1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $servicePrix=$_POST['prixService'.$k+1];
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
        global $pdo;
        require($_SERVER['DOCUMENT_ROOT'].'/src/php/connect.php');
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

            //CHAMBRES
            $numChambre =0;
            foreach ($chambres as $key => $value){
                // Préparer la requête SQL
                $query = "INSERT INTO ldc.Chambre (nbLitsSimples, nbLitsDoubles) VALUES (:nbLitsSimples, :nbLitsDoubles)";
                $statement = $pdo->prepare($query);

                // Lier les paramètres
                $statement->bindParam(':nbLitsSimples', $value[0], PDO::PARAM_INT);
                $statement->bindParam(':nbLitsDoubles', $value[1], PDO::PARAM_INT);

                // Exécuter la requête
                $statement->execute();

                // Obtenir le dernier ID inséré
                $numChambre = $pdo->lastInsertId();

                 $stmtChambre = $pdo->prepare(
                     "INSERT INTO ldc.LogementChambre (numChambre,numlogement) VALUES (?, ?)");
                 $stmtChambre->bindParam(1, $numChambre);
                 $stmtChambre->bindParam(2, $id_logem);
                 print_r($numChambre);
                 print_r($id_logem);
                 $stmtChambre->execute();
            }
            //INSTALLATIONS
            foreach($installations as $key => $value){
                $stmtInstallation = $pdo->prepare(
                    "INSERT INTO ldc.installation (numlogement, numinstall, nom) VALUES (?, ?, ?)"
                );
                $stmtInstallation->bindParam(1, $id_logem);
                $stmtInstallation->bindParam(2, $key);
                $stmtInstallation->bindParam(3, $value);
                $stmtInstallation->execute();
            }
    
            //EQUIPEMENTS
            foreach($equipements as $key => $value){
                $stmtEquipement = $pdo->prepare(
                    "INSERT INTO ldc.equipement (numlogement, numeequip, nom) VALUES (?, ?, ?)"
                );
                $stmtEquipement->bindParam(1, $id_logem);
                $stmtEquipement->bindParam(2, $key);
                $stmtEquipement->bindParam(3, $value);
                $stmtEquipement->execute();
            }

            //SERVICES    
            foreach($services as $key => $value){
                $stmtServcie = $pdo->prepare(
                    "INSERT INTO ldc.service (numlogement, numserv, nom, prix) VALUES (?, ?, ?, ?)"
                );
                $stmtServcie->bindParam(1, $id_logem);
                $stmtServcie->bindParam(2, $key);
                $stmtServcie->bindParam(3, $value);
                $stmtServcie->bindParam(4, $prices[$key]);
                $stmtServcie->execute();
            }


            //Création Calendrier
/*             $stmt=$pdo->prepare("INSERT INTO Calendrier (numCal,numLogement)VALUES($id_logem,$id_logem)");
            $stmt->execute(); */

            if ($stmtChambre->affected_rows < 1) {
                ?>
                <script>
               Swal.fire({
                icon: "success",
                title: "Logement bien créé",
                showConfirmButton: false,
                timer: 2000
            });
               </script>
            <?php   
            } else {
                ?>
                <script>
                Swal.fire({
                title: "Erreur : logement non créé",
                icon: "error",
                });
               </script>
                <?php
            }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$pdo = null;

//Créer un dossier pour les photos du logement
$nom_dossier = $_SERVER['DOCUMENT_ROOT'] . "/public/img/logements/" . $id_logem;
$nbPhotos = count(glob($nom_dossier . "/*.png"));

if (!is_dir($nom_dossier)){
    if (mkdir($nom_dossier)) {
        $url = $nom_dossier . "/" . ($nbPhotos + 1) . ".png";
        move_uploaded_file($_FILES['photos']['tmp_name'], $url);
    }
}

?>
<script>

    //Faire une popup de confirmation
    Swal.fire({
            icon: "success",
            title: "Logement bien créé",
            showConfirmButton: false,
            timer: 2000
        });
/* 
    setTimeout(() => {
         window.location.href = '/src/php/logement/mesLogements.php';
}, 2000); */
</script>
<?php
exit;
}
?>
</body>
</html>

