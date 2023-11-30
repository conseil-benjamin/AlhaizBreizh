<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Connexion à la base de données
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');


    if (isset($_GET['numLogement'])) {
        $numLogement = $_GET['numLogement'];
        $proprio = $_SESSION['id'];

        if (isset($pdo)&&!empty($numLogement)) {
            // Vérifier si numLogement existe dans la base de données
            $stmt = $pdo->query("SELECT COUNT(*) FROM ldc.Logement WHERE numLogement = $numLogement");
            $numLogementExists = $stmt ? $stmt->fetchColumn() : null;
    

            if ($numLogementExists) {

                // Récupérer les données du calendrier
                $stmtCalendrier = $pdo->prepare("SELECT * FROM ldc.Calendrier WHERE numLogement = $numLogement");
                $stmtCalendrier->execute();
                while($calendrierData = $stmtCalendrier->fetch(PDO::FETCH_NUM)){
                    $numCal = isset($calendrierData[0]) ? $calendrierData[0] : null;
                    $statutDispo = isset($calendrierData[1]) ? $calendrierData[1] : null;
                    $dureeMinLoc = isset($calendrierData[2]) ? $calendrierData[2] : null;
                    $delaisEntreResArrivee = isset($calendrierData[3]) ? $calendrierData[3] : null;
                    $contrainteArriveeDepart = isset($calendrierData[4]) ? $calendrierData[4] : null;
                }

                //Récupérer les données des plages de disponibilité du calendrier
                $stmtPlagesDispo = $pdo->prepare("SELECT * FROM ldc.PlageDeDisponibilite WHERE numCal = $numCal");
                $stmtPlagesDispo->execute(); 
                $plagesDisponibilite = $stmtPlagesDispo->fetchAll(PDO::FETCH_ASSOC);
                $evenements = [];

                foreach ($plagesDisponibilite as $plage) {
                    if (isset($plage['datedebutplage'], $plage['datefinplage'])) {
                        $evenement = [
                            'id' => $plage['numplage'],
                            'title' => 'Tarif journalier : '.$plage['tarifjournalier'],
                            'start' => $plage['datedebutplage'],
                            'end' => $plage['datefinplage'],
                        ];
                        $evenements[] = $evenement;
                    }
                }
                $evenementsJSON = json_encode($evenements);

                //Insérer une plage de disponibilité
                if (isset($_POST['submitPlageDispo'])) {
                    $datedebutplage = $_POST['datedebutplage'];
                    $datefinplage = $_POST['datefinplage'];
                    $tarifjournalier = $_POST['tarifjournalier'];
                
                    // Vérifie si les dates sont valides et le tarif est numérique
                    if (strtotime($datedebutplage) && strtotime($datefinplage) && is_numeric($tarifjournalier)) {
                        $stmt = $pdo->prepare("INSERT INTO ldc.PlageDeDisponibilite (numCal, datedebutplage, datefinplage, tarifjournalier) 
                            VALUES (?, ?, ?, ?) ");
                        $stmt->execute([$numCal, $datedebutplage, $datefinplage, $tarifjournalier]);
                
                        // Rafraîchit les plages de disponibilité
                        header("Refresh:0");
                    }
                }
            }else{
                echo "Le numéro de logement spécifié n'existe pas.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/styleAfficherDispo.css">   
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
        <title>Calendrier</title>
    </head>
    <body>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php');?>
        <main>
            
            <div id="logement-name">
                <?php
                    // Récupérer le nom du logement depuis la base de données                
                    if ($numLogementExists) {
                        $stmt = $pdo->prepare("SELECT libelle FROM ldc.Logement WHERE numLogement = ?");
                        $stmt->execute([$numLogement]);
                        $logementName = $stmt->fetchColumn();
                        echo "<h2>$logementName :</h2>";
                    } else {
                        echo "<p>Le logement n'existe pas</p>";
                    }
                ?>
            </div>
            
            <div id='calendar'>
                <script> 
                    //script de création du calendrier
                    var evenements = <?php echo $evenementsJSON; ?>;
                    document.addEventListener('DOMContentLoaded', function() {
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            locale: 'fr',
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            height: 600,
                            selectable: true,
                            unselectAuto: true,
                            events: evenements,
                            eventClick: function(info) {
                                Swal.fire({
                                    title: "Voulez-vous supprimer la plage de disponibilité ?",
                                    showCancelButton: true,
                                    confirmButtonText: "Confirmer",
                                    cancelButtonText: "Annuler"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        Swal.fire({
                                            icon: "success",
                                            title: "La plage de disponibilité a bien été supprimée",
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        $.ajax({
                                            url: 'supprimerPlage.php', 
                                            type: 'POST',
                                            data: {
                                                numPlage: info.event.id
                                            },
                                            success: function(response) {
                                                console.log(response);
                                                calendar.refetchEvents();
                                                setTimeout(()=> {
                                                    location.reload(); // Rafraîchit la page après la suppression
                                                }, 2000);
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Erreur lors de la suppression :', error);
                                            }
                                        });                                        
                                    }
                                });                                
                            }
                        });
                        calendar.render();  
                    });
                </script>
            </div>

            <?php
                if (isset($calendrierData)) {
                    // Utilise les données du calendrier pour afficher les informations complémentaires
                    echo '<div>';
                    echo '<h2>Informations complémentaires :</h2>';
                    echo '<p>Statut : ' . ($statutDispo ? 'Disponible' : 'Indisponible') . ' à la réservation.' . '</p>';
                    echo '<p>Durée minimale de location : ' . $dureeMinLoc . ' jours.</p>';
                    echo '<p>Délais entre réservation et arrivée : ' . $delaisEntreResArrivee . ' jours.</p>';
                    echo '<p> Contraintes d\'arrivée et de départ : ' . $contrainteArriveeDepart . '.</p>';
                    echo '</div>';
                }
            ?>

            <?php
                //Formulaire permettant l'ajout d'une plage de disponibilité
                if (isset($_SESSION['id']) && $numLogementExists) {
                    if ($_SESSION['proprio'] == true) { 
            ?>
                        <div id="plage-form">
                            <h3>Ajouter une plage de disponibilité :</h3>
                            <form method="POST">
                                <label for="datedebutplage">Date de début :</label>
                                <input type="date" name="datedebutplage" required>

                                <label for="datefinplage">Date de fin :</label>
                                <input type="date" name="datefinplage" required>

                                <label for="tarifjournalier">Tarif journalier :</label>
                                <input type="number" name="tarifjournalier" step="0.01" required>

                                <input type="submit" name="submitPlageDispo" value="Ajouter">
                            </form>
                        </div>
            <?php   }
                }
             ?>
        </main>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?>
</html>