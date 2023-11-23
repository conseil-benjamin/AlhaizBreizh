<?php

session_start();


  if(isset($_POST['nbInstallations'])) {
            $nbInstallations = $_POST['nbInstallations'];
            
            // Faire quelque chose avec la variable en PHP
            echo "Variable reçue en PHP : " . $nbInstallations;
        } else {
            echo "Aucune variable reçue en PHP";
        }

        $_SESSION['nbInstallations'] = $nbInstallations;
?>

