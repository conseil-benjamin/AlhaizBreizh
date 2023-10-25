<?php
// Fichier PHP pour se connecter à la base de données
$server = 'localhost';
$driver = 'pgsql';
$dbname = 'sae';
$user = 'Guillaume';
$pass = '';
try {
    $dbh = new PDO("pgsql:host=postgresdb;port=5432;dbname=sae;user=sae;password=Phiegoosequ9en9o");
} catch (PDOException $e) {
    print "Erreur deux !: " . $e->getMessage() . "<br/>";
    die();
}