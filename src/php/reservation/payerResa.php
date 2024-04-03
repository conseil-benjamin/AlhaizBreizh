<?php
$numReservation = $_GET['numReservation'];
global $pdo;
include("../connect.php");
try {
    $sql = "UPDATE ldc.reservation SET etatReservation = 'Confirmée' WHERE numReservation = $numReservation";
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
        title: "La réservation à bien été confirmer",
        text: "l'équipe d'ALHaIZ vous souhaite un agréable séjour !",
        icon: "success",
        button: "Revenir à la liste de mes réservations",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/src/php/reservation/listeResa.php";
        }
    });
});
</script>
