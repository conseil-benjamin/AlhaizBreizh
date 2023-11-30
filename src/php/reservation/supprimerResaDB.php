<?php
$numReservation = $_POST['numReservation'];
global $pdo;
include("../connect.php");
try {
    $sql = "DELETE FROM ldc.reservation WHERE numReservation = $numReservation";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $pdo = null;
} catch (PDOException $e) {
    // En cas d'erreur, affichez un message d'erreur
    echo "Erreur : " . $e->getMessage();
}