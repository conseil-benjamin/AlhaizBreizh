<?php
$numlogement = $_SESSION["numLogement"];
$dateDevis = new DateTime();
$dateDevis = $dateDevis->format("Y-m-d");
$durreeAcceptation = 300;

global $pdo;
try {
    include('connect.php');
    $stmt = $pdo->prepare(
        "UPDATE ldc.devis
SET
  dateDevis = '$dateDevis'
WHERE numlogement = $numlogement;"
    );
    $stmt->execute();
    $pdo = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "";
    die();
}