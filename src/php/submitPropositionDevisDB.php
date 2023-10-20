<?php
$id_client = $_SESSION['id_client'];

print_r($_POST);


$dateArr = new DateTime($_POST['date_arrivee']);
$sqlDateArr = $dateArr->format('Y-m-d');
$dateArr = new DateTime($_POST['date_depart']);
$sqlDateArr = $dateArr->format('Y-m-d');
$nb_personne = $_POST['nb_personne'];

$num_devis = time();

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