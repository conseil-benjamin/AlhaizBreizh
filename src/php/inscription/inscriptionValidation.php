<?php
if (!isset($_POST["prenom"])) {
    die();
}

//Récupérer l'id du dernier client et l'incrémenter
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

$stmt = $pdo->prepare("SELECT MAX(idCompte) FROM ldc.Client");
$stmt->execute();
$img = $stmt->fetch(PDO::FETCH_NUM)[0] + 1;
$numClient=$img;
$img = strval($img);
$img .= ".png";

$pdo = null;

//Ajouter le client dans la base de données
$pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

$stmt = $pdo->prepare("INSERT INTO ldc.Client (firstName, lastName, mail, numeroTel, photoProfil, civilite, adressePostale, pseudoCompte, motDePasse, dateNaissance, notationMoyenne)
VALUES(:firstName, :lastName, :mail, :numeroTel, :photoProfil, :civilite, :adressePostale, :pseudoCompte, :motDePasse, :dateNaissance, :notationMoyenne)");
try {
    $stmt->execute([
        ':firstName' => $_POST["prenom"],
        ':lastName' => $_POST["nom"],
        ':mail' => $_POST["email"],
        ':numeroTel' => str_replace(' ', '', $_POST["num_tel"]),
        ':photoProfil' => $img,
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
catch (Error $err) {
    die();
}

$pdo = null;

//Ajouter la photo de profil dans le dossier
print_r($_FILES);

$dest = $_SERVER['DOCUMENT_ROOT']."/public/img/photos_profil/";
if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
    $src = $_FILES['photo_profil']['tmp_name'];
    $destinationChemin = $dest . $img;
    move_uploaded_file($src, $destinationChemin);
}

echo "<script> notifSucessInscription()  </script>";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert création</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
    echo "var numClient = " . json_encode($numClient) . ";\n";

    setTimeout((), 2000);
</script>
<?php
exit;

?>
</body>
</html>
