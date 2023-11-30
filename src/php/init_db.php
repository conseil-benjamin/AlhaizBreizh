<?php
try {
    // Créer une connexion PDO
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

    // Début de la transaction
    $pdo->beginTransaction();

    // Lire le fichier SQL
    $sql = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/db/LDC_SQL.sql');

    // Exécuter les requêtes SQL
    if ($pdo->exec($sql) === false) {
        throw new PDOException('Erreur lors de l\'exécution des requêtes SQL');
    }

    // Valider la transaction
    $pdo->commit();

    $pdo = null;

    echo "Initialisation de la base de données réussie";
} catch(PDOException $e) {
    die("La connexion a échoué: " . $e->getMessage());
}
