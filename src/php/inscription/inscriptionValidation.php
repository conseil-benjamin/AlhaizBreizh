<?php
if (!isset($_POST)) {
    die();
}
session_start();
global $pdo;
require("../connect.php");
$stmt = $pdo->prepare("INSERT INTO ldc.Client (firstName, lastName, mail, numeroTel, photoProfil, civilite, adressePostale, pseudoCompte, motDePasse, dateNaissance, notationMoyenne)
VALUES(:firstName, :lastName, :mail, :numeroTel, :photoProfil, :civilite, :adressePostale, :pseudoCompte, :motDePasse, :dateNaissance, :notationMoyenne)");
try {
    $stmt->execute([
        ':firstName' => $_POST["prenom"],
        ':lastName' => $_POST["nom"],
        ':mail' => $_POST["mail"],
        ':numeroTel' => $_POST["num_tel"],
        ':photoProfil' => 'photo2.jpg',
        ':civilite' => $_POST["civilite"],
        ':adressePostale' => $_POST["adresse"],
        ':pseudoCompte' => $_POST["identifiant"],
        ':motDePasse' => $_POST["mdp"],
        ':dateNaissance' => $_POST["date_naissance"],
        ':notationMoyenne' => '0'
    ]);
} catch (PDOException $err) {
    print_r($err);
    die();
}
include("../connexion/login.php")
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="/src/styles/inscription.css" rel="stylesheet" type="text/css">
    <link href="/src/styles/styles.css" rel="stylesheet" type="text/css">
    <title>S'inscrire</title>
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/header.php'); ?>
<div id="contenu">
    <h1> Votre Inscription a bien été enregistré </h1>
    <h2>
        Vous pouvez maintenant :
    </h2>
    <ul>
        <li><h2> - <a href="/#logement">Voir tous les logements du site</a></h2></li>
        <li><h2> - Passer Propriétaire</li>
    </ul>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/footer.php'); ?>
</body>
</html>
