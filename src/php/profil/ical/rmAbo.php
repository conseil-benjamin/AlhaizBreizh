<?php
$token = $_GET['token'];
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

$stmtDelToken = $pdo->prepare("DELETE FROM ldc.tokenical WHERE token = :token");
$stmtDelToken->bindParam(':token', $token);

$stmtDelLogToken = $pdo->prepare("DELETE FROM ldc.logements_tokenical WHERE token = :token");
$stmtDelLogToken->bindParam(':token', $token);
try {
    $stmtDelLogToken->execute();
    $stmtDelToken->execute();
    header('Location: /src/php/profil/ical/index.php');
}
catch (PDOException $e) {
    var_dump($e);
}

$pdo = null;

?>