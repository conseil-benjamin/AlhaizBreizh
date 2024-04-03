DROP SCHEMA IF EXISTS ldc CASCADE;
CREATE SCHEMA ldc;
SET SCHEMA 'ldc';

set datestyle to ISO, DMY;

CREATE TABLE Admin (
    idAdmin SERIAL NOT NULL PRIMARY KEY,
    pseudo_admin VARCHAR(50),
    mdp_admin VARCHAR(50)
);

CREATE TABLE APIkey (
    num_api SERIAL NOT NULL PRIMARY KEY,
    apikey VARCHAR(50) NOT NULL,
    droit VARCHAR(4) NOT NULL,
    id_proprio integer,
    id_admin integer,
    est_admin boolean NOT NULL,
    CHECK (
        (est_admin = true AND id_admin IS NOT NULL AND id_proprio IS NULL) OR
        (est_admin = false AND id_proprio IS NOT NULL AND id_admin IS NULL)
    )
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
    tarifNuitees DOUBLE PRECISION,
    note DOUBLE PRECISION,
    typeLogement VARCHAR(255),
    coordX DOUBLE PRECISION,
    coordY DOUBLE PRECISION
);

-- Table Abonnement Ical
CREATE TABLE tokenICal (
    token      VARCHAR(50) NOT NULL PRIMARY KEY,
    id_proprio integer     NOT NULL,
    date_debut text        NOT NULL,
    date_fin   text        NOT NULL

);

CREATE TABLE logements_tokenIcal (
    id           SERIAL NOT NULL PRIMARY KEY,
    token        VARCHAR    NOT NULL,
    num_logement INT    NOT NULL,
    CONSTRAINT fk_token FOREIGN KEY (token) REFERENCES tokenICal (token),
    CONSTRAINT fk_logements FOREIGN KEY (num_logement) REFERENCES Logement(numLogement)
);

CREATE TABLE Chambre (
    numChambre    SERIAL NOT NULL ,
    nbLitsSimples INTEGER,
    nbLitsDoubles INTEGER,
    PRIMARY KEY (numChambre)
);

CREATE TABLE LogementChambre
(
    id          SERIAL NOT NULL PRIMARY KEY,
    numChambre  INT    NOT NULL,
    numLogement INT    NOT NULL,
    FOREIGN KEY (numChambre) REFERENCES Chambre (numChambre),
    FOREIGN KEY (numLogement) REFERENCES Logement (numLogement)
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
    optionAnnulation VARCHAR(255),
    etatReservation VARCHAR(255)
    optionAnnulation VARCHAR(255),
    etatReservation VARCHAR(255)
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
    RIB VARCHAR(34),
    languesParlees VARCHAR(100),
    messageType VARCHAR(300),
    src_pi VARCHAR(300)
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
  prix FLOAT DEFAULT 0,
  PRIMARY KEY (numLogement,numServ),
  CONSTRAINT fk_numService
        FOREIGN KEY (numLogement)
        REFERENCES Logement (numLogement)
);

CREATE TABLE Devis_Services (
    ID SERIAL NOT NULL,
    numDevis INTEGER NOT NULL ,
    numServ INT NOT NULL ,
    numLogement INT NOT NULL ,
    PRIMARY KEY (ID),
    FOREIGN KEY (numDevis) REFERENCES Devis (numDevis),
    FOREIGN KEY (numLogement,numServ) REFERENCES Service (numLogement,numServ)
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

CREATE TABLE AvisLogement (
    numAvis SERIAL NOT NULL PRIMARY KEY,
    contenuAvis VARCHAR(255),
    nbEtoiles DOUBLE PRECISION,
    idClient INTEGER,
    idLogement INTEGER  
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
ALTER TABLE AvisLogement ADD CONSTRAINT avislogement_client_fk FOREIGN KEY (idClient) REFERENCES Client(idCompte);
ALTER TABLE LogementProprio ADD CONSTRAINT logementproprio_logement_fk FOREIGN KEY (numLogement) REFERENCES Logement(numLogement);
ALTER TABLE LogementProprio ADD CONSTRAINT logementproprio_proprietaire_fk FOREIGN KEY (idCompte) REFERENCES Proprietaire(idCompte);
ALTER TABLE APIkey ADD CONSTRAINT apikey_proprio_fk1 FOREIGN KEY (id_proprio) REFERENCES Proprietaire (idcompte);
ALTER TABLE APIkey ADD CONSTRAINT apikey_admin_fk1 FOREIGN KEY (id_admin) REFERENCES Admin (idAdmin);


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
    ('Jeanne', 'Robert', 'jeanne.robert@email.com', '987654321', 'photo2.jpg', 'Madame', '456 Avenue Charles de Gaule',
     'jrob', '1234', '25-07-1998', 4.0),
    ('Quentin', 'Dupond', 'Quentin.Dupond@free.fr', '987654321', 'photo1.jpg', 'Monsieur', '123 Rue des lilas',
     'quendpd', '1234', '15-01-1997', 4.4);

INSERT INTO AvisLogement (contenuAvis, nbEtoiles, idClient, idLogement)
VALUES
    ('Un véritable havre de paix ! Ce logement est niché dans un quartier calme et résidentiel, offrant une atmosphère paisible et relaxante.', 4.0, 1, 1),
    ('Un bijou architectural ! Cette maison unique allie élégance et fonctionnalité à la perfection. Les espaces de vie spacieux et lumineux sont idéaux pour se détendre en famille ou pour recevoir des invités.', 2.0, 2, 1),
    ('Une escapade romantique ! Niché dans un cadre pittoresque, ce chalet rustique offre une retraite romantique parfaite pour les couples.', 1.5, 2, 2),
    ('Un appartement moderne avec une touche d''histoire ! Situé dans un bâtiment historique rénové, cet appartement combine le charme du passé avec le confort contemporain. ', 1.5, 2, 3),
    ('Un appartement moderne avec une touche d''histoire ! Situé dans un bâtiment historique rénové, cet appartement combine le charme du passé avec le confort contemporain. ', 1.5, 3, 1),
    ('', 1.5, 3, 1);

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
    (2, 'FR7630004000031234567890143', TRUE, 'Espagnol, Français, Anglais', 'Message B'),
    (6, 'FR7630004000031234567890143', TRUE, 'Espagnol, Français, Anglais', 'Message B');


    
-- Insertion de données dans la table Logement
INSERT INTO Logement (surfaceHabitable, libelle, accroche, descriptionLogement, natureLogement, typeLogement, adresse, cp, ville, proprio, photoCouverture, LogementEnLigne, nbPersMax, nbChambres, nbSalleDeBain, tarifNuitees)
VALUES
    (80, 'Maison en pierre', 'Une adorable maison de charactère', 'Cette maison est parfaite pour un weekend en famille.', 'maison','maison','9 Rue Joseph Morand','22300','Lannion', 1, 'maison.jpg', TRUE, 4, 2, 1, 150.0),
    (100.2, 'Cave spacieuse', 'Au coeur de la ville', 'Profitez de la vie urbaine grâce à cette magnifique cave.',
     'cave','appartement', '5 Rue Colbert', '29000', 'Brest', 2, 'cave.jpg', TRUE, 3, 1, 2, 120.0),
    (50, 'Appartement de charme', 'Un appartement cosy en plein centre-ville de Rennes',
     'Cet appartement est situé au cœur de Rennes, à proximité de toutes les commodités. Il est idéal pour un séjour en amoureux ou un week-end en ville.',
     'appartement', 'appartement','Rue du 21 Juillet 1954', 35000, 'Rennes', 2, 'appartement_rennes.jpg', TRUE, 2, 1, 1, 100.0),
    (70, 'Grande maison familiale', 'Une maison de famille au bord de la mer',
     'Cette maison est située à seulement quelques minutes de la plage de Saint-Malo. Elle est idéale pour des vacances en famille ou entre amis.',
     'maison', 'maison', '12 Rue de la Maison Neuve', 35400, 'Saint-Malo', 2, 'maison_saintmalo.jpg', TRUE, 6, 3, 2, 200.0),
    (80, 'Gîte au bord du lac', 'Un gîte confortable en pleine nature',
     'Ce gîte est situé au bord du lac de Guerlédan. Il est idéal pour des vacances en amoureux ou un week-end en randonnée.',
     'gite', 'maison', '2 Rue Sainte-Suzanne', 22530, 'Guerlédan', 2, 'gite_guerledan.jpg', TRUE, 4, 2, 1, 150.0),
    (100, 'Château de charme', 'Une expérience unique dans un château historique',
     'Ce château est situé au cœur de la campagne bretonne. Il est idéal pour un séjour romantique ou un événement spécial.',
     'chateau', 'villa', '84 Rue de Kergestin', 29000, 'Quimper', 2, 'chateau_quimper.jpg', TRUE, 10, 5, 3, 300.0),
    (30, 'Maison Moche', 'Une maison moche', 'Cette maison est moche', 'maison','maison','19 Bd de la Fayette','22300','Lannion', 1, 'maison.jpg', TRUE, 4, 2, 1, 25.0),
    (40, 'Appartement Moche', 'Un appartement moche', 'Cet appartement est moche', 'appartement', 'appartement','10 Rue Jeanne d''Arc', 22300, 'Perros-Guirec', 2, 'appartement_rennes.jpg', TRUE, 2, 1, 1, 30.0),
    (120, 'Villa de rêve', 'Une villa où tout est possible', 'Cette villa est située au bord de la mer. Elle est idéale pour des vacances en famille ou entre amis.', 'villa', 'villa', '11 Rue des Ajoncs d''Or', 22710, 'Penvénan', 2, 'maison_saintmalo.jpg', TRUE, 6, 3, 2, 200.0),
    (60, 'Maison bretonne traditionnelle', 'Une charmante maison en pierre typiquement bretonne', 'Cette maison traditionnelle offre tout le confort moderne dans un cadre authentique.', 'maison', 'maison', '4 Rue du Port', 29600, 'Morlaix', 1, 'maison_bretagne.jpg', TRUE, 4, 2, 1, 140.0),
    (90, 'Appartement avec vue sur la mer', 'Vue imprenable depuis cet appartement en front de mer', 'Profitez du bruit des vagues et de la vue sur l''océan Atlantique depuis ce bel appartement.', 'appartement', 'appartement', '1 Rue de la Plage', 29900, 'Concarneau', 2, 'appartement_mer.jpg', TRUE, 4, 2, 1, 180.0),
    (55, 'Gîte rustique au milieu des champs', 'Retraite paisible dans cette ancienne ferme rénovée', 'Entouré de champs verdoyants, ce gîte offre calme et tranquillité pour un séjour ressourçant.', 'gite', 'maison', '6 Chemin des Champs', 56400, 'Auray', 1, 'gite_champs.jpg', TRUE, 3, 1, 1, 110.0),
    (75, 'Maison avec jardin clos', 'Espace extérieur parfait pour les familles', 'Idéal pour les enfants et les animaux de compagnie, cette maison offre un grand jardin clos.', 'maison', 'maison', '8 Rue des Lilas', 56000, 'Vannes', 1, 'maison_jardin.jpg', TRUE, 6, 3, 2, 190.0),
    (65, 'Appartement en centre-ville historique', 'Séjournez au cœur de la vieille ville', 'Cet appartement est situé dans le quartier historique de la ville, à proximité des sites touristiques et des restaurants.', 'appartement', 'appartement', '3 Rue des Remparts', 56100, 'Lorient', 2, 'appartement_historique.jpg', TRUE, 3, 1, 1, 130.0),
    (85, 'Villa moderne avec piscine', 'Vacances luxueuses dans cette villa contemporaine', 'Profitez du confort et du luxe dans cette villa équipée d''une piscine privée et d''équipements haut de gamme.', 'villa', 'villa', '2 Allée des Pins', 56800, 'Ploërmel', 2, 'villa_piscine.jpg', TRUE, 8, 4, 3, 280.0),
    (95, 'Maison de pêcheur rénovée', 'Charme authentique près du port', 'Cette maison de pêcheur rénovée offre un mélange parfait de charme traditionnel et de confort moderne.', 'maison', 'maison', '5 Rue du Port', 56410, 'Étel', 1, 'maison_pecheur.jpg', TRUE, 5, 2, 2, 160.0),
    (110, 'Château rénové avec parc', 'Un séjour royal dans ce château du XVIIIe siècle', 'Vivez comme la noblesse dans ce château magnifiquement rénové, entouré de vastes jardins et de forêts.', 'chateau', 'villa', '1 Avenue du Château', 56120, 'Josselin', 1, 'chateau_jardin.jpg', TRUE, 12, 6, 4, 350.0),
    (70, 'Appartement avec terrasse sur le port', 'Vue panoramique depuis cette terrasse ensoleillée', 'Profitez du spectacle des bateaux entrant et sortant du port depuis cette belle terrasse.', 'appartement', 'appartement', '7 Quai de la Douane', 56610, 'Arradon', 2, 'appartement_port.jpg', TRUE, 4, 2, 1, 150.0),
    (45, 'Gîte dans les collines bretonnes', 'Retraite paisible en pleine nature', 'Niché au cœur des collines bretonnes, ce gîte offre tranquillité et sérénité pour un séjour ressourçant.', 'gite', 'maison', '9 Chemin des Collines', 56700, 'Merlevenez', 2, 'gite_collines.jpg', TRUE, 3, 1, 1, 100.0),
    (105, 'Villa avec vue panoramique', 'Vues à couper le souffle depuis cette villa moderne', 'Cette villa contemporaine offre des vues imprenables sur la côte bretonne, avec un accès direct à la plage.', 'villa', 'villa', '3 Avenue de la Corniche', 56130, 'Nivillac', 2, 'villa_vue_mer.jpg', TRUE, 8, 4, 3, 270.0),
    (50, 'Maisonnette dans un village pittoresque', 'Séjour authentique dans ce village traditionnel', 'Cette charmante maisonnette est située dans un village préservé, offrant un cadre authentique pour des vacances en famille.', 'maison', 'maison', '10 Rue du Village', 56200, 'La Gacilly', 1, 'maison_village.jpg', TRUE, 4, 2, 1, 120.0),
    (75, 'Appartement avec balcon vue sur mer', 'Vues dégagées depuis ce balcon en front de mer', 'Profitez de l''air marin et du panorama sur l''océan Atlantique depuis ce confortable appartement.', 'appartement', 'appartement', '6 Boulevard de la Mer', 56340, 'Carnac', 2, 'appartement_balcon.jpg', TRUE, 4, 2, 1, 180.0),
    (80, 'Gîte en bordure de forêt', 'Retraite au cœur de la nature', 'Ce gîte est niché au bord d''une forêt préservée, offrant calme et tranquillité pour un séjour ressourçant.', 'gite', 'maison', '12 Chemin des Bois', 56470, 'La Trinité-sur-Mer', 1, 'gite_foret.jpg', TRUE, 4, 2, 1, 140.0),
    (65, 'Maison de campagne avec jardin', 'Séjour au calme dans la campagne bretonne', 'Cette maison de campagne offre un cadre paisible avec un grand jardin, idéal pour se ressourcer en pleine nature.', 'maison', 'maison', '11 Route de la Campagne', 56190, 'Muzillac', 1, 'maison_campagne.jpg', TRUE, 6, 3, 2, 160.0),
    (55, 'Appartement en bord de rivière', 'Tranquillité au fil de l''eau', 'Cet appartement offre une vue apaisante sur la rivière, avec la possibilité de pêcher et de se détendre au bord de l''eau.', 'appartement', 'appartement', '8 Quai des Berges', 56300, 'Pontivy', 2, 'appartement_riviere.jpg', TRUE, 3, 1, 1, 110.0),
    (70, 'Chalet en montagne', 'Séjour rustique au cœur des montagnes bretonnes', 'Ce chalet en bois est idéal pour les amateurs de nature et de randonnée, offrant un refuge chaleureux dans les montagnes bretonnes.', 'chalet', 'maison', '7 Rue des Montagnes', 56640, 'Arzon', 2, 'chalet_montagne.jpg', TRUE, 4, 2, 1, 150.0),
    (90, 'Villa avec piscine intérieure', 'Détente et luxe dans cette villa d''exception', 'Profitez d''une piscine intérieure privée et de tous les équipements haut de gamme dans cette villa moderne.', 'villa', 'villa', '5 Allée des Roses', 56170, 'Quiberon', 1, 'villa_piscine_interieure.jpg', TRUE, 8, 4, 3, 250.0),
    (85, 'Maison de vacances', 'Vue imprenable sur la campagne bretonne', 'Cette maison de vacances offre une vue panoramique sur les collines verdoyantes de la Bretagne, idéale pour un séjour reposant en pleine nature.', 'maison', 'maison', '3 Chemin des Prés', 56780, 'Île-aux-Moines', 2, 'maison_vacances.jpg', TRUE, 6, 3, 2, 200.0),
    (60, 'Appartement lumineux en ville', 'Séjour confortable au cœur de la ville', 'Cet appartement moderne et lumineux est situé en plein centre-ville, à proximité des commerces et des restaurants.', 'appartement', 'appartement', '2 Rue des Artisans', 56330, 'Pluvigner', 1, 'appartement_lumineux.jpg', TRUE, 4, 2, 1, 140.0),
    (110, 'Manoir historique avec parc privé', 'Un séjour royal dans un cadre enchanteur', 'Ce magnifique manoir du XVIIIe siècle offre un cadre majestueux avec son parc privé, idéal pour des vacances luxueuses en famille ou entre amis.', 'manoir', 'villa', '1 Chemin du Manoir', 56450, 'Theix-Noyalo', 1, 'manoir_historique.jpg', TRUE, 10, 5, 4, 350.0);


INSERT INTO Chambre (nbLitsSimples, nbLitsDoubles) VALUES ( 2, 3);
INSERT INTO Chambre (nbLitsSimples, nbLitsDoubles) VALUES ( 1, 1);
INSERT INTO Chambre (nbLitsSimples, nbLitsDoubles) VALUES ( 1, 2);

INSERT INTO LogementChambre(numLogement,numChambre) VALUES (1,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (1,2);

INSERT INTO LogementChambre(numLogement,numChambre) VALUES (2,1);

INSERT INTO LogementChambre(numLogement,numChambre) VALUES (3,3);


INSERT INTO LogementChambre(numLogement,numChambre) VALUES (4,3);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (4,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (4,1);

INSERT INTO LogementChambre(numLogement,numChambre) VALUES (5,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (5,2);

INSERT INTO LogementChambre(numLogement,numChambre) VALUES (6,3);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (6,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (6,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (6,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (6,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (7,3);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (8,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (9,3);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (9,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (9,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (10,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (11,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (12,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (13,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (14,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (15,3);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (16,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (17,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (18,3);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (19,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (20,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (21,3);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (22,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (23,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (24,3);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (25,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (26,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (27,3);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (28,2);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (29,1);
INSERT INTO LogementChambre(numLogement,numChambre) VALUES (30,3);

-- Insertion de données dans la table Reservation
INSERT INTO Reservation (numClient, numLogement, dateReservation, nbPersonnes, dateDebut, dateFin, dateDevis, nbJours, optionAnnulation, etatReservation)
INSERT INTO Reservation (numClient, numLogement, dateReservation, nbPersonnes, dateDebut, dateFin, dateDevis, nbJours, optionAnnulation, etatReservation)
VALUES
    (1, 2, '2023-10-18', 2, '2023-11-01', '2023-11-07', '2023-10-15', 7, 'Stricte', 'Annulée'),
    (1, 5, '2023-10-19', 2, '2023-11-04', '2023-11-22', '2023-10-25', 7, 'Stricte', 'Validée'),
    (2, 1, '2023-10-20', 3, '2023-11-05', '2023-11-10', '2023-10-16', 6, 'Flexible', 'Validée'),
    (1, 10, '2023-10-20', 3, '2024-11-05', '2024-11-10', '2023-10-16', 6, 'Flexible', 'En attente de validation');
    (1, 2, '2023-10-18', 2, '2023-11-01', '2023-11-07', '2023-10-15', 7, 'Stricte', 'Annulée'),
    (1, 5, '2023-10-19', 2, '2023-11-04', '2023-11-22', '2023-10-25', 7, 'Stricte', 'Validée'),
    (2, 1, '2023-10-20', 3, '2023-11-05', '2023-11-10', '2023-10-16', 6, 'Flexible', 'Validée'),
    (1, 10, '2023-10-20', 3, '2024-11-05', '2024-11-10', '2023-10-16', 6, 'Flexible', 'En attente de validation');

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
INSERT INTO Plage (isIndispo, numCal, dateDebutPlage, dateFinPlage)
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

-- Insertion de données dans la table PhotosComplementairesLogement
INSERT INTO PhotosComplementairesLogement (numLogement, photosComplementaires) 
VALUES 
    (1, 'photo.png'),
    (2, 'photo.jpg');

INSERT INTO Admin (pseudo_admin, mdp_admin) VALUES ('admin', 'admin');

INSERT INTO LogementProprio (numLogement,idCompte)
VALUES
    ('3','2'),
    ('4','2'),
    ('5','2'),
    ('6','2');


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

INSERT INTO Service (numLogement, numServ, nom, prix)
VALUES
(1, 1, 'Wifi', 10.0),
(1, 2, 'Accès lave-linge', 15.0),
(2, 1, 'Wifi', 10.0),
(2, 2, 'Accès lave-linge', 15.0),
(2, 3, 'Accès parking', 20.0),
(3, 1, 'Wifi', 5.0),
(3, 2, 'Accès lave-linge', 10.0),
(3, 3, 'Accès piscine', 30.0),
(4, 1, 'Wifi', 12.0),
(4, 2, 'Accès lave-linge', 25.0),
(4, 3, 'Accès sauna', 40.0),
(5, 1, 'Wifi', 5.0),
(5, 2, 'Accès lave-linge', 5.0),
(5, 3, 'Accès jacuzzi', 25.0),
(6, 1, 'Wifi', 5.0),
(6, 2, 'Accès lave-linge', 5.0),
(6, 3, 'Accès salle de sport', 5.0);

