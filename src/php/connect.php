<?php
// Fichier PHP pour se connecter à la base de données
$pdo = null;
try {
    //$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=sae;user=Guillaume;password=1234"); //local Guillaume
    //$pdo = new PDO("pgsql:host=servbdd;port=5432;dbname=pg_mbisquay;user=mbisquay;password=Ipsum22!"); //local Marguaux
    $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=root"); //local William
    //$pdo = new PDO("pgsql:host=servbdd;port=5432;dbname=pg_lruellan;user=lruellan;password=LouLou-22-550"); //local Louis
    //$pdo = new PDO("pgsql:host=postgresdb;port=5432;dbname=sae;user=sae;password=Phiegoosequ9en9o"); //serveur
} catch (PDOException $e) {
    print "Erreur PDO !: " . $e->getMessage() . "<br/>";
    //die();
}
return $pdo;
