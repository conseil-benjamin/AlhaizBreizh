<?php
// Fichier PHP pour se connecter à la base de données
$server = 'localhost';
$driver = 'pgsql';
$dbname = 'sae';
$user = 'Guillaume';
$pass = '';
try {
    $dbh = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=root");
} catch (PDOException $e) {
    print "Erreur deux !: " . $e->getMessage() . "<br/>";
    die();
}
