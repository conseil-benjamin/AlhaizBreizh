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
        print_r("test01");
        $pieceid = $_FILES['photos']['name'];
        $rib = htmlspecialchars(strip_tags($_POST['ibn1']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // .$_POST['ibn2'].$_POST['ibn3'].$_POST['ibn4'].$_POST['ibn5'].$_POST['ibn6'].$_POST['ibn7'].$_POST['ibn8']
        $languesparlees = htmlspecialchars(strip_tags($_POST['languesparlees']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $message="Message G";
    try {
        print_r($id." ".$pieceid." ".$rib." ".$languesparlees);
        global $pdo;
        require($_SERVER['DOCUMENT_ROOT'].'/src/php/connect.php');
        $stmt = $pdo->prepare("INSERT INTO ldc.proprietaire (idcompte, pieceidentite, rib, languesparlees, messagetype) VALUES (?, ?, ?, ?, ?)");

            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $pieceid);
            $stmt->bindParam(3, $rib);
            $stmt->bindParam(4, $languesparlees);
            $stmt->bindParam(5, $message);
            
            $stmt->execute();

            print_r("test04");

            if ($stmt->affected_rows < 1) {
                ?>
                <script>
               Swal.fire({
                icon: "success",
                title: "TEST",
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
    echo "Erreur : " . $e->getMessage();
}

$pdo = null;

?>
<script>

    //Faire une popup de confirmation
//     Swal.fire({
//             icon: "success",
//             title: "Logement bien créé",
//             showConfirmButton: false,
//             timer: 2000
//         });

//     setTimeout(() => {
//          window.location.href = '/src/php/profil/profil.php';
// }, 2000);
</script>
<?php
exit;
}
?>
</body>
</html>