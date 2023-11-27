<?php

session_start();

if ((isset($_GET['user'])) && ($_GET['user'] == $_SESSION['id'])) {
    $user = $_GET['user'];
    $header = 'profil.php?user='.$user;
    $invalid = [];

    try {
        $pdo = require_once($_SERVER['DOCUMENT_ROOT'].'/src/php/connect.php');
        $query = "UPDATE ldc.Client SET 
                    firstName = :prenom, 
                    lastName = :nom, 
                    pseudoCompte = :pseudo,
                    mail = :email, 
                    numeroTel = :tel,
                    adressePostale = :adresse,
                    motDePasse = :password 
                    WHERE idCompte = :id";

        $stmt = $pdo->prepare($query);

        $keys = ['prenom', 'nom', 'pseudo', 'email', 'tel', 'adresse', 'password'];
        $i = 0;
        foreach ($_POST as $value) {
            $stmt->bindValue(':'.$keys[$i], $value, PDO::PARAM_STR);
            $i++;
        }

        $stmt->bindValue(':id', $user, PDO::PARAM_INT);

        $stmt->execute();
        $pdo = null;

        $_SESSION['pseudo'] = $_POST['Pseudo'];

        //Récupération de la nouvelle photo de profil
        if (isset($_FILES['file'])) {
            $info = pathinfo($_FILES['file']['name']);
            if (isset($info['extension'])){
                $ext = $info['extension'];
                $validExt = ['png', 'jpg', 'jpeg'];
                if (!in_array($ext, $validExt)){
                    $invalid[] = 'file';
                } else{
                    $path = $_SERVER['DOCUMENT_ROOT'].'/public/img/photos_profil/'.$user.'.png';
                    move_uploaded_file( $_FILES['file']['tmp_name'], $path);
                }
            }    
        }

    } catch (\Throwable $th) {

        //Gestions des erreurs
        if ($th instanceof PDOException) {
            $header .= '&error';
        } 
    }
} else {
    $header = '/index.php';
}

//Gestion des champs invalides
if ($invalid != []){
    $header .= '&edit';
    if (in_array('file', $invalid)) $header .= '&invalidFile';
}


if ((!isset($th)) && ($invalid == [])) $header .= '&save';

header('Location: '.$header);
exit();