<?php
if (!isset($_POST)) {
    die();
}
$characters = 'abcdefghijklmnopqrstuvwxyz';
$nomRnd = '';

for ($i = 0; $i < 5; $i++) {
    $nomRnd .= $characters[rand(0, strlen($characters) - 1)];
}

global $pdo;
require("../connect.php");
$stmt = $pdo->prepare("INSERT INTO ldc.Client (firstName, lastName, mail, numeroTel, photoProfil, civilite, adressePostale, pseudoCompte, motDePasse, dateNaissance, notationMoyenne)
VALUES(:firstName, :lastName, :mail, :numeroTel, :photoProfil, :civilite, :adressePostale, :pseudoCompte, :motDePasse, :dateNaissance, :notationMoyenne)");
try {
    $stmt->execute([
        ':firstName' => $_POST["prenom"],
        ':lastName' => $_POST["nom"],
        ':mail' => $_POST["email"],
        ':numeroTel' => str_replace(' ', '', $_POST["num_tel"]),
        ':photoProfil' => $nomRnd.".png",
        ':civilite' => $_POST["civilite"],
        ':adressePostale' => $_POST["adresse"],
        ':pseudoCompte' => $_POST["identifiant"],
        ':motDePasse' => $_POST["mdp"],
        ':dateNaissance' => $_POST["date_naissance"],
        ':notationMoyenne' => '0.0'
    ]);
} catch (PDOException $err) {
    print_r($err);
    die();
}

$dest = $_SERVER['DOCUMENT_ROOT']."/public/img/photos_profil/";
if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
    $src = $_FILES['photo_profil']['tmp_name'];
    $destinationChemin = $dest . $nomRnd.".png";
    move_uploaded_file($src, $destinationChemin);
}

echo "<script> notifSucessInscription()  </script>";
?>

