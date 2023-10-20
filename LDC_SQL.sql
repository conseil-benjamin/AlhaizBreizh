DROP SCHEMA IF EXISTS ldc CASCADE;
CREATE SCHEMA ldc;
SET SCHEMA 'ldc';

-- Table Admin
CREATE TABLE Admin (
    idAdmin SERIAL NOT NULL PRIMARY KEY,
    pseudo_admin VARCHAR(50),
    mdp_admin VARCHAR(50)
);

-- Table Client
CREATE TABLE Client (
    idCompte SERIAL NOT NULL PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    mail VARCHAR(100) NOT NULL,
    numeroTel VARCHAR(10) NOT NULL,
    photoProfil VARCHAR(255),
    civilite VARCHAR(15) NOT NULL,
    adressePostale VARCHAR(50) NOT NULL,
    pseudoCompte VARCHAR(50) NOT NULL,
    motDePasse VARCHAR(50) NOT NULL,
    dateNaissance DATE NOT NULL,
    notationMoyenne DOUBLE PRECISION
);

-- Table Message
CREATE TABLE Message (
    numMessage SERIAL NOT NULL PRIMARY KEY,
    destinataire INTEGER NOT NULL,
    expediteur INTEGER NOT NULL,
    dateExpedition DATE NOT NULL,
    contenu VARCHAR(500) NOT NULL
);

-- Table Conversation
CREATE TABLE Conversation (
    numConversation SERIAL NOT NULL PRIMARY KEY,
    titreConversation VARCHAR(50)
);

-- Table MessageConversation
CREATE TABLE MessageConversation (
    idInsertMessage SERIAL PRIMARY KEY,
    numMessage INTEGER,
    numConversation INTEGER
);

-- Table Messagerie
CREATE TABLE Messagerie (
    numMessagerie SERIAL NOT NULL PRIMARY KEY,
    idCompte INTEGER
);

-- Table ConversationMessagerie 
CREATE TABLE ConversationMessagerie (
    idInsertConversation SERIAL PRIMARY KEY,
    numConversation INTEGER,
    numMessagerie INTEGER
);

-- Table Avis
CREATE TABLE Avis (
    numAvis SERIAL NOT NULL PRIMARY KEY,
    contenuAvis VARCHAR(1000),
    nbEtoiles DOUBLE PRECISION
);

-- Table Logement
CREATE TABLE Logement (
    numLogement SERIAL NOT NULL PRIMARY KEY,
    surfaceHabitable DOUBLE PRECISION,
    libelle VARCHAR(50),
    accroche VARCHAR(200),
    description VARCHAR(500),
    natureLogement VARCHAR(25),
    proprio INTEGER,
    photoCouverture VARCHAR(100),
    LogementEnLigne BOOLEAN,
    nbPersMax INTEGER,
    nbChambres INTEGER,
    nbLitsSimples INTEGER,
    nbLitsDoubles INTEGER,
    detailsLitsDispos VARCHAR(100),
    nbSalleDeBain INTEGER
);

-- Table PhotosComplementairesLogement
CREATE TABLE PhotosComplementairesLogement (
        idPhotos SERIAL PRIMARY KEY,
        numLogement INTEGER,
        photosComplementaires VARCHAR(255)
);

-- Table Reservation
CREATE TABLE Reservation (
    numReservation SERIAL NOT NULL PRIMARY KEY,
    numClient INTEGER,
    numLogement INTEGER,
    dateReservation DATE,
    nbPersonnes INTEGER,
    dateDebut DATE,
    dateFin DATE,
    dateDevis DATE, 
    nbJours INTEGER,
    optionAnnulation VARCHAR(20)
);

-- Table Devis
CREATE TABLE Devis (
    numDevis SERIAL NOT NULL PRIMARY KEY,
    nbPersonnes INTEGER,
    numReservation INTEGER,
    numLogement INTEGER,
    dateDebut DATE,
    dateFin DATE,
    dateDevis DATE,
    dateValid DATE,
    optionAnnulation VARCHAR(20),
    dureeDelaisAcceptation INTEGER 
);

-- Table Calendrier
CREATE TABLE Calendrier (
    numCal SERIAL NOT NULL PRIMARY KEY,
    statutDispo BOOLEAN,
    dureeMinLoc INTEGER,
    delaisEntreResArrivee INTEGER,
    contrainteArriveeDepart VARCHAR(200),
    numLogement INTEGER
);

-- Table PlageDeDisponibilite 
CREATE TABLE PlageDeDisponibilite (
    numCal SERIAL NOT NULL PRIMARY KEY,    
    dateDebutPlage DATE,
    dateFinPlage DATE,
    tarifJournalier INTEGER
);

-- Table Proprietaire
CREATE TABLE Proprietaire (
    idCompte SERIAL NOT NULL PRIMARY KEY,
    pieceIdentite BOOLEAN,
    RIB VARCHAR(30),
    languesParlees VARCHAR(100),
    messageType VARCHAR(300)
);

-- Table Localisation
CREATE TABLE Localisation (
    numLogement INTEGER NOT NULL PRIMARY KEY,
    gps VARCHAR(25),
    rue VARCHAR(50),
    cp VARCHAR(5),
    ville VARCHAR(50)
);

-- Table Tarification
CREATE TABLE Tarification (
    numDevis SERIAL NOT NULL PRIMARY KEY,
    tarifNuitees DOUBLE PRECISION,
    chargesHT DOUBLE PRECISION,
    sousTotalHT DOUBLE PRECISION,
    sousTotalTTC DOUBLE PRECISION,
    fraisServicePlateformeHT DOUBLE PRECISION,
    fraisServicePlateformeTTC DOUBLE PRECISION,
    taxeSejour DOUBLE PRECISION,
    total DOUBLE PRECISION 
);

-- Table Services
CREATE TABLE Services (
    numLogement SERIAL NOT NULL PRIMARY KEY,
    installationsOffertes VARCHAR(255),
    equipementsProposes VARCHAR(255),
    servicesComplementaires VARCHAR(255),
    amenagementsProposes VARCHAR(255),
    chargesAdditionnelles VARCHAR(255) 
);

-- Table Devis_Client_Reservation
CREATE TABLE Devis_Client_Reservation (
    numDevis INTEGER,
    idCompte INTEGER,
    numReservation INTEGER,
    PRIMARY KEY (numDevis, idCompte, numReservation)
);

-- Table FavorisClient
CREATE TABLE FavorisClient (
    idFavorisClient SERIAL PRIMARY KEY,
    idCompte INTEGER,
    numLogement INTEGER
);

-- Table AvisClient
CREATE TABLE AvisClient (
    idAvisClient SERIAL PRIMARY KEY, 
    idCompte INTEGER, 
    idAvis INTEGER
);

-- Table LogementProprio
CREATE TABLE LogementProprio (
    idLogementProprio SERIAL PRIMARY KEY,
    numLogement INTEGER,
    idCompte INTEGER
);

-- Contraintes de clés étrangères

ALTER TABLE MessageConversation ADD CONSTRAINT messageconversation_conversation_fk1 FOREIGN KEY (numMessage) REFERENCES Message (numMessage);
ALTER TABLE MessageConversation ADD CONSTRAINT messageconversation_conversation_fk2 FOREIGN KEY (numConversation) REFERENCES Conversation (numConversation);
ALTER TABLE Messagerie ADD CONSTRAINT messagerie_client_fk FOREIGN KEY (idCompte) REFERENCES Client (idCompte);
ALTER TABLE ConversationMessagerie ADD CONSTRAINT conversationmessagerie_conversation_fk1 FOREIGN KEY (numConversation) REFERENCES Conversation (numConversation);
ALTER TABLE ConversationMessagerie ADD CONSTRAINT conversationmessagerie_messagerie_fk2 FOREIGN KEY (numMessagerie) REFERENCES Messagerie (numMessagerie);
ALTER TABLE Logement ADD CONSTRAINT logement_avis_fk FOREIGN KEY (numLogement) REFERENCES Avis (numAvis);
ALTER TABLE Logement ADD CONSTRAINT logement_localisation_fk FOREIGN KEY (numLogement) REFERENCES Localisation (numLogement);
ALTER TABLE Logement ADD CONSTRAINT logement_proprio_fk FOREIGN KEY (proprio) REFERENCES Proprietaire (idCompte);
ALTER TABLE PhotosComplementairesLogement ADD CONSTRAINT phtoscomplementaireslogement_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement (numLogement);
ALTER TABLE Reservation ADD CONSTRAINT reservation_client_fk FOREIGN KEY (numClient) REFERENCES Client (idCompte);
ALTER TABLE Reservation ADD CONSTRAINT reservation_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement (numLogement);
ALTER TABLE Calendrier ADD CONSTRAINT calendrier_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement (numLogement);
ALTER TABLE PlageDeDisponibilite ADD CONSTRAINT plagededisponibilite_calendrier_fk FOREIGN KEY (numCal) REFERENCES Calendrier (numCal);
ALTER TABLE Proprietaire ADD CONSTRAINT proprietaire_client_fk FOREIGN KEY (idCompte) REFERENCES Client (idCompte);
ALTER TABLE Tarification ADD CONSTRAINT tarification_devis_fk FOREIGN KEY (numDevis) REFERENCES Devis (numDevis);
ALTER TABLE Services ADD CONSTRAINT services_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement (numLogement);
ALTER TABLE Devis_Client_Reservation ADD CONSTRAINT devis_client_reservation_fk1 FOREIGN KEY (numDevis) REFERENCES Devis (numDevis);
ALTER TABLE Devis_Client_Reservation ADD CONSTRAINT devis_client_reservation_fk2 FOREIGN KEY (idCompte) REFERENCES Client (idCompte);
ALTER TABLE Devis_Client_Reservation ADD CONSTRAINT devis_client_reservation_fk3 FOREIGN KEY (numReservation) REFERENCES Reservation (numReservation);
ALTER TABLE FavorisClient ADD CONSTRAINT favorisclient_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement(numLogement);
ALTER TABLE FavorisClient ADD CONSTRAINT favorisclient_client_fk FOREIGN KEY (idCompte) REFERENCES Client(idCompte);
ALTER TABLE AvisClient ADD CONSTRAINT avisclient_client_fk FOREIGN KEY (idCompte) REFERENCES Client(idCompte);
ALTER TABLE AvisClient ADD CONSTRAINT avisclient_avis_fk FOREIGN KEY (idAvis) REFERENCES Avis(numAvis);
ALTER TABLE LogementProprio ADD CONSTRAINT logementproprio_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement(numLogement);
ALTER TABLE LogementProprio ADD CONSTRAINT logementproprio_proprietaire_fk FOREIGN KEY (idCompte) REFERENCES Proprietaire(idCompte);



-- Insertion de données dans la table Avis
INSERT INTO Avis (contenuAvis, nbEtoiles)
VALUES
    ('Très bel endroit', 4.5),
    ('Excellent séjour', 4.8);

-- Insertion de données dans la table Client
INSERT INTO Client (firstName, lastName, mail, numeroTel, photoProfil, civilite, adressePostale, pseudoCompte, motDePasse, dateNaissance, notationMoyenne)
VALUES
    ('Thierry', 'Richard', 'thierry.richard@email.com', '123456789', 'photo1.jpg', 'Monsieur', '123 Rue des lilas', 'trich', 'motdepasse123', '15-01-2000', 4.5),
    ('Jeanne', 'Robert', 'jeanne.robert@email.com', '987654321', 'photo2.jpg', 'Madame', '456 Avenue Charles de Gaule', 'jrob', 'password123', '25-07-1998', 4.0);


-- Insertion de données dans la table Message
INSERT INTO Message (destinataire, expediteur, dateExpedition, contenu)
VALUES
    (1, 2, '15-10-2023', 'Bonjour, je viens pour une réservation...'),
    (2, 1, '16-10-2023', 'Bonsoir, j''adore votre cave'),
    (1, 2, '16-10-2023', 'Merci !...');

-- Insertion de données dans la table Conversation
INSERT INTO Conversation (titreConversation)
VALUES
    ('Réservation de logement'),
    ('Coucou'),
    ('Annulation de séjour');
    
-- Insertion de données dans la table MessageConversation
INSERT INTO MessageConversation (numMessage, numConversation)
VALUES
    (1, 1),
    (2, 2),
    (3, 2);
    
-- Insertion de données dans la table Messagerie
INSERT INTO Messagerie (idCompte)
VALUES
    (1),
    (2),
    (2);
    
-- Insertion de données dans la table ConversationMessagerie
INSERT INTO ConversationMessagerie (numConversation, numMessagerie)
VALUES
    (1, 1),
    (2, 2);
    
-- Insertion de données dans la table Proprietaire
INSERT INTO Proprietaire (idCompte, pieceIdentite, RIB, languesParlees, messageType)
VALUES
    (1, TRUE, '123456789', 'Français, Anglais', 'Message A'),
    (2, TRUE, '987654321', 'Espagnol, Français, Anglais', 'Message B');

-- Insertion de données dans la table Localisation
INSERT INTO Localisation (numLogement, gps, rue, cp, ville)
VALUES
    (1, '546498', '123 Rue des Roses', '12345', 'Ville A'),
    (2, '489445', '456 Avenue des Soldats', '67890', 'Ville B');
    
-- Insertion de données dans la table Logement
INSERT INTO Logement (surfaceHabitable, libelle, accroche, description, natureLogement, proprio, photoCouverture, LogementEnLigne, nbPersMax, nbChambres, nbLitsSimples, nbLitsDoubles, detailsLitsDispos, nbSalleDeBain)
VALUES
    (80.5, 'Appartement cozy', 'Un adorable appartement dans les bois', 'Cet appartement est parfait pour un weekend en amoureux.', 'appartement', 1, 'appartement.jpg', TRUE, 4, 2, 2, 1, '1 lit double, 2 lits simples', '1'),
    (100.2, 'Cave spacieuse', 'Au coeur de la ville', 'Profitez de la vie urbaine grâce à cette magnifique cave.', 'cave', 2, 'cave.jpg', TRUE, 3, 1, 2, 1, '2 lits simples', '2');

-- Insertion de données dans la table Reservation
INSERT INTO Reservation (numClient, numLogement, dateReservation, nbPersonnes, dateDebut, dateFin, dateDevis, nbJours, optionAnnulation)
VALUES
    (1, 1, '2023-10-18', 2, '2023-11-01', '2023-11-07', '2023-10-15', 7, 'Stricte'),
    (2, 2, '2023-10-20', 3, '2023-11-05', '2023-11-10', '2023-10-16', 6, 'Flexible');

-- Insertion de données dans la table Devis
INSERT INTO Devis (nbPersonnes, numReservation, numLogement, dateDebut, dateFin, dateDevis, dateValid, optionAnnulation, dureeDelaisAcceptation)
VALUES
    (2, 1, 1, '2023-11-01', '07-11-2023', '15-10-2023', '20-10-2023', 'Stricte', 48),
    (3, 2, 2, '2023-11-05', '10-11-2023', '16-10-2023', '22-10-2023', 'Flexible', 72);
    
-- Insertion de données dans la table Calendrier
INSERT INTO Calendrier (statutDispo, dureeMinLoc, delaisEntreResArrivee, contrainteArriveeDepart, numLogement)
VALUES
    (TRUE, 2, 3, 'Arrivées le samedi', 1),
    (TRUE, 3, 2, 'Départ avant midi, arrivées le jeudi', 2);

-- Insertion de données dans la table PlageDeDisponibilite
INSERT INTO PlageDeDisponibilite (numCal, dateDebutPlage, dateFinPlage, tarifJournalier)
VALUES
    (1, '01-11-2023', '2023-11-07', 100),
    (2, '05-11-2023', '2023-11-10', 120);

-- Insertion de données dans la table Favoris
INSERT INTO FavorisClient (idCompte, numLogement)
VALUES
    (1, 1),
    (1, 2),
    (2, 1);

-- Insertion de données dans la table Tarification
INSERT INTO Tarification (numDevis, tarifNuitees, chargesHT, sousTotalHT, sousTotalTTC, fraisServicePlateformeHT, fraisServicePlateformeTTC, taxeSejour, total)
VALUES
    (1, 10, 80, 560, 600, 40, 48, 12, 660),
    (2, 90, 100, 600, 660, 60, 72, 18, 720);

-- Insertion de données dans la table Services
INSERT INTO Services (numLogement, installationsOffertes, equipementsProposes, servicesComplementaires, amenagementsProposes, chargesAdditionnelles)
VALUES
    (1,'toilettes' ,'wifi, TV', 'Petit-déjeuner inclus', 'Piscine, Salle de sport', 'Ménage 50€'),
    (2, 'salle de bains','wifi, parking', 'Service de chambre', 'Jardin', 'Supplément animal de compagnie 25€');

-- Insertion de données dans la table AvisClient
INSERT INTO AvisClient (idCompte, idAvis) 
VALUES 
    ('1', '1'),
    ('1', '2');

-- Insertion de données dans la table LogementProprio
INSERT INTO LogementProprio (numLogement, idCompte) 
VALUES 
    ('1', '2'),
    ('2', '2');

-- Insertion de données dans la table PhotosComplementairesLogement
INSERT INTO PhotosComplementairesLogement (numLogement, photosComplementaires) 
VALUES 
    (1, 'photo.png'),
    (2, 'photo.jgp');