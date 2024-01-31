DROP SCHEMA IF EXISTS ldc CASCADE;
CREATE SCHEMA ldc;
SET SCHEMA 'ldc';

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
    destinataire SERIAL NOT NULL,
    expediteur SERIAL NOT NULL,
    dateExpedition DATE NOT NULL,
    contenu VARCHAR(255) NOT NULL
);

-- Table Conversation
CREATE TABLE Conversation (
    numConversation SERIAL NOT NULL PRIMARY KEY,
    titreConversation VARCHAR(255)
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

CREATE TABLE ConversationMessagerie (
    idInsertConversation SERIAL PRIMARY KEY,
    numConversation INTEGER,
    numMessagerie INTEGER
);

-- Table Avis
CREATE TABLE Avis (
    numAvis SERIAL NOT NULL PRIMARY KEY,
    contenuAvis VARCHAR(255),
    nbEtoiles DOUBLE PRECISION
);

-- Table Logement
CREATE TABLE Logement (
    numLogement SERIAL NOT NULL PRIMARY KEY,
    surfaceHabitable DOUBLE PRECISION,
    libelle VARCHAR(255),
    accroche VARCHAR(255),
    descriptionLogement VARCHAR(255),
    natureLogement VARCHAR(255),
    adresse VARCHAR(255),
    cp CHAR(5),
    ville VARCHAR(255),
    proprio INTEGER,
    photoCouverture VARCHAR(255),
    LogementEnLigne BOOLEAN,
    nbPersMax INTEGER,
    nbChambres INTEGER,
    nbSalleDeBain INTEGER,
    tarifNuitees DOUBLE PRECISION
);

CREATE TABLE Chambre (
    numChambre INTEGER,
    nbLitsSimples INTEGER,
    nbLitsDoubles INTEGER,
    numLogement INTEGER,
    PRIMARY KEY (numChambre,numLogement),
    CONSTRAINT fk_numLogement
        FOREIGN KEY (numLogement)
        REFERENCES Logement (numLogement)
);

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
    optionAnnulation VARCHAR(255)
);

-- Table Devis
CREATE TABLE Devis (
    numDevis SERIAL NOT NULL PRIMARY KEY,
    nbPersonnes INTEGER,
    numReservation INTEGER,
    numLogement INTEGER,
    dateDebut DATE,
    dateFin DATE,
    dateDevis VARCHAR(255),
    dateValid VARCHAR(255),
    optionAnnulation VARCHAR(255),
    dureeDelaisAcceptation INTEGER 
);

-- Table Calendrier
CREATE TABLE Calendrier (
    numCal SERIAL NOT NULL PRIMARY KEY,
    statutDispo BOOLEAN,
    dureeMinLoc INTEGER,
    delaisEntreResArrivee INTEGER,
    contrainteArriveeDepart VARCHAR(255),
    numLogement INTEGER
);

-- Table Plage
CREATE TABLE Plage (
    numPlage SERIAL PRIMARY KEY,
    isIndispo BOOLEAN,
    numCal INTEGER NOT NULL,    
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

CREATE TABLE Service(
  numLogement INTEGER NOT NULL,
  numServ INTEGER NOT NULL,
  nom VARCHAR(255),
  PRIMARY KEY (numLogement,numServ),
  CONSTRAINT fk_numService
        FOREIGN KEY (numLogement)
        REFERENCES Logement (numLogement)
);

CREATE TABLE Equipement(
  numLogement INTEGER NOT NULL,
  numeEquip INTEGER NOT NULL,
  nom VARCHAR(255),
  PRIMARY KEY (numLogement,numeEquip),
    CONSTRAINT fk_numEquipement
        FOREIGN KEY (numLogement)
        REFERENCES Logement (numLogement)
);

CREATE TABLE Installation(
  numLogement INTEGER NOT NULL,
  numInstall INTEGER NOT NULL,
  nom VARCHAR(255),
  PRIMARY KEY (numLogement,numInstall),
    CONSTRAINT fk_numInstallation
        FOREIGN KEY (numLogement)
        REFERENCES Logement (numLogement)
);

-- Table Devis_Client_Reservation
CREATE TABLE Devis_Client_Reservation (
    numDevis INTEGER,
    idCompte INTEGER,
    numReservation INTEGER,
    PRIMARY KEY (numDevis, idCompte, numReservation)
);

-- Table Favoris
CREATE TABLE FavorisClient (
    idFavorisClient SERIAL PRIMARY KEY,
    idCompte INTEGER,
    numLogement INTEGER
);

CREATE TABLE AvisClient (
    idAvisClient SERIAL PRIMARY KEY, 
    idCompte INTEGER, 
    idDestinataire INTEGER,
    idAvis INTEGER
);

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
--ALTER TABLE Logement ADD CONSTRAINT logement_avis_fk FOREIGN KEY (numLogement) REFERENCES Avis (numAvis);
ALTER TABLE Logement ADD CONSTRAINT logement_proprio_fk FOREIGN KEY (proprio) REFERENCES Proprietaire (idCompte);
ALTER TABLE PhotosComplementairesLogement ADD CONSTRAINT phtoscomplementaireslogement_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement (numLogement);
ALTER TABLE Reservation ADD CONSTRAINT reservation_client_fk FOREIGN KEY (numClient) REFERENCES Client (idCompte);
ALTER TABLE Reservation ADD CONSTRAINT reservation_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement (numLogement);
ALTER TABLE Calendrier ADD CONSTRAINT calendrier_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement (numLogement);
ALTER TABLE Plage ADD CONSTRAINT plage_calendrier_fk FOREIGN KEY (numCal) REFERENCES Calendrier (numCal);
ALTER TABLE Proprietaire ADD CONSTRAINT proprietaire_client_fk FOREIGN KEY (idCompte) REFERENCES Client (idCompte);
ALTER TABLE Tarification ADD CONSTRAINT tarification_devis_fk FOREIGN KEY (numDevis) REFERENCES Devis (numDevis);
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
    ('Excellent séjour', 4.8),
    ('Personne très accueillante', 4.0),
    ('A laissé en bon état ma maison !', 5.0),
    ('Elle a offert à ma propre personne un très bon jus de pomme et je suis tellement ému par rapport à ça !', 4.5),
    ('Personne très mal élévée', 1.0);

-- Insertion de données dans la table Client
INSERT INTO Client (firstName, lastName, mail, numeroTel, photoProfil, civilite, adressePostale, pseudoCompte, motDePasse, dateNaissance, notationMoyenne)
VALUES
    ('Gérard', 'LeG', 'gerard.leg@email.com', '123456789', 'photo1.jpg', 'Monsieur', '123 Rue des lilas', 'gege', '1234', '2000-01-15', 4.5),
    ('Jeanne', 'Robert', 'jeanne.robert@email.com', '987654321', 'photo2.jpg', 'Madame', '456 Avenue Charles de Gaule', 'propro', '1234', '1998-07-25', 4.0),
    ('Julien', 'LeBras', 'julien.lebras@email.com', '895432156', 'photo2.jpg', 'Monsieur', '2 Rue du moine', 'JuJu', '1234', '1999-07-25', 4.0),
    ('Thierry', 'Richard', 'thierry.richard@email.com', '123456789', 'photo1.jpg', 'Monsieur', '123 Rue des lilas', 'trich', '1234', '15-01-2000', 4.5),
    ('Jeanne', 'Robert', 'jeanne.robert@email.com', '987654321', 'photo2.jpg', 'Madame', '456 Avenue Charles de Gaule', 'jrob', '1234', '25-07-1998', 4.0);


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
    
INSERT INTO ConversationMessagerie (numConversation, numMessagerie)
VALUES
    (1, 1),
    (2, 2);
    
-- Insertion de données dans la table Proprietaire
INSERT INTO Proprietaire (idCompte, RIB, pieceIdentite, languesParlees, messageType)
VALUES
    (1, 'FR7630001007941234567890185', TRUE,  'Français, Anglais', 'Message A'),
    (2, 'FR7630004000031234567890143', TRUE, 'Espagnol, Français, Anglais', 'Message B');


    
-- Insertion de données dans la table Logement
INSERT INTO Logement (surfaceHabitable, libelle, accroche, descriptionLogement, natureLogement, adresse, cp, ville, proprio, photoCouverture, LogementEnLigne, nbPersMax, nbChambres, nbSalleDeBain, tarifNuitees)
VALUES
    (80, 'Maison en pierre', 'Une adorable maison de charactère', 'Cette maison est parfaite pour un weekend en famille.', 'maison','9 rue des serpentins','22500','Lannion', 1, 'maison.jpg', TRUE, 4, 2, 1, 150.0),
    (100.2, 'Cave spacieuse', 'Au coeur de la ville', 'Profitez de la vie urbaine grâce à cette magnifique cave.', 'cave','2 rue des tulipes','29000','Brest', 2, 'cave.jpg', TRUE, 3, 1, 2, 120.0);

INSERT INTO Chambre (numChambre,numLogement, nbLitsSimples, nbLitsDoubles) VALUES (1,1, 2, 3);
INSERT INTO Chambre (numChambre,numLogement, nbLitsSimples, nbLitsDoubles) VALUES (2,1, 2, 3);

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

-- Insertion de plage dispo dans la table Plage
INSERT INTO Plage (isIndispo, numCal, dateDebutPlage, dateFinPlage, tarifJournalier)
VALUES
    (false, 2, '2023-11-20', '2023-11-27', 100),
    (false, 1, '2023-11-10', '2023-11-12', 120);
    
-- Insertion de plage indispo dans la table Plage
INSERT INTO Plage (isIndispo, numCal, dateDebutPlageI, dateFinPlageI)
VALUES
    (true, 2, '2023-11-10', '2023-11-20'),
    (true, 1, '2023-11-20', '2023-11-23');


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

-- Insertion de données dans la table Avis_Client
INSERT INTO AvisClient (idCompte, idDestinataire, idAvis)
VALUES
    (1, 2, 3),
    (2, 1, 4),
    (3, 2, 5),
    (1, 2, 6);

-- Insertion de données dans la table LogementProprio
INSERT INTO LogementProprio (numLogement,idCompte) 
VALUES 
    ('2','2'),
    ('1','2');

-- Insertion de données dans la table PhotosComplementairesLogement
INSERT INTO PhotosComplementairesLogement (numLogement, photosComplementaires) 
VALUES 
    (1, 'photo.png'),
    (2, 'photo.jgp');

INSERT INTO Admin (pseudo_admin, mdp_admin) VALUES ('admin', 'admin');



INSERT INTO Logement (surfaceHabitable, libelle, accroche, descriptionLogement, natureLogement, adresse, cp, ville, proprio, photoCouverture, LogementEnLigne, nbPersMax, nbChambres, nbSalleDeBain, tarifNuitees)
VALUES
(50, 'Appartement de charme', 'Un appartement cosy en plein centre-ville de Rennes', 'Cet appartement est situé au cœur de Rennes, à proximité de toutes les commodités. Il est idéal pour un séjour en amoureux ou un week-end en ville.', 'appartement', '2 rue du Chapitre', 35000, 'Rennes', 2, 'appartement_rennes.jpg', TRUE, 2, 1, 1, 100.0),
(70, 'Grande maison familiale', 'Une maison de famille au bord de la mer', 'Cette maison est située à seulement quelques minutes de la plage de Saint-Malo. Elle est idéale pour des vacances en famille ou entre amis.', 'maison', '12 rue de la plage', 35400, 'Saint-Malo', 2, 'maison_saintmalo.jpg', TRUE, 6, 3, 2, 200.0),
(80, 'Gîte au bord du lac', 'Un gîte confortable en pleine nature', 'Ce gîte est situé au bord du lac de Guerlédan. Il est idéal pour des vacances en amoureux ou un week-end en randonnée.', 'gite', '10 rue du lac', 22590, 'Mûr-de-Bretagne', 2, 'gite_guerledan.jpg', TRUE, 4, 2, 1, 150.0),
(100, 'Château de charme', 'Une expérience unique dans un château historique', 'Ce château est situé au cœur de la campagne bretonne. Il est idéal pour un séjour romantique ou un événement spécial.', 'chateau', '1 rue du château', 29500, 'Quimper', 2, 'chateau_quimper.jpg', TRUE, 10, 5, 3, 300.0);

INSERT INTO LogementProprio (numLogement,idCompte) 
VALUES 
    ('3','2'),
    ('4','2'),
    ('5','2'),
    ('6','2');
    

INSERT INTO Chambre (numChambre,numLogement, nbLitsSimples, nbLitsDoubles) VALUES (1,3, 0, 1);


INSERT INTO Equipement (numLogement, numeEquip, nom)
VALUES
(1, 1, 'Climatisation'),
(1, 2, 'TV écran plat'),
(2, 1, 'Climatisation'),
(2, 2, 'TV écran plat'),
(2, 3, 'Lecteur DVD'),
(3, 1, 'Climatisation'),
(3, 2, 'TV écran plat'),
(3, 3, 'Barbecue'),
(4, 1, 'Climatisation'),
(4, 2, 'TV écran plat'),
(4, 3, 'Jeux de société'),
(5, 1, 'Climatisation'),
(5, 2, 'TV écran plat'),
(5, 3, 'Matériel de sport'),
(6, 1, 'Climatisation'),
(6, 2, 'TV écran plat'),
(6, 3, 'Livres');

INSERT INTO Installation (numLogement, numInstall, nom)
VALUES
(1, 1, 'Alarme'),
(1, 2, 'Caméra de surveillance'),
(2, 1, 'Alarme'),
(2, 2, 'Caméra de surveillance'),
(3, 1, 'Alarme'),
(3, 2, 'Caméra de surveillance'),
(4, 1, 'Alarme'),
(4, 2, 'Caméra de surveillance'),
(5, 1, 'Alarme'),
(5, 2, 'Caméra de surveillance'),
(6, 1, 'Alarme'),
(6, 2, 'Caméra de surveillance');

INSERT INTO Service (numLogement, numServ, nom)
VALUES
(1, 1, 'Wifi'),
(1, 2, 'Accès lave-linge'),
(2, 1, 'Wifi'),
(2, 2, 'Accès lave-linge'),
(2, 3, 'Accès parking'),
(3, 1, 'Wifi'),
(3, 2, 'Accès lave-linge'),
(3, 3, 'Accès piscine'),
(4, 1, 'Wifi'),
(4, 2, 'Accès lave-linge'),
(4, 3, 'Accès sauna'),
(5, 1, 'Wifi'),
(5, 2, 'Accès lave-linge'),
(5, 3, 'Accès jacuzzi'),
(6, 1, 'Wifi'),
(6, 2, 'Accès lave-linge'),
(6, 3, 'Accès salle de sport');