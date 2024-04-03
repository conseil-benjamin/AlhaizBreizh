<?php
$numReservation = $_GET['numReservation'];
global $pdo;
include("../connect.php");
try {
    $sql = "UPDATE ldc.reservation SET etatReservation = 'Validée' WHERE numReservation = $numReservation";
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
        title: "La réservation a bien été validée !",
        showConfirmButton: false,
        timer: 2000,
    });
    setTimeout(() => {
        window.location.href = "/src/php/reservation/les_reservations.php";
    }, 2000);
});
</script>

