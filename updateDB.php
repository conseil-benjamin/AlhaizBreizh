<?php
global $dbh;
try {
include('connect.php');

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


