<?php
$id_client = $_SESSION['id_client'];

print_r($_POST);

$dateDevis = new DateTime();
$durreeAcceptation = 300;

global $dbh;
try {
    include('connect.php');
    $stmt = $dbh->prepare(
        "SELECT tarifNuitees FROM Tarification"
    );
    $stmt->execute();
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}