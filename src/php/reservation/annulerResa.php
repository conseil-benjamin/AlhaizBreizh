<?php
$numReservation = $_GET['numReservation'];
global $pdo;
include("../connect.php");
try {
    $sql = "UPDATE ldc.reservation SET etatReservation = 'Annulée' WHERE numReservation = $numReservation";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $pdo = null;
} catch (PDOException $e) {
    // En cas d'erreur, affichez un message d'erreur
    echo "Erreur : " . $e->getMessage();
}
?>  
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: "success",
        title: "La réservation a bien été annulée !",
        button: "Revenir à la liste des réservations",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/src/php/reservation/les_reservations.php";
        }
    });
});
</script>
