<?php
// Fichier PHP pour se connecter à la base de données
$server = 'postgresdb';
$driver = 'pgsql';
$dbname = 'sae';
$user = 'sae';
$pass = 'Phiegoosequ9en9o';
try {
$dbh = new PDO("$driver:host=$server;dbname=$dbname",
    $user, $pass);
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
