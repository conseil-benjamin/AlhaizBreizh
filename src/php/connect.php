<?php
// Fichier PHP pour se connecter à la base de données
$server = 'localhost';
$driver = 'pgsql';
$dbname = 'sae';
$user = 'Guillaume';
$pass = '';
try {
    $dbh = new PDO("pgsql:host=$server;dbname=$dbname",
        $user, $pass);
} catch (PDOException $e) {
    print "Erreur deux !: " . $e->getMessage() . "<br/>";
    die();
}