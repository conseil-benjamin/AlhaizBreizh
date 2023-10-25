<?php
$id_client = $_SESSION['id_client'];
$numlogement = $_SESSION["numLogement"];

print_r($_SESSION);

$dateDevis = new DateTime();
$dateDevis = $dateDevis->format("Y-m-d");
$durreeAcceptation = 300;

global $dbh;
try {
    include('connect.php');
    $stmt = $dbh->prepare(
        "UPDATE ldc.devis
SET
  dateDevis = '$dateDevis',
WHERE numlogement = $numlogement ;"
    );
    $stmt->execute();
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "";
    die();
}