<?php
    session_start();

    // Connexion à la base de données
    $pdo = include($_SERVER['DOCUMENT_ROOT'] . '/src/php/connect.php');

    // Pour savoir si l'utilisateur connecté est un propriétaire ou non
    $isProprio = $_SESSION['proprio'];
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
                $stmtPlagesDispo = $pdo->prepare("SELECT * FROM ldc.Plage WHERE numCal = $numCal AND isIndispo = false");
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

                //Récupérer les données des plages d'indisponibilité du calendrier
                $stmtPlagesIndispo = $pdo->prepare("SELECT * FROM ldc.Plage WHERE numCal = $numCal AND (isIndispo = true OR tarifjournalier = 0)");
                $stmtPlagesIndispo->execute(); 
                $plagesIndisponibilite = $stmtPlagesIndispo->fetchAll(PDO::FETCH_ASSOC);
                $evenementsI = [];

                foreach ($plagesIndisponibilite as $plage) {
                    if (isset($plage['datedebutplage'], $plage['datefinplage'])) {
                        $evenementI = [
                            'id' => $plage['numplage'],
                            'title' => 'Indisponible',
                            'start' => $plage['datedebutplage'],
                            'end' => $plage['datefinplage'],
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

                    // // On recherche si le logement possède déjà une plage de disponibilité sur la période donnée
                    // $stmtPrecis = $pdo->prepare("SELECT * FROM ldc.Plage WHERE idLogement = ? AND datedebutplage = ? AND datefinplage = ?");
                    // $stmtPrecis->execute([$idLogement, $dateDebutPlage, $dateFinPlage]);

                    // $stmtVague = $pdo->prepare("SELECT * FROM ldc.Plage WHERE idLogement = ? AND datedebutplage >= ? AND datefinplage <= ?");
                    // $stmtVague->execute([$idLogement, $dateDebutPlage, $dateFinPlage]);

                    // // Si le logement possède déjà une plage de disponibilité sur la période donnée, on met à jour la disponibilité du logement
                    // if ($stmtPrecis->rowCount() > 0) {
                    //     $plagePrecise = $stmtPrecis->fetch(PDO::FETCH_ASSOC);

                    //     // On vérifie si la plage de disponibilité est déjà indisponible
                    //     if (!$plagePrecise['disponible']) {
                    //         echo "La plage sélectionnée est déjà indisponible, veuillez réessayer avec une autre plage disponible";
                    //         exit(1);
                    //     }

                    //     // Met à jour la disponibilité de la plage
                    //     $stmtUpdate = $pdo->prepare("UPDATE ldc.PlageDeDisponibilite SET disponible = false WHERE idPlage = ?");
                    //     $stmtUpdate->execute([$plagePrecise['idPlage']]);

                    // } elseif ($stmtVague->rowCount() > 0) {
                    //     $plageVague = $stmtVague->fetch(PDO::FETCH_ASSOC);

                    //     // Divise la plage existante en deux plages distinctes à la date de début de la nouvelle plage d'indisponibilité
                    //     $stmtUpdateDuree = $pdo->prepare("UPDATE ldc.PlageDeDisponibilite SET datefinplage = ? WHERE idLogement = ? AND datedebutplage <= ? AND datefinplage >= ?");
                    //     $stmtUpdateDuree->execute([$dateDebutPlage, $idLogement, $dateDebutPlage, $dateFinPlage]);
            
                    //     // Insère la nouvelle plage d'indisponibilité
                    //     $stmtInsertIndispo = $pdo->prepare("INSERT INTO ldc.PlageDeDisponibilite (datedebutplage, datefinplage, tarifjournalier, idLogement, numCal, disponible) VALUES (?, ?, ?, ?, ?, false)");
                    //     $stmtInsertIndispo->execute([$dateFinPlage, $plageVague['datefinplage'], $plageVague['tarifjournalier'], $idLogement, $idLogement]);
                    //}
                    if (strtotime($datedebutplage) && strtotime($datefinplage) && is_numeric($tarifjournalier)) {
                        // Vérifie si les dates sont valides et le tarif est numérique
                        $isDispo = false;
                        $stmt = $pdo->prepare("INSERT INTO ldc.Plage (isIndispo, numCal, datedebutplage, datefinplage, tarifjournalier) 
                            VALUES (?, ?, ?, ?, ?) ");
                        $stmt->bindParam(1, $isDispo, PDO::PARAM_BOOL);
                        $stmt->bindParam(2, $numCal);
                        $stmt->bindParam(3, $datedebutplage);
                        $stmt->bindParam(4, $datefinplage);
                        $stmt->bindParam(5, $tarifjournalier);
                        $stmt->execute();

                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Plage de disponibilité ajoutée avec succès',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        </script>";
                        
                        // Rafraîchit les plages de disponibilité
                        header("Refresh:0");
                    }
                }

                //Insérer une plage d'indisponibilité
                if (isset($_POST['submitPlageIndispo'])) {
                    $datedebutplage = $_POST['datedebutplage'];
                    $datefinplage = $_POST['datefinplage'];
                
                    // Vérifie si les dates sont valides et le tarif est numérique
                    if (strtotime($datedebutplage) && strtotime($datefinplage)) {
                        $idIndisponible = true;
                        $tarif = 0;
                        $stmt = $pdo->prepare("INSERT INTO ldc.Plage (isIndispo, numCal, datedebutplage, datefinplage, tarifjournalier) 
                            VALUES (?, ?, ?, ?, ?) ");
                        $stmt->bindParam(1,$isIndisponible, PDO::PARAM_BOOL);    
                        $stmt->bindParam(2,$numCal);
                        $stmt->bindParam(3,$datedebutplage);
                        $stmt->bindParam(4,$datefinplage);
                        $stmt->bindParam(5,$tarif);
                        $stmt->execute();

                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Plage d\'indisponibilité ajoutée avec succès',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        </script>";
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
                                    if(isProprio && idSession === proprioID){
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
                                }                   
                            });
                            calendar.render();  
                        });
                    </script>
                </div>

                <?php
                    //Formulaire permettant l'ajout d'une plage de disponibilité
                    if (isset($_SESSION['id']) && $numLogementExists) {
                        if ($isProprio == true && $_SESSION['id'] == $proprioId) {
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
                                    <label for="datedebutplage">Date de début :</label>
                                    <input type="date" name="datedebutplage" required>
                                    <br>
                                    <label for="datefinplage">Date de fin :</label>
                                    <input type="date" name="datefinplage" required>
                                
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