<?php 
    session_start(); 
    error_reporting(E_ALL & ~E_WARNING);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php  

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else{
}
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pieceid = $_FILES['photos']['name'];
        $rib = htmlspecialchars(strip_tags($_POST['rib']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $languesparlees = htmlspecialchars(strip_tags($_POST['languesparlees']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    try {
        global $pdo;
        require($_SERVER['DOCUMENT_ROOT'].'/src/php/connect.php');
        $stmt = $pdo->prepare("INSERT INTO ldc.Propietaire (idcompte, pieceidentite, rib, languesparlees) VALUES (?, ?, ?, ?)");

            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $pieceid);
            $stmt->bindParam(3, $rib);
            $stmt->bindParam(4, $languesparlees);
            
            $stmt->execute();

            if ($stmt->affected_rows < 1) {
                ?>
                <script>
               Swal.fire({
                icon: "success",
                title: "Logement bien créé",
                showConfirmButton: false,
                timer: 2000
            });
               </script>
            <?php   
            } else {
                ?>
                <script>
                Swal.fire({
                title: "Erreur : logement non créé",
                icon: "error",
                });
               </script>
                <?php
            }

} catch (PDOException $e) {
    //echo "Erreur : " . $e->getMessage();
}

$pdo = null;

//Créer un dossier pour les photos du logement
$nom_dossier = $_SERVER['DOCUMENT_ROOT'] . "/public/img/logements/" . $id_logem;
$nbPhotos = count(glob($nom_dossier . "/*.png"));

if (!is_dir($nom_dossier)){
    if (mkdir($nom_dossier)) {
        $url = $nom_dossier . "/" . ($nbPhotos + 1) . ".png";
        move_uploaded_file($_FILES['photos']['tmp_name'], $url);
    }
}

?>
<script>

    //Faire une popup de confirmation
    Swal.fire({
            icon: "success",
            title: "Logement bien créé",
            showConfirmButton: false,
            timer: 2000
        });

    setTimeout(() => {
         window.location.href = '/src/php/profil/profil.php';
}, 2000);
</script>
<?php
exit;
}
?>
</body>
</html>