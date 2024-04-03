<?php
    session_start(); 
    if (!isset($_SESSION['id'])) {
        header('Location: /src/php/connexion/connexion.php');
    } else if ($_SESSION['proprio'] == false) {
        header('Location: /');
    }

    function obtenirFavoris($idd) {
        $logements = array();
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
            $stmt = $pdo->prepare("SELECT numlogement FROM ldc.favorisclient WHERE idcompte=$idd");
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

    function deleteFavoris($idd,$iid) {
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
            $stmt = $pdo->prepare("DELETE FROM ldc.favorisclient WHERE idcompte = $idd and numlogement = $iid");
            $stmt->execute();
            $pdo = null;
        } catch (PDOException $e) {
        }
    }

    function addFavoris($idd,$iid) {
        try {
            $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
            $stmt = $pdo->prepare("INSERT INTO ldc.favorisclient (idcompte, numlogement) VALUES (?, ?)");
            $stmt->bindParam(1, $idd);
            $stmt->bindParam(2, $iid);
            $stmt->execute();
            $pdo = null;
        } catch (PDOException $e) {
        }
    }

    if($_GET["json"]==0) {
        $logements = obtenirFavoris($_SESSION['id']);
        $json = json_encode($logements);
        echo $json;
    }
    else if ($_GET["json"]==1){
        addFavoris($_SESSION["id"],$_GET["num"]);
    }
    else if($_GET["json"]==2) {
        deleteFavoris($_SESSION["id"],$_GET["num"]);
    }
    ?>