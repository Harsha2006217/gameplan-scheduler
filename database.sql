-- ============================================================================
-- database.sql - DATABASE SCHEMA SCRIPT
-- ============================================================================
-- 
-- AUTEUR / AUTHOR: Harsha Kanaparthi
-- STUDENTNUMMER / STUDENT NUMBER: 2195344
-- DATUM / DATE: 30-09-2025
-- PROJECT: GamePlan Scheduler
-- 
-- ============================================================================
-- WAT DOET DIT BESTAND? / WHAT DOES THIS FILE DO?
-- ============================================================================
-- 
-- Dit SQL-script maakt de complete database structuur aan voor GamePlan Scheduler.
-- Het bevat 6 tabellen die samen alle data van de applicatie opslaan:
-- 
-- 1. Users     - Gebruikersaccounts (naam, email, wachtwoord)
-- 2. Games     - Alle games die in de app worden gebruikt
-- 3. UserGames - Koppeling tussen gebruikers en hun favoriete games
-- 4. Friends   - Vriendenlijst van gebruikers
-- 5. Schedules - Gaming sessie schema's
-- 6. Events    - Evenementen zoals toernooien
-- 
-- ============================================================================
-- HOE DIT SCRIPT UITVOEREN?
-- ============================================================================
-- 
-- 1. Open XAMPP Control Panel en start MySQL
-- 2. Open phpMyAdmin (http://localhost/phpmyadmin)
-- 3. Klik op "Import" tab
-- 4. Selecteer dit bestand (database.sql)
-- 5. Klik op "Go" / "Uitvoeren"
-- 
-- OF kopieer-plak de code in de SQL tab en voer uit.
-- 
-- ============================================================================


-- ============================================================================
-- STAP 1: DATABASE AANMAKEN
-- ============================================================================

-- Maak database aan als deze nog niet bestaat
-- CHARACTER SET utf8mb4: Ondersteunt alle tekens inclusief emoji's
-- COLLATE utf8mb4_unicode_ci: Zorgt voor correcte sortering van tekst (case-insensitive)
CREATE DATABASE IF NOT EXISTS gameplan_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Selecteer de database voor verdere commando's
USE gameplan_db;


-- ============================================================================
-- STAP 2: USERS TABEL - Gebruikersaccounts
-- ============================================================================
-- 
-- Deze tabel slaat alle geregistreerde gebruikers op.
-- Elk account heeft een uniek ID, gebruikersnaam, email en gehashed wachtwoord.
-- 
-- KOLOMMEN:
-- - user_id: Unieke identificatie (automatisch oplopend nummer)
-- - username: Weergavenaam van de gebruiker (max 50 tekens)
-- - email: Email adres (uniek, voor inloggen)
-- - password_hash: Wachtwoord versleuteld met bcrypt (NOOIT plain text!)
-- - last_activity: Laatste activiteit voor sessie timeout controle
-- - deleted_at: Soft delete - als gevuld is account "verwijderd" maar data blijft
-- 
CREATE TABLE IF NOT EXISTS Users (
    -- PRIMARY KEY: Uniek ID voor elke gebruiker, AUTO_INCREMENT = automatisch +1
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Gebruikersnaam, NOT NULL = verplicht veld
    username VARCHAR(50) NOT NULL,
    
    -- Email, UNIQUE = geen dubbele emails toegestaan
    email VARCHAR(100) UNIQUE NOT NULL,
    
    -- Gehashed wachtwoord (bcrypt genereert ~60 karakters, 255 voor toekomst)
    password_hash VARCHAR(255) NOT NULL,
    
    -- Laatste activiteit, automatisch bijgewerkt bij elke wijziging
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Soft delete: NULL = actief, timestamp = "verwijderd"
    deleted_at TIMESTAMP NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- ENGINE=InnoDB: Ondersteunt foreign keys en transacties (veiliger)


-- ============================================================================
-- STAP 3: GAMES TABEL - Beschikbare games
-- ============================================================================
-- 
-- Deze tabel bevat alle games die gebruikers kunnen toevoegen aan hun profiel
-- of kunnen koppelen aan een schedule.
-- 
CREATE TABLE IF NOT EXISTS Games (
    -- Uniek ID voor elke game
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Titel van de game (bijv. "Fortnite", "Minecraft")
    -- LET OP: 'titel' is Nederlands, zou 'title' kunnen zijn in Engels
    titel VARCHAR(100) NOT NULL,
    
    -- Beschrijving van de game (TEXT = voor langere teksten)
    description TEXT,
    
    -- Soft delete
    deleted_at TIMESTAMP NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STAP 4: USERGAMES TABEL - Favoriete games van gebruikers
-- ============================================================================
-- 
-- Dit is een KOPPELTABEL (junction table) die een many-to-many relatie maakt:
-- - Eén gebruiker kan MEERDERE favoriete games hebben
-- - Eén game kan favoriet zijn van MEERDERE gebruikers
-- 
-- De PRIMARY KEY is een COMBINATIE van user_id en game_id, wat betekent dat
-- een gebruiker dezelfde game maar ÉÉN keer kan toevoegen.
-- 
CREATE TABLE IF NOT EXISTS UserGames (
    -- Verwijzing naar Users tabel
    user_id INT NOT NULL,
    
    -- Verwijzing naar Games tabel
    game_id INT NOT NULL,
    
    -- Persoonlijke notitie van gebruiker over de game
    note TEXT,
    
    -- Samengestelde primary key: combinatie moet uniek zijn
    PRIMARY KEY (user_id, game_id),
    
    -- FOREIGN KEY: zorgt dat user_id moet bestaan in Users tabel
    -- ON DELETE CASCADE: als gebruiker verwijderd wordt, worden ook zijn favorieten verwijderd
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    
    -- FOREIGN KEY naar Games tabel
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STAP 5: FRIENDS TABEL - Vriendenlijst
-- ============================================================================
-- 
-- Slaat de vrienden van elke gebruiker op.
-- Vrienden worden opgeslagen op gebruikersnaam (niet user_id) zodat je ook
-- vrienden kunt toevoegen die geen account in de app hebben.
-- 
CREATE TABLE IF NOT EXISTS Friends (
    -- Uniek ID voor elke vriendschap
    friend_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- De gebruiker die de vriend heeft toegevoegd
    user_id INT NOT NULL,
    
    -- Gebruikersnaam van de vriend (kan buiten systeem zijn)
    friend_username VARCHAR(50) NOT NULL,
    
    -- Persoonlijke notitie over de vriend
    note TEXT,
    
    -- Status van de vriend (bijv. "Online", "Offline", "Gaming")
    status VARCHAR(50) DEFAULT 'Offline',
    
    -- Soft delete
    deleted_at TIMESTAMP NULL,
    
    -- FOREIGN KEY naar Users
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STAP 6: SCHEDULES TABEL - Speelschema's
-- ============================================================================
-- 
-- Slaat geplande gaming sessies op.
-- Een schedule heeft een datum, tijd, game, en kan gedeeld worden met vrienden.
-- 
CREATE TABLE IF NOT EXISTS Schedules (
    -- Uniek ID voor elk schema
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Eigenaar van het schema
    user_id INT NOT NULL,
    
    -- Welke game wordt gespeeld
    game_id INT NOT NULL,
    
    -- Datum van de sessie (YYYY-MM-DD formaat)
    date DATE NOT NULL,
    
    -- Tijd van de sessie (HH:MM:SS formaat)
    time TIME NOT NULL,
    
    -- Vrienden die meedoen (komma-gescheiden lijst)
    friends TEXT,
    
    -- Met wie gedeeld (komma-gescheiden lijst)
    shared_with TEXT,
    
    -- Soft delete
    deleted_at TIMESTAMP NULL,
    
    -- FOREIGN KEYS
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STAP 7: EVENTS TABEL - Evenementen
-- ============================================================================
-- 
-- Slaat gaming events op zoals toernooien, streams, releases, etc.
-- Events kunnen herinneringen hebben en externe links.
-- 
CREATE TABLE IF NOT EXISTS Events (
    -- Uniek ID voor elk event
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Eigenaar van het event
    user_id INT NOT NULL,
    
    -- Titel van het event (bijv. "Fortnite Tournament")
    title VARCHAR(100) NOT NULL,
    
    -- Datum van het event
    date DATE NOT NULL,
    
    -- Tijd van het event
    time TIME NOT NULL,
    
    -- Beschrijving van het event
    description TEXT,
    
    -- Herinnering: 'none', '1_hour', of '1_day'
    reminder VARCHAR(50),
    
    -- Link naar externe pagina (bijv. tournament website)
    external_link VARCHAR(255),
    
    -- Met wie gedeeld
    shared_with TEXT,
    
    -- Soft delete
    deleted_at TIMESTAMP NULL,
    
    -- FOREIGN KEY
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STAP 8: INDEXES AANMAKEN - Voor snellere queries
-- ============================================================================
-- 
-- Indexes zijn als een inhoudsopgave in een boek:
-- Zonder index moet de database ELKE rij doorzoeken (langzaam!)
-- Met index kan de database direct naar de juiste rij springen (snel!)
-- 

-- Index op email in Users: Snel zoeken bij inloggen
CREATE INDEX idx_users_email ON Users(email);

-- Index op user_id en date in Schedules: Snel kalender ophalen
CREATE INDEX idx_schedules_user_date ON Schedules(user_id, date);

-- Index op user_id en date in Events: Snel events ophalen
CREATE INDEX idx_events_user_date ON Events(user_id, date);


-- ============================================================================
-- STAP 9: VOORBEELD DATA INVOEGEN
-- ============================================================================
-- 
-- Dit voegt wat standaard games toe zodat users iets te kiezen hebben.
-- Bij een echte productie zou dit via een admin panel worden gedaan.
-- 

INSERT INTO Games (titel, description) VALUES
    ('Fortnite', 'Battle Royale game - Populaire multiplayer shooter'),
    ('Minecraft', 'Sandbox building game - Bouw je eigen wereld'),
    ('League of Legends', 'MOBA strategy game - Teamgebaseerde strategie'),
    ('Valorant', 'Tactical shooter - 5v5 competitief'),
    ('Rocket League', 'Soccer with cars - Voetbal met auto\'s'),
    ('Apex Legends', 'Battle Royale - Squad-based shooter');


-- ============================================================================
-- EINDE VAN HET SCRIPT
-- ============================================================================
-- 
-- SAMENVATTING TABEL RELATIES:
-- 
--    [Users] ─────1:N────> [Friends]
--       │                     
--       │ ──────1:N────> [Schedules] ────N:1────> [Games]
--       │                     
--       │ ──────1:N────> [Events]
--       │
--       └──────M:N────> [UserGames] ────N:1────> [Games]
-- 
-- 
-- 1:N = Een-op-veel relatie (bijv. één user heeft veel schedules)
-- M:N = Veel-op-veel relatie (bijv. users en hun favoriete games)
-- 
-- ============================================================================