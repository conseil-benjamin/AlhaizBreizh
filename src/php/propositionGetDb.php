<?php
$numlogement = $_SESSION["id_logement"];
global $dbh;
try {
    include('connect.php');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $stmt = $dbh->prepare(
        "SELECT * FROM ldc.Devis where numlogement=1"
    );
    $stmt->execute();
    $result = $stmt->fetchAll();
    $dateDebut = $result[0]["datedebut"];
    $dateFin = $result[0]["datefin"];
    $nbpersonne = $result[0]["nbpersonnes"];
    $demande = $result[0]["demande"];
    print_r($result);
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}