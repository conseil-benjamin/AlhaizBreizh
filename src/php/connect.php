<?php
$server = 'servbdd.iutlan.etu.univ-rennes1.fr';
$driver = 'pgsql';
$dbname = 'test';
$user = 'test';
$pass = 'test';
try {
$dbh = new PDO("$driver:host=$server;dbname=$dbname",
    $user, $pass);
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
