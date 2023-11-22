<?php
$numReservation = $_POST['numReservation'];
try {
    $dbh = new PDO("pgsql:host=postgresdb;port=5432;dbname=sae;user=sae;password=Phiegoosequ9en9o");
    $sql = "DELETE FROM ldc.reservation WHERE numReservation = $numReservation";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $dbh = null;
} catch (PDOException $e) {
    // En cas d'erreur, affichez un message d'erreur
    echo "Erreur : " . $e->getMessage();
}