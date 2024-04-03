<?php
    session_start();

    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
     if (isset($_POST['moyenne'])) {
        $stmtAvis = $pdo->prepare("INSERT INTO ldc.AvisLogement (contenuAvis, nbEtoiles, idClient, idLogement) VALUES (?, ?, ?, ?)");
        $stmtAvis->bindParam(1, $_POST['contenusAvis']);
        $stmtAvis->bindParam(2, $_POST['moyenne']);
        $stmtAvis->bindParam(3, $_SESSION['id']);
        $stmtAvis->bindParam(4, $_POST['idLogement']);
        try {
            $stmtAvis->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }    
    } else {
        echo "moyenne n'est pas définie";
    } 

    if (isset($_POST['moyenne'])) {
        $stmtAvis = $pdo->prepare("INSERT INTO ldc.AvisLogement (contenuAvis, nbEtoiles, idClient, idLogement) VALUES (?, ?, ?, ?)");
        $stmtAvis->bindParam(1, $_POST['contenusAvis']);
        $stmtAvis->bindParam(2, $_POST['moyenne']);
        $stmtAvis->bindParam(3, $_SESSION['id']);
        $stmtAvis->bindParam(4, $_POST['idLogement']);
        try {
            $stmtAvis->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }    
    } else {
        echo "moyenne n'est pas définie";
    } 
?>
