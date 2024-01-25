<?php
print_r($_GET);
$numReservation = $_GET['numReservation'];
global $pdo;
include("../connect.php");
try {
    $sql = "DELETE FROM ldc.reservation WHERE numReservation = $numReservation";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $pdo = null;
} catch (PDOException $e) {
    // En cas d'erreur, affichez un message d'erreur
    echo "Erreur : " . $e->getMessage();
}


$response = array();
$response['message'] =' Réservation supprimé avec succés';
header ('Content-Type: application/json');
echo json_encode($response);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <!-- Librairie pour les alertes -->
<script src="/src/js/detailsReservation.js"></script>
 
</body>
</html>
