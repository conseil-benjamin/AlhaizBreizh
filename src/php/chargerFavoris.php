<?php
    session_start(); 

    if (isset($_GET['json'])){
        $_POST["json"] = $_GET['json'];
    }

    function obtenirFavoris($idd) {
        $logements = array();
            try {
                $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
                $stmt = $pdo->prepare("SELECT numlogement FROM ldc.favorisclient WHERE idcompte=$idd");
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $logements[] = $row[0];
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
    if (isset($_POST["json"])){
        if($_POST["json"]==0) {
        
            if (isset($_SESSION['id'])) {
                $logements = obtenirFavoris($_SESSION['id']);
            }
            else{
                $logements= array();
            }
            try{
                $json = json_encode($logements);
            }
            catch(Throwable $e){
                console.log("dfdfd");
            }
    
            echo $json;
        }
        else if ($_POST["json"]==1){
            addFavoris($_SESSION["id"],$_POST["num"]);
            $json = json_encode($_POST["num"]);
            echo $json;
        }
        else if($_POST["json"]==2) {
            deleteFavoris($_SESSION["id"],$_POST["num"]);
        }
    }

?>