<?php
$token = $_GET['token'];
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

$stmt = $pdo->prepare("DELETE FROM ldc.tokenical WHERE token = :token");
$stmt->bindParam(':token', $token);
$stmt->execute();
$stmt = $pdo->prepare("DELETE FROM ldc.logements_tokenical WHERE num_token = :token");
$stmt->bindParam(':token', $token);
$stmt->execute();
$pdo = null;

?>