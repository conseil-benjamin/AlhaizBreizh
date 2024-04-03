BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN
<?php
$tabIdLog = array();

if (!isset($_GET["token"])) {
    exit();
}
$token = $_GET["token"];

$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
$stmtToken = $pdo->prepare("SELECT * FROM ldc.tokenical WHERE token='$token'");
$stmtToken->execute();
$tokenData = $stmtToken->fetch(PDO::FETCH_ASSOC);
try {
    $stmtTokenLog = $pdo->prepare("SELECT num_logement FROM ldc.logements_tokenical WHERE token='$token'");
    $stmtTokenLog->execute();
    $tokenLogData = $stmtTokenLog->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $tokenLogData = array();
}
if (sizeof($tokenLogData) > 0) {
   foreach ($tokenLogData as $rowData) {
        $tabIdLog[] = $rowData["num_logement"];
   }
}
/*
else {
    die();
}*/

/*******/


// Connexion à la base de données

foreach ($tabIdLog as $idLog) {
$stmtCalendrier = $pdo->prepare("SELECT numcal,statutdispo FROM ldc.Calendrier WHERE numLogement = $idLog");
$stmtCalendrier->execute();

while($calendrierData = $stmtCalendrier->fetch(PDO::FETCH_NUM)){
    $numCal = $calendrierData[0] ?? null;
    $statutDispo = $calendrierData[1] ?? null;
}

try {
    $dateDeb = new DateTime($tokenData['date_debut']);
} catch (Exception $e) {
    var_dump($e);
    die();
}
$sqlDatedeb = $dateDeb->format('Y-m-d');
try {
    $dateFin = new DateTime($tokenData['date_fin']);
} catch (Exception $e) {
    var_dump($e);
    die();
}
$sqlDateFin = $dateFin->format('Y-m-d');

$stmtPlagesIndispo = $pdo->prepare("SELECT * FROM ldc.Plage WHERE numCal = $numCal AND (isIndispo = true OR tarifjournalier = 0) AND datedebutplage >= '$sqlDatedeb' AND datefinplage <= '$sqlDateFin'");
$stmtPlagesIndispo->execute();
$plagesIndisponibilite = $stmtPlagesIndispo->fetchAll(PDO::FETCH_ASSOC);
$evenementsI = [];
foreach ($plagesIndisponibilite as $plage) {
    if (isset($plage['datedebutplage'], $plage['datefinplage'])) {
        $evenementI = [
            'id' => $plage['numplage'],
            'title' => 'Indisponible',
            'start' => $plage['datedebutplage'],
            'end' => $plage['datefinplage'],
        ];
        $evenementsI[] = $evenementI;
    }
}
foreach ($evenementsI as $event) {
?>
BEGIN:VEVENT
X-WR-TIMEZONE:Europe/Paris
DTSTART:<?=date('Ymd',strtotime($event['start']))."T".date('His',strtotime($event['start']))."Z\n" ?>
DTEND:<?= date('Ymd', strtotime($event['end']))."T".date('His', strtotime( $event['end']))."Z\n" ?>
UID:<?= uniqid() . "\n" ?>
DTSTAMP:<?=   gmdate('Ymd').'T'. gmdate('His') . "Z"."\n" ?>
LOCATION:<?= "Localisation\n" ?>
SUMMARY:<?= "Logement ".$event['title'] ."\n" ?>
DESCRIPTION:<?= "Logement ".$event['title'] ."\n" ?>
END:VEVENT
<?php }} ?>
END:VCALENDAR
