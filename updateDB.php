<?php
try {
/**include('connect.php');*/
    $dbh = new PDO("pgsql:host=postgresdb;port=5432;dbname=sae;user=sae;password=Phiegoosequ9en9o");
$stmt = $dbh->prepare(
"ALTER TABLE ldc.Devis ADD demande VARCHAR(255) DEFAULT '';"
);
$stmt->execute();
print "Table MAJ avec succées";
$dbh = null;
} catch (PDOException $e) {
print "Erreur !: " . $e->getMessage();
die();
}


