<?php
if(isset($_SESSION)) {
    echo "oui";
}
$numlogement = $_SESSION["numLogement"];
global $pdo;
try {
    include('connect.php');
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare(
        "SELECT * FROM ldc.Devis where numlogement=1 order by numdevis ASC"
    );
    $stmt->execute();
    $result = $stmt->fetchAll();
    $dateDebut = $result[0]["datedebut"];
    $dateFin = $result[0]["datefin"];
    $nbpersonne = $result[0]["nbpersonnes"];
    $demande = $result[0]["demande"];
    $pdo = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}