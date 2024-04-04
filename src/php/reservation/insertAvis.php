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
        /**
         * Calcul de la moyenne des notes
         */
        $stmtNoteLogement = $pdo->prepare("SELECT AVG(nbEtoiles) as moyenne FROM ldc.AvisLogement WHERE idLogement = ?");
        $stmtNoteLogement->bindParam(1, $_POST['idLogement']);
        $stmtNoteLogement->execute();
        try {
            $stmtNoteLogement->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        /**
         * Mise à jour de la note du logement
         */
        $moyenne = $stmtNoteLogement->fetch(PDO::FETCH_ASSOC);
        $stmtUpdateNote = $pdo->prepare("UPDATE ldc.Logement SET note = ? WHERE numLogement = ?");
        $stmtUpdateNote->bindParam(1, $moyenne['moyenne']);
        $stmtUpdateNote->bindParam(2, $_POST['idLogement']);
        try {
            $stmtUpdateNote->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "moyenne n'est pas définie";
    } 
?>
