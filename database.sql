/* ==========================================================================
   DATABASE.SQL - DATABASE STRUCTUUR VOOR GAMEPLAN SCHEDULER
   ==========================================================================
   Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025

   Dit SQL-bestand maakt de complete database voor GamePlan Scheduler.
   Voer dit bestand uit in phpMyAdmin om de database aan te maken.

   Tabellen: Users, Games, UserGames, Friends, Schedules, Events
   ========================================================================== */


/* --------------------------------------------------------------------------
   STAP 1: DATABASE AANMAKEN
   --------------------------------------------------------------------------
   Maak de database aan als deze nog niet bestaat.
   utf8mb4 ondersteunt alle tekens inclusief speciale tekens.
   -------------------------------------------------------------------------- */
CREATE DATABASE IF NOT EXISTS gameplan_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Selecteer de database voor gebruik
USE gameplan_db;


/* --------------------------------------------------------------------------
   STAP 2: GEBRUIKERS TABEL (Users)
   --------------------------------------------------------------------------
   Slaat alle geregistreerde gebruikers op.
   Dit is de hoofdtabel waar andere tabellen naar verwijzen.

   Kolommen:
   - user_id       : Uniek nummer per gebruiker (automatisch oplopend)
   - username       : Gekozen gebruikersnaam (max 50 tekens)
   - email          : E-mailadres (moet uniek zijn, wordt gebruikt om in te loggen)
   - password_hash  : Versleuteld wachtwoord (nooit als platte tekst opgeslagen)
   - last_activity  : Laatste activiteit (voor sessie controle)
   - deleted_at     : Verwijderdatum (NULL = actief, datum = verwijderd)
   -------------------------------------------------------------------------- */
CREATE TABLE IF NOT EXISTS Users (
    user_id       INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50) NOT NULL,
    email         VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at    TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 3: SPELLEN TABEL (Games)
   --------------------------------------------------------------------------
   Slaat alle spellen op die gebruikers als favoriet kunnen kiezen.
   Meerdere gebruikers kunnen hetzelfde spel als favoriet hebben.

   Kolommen:
   - game_id     : Uniek nummer per spel
   - titel       : Naam van het spel (bijv. "Fortnite")
   - description : Optionele beschrijving van het spel
   - deleted_at  : Verwijderdatum
   -------------------------------------------------------------------------- */
CREATE TABLE IF NOT EXISTS Games (
    game_id     INT AUTO_INCREMENT PRIMARY KEY,
    titel       VARCHAR(100) NOT NULL,
    description TEXT,
    deleted_at  TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 4: FAVORIETE SPELLEN KOPPELTABEL (UserGames)
   --------------------------------------------------------------------------
   Koppelt gebruikers aan hun favoriete spellen (veel-op-veel relatie).
   Een gebruiker kan meerdere favoriete spellen hebben.
   Een spel kan door meerdere gebruikers als favoriet zijn gekozen.

   Voorbeeld:
   - Gebruiker 1 vindt Fortnite en Minecraft leuk
   - Gebruiker 2 vindt Minecraft en League of Legends leuk

   Kolommen:
   - user_id : Verwijst naar de Users tabel
   - game_id : Verwijst naar de Games tabel
   - note    : Persoonlijke notitie (bijv. "Mijn favoriete spel!")
   -------------------------------------------------------------------------- */
CREATE TABLE IF NOT EXISTS UserGames (
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    note    TEXT,
    PRIMARY KEY (user_id, game_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 5: VRIENDEN TABEL (Friends)
   --------------------------------------------------------------------------
   Slaat gaming vrienden van gebruikers op.
   Vrienden hoeven geen account te hebben in dit systeem.

   Kolommen:
   - friend_id        : Uniek nummer per vriendvermelding
   - user_id          : Welke gebruiker deze vriend toevoegde
   - friend_username  : Gebruikersnaam van de vriend
   - note             : Optionele notitie (bijv. "Goed in Fortnite")
   - status           : Online status (Online, Offline, Speelt, Afwezig)
   - deleted_at       : Verwijderdatum
   -------------------------------------------------------------------------- */
CREATE TABLE IF NOT EXISTS Friends (
    friend_id       INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL,
    friend_username VARCHAR(50) NOT NULL,
    note            TEXT,
    status          VARCHAR(50) DEFAULT 'Offline',
    deleted_at      TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 6: SPEELSCHEMA'S TABEL (Schedules)
   --------------------------------------------------------------------------
   Slaat gaming speelschema's op.
   Gebruikers plannen wanneer ze een spel willen spelen.

   Kolommen:
   - schedule_id : Uniek nummer per schema
   - user_id     : Wie dit schema aanmaakte
   - game_id     : Welk spel (verwijst naar Games tabel)
   - date        : Datum van de sessie (JJJJ-MM-DD)
   - time        : Tijd van de sessie (UU:MM:SS)
   - friends     : Komma-gescheiden lijst van meespelende vrienden
   - shared_with : Komma-gescheiden lijst van gebruikers die dit kunnen zien
   - deleted_at  : Verwijderdatum
   -------------------------------------------------------------------------- */
CREATE TABLE IF NOT EXISTS Schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    game_id     INT NOT NULL,
    date        DATE NOT NULL,
    time        TIME NOT NULL,
    friends     TEXT,
    shared_with TEXT,
    deleted_at  TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 7: EVENEMENTEN TABEL (Events)
   --------------------------------------------------------------------------
   Slaat gaming evenementen op zoals toernooien en streams.
   Evenementen kunnen herinneringen en externe links bevatten.

   Kolommen:
   - event_id      : Uniek nummer per evenement
   - user_id       : Wie dit evenement aanmaakte
   - title         : Naam van het evenement (bijv. "Fortnite Toernooi")
   - date          : Datum van het evenement
   - time          : Starttijd van het evenement
   - description   : Beschrijving van het evenement
   - reminder      : Herinnering instelling ('none', '1_hour', '1_day')
   - external_link : Link naar evenementpagina
   - shared_with   : Wie dit evenement kan zien
   - deleted_at    : Verwijderdatum
   -------------------------------------------------------------------------- */
CREATE TABLE IF NOT EXISTS Events (
    event_id      INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    title         VARCHAR(100) NOT NULL,
    date          DATE NOT NULL,
    time          TIME NOT NULL,
    description   TEXT,
    reminder      VARCHAR(50),
    external_link VARCHAR(255),
    shared_with   TEXT,
    deleted_at    TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 8: INDEXEN VOOR SNELHEID
   --------------------------------------------------------------------------
   Indexen maken zoekopdrachten sneller, net als een index in een boek.
   Zonder indexen moet de database elke rij doorzoeken.
   Met indexen springt de database direct naar de juiste rijen.
   -------------------------------------------------------------------------- */

-- Index op e-mail: versnelt het inloggen (zoeken op e-mail)
CREATE INDEX idx_users_email ON Users(email);

-- Index op schema's: versnelt het ophalen van schema's per gebruiker en datum
CREATE INDEX idx_schedules_user_date ON Schedules(user_id, date);

-- Index op evenementen: versnelt het ophalen van evenementen per gebruiker en datum
CREATE INDEX idx_events_user_date ON Events(user_id, date);


/* --------------------------------------------------------------------------
   STAP 9: VOORBEELDSPELLEN TOEVOEGEN
   --------------------------------------------------------------------------
   Voeg populaire spellen toe zodat de app meteen bruikbaar is.
   Gebruikers kunnen ook eigen spellen toevoegen.
   -------------------------------------------------------------------------- */
INSERT INTO Games (titel, description) VALUES
    ('Fortnite', 'Battle Royale spel - Bouw, vecht en wees de laatste die overeind staat!'),
    ('Minecraft', 'Sandbox bouwspel - Maak alles wat je kunt bedenken!'),
    ('League of Legends', 'MOBA strategie spel - Werk samen en vernietig de vijandige nexus!');


/* ==========================================================================
   EINDE VAN DATABASE STRUCTUUR
   ==========================================================================
   De database bevat nu:
   - 6 tabellen: Users, Games, UserGames, Friends, Schedules, Events
   - Foreign key relaties tussen tabellen
   - Indexen voor snellere zoekopdrachten
   - 3 voorbeeldspellen

   Installatie stappen:
   1. Open phpMyAdmin via localhost/phpmyadmin
   2. Klik op "Importeren" en selecteer dit bestand
   3. Klik op "Start" om de database aan te maken
   4. Ga naar localhost/gameplan-scheduler/ om de app te gebruiken
   ========================================================================== */
