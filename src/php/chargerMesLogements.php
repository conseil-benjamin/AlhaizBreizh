<?php
    session_start(); 
    if (!isset($_SESSION['id'])) {
        header('Location: /src/php/connexion/connexion.php');
    } else if ($_SESSION['proprio'] == false) {
        header('Location: /');
    }

    function obtenirLogementsProprio($id) {
        $logements = array();
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
            $stmt = $pdo->prepare("SELECT numLogement,proprio,libelle,accroche,ville FROM ldc.Logement");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                if ($row[1] == $id){
                    $logements[] = $row;
                }
            }
            $pdo = null;
        } catch (PDOException $e) {
            $logements = array();
        }
        return $logements;
    }

    $logements = obtenirLogementsProprio($_SESSION['id']);
    if(isset($_GET["json"])) {
        $json = json_encode($logements);
        echo $json;
    }
    ?>