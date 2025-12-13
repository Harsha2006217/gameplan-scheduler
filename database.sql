-- ============================================================================
-- database.sql - Database Schema Script
-- ============================================================================
-- 
-- @author      Harsha Kanaparthi
-- @student     2195344
-- @date        30-09-2025
-- @version     1.0
-- @project     GamePlan Scheduler
-- 
-- ============================================================================
-- BESCHRIJVING / DESCRIPTION:
-- ============================================================================
-- Dit SQL script maakt de volledige database structuur aan voor GamePlan
-- Scheduler. Het bevat:
-- 
-- 1. DATABASE AANMAKEN - gameplan_db met UTF-8 ondersteuning
-- 2. TABELLEN - Users, Games, UserGames, Friends, Schedules, Events
-- 3. RELATIES - Foreign keys tussen tabellen
-- 4. INDEXEN - Voor snelle queries
-- 5. VOORBEELD DATA - Enkele games om mee te beginnen
-- 
-- This SQL script creates the complete database structure for GamePlan
-- Scheduler including all tables, relationships, and indexes.
-- 
-- ============================================================================
-- INSTRUCTIES VOOR INSTALLATIE / INSTALLATION INSTRUCTIONS:
-- ============================================================================
-- 1. Start XAMPP (Apache + MySQL)
-- 2. Open phpMyAdmin: http://localhost/phpmyadmin
-- 3. Klik op "Import" tabblad
-- 4. Selecteer dit bestand (database.sql)
-- 5. Klik "Go" om uit te voeren
-- 
-- Of gebruik MySQL command line:
-- mysql -u root -p < database.sql
-- ============================================================================

-- ============================================================================
-- DATABASE AANMAKEN / CREATE DATABASE
-- ============================================================================
-- CREATE DATABASE IF NOT EXISTS - Maakt alleen aan als deze nog niet bestaat
-- CHARACTER SET utf8mb4 - Ondersteunt alle Unicode karakters (incl. emoji's)
-- COLLATE utf8mb4_unicode_ci - Case-insensitive vergelijking (A = a)
-- ============================================================================
CREATE DATABASE IF NOT EXISTS gameplan_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- ============================================================================
-- SELECTEER DATABASE / USE DATABASE
-- ============================================================================
-- Vanaf nu werken alle commando's op gameplan_db
-- ============================================================================
USE gameplan_db;

-- ############################################################################
-- ##                                                                        ##
-- ##                         TABEL 1: USERS                                 ##
-- ##                         (Gebruikers Tabel)                             ##
-- ##                                                                        ##
-- ############################################################################
-- 
-- Deze tabel slaat alle geregistreerde gebruikers op.
-- Elke gebruiker heeft een uniek ID, gebruikersnaam, email, en wachtwoord hash.
-- 
-- KOLOMMEN UITLEG:
-- - user_id: Unieke identificatie (automatisch opgehoogd)
-- - username: Weergavenaam van de gebruiker
-- - email: E-mail adres (moet uniek zijn voor login)
-- - password_hash: Bcrypt gehashte wachtwoord (NOOIT plain text!)
-- - last_activity: Wanneer gebruiker laatst actief was
-- - deleted_at: Voor "soft delete" - gebruiker niet echt verwijderen
-- 
-- ############################################################################

CREATE TABLE IF NOT EXISTS Users (
    -- ========================================================================
    -- PRIMARY KEY: user_id
    -- ========================================================================
    -- INT = geheel getal (max 2.147.483.647 gebruikers mogelijk)
    -- AUTO_INCREMENT = database verhoogt automatisch bij elke nieuwe gebruiker
    -- PRIMARY KEY = unieke identifier, elke rij heeft een andere waarde
    -- ========================================================================
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- ========================================================================
    -- username - De weergavenaam
    -- ========================================================================
    -- VARCHAR(50) = variabele tekst, maximaal 50 karakters
    -- NOT NULL = verplicht veld, mag niet leeg zijn
    -- ========================================================================
    username VARCHAR(50) NOT NULL,
    
    -- ========================================================================
    -- email - Het e-mail adres
    -- ========================================================================
    -- VARCHAR(100) = maximaal 100 karakters voor e-mail
    -- UNIQUE = geen twee gebruikers kunnen hetzelfde email hebben
    -- NOT NULL = verplicht veld
    -- ========================================================================
    email VARCHAR(100) UNIQUE NOT NULL,
    
    -- ========================================================================
    -- password_hash - Het gehashte wachtwoord
    -- ========================================================================
    -- VARCHAR(255) = ruimte voor bcrypt hash (60 karakters) + marge
    -- NOT NULL = verplicht (iedereen moet een wachtwoord hebben)
    -- 
    -- BELANGRIJK: Dit is NIET het echte wachtwoord!
    -- Het is een cryptografische hash die niet omgekeerd kan worden.
    -- password_hash('geheim123', PASSWORD_BCRYPT) geeft bijv:
    -- $2y$10$xyz...abc... (60 karakters)
    -- ========================================================================
    password_hash VARCHAR(255) NOT NULL,
    
    -- ========================================================================
    -- last_activity - Laatste activiteit tijdstip
    -- ========================================================================
    -- TIMESTAMP = datum en tijd
    -- DEFAULT CURRENT_TIMESTAMP = standaard is nu
    -- ON UPDATE CURRENT_TIMESTAMP = update automatisch bij wijziging
    -- ========================================================================
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- ========================================================================
    -- deleted_at - Soft delete tijdstip
    -- ========================================================================
    -- TIMESTAMP NULL = kan leeg zijn (NULL = niet verwijderd)
    -- Als dit een datum heeft, is de gebruiker "verwijderd"
    -- We verwijderen nooit echt, we markeren alleen als verwijderd
    -- ========================================================================
    deleted_at TIMESTAMP NULL
    
-- ============================================================================
-- ENGINE=InnoDB - Database engine met transactie ondersteuning
-- DEFAULT CHARSET=utf8mb4 - Unicode ondersteuning
-- ============================================================================
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ############################################################################
-- ##                                                                        ##
-- ##                         TABEL 2: GAMES                                 ##
-- ##                         (Spellen Tabel)                                ##
-- ##                                                                        ##
-- ############################################################################
-- 
-- Deze tabel slaat alle beschikbare games op.
-- Gebruikers kunnen games toevoegen aan hun favorieten.
-- Nieuwe games worden automatisch aangemaakt als een gebruiker een
-- game opgeeft die nog niet bestaat.
-- 
-- ############################################################################

CREATE TABLE IF NOT EXISTS Games (
    -- ========================================================================
    -- PRIMARY KEY: game_id
    -- ========================================================================
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- ========================================================================
    -- titel - De naam van de game
    -- ========================================================================
    -- "titel" is Nederlands voor "title"
    -- VARCHAR(100) = maximaal 100 karakters
    -- ========================================================================
    titel VARCHAR(100) NOT NULL,
    
    -- ========================================================================
    -- description - Beschrijving van de game
    -- ========================================================================
    -- TEXT = langere tekst (tot 65.535 karakters)
    -- Geen NOT NULL = beschrijving is optioneel
    -- ========================================================================
    description TEXT,
    
    -- ========================================================================
    -- deleted_at - Soft delete
    -- ========================================================================
    deleted_at TIMESTAMP NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ############################################################################
-- ##                                                                        ##
-- ##                       TABEL 3: USERGAMES                               ##
-- ##                    (Koppeltabel Gebruikers-Games)                      ##
-- ##                                                                        ##
-- ############################################################################
-- 
-- Dit is een KOPPELTABEL (ook wel "junction table" of "many-to-many table").
-- 
-- PROBLEEM: Een gebruiker kan MEERDERE favoriete games hebben, en een game
--           kan favoriet zijn van MEERDERE gebruikers.
--           Dit is een "many-to-many" (veel-op-veel) relatie.
-- 
-- OPLOSSING: We maken een koppeltabel met:
--            - user_id (verwijst naar Users)
--            - game_id (verwijst naar Games)
--            - note (persoonlijke notitie)
-- 
-- Elke rij in deze tabel betekent: "Gebruiker X heeft Game Y als favoriet"
-- 
-- ############################################################################

CREATE TABLE IF NOT EXISTS UserGames (
    -- ========================================================================
    -- user_id - Verwijzing naar de gebruiker
    -- ========================================================================
    -- Dit is een FOREIGN KEY (zie onder)
    -- ========================================================================
    user_id INT NOT NULL,
    
    -- ========================================================================
    -- game_id - Verwijzing naar de game
    -- ========================================================================
    game_id INT NOT NULL,
    
    -- ========================================================================
    -- note - Persoonlijke notitie over de game
    -- ========================================================================
    -- TEXT voor langere notities
    -- Kan leeg zijn (geen NOT NULL)
    -- ========================================================================
    note TEXT,
    
    -- ========================================================================
    -- SAMENGESTELDE PRIMARY KEY
    -- ========================================================================
    -- De combinatie (user_id, game_id) is de primary key
    -- Dit betekent:
    -- - Gebruiker 1 kan Game 5 maar ÉÉN keer als favoriet hebben
    -- - Gebruiker 1 kan WEL Game 5 én Game 6 hebben
    -- - Gebruiker 2 kan OOK Game 5 hebben
    -- ========================================================================
    PRIMARY KEY (user_id, game_id),
    
    -- ========================================================================
    -- FOREIGN KEY naar Users
    -- ========================================================================
    -- FOREIGN KEY = deze kolom moet verwijzen naar een bestaande rij
    -- REFERENCES Users(user_id) = moet bestaan in Users tabel
    -- ON DELETE CASCADE = als gebruiker verwijderd wordt, verwijder ook
    --                     al hun favorieten (automatisch opschonen)
    -- ========================================================================
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    
    -- ========================================================================
    -- FOREIGN KEY naar Games
    -- ========================================================================
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ############################################################################
-- ##                                                                        ##
-- ##                        TABEL 4: FRIENDS                                ##
-- ##                        (Vrienden Tabel)                                ##
-- ##                                                                        ##
-- ############################################################################
-- 
-- Deze tabel slaat de vrienden van elke gebruiker op.
-- We slaan de vriend op als USERNAME (tekst), niet als user_id.
-- 
-- WAAROM USERNAME IN PLAATS VAN USER_ID?
-- - Vrienden hoeven geen account te hebben in onze app
-- - Je kunt gaming vrienden toevoegen die de app niet gebruiken
-- - Dit maakt de app flexibeler
-- 
-- ############################################################################

CREATE TABLE IF NOT EXISTS Friends (
    -- ========================================================================
    -- PRIMARY KEY: friend_id
    -- ========================================================================
    friend_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- ========================================================================
    -- user_id - De eigenaar van deze vriendenlijst
    -- ========================================================================
    user_id INT NOT NULL,
    
    -- ========================================================================
    -- friend_username - De naam van de vriend
    -- ========================================================================
    -- VARCHAR(50) = maximaal 50 karakters
    -- Dit is de gamer tag / gebruikersnaam van de vriend
    -- ========================================================================
    friend_username VARCHAR(50) NOT NULL,
    
    -- ========================================================================
    -- note - Notitie over de vriend
    -- ========================================================================
    -- Bijv: "Kent via Fortnite toernooi" of "Speelt meestal 's avonds"
    -- ========================================================================
    note TEXT,
    
    -- ========================================================================
    -- status - De status van de vriend
    -- ========================================================================
    -- Bijv: "Online", "Offline", "Gaming", "AFK"
    -- DEFAULT 'Offline' = standaard waarde
    -- ========================================================================
    status VARCHAR(50) DEFAULT 'Offline',
    
    -- ========================================================================
    -- deleted_at - Soft delete
    -- ========================================================================
    deleted_at TIMESTAMP NULL,
    
    -- ========================================================================
    -- FOREIGN KEY naar Users
    -- ========================================================================
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ############################################################################
-- ##                                                                        ##
-- ##                       TABEL 5: SCHEDULES                               ##
-- ##                       (Speelschema's Tabel)                            ##
-- ##                                                                        ##
-- ############################################################################
-- 
-- Deze tabel slaat speelschema's op - wanneer je van plan bent om te spelen.
-- 
-- RELATIES:
-- - Elke schedule hoort bij één gebruiker (user_id)
-- - Elke schedule is voor één game (game_id)
-- - Friends en shared_with zijn TEXT velden met komma-gescheiden namen
-- 
-- ############################################################################

CREATE TABLE IF NOT EXISTS Schedules (
    -- ========================================================================
    -- PRIMARY KEY: schedule_id
    -- ========================================================================
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- ========================================================================
    -- user_id - Wie heeft dit schema gemaakt
    -- ========================================================================
    user_id INT NOT NULL,
    
    -- ========================================================================
    -- game_id - Welke game
    -- ========================================================================
    game_id INT NOT NULL,
    
    -- ========================================================================
    -- date - De datum van de gaming sessie
    -- ========================================================================
    -- DATE format: YYYY-MM-DD (bijv: 2025-10-15)
    -- ========================================================================
    date DATE NOT NULL,
    
    -- ========================================================================
    -- time - De starttijd
    -- ========================================================================
    -- TIME format: HH:MM:SS (bijv: 20:00:00)
    -- ========================================================================
    time TIME NOT NULL,
    
    -- ========================================================================
    -- friends - Met wie je gaat spelen
    -- ========================================================================
    -- TEXT met komma-gescheiden namen, bijv: "Jan, Piet, Klaas"
    -- Kan leeg zijn als je solo speelt
    -- ========================================================================
    friends TEXT,
    
    -- ========================================================================
    -- shared_with - Met wie je dit schema deelt
    -- ========================================================================
    -- TEXT met komma-gescheiden namen
    -- Dit zijn mensen die dit schema kunnen zien
    -- ========================================================================
    shared_with TEXT,
    
    -- ========================================================================
    -- deleted_at - Soft delete
    -- ========================================================================
    deleted_at TIMESTAMP NULL,
    
    -- ========================================================================
    -- FOREIGN KEYS
    -- ========================================================================
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ############################################################################
-- ##                                                                        ##
-- ##                        TABEL 6: EVENTS                                 ##
-- ##                       (Evenementen Tabel)                              ##
-- ##                                                                        ##
-- ############################################################################
-- 
-- Deze tabel slaat evenementen op zoals toernooien, game releases, etc.
-- 
-- VERSCHIL MET SCHEDULES:
-- - Schedule = dagelijkse/regelmatige gaming sessies
-- - Event = speciale eenmalige gebeurtenissen (toernooi, release, etc.)
-- 
-- EXTRA FEATURES:
-- - reminder: kan 1 uur of 1 dag van tevoren herinneren
-- - external_link: link naar bijv. toernooi registratie pagina
-- 
-- ############################################################################

CREATE TABLE IF NOT EXISTS Events (
    -- ========================================================================
    -- PRIMARY KEY: event_id
    -- ========================================================================
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- ========================================================================
    -- user_id - Wie heeft dit event gemaakt
    -- ========================================================================
    user_id INT NOT NULL,
    
    -- ========================================================================
    -- title - Titel van het evenement
    -- ========================================================================
    -- Bijv: "Fortnite Toernooi", "Minecraft Server Launch", etc.
    -- ========================================================================
    title VARCHAR(100) NOT NULL,
    
    -- ========================================================================
    -- date & time - Wanneer is het evenement
    -- ========================================================================
    date DATE NOT NULL,
    time TIME NOT NULL,
    
    -- ========================================================================
    -- description - Uitgebreide beschrijving
    -- ========================================================================
    -- TEXT voor langere beschrijvingen
    -- ========================================================================
    description TEXT,
    
    -- ========================================================================
    -- reminder - Herinneringsinstelling
    -- ========================================================================
    -- VARCHAR(50) met waardes: 'none', '1_hour', '1_day'
    -- JavaScript gebruikt dit om pop-ups te tonen
    -- ========================================================================
    reminder VARCHAR(50),
    
    -- ========================================================================
    -- external_link - Link naar externe pagina
    -- ========================================================================
    -- VARCHAR(255) voor URL
    -- Bijv: link naar toernooi aanmelding, Discord server, etc.
    -- ========================================================================
    external_link VARCHAR(255),
    
    -- ========================================================================
    -- shared_with - Met wie dit event gedeeld is
    -- ========================================================================
    shared_with TEXT,
    
    -- ========================================================================
    -- deleted_at - Soft delete
    -- ========================================================================
    deleted_at TIMESTAMP NULL,
    
    -- ========================================================================
    -- FOREIGN KEY naar Users
    -- ========================================================================
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ############################################################################
-- ##                                                                        ##
-- ##                           INDEXEN                                      ##
-- ##                    (Performance Optimalisatie)                         ##
-- ##                                                                        ##
-- ############################################################################
-- 
-- INDEXEN maken zoekopdrachten sneller.
-- 
-- ANALOGIE: Denk aan een index achter in een boek.
-- Zonder index: je moet het hele boek doorlezen om iets te vinden
-- Met index: je kijkt achter in het boek en gaat direct naar de juiste pagina
-- 
-- We maken indexen op kolommen waar we vaak op zoeken/filteren.
-- 
-- ############################################################################

-- ============================================================================
-- Index op Users.email - We zoeken vaak op email (bij login)
-- ============================================================================
CREATE INDEX idx_users_email ON Users(email);

-- ============================================================================
-- Index op Schedules (user_id, date) - We halen vaak schedules op per gebruiker en datum
-- ============================================================================
CREATE INDEX idx_schedules_user_date ON Schedules(user_id, date);

-- ============================================================================
-- Index op Events (user_id, date) - Zelfde reden als boven
-- ============================================================================
CREATE INDEX idx_events_user_date ON Events(user_id, date);


-- ############################################################################
-- ##                                                                        ##
-- ##                        VOORBEELD DATA                                  ##
-- ##                      (Sample/Test Data)                                ##
-- ##                                                                        ##
-- ############################################################################
-- 
-- We voegen een paar populaire games toe zodat nieuwe gebruikers
-- meteen uit een lijst kunnen kiezen.
-- 
-- ############################################################################

INSERT INTO Games (titel, description) VALUES
    ('Fortnite', 'Battle Royale game where 100 players compete to be the last one standing'),
    ('Minecraft', 'Sandbox building and survival game with endless possibilities'),
    ('League of Legends', 'MOBA strategy game with team-based competitive gameplay'),
    ('Call of Duty', 'First-person shooter game with multiplayer modes'),
    ('FIFA 25', 'Football/soccer simulation game'),
    ('Rocket League', 'Vehicular soccer game with rocket-powered cars');

-- ============================================================================
-- EINDE VAN HET SCRIPT / END OF SCRIPT
-- ============================================================================
-- De database is nu klaar voor gebruik!
-- 
-- VOLGENDE STAPPEN:
-- 1. Ga naar http://localhost/gameplan-scheduler/
-- 2. Registreer een account
-- 3. Begin met het toevoegen van favoriete games, vrienden, en schema's!
-- ============================================================================