<?php

// Verifie que la session est bien initialisée
if(isset($_SESSION)) {
    $id_client = $_SESSION['id_client'];
    $numLogement = $_SESSION['num_logement'];
}
else {
    $id_client = 1;
    $numLogement = 1;
}

// Verifie si le formulaire est bien soumis
if (isset($_POST['nb_personne'])) {
    $dateArr = new DateTime($_POST['date_arrivee']);
    $sqlDateArr = $dateArr->format('Y-m-d');
    $dateDep = new DateTime($_POST['date_depart']);
    $sqlDateDep = $dateDep->format('Y-m-d');
    $nb_personne = $_POST['nb_personne'];
    $demande = $_POST['demande'];
}
else {
    $sqlDateArr = new DateTime("2023-10-24");
    $sqlDateArr = $sqlDateArr->format('Y-m-d');
    $sqlDateDep = new DateTime("2023-10-24");
    $sqlDateDep = $sqlDateDep->format('Y-m-d');
    $nb_personne = 4;
    $demande = "Un petit dejeuner au lit";
}


$numReservation = time();

$optionAnnulation = "";
$dateValid = "";

$EPOCH ="2000-01-01";

try {
    include('connect.php');
    $stmt = $pdo->prepare(
        "INSERT INTO ldc.devis(nbPersonnes, numReservation, numLogement, dateDebut, dateFin, dateDevis, dateValid, optionAnnulation, dureeDelaisAcceptation,demande) 
VALUES('$nb_personne','$numReservation','$numLogement','$sqlDateArr','$sqlDateDep','$EPOCH','$EPOCH','\"\"','0','$demande')"
    );
    $stmt->execute();
    $pdo = null;
} catch (PDOException $e) {
    print "Erreur une !: " . $e->getMessage();
    die();
}