<?php
// Fichier PHP pour se connecter à la base de données

try {
    //$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=sae;user=Guillaume;password="); //local Guillaume
    $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=root"); //local
    //$pdo = new PDO("pgsql:host=postgresdb;port=5432;dbname=sae;user=sae;password=Phiegoosequ9en9o"); //serveur
} catch (PDOException $e) {
    print "Erreur deux !: " . $e->getMessage() . "<br/>";
    die();
}
