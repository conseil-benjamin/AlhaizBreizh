<?php
global $prixNuit;
// Vérifie que la session est bien initialisée
if(isset($_SESSION)) {
    $id_client = $_SESSION['id'];
    $numLogement = $_SESSION['num_logement'];
}
else {
    $id_client = 1;
    $numLogement = 1;
}

// Vérifie si le formulaire est bien soumis
if (isset($_POST['nb_personne'])) {
    try {
        $dateArr = new DateTime($_POST['date_arrivee']);
    } catch (Exception $e) {
        die();
    }
    $sqlDateArr = $dateArr->format('Y-m-d');
    try {
        $dateDep = new DateTime($_POST['date_depart']);
    } catch (Exception $e) {
        die();
    }
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
    $demande = "Un petit déjeuner au lit";
}


$AUJ = new DateTime();
$AUJ = $sqlDateArr->format('Y-m-d');

$diffEnJours = $dateDep->diff($dateArr)->days;

$optionAnnulation = "";
$dateValid = "";

$EPOCH ="2000-01-01";
echo '<script src="../../js/devis.js"></script>';
try {
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $insertDevis = $pdo->prepare(
        "INSERT INTO ldc.Devis(nbPersonnes, numReservation, numLogement, dateDebut, dateFin, dateDevis, dateValid, optionAnnulation, dureeDelaisAcceptation) 
        VALUES(:nb_personne, :numReservation, :numLogement, :sqlDateArr, :sqlDateDep, :EPOCH, :EPOCH, :optionAnnulation, :dureeDelaisAcceptation)"
    );
    $insertResa = $pdo->prepare(
        "INSERT INTO ldc.reservation(numclient, numlogement, datereservation, nbpersonnes, datedebut, datefin, datedevis, nbjours, optionannulation) 
        VALUES (:numClient,:numLogement,:dateResa,:nbPersonne,:sqlDateArr, :sqlDateDep, :EPOCH,:nbJour,'')"
    );
    $insertTarif = $pdo->prepare(
        "INSERT INTO ldc.tarification(numdevis, tarifnuitees, chargesht, soustotalht, soustotalttc, fraisserviceplateformeht, fraisserviceplateformettc, taxesejour, total) 
VALUES (:numDevis,:prixNuit,80, 560, 600, 40, 48, 12,:total) "
    );
    $insertResa->execute([
        "numClient" => $id_client,
        "numLogement" => $numLogement,
        "EPOCH" => $EPOCH,
        "dateResa" => $AUJ,
        "nbPersonne" => $nb_personne,
        "sqlDateArr" => $sqlDateArr,
        "sqlDateDep" => $sqlDateDep,
        "nbJour" => $diffEnJours
    ]);
    $numReservation = $pdo->lastInsertId();
    $insertDevis->execute([
        'nb_personne' => $nb_personne,
        'numReservation' => $numReservation,
        'numLogement' => $numLogement,
        'sqlDateArr' => $sqlDateArr,
        'sqlDateDep' => $sqlDateDep,
        'EPOCH' => $EPOCH,
        'optionAnnulation' => $optionAnnulation,
        'dureeDelaisAcceptation' => 14
    ]);
    $numDevis = $pdo->lastInsertId();
    $insertTarif->execute([
        'numDevis' => $numDevis,
        'prixNuit' => $prixNuit,
        'total' => $_POST["total"]
    ]);

    // Services
    $sql = "SELECT numServ FROM ldc.Service WHERE numlogement = $numLogement";
    $insertDevis = $pdo->prepare($sql);
    $insertDevis->execute();

    // Fetch all rows
    $result = $insertDevis->fetchAll(PDO::FETCH_ASSOC);

    // Output data
    foreach ($result as $row) {
        $numServ = $row['numserv'];
        $sql = "INSERT INTO ldc.Devis_Services (numDevis, numLogement, numServ) VALUES ($numDevis, $numLogement, $numServ)";
        $insertDevis = $pdo->prepare($sql);
        $insertDevis->execute();
    }

    $pdo = null;
    echo '<script>
        notifSuccess()
    </script>';
} catch (PDOException $e) {
    print "Erreur submitDB !: " . $e->getMessage();
    echo '<script>
       notifErr()
    </script>';
    die();
}
catch (Error $e) {
    echo '<script>
        notifErr()
    </script>';
    die();
}