<?php session_start(); 

    $title = $_POST['title'];
    $description = $_POST['description'];
    $photos = $_POST['photos'];
    $typeLogement = $_POST['typeLogement'];
    $surface = $_POST['surface'];
    $natureLogement = $_POST['natureLogement'];
    $services = $_POST['services'];
    $lits = $_POST['lits'];
    $adresse = $_POST['adresse'];
    $cdPostal = $_POST['cdPostal'];
    $ville = $_POST['ville'];
    $accroche = $_POST['accroche'];
    $nbChambres = $_POST['nbChambres'];
    $nbSalleBain = $_POST['nbSallesBain'];
    $nbMaxPers = $_POST['nbMaxPers'];
    $prixParNuit = $_POST['prixParNuit'];
    $installationsDispo = $_POST['installDispo'];
    $equipementsDispo = $_POST['equipementDispo'];
    
    echo "Titre : $title";
    echo "<br>";
    echo "Description : $description";
    echo "<br>";
    echo "Photos : $photos";
    echo "<br>";
    echo "Type de logement : $typeLogement";
    echo "<br>";
    echo "Surface : $surface";
    echo "<br>";
    echo "Nature du logement : $natureLogement";
    echo "<br>";
    echo "Services : $services";
    echo "<br>";
    echo "Nombre de lits : $lits";
    echo "<br>";
    echo "Adresse : $adresse";
    echo "<br>";
    echo "Code postal : $cdPostal";
    echo "<br>";
    echo "Ville : $ville";
    echo "<br>";
    echo "Accroche : $accroche";
    echo "<br>";
    echo "Nombre de chambres : $nbChambres";
    echo "<br>";
    echo "Nombre de salles de bain : $nbSalleBain";
    echo "<br>";
    echo "Nombre maximum de personnes : $nbMaxPers";
    echo "<br>";
    echo "Prix par nuit : $prixParNuit";
    echo "<br>";
    echo "Installations disponibles : $installationsDispo";
    echo "<br>";
    echo "Équipements disponibles : $equipementsDispo";
    

    try {
        $pdo = new PDO("pgsql:host=servbdd;port=5432;dbname=pg_bconseil;user=bconseil;password=Anneso73!");

        $stmt2 = $pdo->prepare("INSERT INTO ldc.Localisation (rue, cp, ville) VALUES (?, ?,?)");

        $stmt->bindParam(1, ");
        $stmt->bindParam(2, $libelle);
        $stmt->bindParam(3, $accroche);
    
        $stmt = $pdo->prepare("INSERT INTO ldc.Logement (surfaceHabitable, libelle, accroche, descriptionLogement, natureLogement, proprio, photoCouverture, LogementEnLigne, nbPersMax, nbChambres, detailsLitsDispos, nbSalleDeBain, tarifNuitees) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bindParam(1, $surfaceHabitable);
            $stmt->bindParam(2, $libelle);
            $stmt->bindParam(3, $accroche);
            $stmt->bindParam(4, $description);
            $stmt->bindParam(5, $natureLogement);
            $stmt->bindParam(6, $proprio);
            $stmt->bindParam(7, $photoCouverture);
            $stmt->bindParam(8, $LogementEnLigne);
            $stmt->bindParam(9, $nbPersMax);
            $stmt->bindParam(10, $nbChambres);
            $stmt->bindParam(11, $detailsLitsDispos);
            $stmt->bindParam(12, $nbSalleDeBain);
            $stmt->bindParam(13, $tarifNuitees);
            
            $stmt->execute();
            echo "logement crée avec succès ";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
$pdo = null; 
?>