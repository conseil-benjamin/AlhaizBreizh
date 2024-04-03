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
        $rib = htmlspecialchars(strip_tags($_POST['ibn1'].$_POST['ibn2'].$_POST['ibn3'].$_POST['ibn4'].$_POST['ibn5'].$_POST['ibn6'].$_POST['ibn7'].$_POST['ibn8']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $languesparlees = htmlspecialchars(strip_tags($_POST['languesparlees']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $message="Message G";
        $f=true;
        try {
            global $pdo;
            require($_SERVER['DOCUMENT_ROOT'].'/src/php/connect.php');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare("INSERT INTO ldc.proprietaire (idcompte, pieceidentite, rib, languesparlees, messagetype, src_pi) VALUES (?, ?, ?, ?, ?, ?)");

            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $f, PDO::PARAM_INT); // Faut l'obliger à considérer f comme un booleen
            $stmt->bindParam(3, $rib);
            $stmt->bindParam(4, $languesparlees);
            $stmt->bindParam(5, $message);
            $stmt->bindParam(6, $pieceid);
        
            $stmt->execute();

            $_SESSION['proprio']=1;
        
        
            if ($stmt->rowCount() > 0) { 
        ?>
                <script>
                    Swal.fire({
                        icon: "success",
                        title: "Votre demande pour devenir propriétaire a bien été prise en compte",
                        showConfirmButton: false,
                        timer: 4000
                    });
                </script>
        <?php   
            } else {
        ?>
                <script>
                    Swal.fire({
                        title: "Erreur : Une Erreur s'est produite lors de la manipulation",
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
     Swal.fire({
             icon: "success",
             title: "Votre demande pour devenir propriétaire a bien été prise en compte",
             showConfirmButton: false,
             timer: 4000
         });

     /*setTimeout(() => {
          window.location.href = '/src/php/profil/profil.php';
 }, 4000);*/
</script>
<?php
exit;
}
?>
</body>
</html>