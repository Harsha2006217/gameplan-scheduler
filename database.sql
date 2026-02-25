/**
 * ============================================================================
 * DATABASE.SQL - DATABASE SCHEMA
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit SQL-bestand maakt de complete databasestructuur voor GamePlan Scheduler.
 * Het definieert alle tabellen, kolommen, relaties en indexen.
 * Voer dit bestand eenmaal uit in phpMyAdmin om de database op te zetten.
 *
 * Tabellen: Users, Games, UserGames, Friends, Schedules, Events
 * Engine: InnoDB (ondersteunt foreign keys en transacties)
 * Tekenset: utf8mb4 (ondersteunt alle Unicode-tekens)
 * ============================================================================
 */

-- ============================================================================
-- SECTION 1: CREATE DATABASE / MAAK DATABASE
-- ============================================================================

/**
 * ENGLISH:
 * Create the database if it doesn't already exist.
 * - CHARACTER SET utf8mb4: Supports all Unicode characters including emojis
 * - COLLATE utf8mb4_unicode_ci: Case-insensitive comparison for text
 *   (so 'John' and 'john' are treated as equal in searches)
 * 
 * DUTCH / NEDERLANDS:
 * Maak de database als deze nog niet bestaat.
 * - CHARACTER SET utf8mb4: Ondersteunt alle Unicode tekens inclusief emoji's
 * - COLLATE utf8mb4_unicode_ci: Hoofdletterongevoelige vergelijking voor tekst
 *   (dus 'John' en 'john' worden als gelijk behandeld bij zoeken)
 */
CREATE DATABASE IF NOT EXISTS gameplan_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Switch to use this database for all following commands
-- Schakel over naar deze database voor alle volgende commando's
USE gameplan_db;


-- ============================================================================
-- SECTION 2: USERS TABLE / GEBRUIKERS TABEL
-- ============================================================================

/**
 * ENGLISH:
 * The Users table stores all registered user accounts.
 * This is the MAIN table - other tables link back to it.
 * 
 * COLUMNS EXPLAINED:
 * - user_id: Unique number for each user (auto-increments: 1, 2, 3...)
 * - username: Display name chosen by user (max 50 characters)
 * - email: Email address (must be unique, used for login)
 * - password_hash: Encrypted password (NEVER store plain text passwords!)
 * - last_activity: When user was last active (for session timeout)
 * - deleted_at: Soft delete timestamp (NULL if not deleted)
 * 
 * DUTCH / NEDERLANDS:
 * De Users tabel slaat alle geregistreerde gebruikersaccounts op.
 * Dit is de HOOFD tabel - andere tabellen linken hier naar terug.
 * 
 * KOLOMMEN UITGELEGD:
 * - user_id: Uniek nummer voor elke gebruiker (auto-increment: 1, 2, 3...)
 * - username: Weergavenaam gekozen door gebruiker (max 50 tekens)
 * - email: E-mailadres (moet uniek zijn, gebruikt voor inloggen)
 * - password_hash: Versleuteld wachtwoord (NOOIT plain text wachtwoorden opslaan!)
 * - last_activity: Wanneer gebruiker laatst actief was (voor sessie timeout)
 * - deleted_at: Soft delete timestamp (NULL als niet verwijderd)
 */
CREATE TABLE IF NOT EXISTS Users (
    -- PRIMARY KEY: Unique identifier, auto-increments
    -- PRIMARY KEY: Unieke identificatie, auto-increment
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Username: Required, max 50 characters
    -- Gebruikersnaam: Verplicht, max 50 tekens
    username VARCHAR(50) NOT NULL,
    
    -- Email: Required, must be unique (one account per email)
    -- E-mail: Verplicht, moet uniek zijn (één account per e-mail)
    email VARCHAR(100) UNIQUE NOT NULL,
    
    -- Password hash: Stores bcrypt encrypted password (255 chars for future)
    -- Wachtwoord hash: Slaat bcrypt versleuteld wachtwoord op (255 tekens voor toekomst)
    password_hash VARCHAR(255) NOT NULL,
    
    -- Last activity: Auto-updates when row changes (for session tracking)
    -- Laatste activiteit: Auto-update bij wijziging (voor sessie tracking)
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Soft delete: NULL = active, timestamp = deleted
    -- Soft delete: NULL = actief, timestamp = verwijderd
    deleted_at TIMESTAMP NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- InnoDB: Database engine that supports foreign keys and transactions
-- InnoDB: Database engine die foreign keys en transacties ondersteunt


-- ============================================================================
-- SECTION 3: GAMES TABLE / SPELLEN TABEL
-- ============================================================================

/**
 * ENGLISH:
 * The Games table stores all games that users can add as favorites.
 * Games can be shared between users (many users can have same game as favorite).
 * 
 * COLUMNS:
 * - game_id: Unique number for each game
 * - titel: Game name (e.g., "Fortnite", "Minecraft")
 * - description: Optional description of the game
 * - deleted_at: Soft delete timestamp
 * 
 * DUTCH / NEDERLANDS:
 * De Games tabel slaat alle spellen op die gebruikers als favoriet kunnen toevoegen.
 * Spellen kunnen gedeeld worden (meerdere gebruikers kunnen hetzelfde spel als favoriet hebben).
 * 
 * KOLOMMEN:
 * - game_id: Uniek nummer voor elk spel
 * - titel: Spelnaam (bijv. "Fortnite", "Minecraft")
 * - description: Optionele beschrijving van het spel
 * - deleted_at: Soft delete timestamp
 */
CREATE TABLE IF NOT EXISTS Games (
    -- Primary key: auto-incrementing game ID
    -- Primaire sleutel: auto-increment game ID
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Game title: Required, max 100 characters
    -- Speltitel: Verplicht, max 100 tekens
    titel VARCHAR(100) NOT NULL,
    
    -- Description: Optional text about the game
    -- Beschrijving: Optionele tekst over het spel
    description TEXT,
    
    -- Soft delete timestamp
    -- Soft delete timestamp
    deleted_at TIMESTAMP NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- SECTION 4: USERGAMES TABLE (FAVORITES) / GEBRUIKERSPELLEN TABEL (FAVORIETEN)
-- ============================================================================

/**
 * ENGLISH:
 * UserGames is a JUNCTION TABLE (also called "bridge table" or "many-to-many table").
 * It connects Users to their favorite Games.
 * 
 * WHY NEEDED?
 * - One user can have MANY favorite games
 * - One game can be favorited by MANY users
 * - This is called a "many-to-many relationship"
 * 
 * EXAMPLE:
 * User 1 (John) favorites: Fortnite (game 1), Minecraft (game 2)
 * User 2 (Jane) favorites: Minecraft (game 2), League of Legends (game 3)
 * 
 * Table would have:
 * user_id=1, game_id=1 (John likes Fortnite)
 * user_id=1, game_id=2 (John likes Minecraft)
 * user_id=2, game_id=2 (Jane likes Minecraft)
 * user_id=2, game_id=3 (Jane likes League of Legends)
 * 
 * DUTCH / NEDERLANDS:
 * UserGames is een KOPPELTABEL (ook "bruggetabel" of "veel-op-veel tabel" genoemd).
 * Het verbindt Gebruikers met hun favoriete Spellen.
 * 
 * WAAROM NODIG?
 * - Eén gebruiker kan VEEL favoriete spellen hebben
 * - Eén spel kan door VEEL gebruikers als favoriet zijn
 * - Dit heet een "veel-op-veel relatie"
 */
CREATE TABLE IF NOT EXISTS UserGames (
    -- User ID: Links to Users table
    -- Gebruiker ID: Linkt naar Users tabel
    user_id INT NOT NULL,
    
    -- Game ID: Links to Games table
    -- Spel ID: Linkt naar Games tabel
    game_id INT NOT NULL,
    
    -- Note: Personal note about this game (e.g., "My main game!")
    -- Notitie: Persoonlijke notitie over dit spel (bijv. "Mijn hoofdspel!")
    note TEXT,
    
    -- COMPOSITE PRIMARY KEY: Combination of user_id + game_id must be unique
    -- SAMENGESTELDE PRIMAIRE SLEUTEL: Combinatie van user_id + game_id moet uniek zijn
    -- This prevents the same user from adding the same game twice
    -- Dit voorkomt dat dezelfde gebruiker hetzelfde spel twee keer toevoegt
    PRIMARY KEY (user_id, game_id),
    
    -- FOREIGN KEY to Users: If user is deleted, delete their favorites too
    -- FOREIGN KEY naar Users: Als gebruiker verwijderd wordt, verwijder hun favorieten ook
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    
    -- FOREIGN KEY to Games: If game is deleted, remove from all favorites
    -- FOREIGN KEY naar Games: Als spel verwijderd wordt, verwijder uit alle favorieten
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- SECTION 5: FRIENDS TABLE / VRIENDEN TABEL
-- ============================================================================

/**
 * ENGLISH:
 * The Friends table stores user's gaming friends/contacts.
 * Friends are stored by USERNAME (not user_id) because friends might not
 * have accounts in this system - they could be Xbox/PlayStation friends.
 * 
 * COLUMNS:
 * - friend_id: Unique ID for this friend entry
 * - user_id: Which user added this friend (links to Users table)
 * - friend_username: The friend's gaming username/gamertag
 * - note: Optional note about this friend (e.g., "Good at Fortnite")
 * - status: Online status (Online, Offline, Playing, etc.)
 * - deleted_at: Soft delete timestamp
 * 
 * DUTCH / NEDERLANDS:
 * De Friends tabel slaat gaming vrienden/contacten van gebruikers op.
 * Vrienden worden opgeslagen op GEBRUIKERSNAAM (niet user_id) omdat vrienden
 * mogelijk geen accounts in dit systeem hebben - het kunnen Xbox/PlayStation vrienden zijn.
 * 
 * KOLOMMEN:
 * - friend_id: Unieke ID voor deze vriendvermelding
 * - user_id: Welke gebruiker deze vriend toevoegde (linkt naar Users tabel)
 * - friend_username: De gaming gebruikersnaam/gamertag van de vriend
 * - note: Optionele notitie over deze vriend (bijv. "Goed in Fortnite")
 * - status: Online status (Online, Offline, Speelt, etc.)
 * - deleted_at: Soft delete timestamp
 */
CREATE TABLE IF NOT EXISTS Friends (
    -- Primary key: auto-incrementing friend entry ID
    friend_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- User ID: Who added this friend (required)
    user_id INT NOT NULL,
    
    -- Friend's username/gamertag (required, max 50 chars)
    friend_username VARCHAR(50) NOT NULL,
    
    -- Personal note about this friend
    note TEXT,
    
    -- Status: defaults to 'Offline'
    status VARCHAR(50) DEFAULT 'Offline',
    
    -- Soft delete timestamp
    deleted_at TIMESTAMP NULL,
    
    -- If user is deleted, delete their friend list too
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- SECTION 6: SCHEDULES TABLE / SCHEMA'S TABEL
-- ============================================================================

/**
 * ENGLISH:
 * The Schedules table stores gaming play schedules.
 * Users can schedule when they want to play certain games.
 * 
 * COLUMNS:
 * - schedule_id: Unique ID for this schedule
 * - user_id: Who created this schedule
 * - game_id: Which game to play (links to Games table)
 * - date: The date of the gaming session
 * - time: The time of the gaming session
 * - friends: Comma-separated list of friends joining (stored as text)
 * - shared_with: Comma-separated list of users who can see this schedule
 * - deleted_at: Soft delete timestamp
 * 
 * DUTCH / NEDERLANDS:
 * De Schedules tabel slaat gaming speelschema's op.
 * Gebruikers kunnen plannen wanneer ze bepaalde spellen willen spelen.
 * 
 * KOLOMMEN:
 * - schedule_id: Unieke ID voor dit schema
 * - user_id: Wie dit schema aanmaakte
 * - game_id: Welk spel te spelen (linkt naar Games tabel)
 * - date: De datum van de gaming sessie
 * - time: De tijd van de gaming sessie
 * - friends: Komma-gescheiden lijst van vrienden die meedoen (opgeslagen als tekst)
 * - shared_with: Komma-gescheiden lijst van gebruikers die dit schema kunnen zien
 * - deleted_at: Soft delete timestamp
 */
CREATE TABLE IF NOT EXISTS Schedules (
    -- Primary key
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- User who created this schedule
    user_id INT NOT NULL,
    
    -- Game to play (links to Games table)
    game_id INT NOT NULL,
    
    -- Date of gaming session (YYYY-MM-DD format)
    date DATE NOT NULL,
    
    -- Time of gaming session (HH:MM:SS format)
    time TIME NOT NULL,
    
    -- Friends joining: comma-separated usernames (e.g., "player1, player2")
    friends TEXT,
    
    -- Shared with: comma-separated usernames who can view this
    shared_with TEXT,
    
    -- Soft delete timestamp
    deleted_at TIMESTAMP NULL,
    
    -- Foreign keys with CASCADE delete
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- SECTION 7: EVENTS TABLE / EVENEMENTEN TABEL
-- ============================================================================

/**
 * ENGLISH:
 * The Events table stores gaming events like tournaments, streams, etc.
 * Events can have reminders and external links to tournament pages.
 * 
 * COLUMNS:
 * - event_id: Unique ID for this event
 * - user_id: Who created this event
 * - title: Event name (e.g., "Fortnite Tournament")
 * - date: Date of the event
 * - time: Start time of the event
 * - description: Details about the event
 * - reminder: When to remind ('none', '1_hour', '1_day')
 * - external_link: URL to event page/stream
 * - shared_with: Who can see this event
 * - deleted_at: Soft delete timestamp
 * 
 * DUTCH / NEDERLANDS:
 * De Events tabel slaat gaming evenementen op zoals toernooien, streams, etc.
 * Evenementen kunnen herinneringen en externe links naar toernooi pagina's hebben.
 * 
 * KOLOMMEN:
 * - event_id: Unieke ID voor dit evenement
 * - user_id: Wie dit evenement aanmaakte
 * - title: Evenementnaam (bijv. "Fortnite Toernooi")
 * - date: Datum van het evenement
 * - time: Starttijd van het evenement
 * - description: Details over het evenement
 * - reminder: Wanneer herinneren ('none', '1_hour', '1_day')
 * - external_link: URL naar evenementpagina/stream
 * - shared_with: Wie dit evenement kan zien
 * - deleted_at: Soft delete timestamp
 */
CREATE TABLE IF NOT EXISTS Events (
    -- Primary key
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- User who created this event
    user_id INT NOT NULL,
    
    -- Event title (required, max 100 chars)
    title VARCHAR(100) NOT NULL,
    
    -- Event date
    date DATE NOT NULL,
    
    -- Event time
    time TIME NOT NULL,
    
    -- Event description (optional, detailed info)
    description TEXT,
    
    -- Reminder setting: 'none', '1_hour', '1_day'
    reminder VARCHAR(50),
    
    -- External link: URL to event page (max 255 chars for URLs)
    external_link VARCHAR(255),
    
    -- Shared with: comma-separated usernames
    shared_with TEXT,
    
    -- Soft delete timestamp
    deleted_at TIMESTAMP NULL,
    
    -- Foreign key: delete events when user is deleted
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- SECTION 8: INDEXES FOR PERFORMANCE / INDEXEN VOOR PRESTATIES
-- ============================================================================

/**
 * ENGLISH:
 * Indexes make database searches faster, like an index in a book.
 * Without indexes, the database must scan EVERY row to find data.
 * With indexes, it can jump directly to the right rows.
 * 
 * WHEN TO USE INDEXES:
 * - Columns used in WHERE clauses
 * - Columns used in JOIN operations
 * - Columns used in ORDER BY
 * 
 * TRADE-OFF:
 * - Indexes speed up READ operations (SELECT)
 * - Indexes slow down WRITE operations (INSERT, UPDATE, DELETE)
 * - Only add indexes where really needed!
 * 
 * DUTCH / NEDERLANDS:
 * Indexen maken database zoekopdrachten sneller, zoals een index in een boek.
 * Zonder indexen moet de database ELKE rij scannen om data te vinden.
 * Met indexen kan het direct naar de juiste rijen springen.
 * 
 * WANNEER INDEXEN GEBRUIKEN:
 * - Kolommen gebruikt in WHERE clausules
 * - Kolommen gebruikt in JOIN operaties
 * - Kolommen gebruikt in ORDER BY
 * 
 * AFWEGING:
 * - Indexen versnellen LEES operaties (SELECT)
 * - Indexen vertragen SCHRIJF operaties (INSERT, UPDATE, DELETE)
 * - Voeg alleen indexen toe waar echt nodig!
 */

-- Index on Users.email: Speed up login queries (searching by email)
-- Index op Users.email: Versnel login queries (zoeken op e-mail)
CREATE INDEX idx_users_email ON Users(email);

-- Composite index on Schedules: Speed up queries for user's schedules by date
-- Samengestelde index op Schedules: Versnel queries voor gebruikersschema's op datum
CREATE INDEX idx_schedules_user_date ON Schedules(user_id, date);

-- Composite index on Events: Speed up queries for user's events by date
-- Samengestelde index op Events: Versnel queries voor gebruikersevenementen op datum
CREATE INDEX idx_events_user_date ON Events(user_id, date);


-- ============================================================================
-- SECTION 9: SAMPLE DATA / VOORBEELDDATA
-- ============================================================================

/**
 * ENGLISH:
 * Insert some sample games so the app has content to work with.
 * These are popular games that young gamers might use.
 * Users can add their own games too!
 * 
 * DUTCH / NEDERLANDS:
 * Voeg enkele voorbeeldspellen toe zodat de app content heeft om mee te werken.
 * Dit zijn populaire spellen die jonge gamers mogelijk gebruiken.
 * Gebruikers kunnen ook hun eigen spellen toevoegen!
 */
INSERT INTO Games (titel, description) VALUES
    ('Fortnite', 'Battle Royale game - Build, fight, and be the last one standing! / Battle Royale spel - Bouw, vecht, en wees de laatste die staat!'),
    ('Minecraft', 'Sandbox building game - Create anything you can imagine! / Sandbox bouwspel - Maak alles wat je kunt bedenken!'),
    ('League of Legends', 'MOBA strategy game - Team up and destroy the enemy nexus! / MOBA strategie spel - Werk samen en vernietig de vijandige nexus!');


-- ============================================================================
-- END OF DATABASE SCHEMA / EINDE VAN DATABASE SCHEMA
-- ============================================================================

/**
 * ENGLISH:
 * Database setup complete! The gameplan_db database now contains:
 * - 6 tables: Users, Games, UserGames, Friends, Schedules, Events
 * - Proper foreign key relationships
 * - Indexes for performance
 * - Sample game data
 * 
 * Next steps:
 * 1. Access the app via localhost/gameplan-scheduler/
 * 2. Register a new account
 * 3. Start adding friends, games, schedules, and events!
 * 
 * DUTCH / NEDERLANDS:
 * Database setup compleet! De gameplan_db database bevat nu:
 * - 6 tabellen: Users, Games, UserGames, Friends, Schedules, Events
 * - Juiste foreign key relaties
 * - Indexen voor prestaties
 * - Voorbeeldspellen data
 * 
 * Volgende stappen:
 * 1. Ga naar de app via localhost/gameplan-scheduler/
 * 2. Registreer een nieuw account
 * 3. Begin met toevoegen van vrienden, spellen, schema's, en evenementen!
 */