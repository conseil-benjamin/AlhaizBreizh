<?php
function dateToCal($timestamp): string
{
    return date('Ymd\THis\Z', $timestamp);
}

$idLog = 1;

/*******/
/* Il faut recup les dates de l'abo, ensuite, on regarde dans la table reservation les réservations qui débute dans la plage
si elles se terminent après, osef
Ensuite, on regarde dans la plage de dispo et on regarde les plages d'indispo
A voir comment c'est au niveau de la BDD
Il faut surment ajouter dans le calendrier les Resa plus que juste indispo


/*******/

/*
// Connexion à la base de données
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
$stmtCalendrier = $pdo->prepare("SELECT numcal,statutdispo FROM ldc.Calendrier WHERE numLogement = $idLog");
$stmtCalendrier->execute();

while($calendrierData = $stmtCalendrier->fetch(PDO::FETCH_NUM)){
    $numCal = $calendrierData[0] ?? null;
    $statutDispo = $calendrierData[1] ?? null;
}
*/
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
X-WR-TIMEZONE:Europe/Paris
DTSTART:<?= dateToCal(time()) . "\n" ?>
DTEND:<?= dateToCal(time()) . "\n" ?>
UID:<?= uniqid() . "\n" ?>
DTSTAMP:<?= dateToCal(time()) . "\n" ?>
LOCATION:<?= "test\n" ?>
DESCRIPTION:<?= "OUEOUE OUE\n" ?>
SUMMARY:<?= "Test\n" ?>
END:VEVENT
END:VCALENDAR