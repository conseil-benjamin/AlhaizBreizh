<?php
if(isset($_SESSION)) {
    $numlogement = $_SESSION["numLogement"];
}
else {
    $numlogement = 1;
}
global $pdo;
try {
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare(
        "SELECT * FROM ldc.Devis where numlogement=$numlogement order by numdevis ASC"
    );
    $stmt->execute();
    $result = $stmt->fetchAll();
    $dateArrivee = $result[0]["datedebut"];
    $dateDepart = $result[0]["datefin"];
    $nbPersonne = $result[0]["nbpersonnes"];
    $demande = $result[0]["demande"];
    $pdo = null;
} catch (PDOException $e) {
    echo '<script>
        notifErr()
    </script>';
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}