<?php
    session_start();

    // Connexion à la base de données
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

    // Pour savoir si l'utilisateur connecté est un propriétaire ou non
    $isProprio = $_POST['proprio'];
    $isProprioJSON = json_encode($isProprio);

    $idSession = $_SESSION['id'];
    $idSessionJSON = json_encode($idSession);

    if (isset($_GET['numLogement'])) {
        $numLogement = $_GET['numLogement'];

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

                //Récupérer le tarif par nuitée par défaut
                $stmtTarifDefaut = $pdo->prepare("SELECT * FROM ldc.Logement WHERE numLogement = $numLogement");
                $stmtTarifDefaut->execute();
                while($logementData = $stmtTarifDefaut->fetch(PDO::FETCH_NUM)){
                    $tarifDefaut = isset($logementData[15]) ? $logementData[15] : null;
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

                //Récupérer les données des plages de disponibilité du calendrier
                $stmtPlagesIndispo = $pdo->prepare("SELECT * FROM ldc.PlageIndisponibilite WHERE numCal = $numCal");
                $stmtPlagesIndispo->execute(); 
                $plagesIndisponibilite = $stmtPlagesIndispo->fetchAll(PDO::FETCH_ASSOC);
                $evenementsI = [];

                foreach ($plagesIndisponibilite as $plage) {
                    if (isset($plage['datedebutplagei'], $plage['datefinplagei'])) {
                        $evenementI = [
                            'id' => $plage['numplagei'],
                            'title' => 'Indisponible',
                            'start' => $plage['datedebutplagei'],
                            'end' => $plage['datefinplagei'],
                        ];
                        $evenementsI[] = $evenementI;
                    }
                }
                $evenementsIJSON = json_encode($evenementsI);

                // Récupérer l'ID du propriétaire du logement
                $stmtProprio = $pdo->prepare("SELECT proprio FROM ldc.Logement WHERE numLogement = $numLogement");
                $stmtProprio->execute();
                $proprioArray = $stmtProprio->fetch();
                $proprioId = $proprioArray[0];
                $proprioIdJSON = json_encode($proprioId);

                //Insérer une plage de disponibilité
                if (isset($_POST['submitPlageDispo'])) {
                    $datedebutplage = $_POST['datedebutplage'];
                    $datefinplage = $_POST['datefinplage'];
                    $tarifjournalier = $_POST['tarifjournalier'];
                
                    // Vérifie si les dates sont valides et le tarif est numérique
                    if (strtotime($datedebutplage) && strtotime($datefinplage) && is_numeric($tarifjournalier)) {
                        //vérifie si la nouvelle plage de disponibilité n'intersecte pas avec une plage de disponibilité existante
                        // $intersectionQuerry = $pdo->prepare("SELECT COUNT(*) FROM ldc.PlageDeDisponibilite WHERE numCal = ? AND NOT (? >= datefinplage OR  ? <= datedebutplage)");
                        // $intersectionQuerry->execute([$numCal, $datedebutplage, $datefinplage]);
                        // $inteserctionCount = $intersectionQuerry->fetchColumn();

                        // if($inteserctionCount == 0){
                        //     //vérifie si la nouvelle plage de disponibilité n'intersecte pas avec une plage d'indisponibilité existante
                        //     $intersectionQuerryIndispo = $pdo->prepare("SELECT COUNT(*) FROM Ldc. WHERE numCal = ? AND NOT (? >= datefinplage OR ? <= datedebutplage)");
                        //     $intersectionQuerryIndispo->execute([$numCal, $datedebutplage, $datefinplage]);
                        //     $intersectionCountIndispo = $intersectionQuerryIndispo->fetchColumn();

                        //     if($intersectionCountIndispo == 0){

                                $stmt = $pdo->prepare("INSERT INTO ldc.PlageDeDisponibilite (numCal, datedebutplage, datefinplage, tarifjournalier) 
                                    VALUES (?, ?, ?, ?) ");
                                $stmt->bindParam(1, $numCal);
                                $stmt->bindParam(2, $datedebutplage);
                                $stmt->bindParam(3, $datefinplage);
                                $stmt->bindParam(4, $tarifjournalier);
                                $stmt->execute();
                        
                                // Rafraîchit les plages de disponibilité
                                header("Refresh:0");
                            }//else{

                        }//else{

                        //}
                    }
                }

                //Insérer une plage d'indisponibilité
                if (isset($_POST['submitPlageIndispo'])) {
                    $datedebutplagei = $_POST['datedebutplagei'];
                    $datefinplagei = $_POST['datefinplagei'];
                
                    // Vérifie si les dates sont valides et le tarif est numérique
                    if (strtotime($datedebutplagei) && strtotime($datefinplagei)) {
                        $stmt = $pdo->prepare("INSERT INTO ldc.PlageIndisponibilite (numCal, datedebutplagei, datefinplagei) 
                            VALUES (?, ?, ?) ");
                        $stmt->bindParam(1,$numCal);
                        $stmt->bindParam(2,$datedebutplagei);
                        $stmt->bindParam(3,$datefinplagei);
                        $stmt->execute();
                
                        // Rafraîchit les plages de disponibilité
                        header("Refresh:0");
                    }
                }
            }else{
                echo "Le numéro de logement spécifié n'existe pas.";
            }
    //     }
    // }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="/src/styles/styleAfficherDispo.css">
        <link rel="icon" href="/public/logos/logo-black.svg">   
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
            
            <div id='calform'>
                <div id='calendar'>
                    <script> 
                        //script de création du calendrier
                        let evenements = <?php echo $evenementsJSON; ?>;
                        let evenementsI = <?php echo $evenementsIJSON; ?>;
                        let isProprio = <?php echo $isProprioJSON; ?>;
                        let proprioID = <?php echo $proprioIdJSON; ?>;
                        let idSession = <?php echo $idSessionJSON; ?>;
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
                                events: evenements.concat(evenementsI),
                                // eventColor: function(event) {
                                //     if (event.title === 'Indisponible') {
                                //         return 'red';
                                //     }
                                // },
                                eventClick: function(info) {
                                    if(isProprio && idSession == proprioID){
                                        Swal.fire({
                                            title: "Voulez-vous supprimer la plage ?",
                                            showCancelButton: true,
                                            confirmButtonText: "Confirmer",
                                            cancelButtonText: "Annuler"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                Swal.fire({
                                                    icon: "success",
                                                    title: "La plage a bien été supprimée",
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                });
                                                if (info.event.title.startsWith("Tarif journalier :")) {
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
                                                }else{
                                                    $.ajax({
                                                        url: 'supprimerPlageIndispo.php',
                                                        type: 'POST',
                                                        data: {
                                                            numPlageI: info.event.id
                                                        },
                                                        success: function(response) {
                                                            console.log(response);
                                                            calendar.refetchEvents();
                                                            setTimeout(() => {
                                                                location.reload(); // Rafraîchit la page après la suppression
                                                            }, 2000);
                                                        },
                                                        error: function(xhr, status, error) {
                                                            console.error('Erreur lors de la suppression :', error);
                                                        }
                                                    });
                                                }                                       
                                            }
                                        });
                                    }                                
                                }                   
                            });
                            calendar.render();  
                        });
                    </script>
                </div>

                <?php
                    //Formulaire permettant l'ajout d'une plage de disponibilité
                    if (isset($_SESSION['id']) && $numLogementExists) {
                        if ($_SESSION['proprio'] == true && $_SESSION['id'] == $proprioId) {
                ?>      
                        <div id="forms">
                            <div class="select">
                                <select id="select-form">
                                    <option value="plage-disp-form">Plage de disponibilité</option>
                                    <option value="plage-indisp-form">Plage d'indisponibilité</option>
                                    <span class="focus"></span>
                                </select>
                            </div>
                            <div id="plage-disp-form">
                                <h3>Ajouter une plage de disponibilité :</h3>
                                <form method="POST">
                                    <label for="datedebutplage">Date de début :</label>
                                    <input type="date" name="datedebutplage" required>
                                    <br>
                                    <label for="datefinplage">Date de fin :</label>
                                    <input type="date" name="datefinplage" required>
                                    <br>
                                    <label for="tarifjournalier">Tarif journalier :</label>
                                    <input type="number" name="tarifjournalier" placeholder="<?php echo $tarifDefaut?>" step="0.01" required>
                                    <input type="submit" name="submitPlageDispo" value="Ajouter">
                                </form>
                            </div>
               
                            <!-- Formulaire permettant l'ajout d'une plage d'indisponibilité -->
                    
                            <div id="plage-indisp-form">
                                <h3>Ajouter une plage d'indisponibilité :</h3>
                                <form method="POST">
                                    <label for="datedebutplagei">Date de début :</label>
                                    <input type="date" name="datedebutplagei" required>
                                    <br>
                                    <label for="datefinplagei">Date de fin :</label>
                                    <input type="date" name="datefinplagei" required>
                                
                                    <input type="submit" name="submitPlageIndispo" value="Ajouter">
                                </form>
                            </div>

                        </div>
                <?php   }
                    }
                ?>
                <script src="../../js/switchAddCalendrier.js"></script>
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
        </main>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php'); ?> 
</html>