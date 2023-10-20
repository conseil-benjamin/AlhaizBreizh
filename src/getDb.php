<?php

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