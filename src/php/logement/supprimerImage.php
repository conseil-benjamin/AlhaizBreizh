<?php

// Récupération du nom de la photo
$nomPhoto = $_GET['nomPhoto'];
$numLogement=$_SESSION['num_logement'];

// Vérification du nom de la photo
if (isset($nomPhoto)) {
    // Suppression du fichier image
    $chemin_fichier = $_SERVER['DOCUMENT_ROOT'] . '/public/img/logements/' . $numLogement . '/' . $nomPhoto .'.png';
    unlink($chemin_fichier);

    header('Content-Type: application/json');
    echo json_encode(array('success' => true));

} else {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Le nom de la photo est manquant ou invalide.'));

}

?>