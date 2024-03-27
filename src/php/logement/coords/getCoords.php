<?php

    $data = null;

    try {
        $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
        $idLogement = $_POST['idLogement'];

        $stmt= $pdo->prepare("SELECT coordX, coordY FROM ldc.Logement WHERE numLogement = :idLogement");

        $stmt->bindValue(':idLogement', $idLogement, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch();
    } catch (\Throwable $th) {
        throw $th;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
?>