<?php
if(isset($_SESSION)) {
    $numLogement = $_SESSION["numLogement"];
}
else {
    $numLogement = 1;
}
$dateDevis = new DateTime();
$dateDevis = $dateDevis->format("Y-m-d");
$dureeAcceptation = 300;

global $pdo;
try {
    include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $stmt = $pdo->prepare(
        "UPDATE ldc.devis
        SET dateDevis = '$dateDevis'
        WHERE numlogement = $numLogement;"
    );
    $stmt->execute();
    $pdo = null;
    echo '<script>
        notifSuccess()
    </script>';
} catch (PDOException $e) {
    print "Erreur submitDB !: " . $e->getMessage();
    echo '<script>
       notifErr()
    </script>';
    die();
}
catch (Error $e) {
    echo '<script>
        notifErr()
    </script>';
    die();
}