<?php

//INSERTION DANS LA BASE DE DONNEEE

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_logem=$_SESSION['num_logement'];

    $title = htmlspecialchars(strip_tags($_POST['title']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $description = htmlspecialchars(strip_tags($_POST['description']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $photos = $_POST['photos']; // No filtering required for $_POST['photos']
    $typeLogement = htmlspecialchars(strip_tags($_POST['typeLogement']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $surface = htmlspecialchars(strip_tags($_POST['surface']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $natureLogement = htmlspecialchars(strip_tags($_POST['natureLogement']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $adresse = htmlspecialchars(strip_tags($_POST['adresse']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $cp = htmlspecialchars(strip_tags($_POST['cdPostal']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $ville = htmlspecialchars(strip_tags($_POST['ville']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $accroche = htmlspecialchars(strip_tags($_POST['accroche']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $nbSalleDeBain = htmlspecialchars(strip_tags($_POST['nbSallesBain']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $nbPersMax = htmlspecialchars(strip_tags($_POST['nbMaxPers']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $prixParNuit = htmlspecialchars(strip_tags($_POST['prixParNuit']), ENT_QUOTES | ENT_HTML5, 'UTF-8');

    //INSTALLATIONS

    $installations=[];
    $hollow=$_POST['installDispo1'];
    $i=1;
    while (isset($hollow)){
        if (!($hollow=="")){
            $hollow=htmlspecialchars(strip_tags($hollow), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            array_push($installations, $hollow);
        }
        $i=$i+1;
        $hollow=$_POST['InstallDispo'.$i];
    }
    //EQUIPEMENT

    $equipements=[];
    $equipementElement=$_POST['equipement'];
    $i=1;

    while (isset($equipementElement)){
        if (!($equipementElement=="")){
            $equipementElement=htmlspecialchars(strip_tags($equipementElement), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            array_push($equipements, $equipementElement);
        }
        $i=$i+1;
        $equipementElement=$_POST['equipement'.$i];
    }


    //SERVICE

    $services=[];
    $serviceElement=$_POST['service'];
    $i=1;

    while (isset($serviceElement)){
        if (!($serviceElement=="")){
            $serviceElement=htmlspecialchars(strip_tags($serviceElement), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            array_push($services, $serviceElement);
        }
        $i=$i+1;
        $serviceElement=$_POST['service'.$i];
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
    $erreur=false; //il va verifier si une erreur s'est produite durant la modification d'une des donnees
    
    try {
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("UPDATE ldc.logement SET surfaceHabitable=$surface, libelle='$title',accroche='$accroche',descriptionLogement='$description',natureLogement='$natureLogement',nbpersmax=$nbPersMax,photoCouverture='$photos',nbChambres=$nbChambres,nbSalleDeBain=$nbSalleDeBain,tarifNuitees=$prixParNuit,adresse='$adresse',cp=$cp,ville='$ville' WHERE numlogement=$id_logem");

        $stmt->execute();

        if (!$stmt){
            ECHO "ITS ME";
            $erreur=true;
        }

        //CHAMBRES

        /*
        On compte le nombre de chambres du logement pour savoir si l'utilisateur en a ajoute ou retire
        */
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM ldc.logementchambre WHERE numlogement=$id_logem ");
        $stmt->execute();
        $resultC = $stmt->fetch();

        foreach ($chambres as $key => $value){
            if ($resultC['count']>=$key){ //Si la chambre est deja presente dans la bdd on l'update
                $query = "SELECT numChambre FROM ldc.Chambre WHERE nbLitsDoubles = :nombreDeLitsDoubles AND nblitssimples = :nblitssimples";
                $statement = $pdo->prepare($query);
                $statement->bindParam(':nombreDeLitsDoubles', $value[1], PDO::PARAM_INT);
                $statement->bindParam(':nblitssimples', $value[0], PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $numChambre =  $result['numchambre'];
                } else {
                    $query = "INSERT INTO ldc.Chambre (nbLitsSimples, nbLitsDoubles) VALUES (:nbLitsSimples, :nbLitsDoubles)";

                    $statement = $pdo->prepare($query);

                    $statement->bindParam(':nbLitsSimples', $value[0], PDO::PARAM_INT);
                    $statement->bindParam(':nbLitsDoubles', $value[1], PDO::PARAM_INT);

                    $statement->execute();
                    $numChambre = $pdo->lastInsertId();
                }
                $stmtChambre = $pdo->prepare("UPDATE ldc.LogementChambre SET numChambre = :numChambre WHERE numlogement = $id_logem");
                $stmtChambre->bindParam(':numChambre', $numChambre);
                $stmtChambre->execute();
            }
            else{ //Si le numero de la chambre est plus eleve que le nombre de chambre de la bdd, on l'ajoute
                $query = "SELECT numChambre FROM ldc.Chambre WHERE nbLitsDoubles = :nombreDeLitsDoubles AND nblitssimples = :nblitssimples";
                $statement = $pdo->prepare($query);
                $statement->bindParam(':nombreDeLitsDoubles', $value[1], PDO::PARAM_INT);
                $statement->bindParam(':nblitssimples', $value[0], PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $numChambre =  $result['numChambre'];
                } else {
                    $query = "INSERT INTO ldc.Chambre (nbLitsSimples, nbLitsDoubles) VALUES (:nbLitsSimples, :nbLitsDoubles)";

                    // Préparer la requête
                    $statement = $pdo->prepare($query);

                    // Lier les paramètres
                    $statement->bindParam(':nbLitsSimples', $value[0], PDO::PARAM_INT);
                    $statement->bindParam(':nbLitsDoubles', $value[1], PDO::PARAM_INT);

                    // Exécuter la requête
                    $statement->execute();
                    $numChambre = $statement->fetchColumn();
                }
                $stmtChambre = $pdo->prepare(
                    "INSERT INTO ldc.LogementChambre (numChambre,numlogement) VALUES (?, ?)"
                );
                $stmtChambre->bindParam(1, $numChambre);
                $stmtChambre->bindParam(2, $id_logem);
                $stmtChambre->execute();
            }
            if (!$stmt){
                $erreur=true;
            }
        }
        /*
        Si le nouveau nombre de chambre est inferieur à celui de la bdd, on retire les chambres en trop
        */
        for ($i = $nbChambres+1; $i <= $resultC['count']; $i++) {
            $stmt = $pdo->prepare(
                "DELETE from ldc.logementchambre WHERE numlogement=$id_logem");
            $stmt->execute();
            if (!$stmt){
                $erreur=true;
            }
        }


        //INSTALLATIONS

        /*
        On compte le nombre d'installations du logement pour savoir si l'utilisateur en a ajoute ou retire
        */

        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM ldc.installation WHERE numlogement=$id_logem");
        $stmt->execute();
        $resultI = $stmt->fetch(); //nombre d'installations dans la bdd (ancien nombre d'installation)
        $res=$resultI['count'];
        
        foreach($installations as $key => $value){
            if ($resultI['count']>$key){ //mise a jour des premiers elements
                $stmt = $pdo->prepare(
                    "UPDATE ldc.installation SET nom='$value' WHERE numlogement=$id_logem and numinstall=$key");
                $stmt->execute();
            }
            else{ //ajout des nouveaux elements
                $stmt = $pdo->prepare(
                    "INSERT INTO ldc.installation VALUES ($id_logem, $key, '$value')");
                $stmt->execute();
            }
            if (!$stmt){
                $erreur=true;
            }
        }
        //On enleve les elements en trop
        for ($i = count($installations); $i <= $resultI['count']; $i++) {
            $stmt = $pdo->prepare(
                "DELETE from ldc.installation WHERE numlogement=$id_logem and numinstall=$i");
            $stmt->execute();
            if (!$stmt){
                $erreur=true;
            }
        }

        //EQUIPEMENTS

        /*
        On compte le nombre d'equipements du logement pour savoir si l'utilisateur en a ajoute ou retire
        */
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM ldc.equipement WHERE numlogement=$id_logem");
        $stmt->execute();
        $resultE = $stmt->fetch();

        foreach($equipements as $key => $value){
            if ($resultE['count']>$key){ //mise a jour des premiers elements
                $stmt = $pdo->prepare(
                    "UPDATE ldc.equipement SET nom='$value' WHERE numlogement=$id_logem and numeequip=$key");
                $stmt->execute();
            }
            else{ //ajout des nouveaux elements
                $stmt = $pdo->prepare(
                    "INSERT INTO ldc.equipement VALUES ($id_logem, $key, '$value')");
                $stmt->execute();
            }
            if (!$stmt){
                $erreur=true;
            }
        }
        //On enleve les elements en trop
        for ($i = count($equipements); $i <= $resultE['count']; $i++) {
            $stmt = $pdo->prepare(
                "DELETE from ldc.equipement WHERE numlogement=$id_logem and numeequip=$i");
            $stmt->execute();
            if (!$stmt){
                $erreur=true;
            }
        }


        //SERVICE

        /*
        On compte le nombre de services du logement pour savoir si l'utilisateur en a ajoute ou retire
        */
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM ldc.service WHERE numlogement=$id_logem");
        $stmt->execute();
        $resultS = $stmt->fetch();

   
        foreach($services as $key => $value){
            if ($resultS['count']>$key){ //mise a jour des premiers elements
                $stmt = $pdo->prepare(
                    "UPDATE ldc.service SET nom='$value' WHERE numlogement=$id_logem and numserv=$key");
                $stmt->execute();
            }
            else{ //ajout des nouveaux elements
                $stmt = $pdo->prepare(
                    "INSERT INTO ldc.service VALUES ($id_logem, $key, '$value')");
                $stmt->execute();
            }
            if (!$stmt){
                $erreur=true;
            }
        }
        //On enleve les elements en trop
        for ($i = count($services); $i <= $resultS['count']; $i++) {
            $stmt = $pdo->prepare(
                "DELETE from ldc.service WHERE numlogement=$id_logem and numserv=$i");
            $stmt->execute();
            if (!$stmt){
                $erreur=true;
            }
        }

        if (!$erreur) {
            ?>
            <script>
           Swal.fire({
            icon: "success",
            title: "Logement bien modifié",
            showConfirmButton: false,
            timer: 2000
        });
           </script>
        <?php   
        } else {
            ?>
            <script>
            Swal.fire({
            title: "Erreur : logement non modifié",
            icon: "error",
            });
           </script>
            <?php
        }

    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    $pdo = null;

    ?>

    <script>
    setTimeout(() => {
        window.location.href = '/src/php/logement/mesLogements.php';
    }, 2000);
    </script>
<?php
    exit;
}
    ?>
</body>
</html>