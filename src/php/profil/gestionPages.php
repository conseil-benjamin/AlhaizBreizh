<?php 
session_start();

$header = '/index.php';

if ((isset($_GET['user'])) && (isset($_GET['nbAvis']))) {
    $nbCommentaires = $_GET['nbAvis'];
    $nbCommentaires += 2;
    $user = $_GET['user'];
    $header = '/src/php/profil/profil.php?user='.$user.'&nbCommentaires='.$nbCommentaires.'#commentaires';
}

header('Location: '.$header);