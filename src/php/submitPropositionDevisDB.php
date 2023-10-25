<?php
$id_client = $_SESSION['id_client'];

print_r($_POST);

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
WHERE numDevis = ;"
    );
    $stmt->execute();
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}