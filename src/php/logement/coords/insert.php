<?php
    try {
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $idLogement = $_POST['idLogement'];
        $coordX = $_POST['coordX'];
        $coordY = $_POST['coordY'];

        $stmt = $pdo->prepare("UPDATE ldc.Logement SET coordX = :coordX, coordY = :coordY WHERE numLogement = :idLogement");

        $stmt->bindValue(':idLogement', $idLogement, PDO::PARAM_INT);
        $stmt->bindValue(':coordX', (double)$coordX, PDO::PARAM_STR);
        $stmt->bindValue(':coordY', (double)$coordY, PDO::PARAM_STR);

        $stmt->execute();

    } catch (\Throwable $th) {
        throw $th;
    }
?>