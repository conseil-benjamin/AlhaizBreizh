<?php
    session_start();

    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

    if (isset($_SESSION['id']) && $_SESSION['proprio'] == true) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['numPlage'])) {
            $numPlage = $_POST['numPlage'];
            
            $stmt = $pdo->prepare("DELETE FROM ldc.Plage WHERE numPlage = $numPlage");
            if ($stmt->execute()) {
                http_response_code(200);
                echo "Plage de disponibilité supprimée avec succès";
                
            } else {
                http_response_code(500);
                echo "Erreur lors de la suppression de la plage";
                print_r($stmt->errorInfo()); // Affiche les détails de l'erreur
            }
        } else {
            http_response_code(400);
            echo "Paramètres manquants pour la suppression";
        }
    } else {
        http_response_code(403);
        echo "Accès non autorisé";
    }
?>