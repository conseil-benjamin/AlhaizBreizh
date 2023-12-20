<?php
// supprimer_logement.php
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
$numLogement = $_GET['numLogement'];

$stmt = $pdo->prepare("DELETE FROM ldc.chambre WHERE numLogement=$numLogement");
$stmt->execute(); 
$stmt = $pdo->prepare("DELETE FROM ldc.service WHERE numLogement=$numLogement");
$stmt->execute();
$stmt = $pdo->prepare("DELETE FROM ldc.equipement WHERE numLogement=$numLogement");
$stmt->execute();
$stmt = $pdo->prepare("DELETE FROM ldc.installation WHERE numLogement=$numLogement");
$stmt->execute();
$stmt = $pdo->prepare("DELETE FROM ldc.photoscomplementaireslogement WHERE numLogement=$numLogement");
$stmt->execute();
$stmt = $pdo->prepare("DELETE FROM ldc.reservation WHERE numLogement=$numLogement");
$stmt->execute();
$stmt = $pdo->prepare("DELETE FROM ldc.plagededisponibilite WHERE numcal IN (SELECT numcal FROM ldc.calendrier WHERE numLogement = $numLogement)");
$stmt->execute();
$stmt = $pdo->prepare("DELETE FROM ldc.plageindisponibilite WHERE numcal IN (SELECT numcal FROM ldc.calendrier WHERE numLogement = $numLogement)");
$stmt->execute();
$stmt = $pdo->prepare("DELETE FROM ldc.calendrier WHERE numLogement=$numLogement");
$stmt->execute(); 
$stmt = $pdo->prepare("DELETE FROM ldc.favorisclient WHERE numLogement=$numLogement");
$stmt->execute(); 
$stmt = $pdo->prepare("DELETE FROM ldc.logementproprio WHERE numLogement=$numLogement");
$stmt->execute(); 
$stmt = $pdo->prepare("DELETE FROM ldc.logement WHERE numLogement=$numLogement");
$stmt->execute(); 

$response = array();
$response['message'] = 'Logement supprimé avec succès.';


header('Content-Type: application/json');
echo json_encode($response);
?>
