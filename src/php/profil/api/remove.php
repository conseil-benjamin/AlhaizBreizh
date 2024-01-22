<?php

session_start();

$apiKey = $_GET['key'];

//Récupérer les infos de la clé API
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

$stmt = $pdo->prepare("SELECT id_proprio FROM ldc.APIkey WHERE apikey = :key");
$stmt->bindParam(':key', $apiKey);
$stmt->execute();

$id = $stmt->fetch(PDO::FETCH_NUM)[0];

$pdo = null;

//Vérifier que la clé API appartient bien à l'utilisateur
if ($id != $_SESSION['id']){
    header('Location: /');
    exit();
}

//Supprimer la clé API
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

$stmt = $pdo->prepare("DELETE FROM ldc.APIkey WHERE apikey = :key");
$stmt->bindParam(':key', $apiKey);
$stmt->execute();

$pdo = null;

header('Location: /src/php/profil/api');
exit();