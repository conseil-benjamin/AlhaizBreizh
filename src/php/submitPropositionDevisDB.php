<?php
$numlogement = $_SESSION["numLogement"];
$dateDevis = new DateTime();
$dateDevis = $dateDevis->format("Y-m-d");
$durreeAcceptation = 300;

global $dbh;
try {
    include('connect.php');
    $stmt = $dbh->prepare(
        "UPDATE ldc.devis
SET
  dateDevis = '$dateDevis'
WHERE numlogement = $numlogement;"
    );
    $stmt->execute();
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "";
    die();
}