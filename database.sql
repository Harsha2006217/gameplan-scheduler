/* ==========================================================================
   DATABASE.SQL - VOLLEDIGE DATABASE STRUCTUUR VOOR GAMEPLAN SCHEDULER
   ==========================================================================
   Bestandsnaam : database.sql
   Auteur       : Harsha Kanaparthi
   Studentnummer: 2195344
   Opleiding    : MBO-4 Software Developer (Crebo 25998)
   Datum        : 30-09-2025
   Versie       : 1.0
   Database     : MySQL 8.0+ (InnoDB engine)
   Encoding     : UTF-8 (utf8mb4 met unicode_ci collatie)
   Tool         : phpMyAdmin via XAMPP Control Panel

   ==========================================================================
   WAT IS DIT BESTAND?
   ==========================================================================
   Dit is een SQL-bestand (Structured Query Language = Gestructureerde Vraagtaal).
   SQL is de taal waarmee je met een database praat.
   Een database is als een grote digitale kast met laden (tabellen),
   waarin je gegevens (data) opslaat, zoals gebruikers, spellen en vrienden.

   Dit bestand maakt de HELE database aan voor de GamePlan Scheduler applicatie.
   Je voert dit bestand uit in phpMyAdmin (een programma om databases te beheren).
   Na het uitvoeren heb je alle tabellen nodig om de applicatie te laten werken.

   ==========================================================================
   WELKE TABELLEN WORDEN AANGEMAAKT? (6 tabellen)
   ==========================================================================
   ┌─────┬─────────────┬─────────────────────────────────────────────────────┐
   │ Nr  │ Tabelnaam   │ Beschrijving                                        │
   ├─────┼─────────────┼─────────────────────────────────────────────────────┤
   │ 1   │ Users       │ Alle gebruikers met account (inlog, wachtwoord)     │
   │ 2   │ Games       │ Alle spellen die als favoriet gekozen kunnen worden │
   │ 3   │ UserGames   │ Koppeltabel: welke gebruiker welk spel favoriet heeft│
   │ 4   │ Friends     │ Gaming vrienden van elke gebruiker                  │
   │ 5   │ Schedules   │ Speelschema's (wanneer je gaat gamen)               │
   │ 6   │ Events      │ Gaming evenementen (toernooien, streams, etc.)      │
   └─────┴─────────────┴─────────────────────────────────────────────────────┘

   ==========================================================================
   ENTITY-RELATIONSHIP DIAGRAM (ER-diagram)
   ==========================================================================
   Een ER-diagram toont de RELATIES (verbindingen) tussen tabellen.
   Dit is een belangrijk concept bij database-ontwerp.

   ---< = een-op-veel relatie (1 gebruiker heeft VEEL vrienden)
   >--- = veel-op-een relatie
   ---<  >--- = veel-op-veel relatie (via koppeltabel)

   ┌──────────┐         ┌──────────────┐         ┌──────────┐
   │  Users   │────<────│  UserGames   │────>────│  Games   │
   │          │         │ (koppeltabel)│         │          │
   │ user_id  │         │ user_id (FK) │         │ game_id  │
   │ username │         │ game_id (FK) │         │ titel    │
   │ email    │         │ note         │         │ descript.│
   │ password │         └──────────────┘         │ deleted  │
   │ last_act │                                  └──────────┘
   │ deleted  │                                       │
   └──────────┘                                       │
        │                                             │
        ├────<────┌──────────────┐                    │
        │         │   Friends    │                    │
        │         │ friend_id    │                    │
        │         │ user_id (FK) │                    │
        │         │ friend_user  │                    │
        │         │ note, status │                    │
        │         └──────────────┘                    │
        │                                             │
        ├────<────┌──────────────┐────>───────────────┘
        │         │  Schedules   │
        │         │ schedule_id  │
        │         │ user_id (FK) │
        │         │ game_id (FK) │
        │         │ date, time   │
        │         │ friends      │
        │         │ shared_with  │
        │         └──────────────┘
        │
        └────<────┌──────────────┐
                  │   Events     │
                  │ event_id     │
                  │ user_id (FK) │
                  │ title, date  │
                  │ time, desc   │
                  │ reminder     │
                  │ ext_link     │
                  │ shared_with  │
                  └──────────────┘

   ==========================================================================
   RELATIE-TYPES (Cardinaliteit)
   ==========================================================================
   ┌────────────────────────┬───────────────┬──────────────────────────────┐
   │ Relatie                │ Type          │ Uitleg                       │
   ├────────────────────────┼───────────────┼──────────────────────────────┤
   │ Users ↔ Games          │ N:M (veel)    │ Via koppeltabel UserGames    │
   │ Users → Friends        │ 1:N (veel)    │ 1 user heeft veel vrienden   │
   │ Users → Schedules      │ 1:N (veel)    │ 1 user heeft veel schema's   │
   │ Users → Events         │ 1:N (veel)    │ 1 user heeft veel events     │
   │ Games → Schedules      │ 1:N (veel)    │ 1 game in veel schema's      │
   └────────────────────────┴───────────────┴──────────────────────────────┘

   ==========================================================================
   DATABASE STATISTIEKEN
   ==========================================================================
   - Aantal tabellen       : 6
   - Aantal kolommen totaal: 32
   - Aantal foreign keys   : 6 (allemaal met ON DELETE CASCADE)
   - Aantal indexen        : 3 (email, schedule+date, event+date)
   - Voorbeelddata         : 3 spellen (Fortnite, Minecraft, LoL)
   - Soft delete tabellen  : 5 van 6 (UserGames heeft geen soft delete)

   ==========================================================================
   BEVEILIGING IN DE DATABASE
   ==========================================================================
   1. WACHTWOORD HASHING: password_hash kolom slaat NOOIT het echte
      wachtwoord op. Alleen de bcrypt hash ($2y$10$...) wordt opgeslagen.
      Zelfs als de database gehackt wordt, zijn wachtwoorden onleesbaar.
      → OWASP A02: Cryptographic Failures voorkomen

   2. SOFT DELETE patroon: deleted_at kolom in 5 tabellen voorkomt
      permanent dataverlies. Data wordt gemarkeerd als verwijderd,
      niet echt gewist. Zo kan data hersteld worden bij fouten.

   3. FOREIGN KEY CASCADE: ON DELETE CASCADE zorgt ervoor dat
      gerelateerde data automatisch wordt opgeruimd wanneer een
      parent-record wordt verwijderd. Geen "wees-data" (orphan records).

   4. NOT NULL CONSTRAINTS: Verplichte velden hebben NOT NULL,
      waardoor onvolledige data niet in de database kan komen.

   5. UNIQUE CONSTRAINT: email in Users is UNIQUE, waardoor
      twee accounts met hetzelfde e-mailadres onmogelijk zijn.

   6. UTF8MB4 ENCODING: Voorkomt tekencodering-aanvallen en
      ondersteunt alle internationale tekens en emoji's.

   ==========================================================================
   SQL CONCEPTEN GEBRUIKT IN DIT BESTAND
   ==========================================================================
   ┌─────────────────────────┬────────────────────────────────────────────┐
   │ SQL Concept             │ Uitleg                                     │
   ├─────────────────────────┼────────────────────────────────────────────┤
   │ CREATE DATABASE         │ Een nieuwe database aanmaken               │
   │ CREATE TABLE            │ Een nieuwe tabel aanmaken                  │
   │ CREATE INDEX            │ Een index aanmaken voor sneller zoeken     │
   │ INSERT INTO ... VALUES  │ Nieuwe rijen (data) toevoegen aan tabel    │
   │ IF NOT EXISTS           │ Alleen aanmaken als het nog niet bestaat   │
   │ USE                     │ Een database selecteren om mee te werken   │
   │ PRIMARY KEY             │ Unieke identificatie van elke rij          │
   │ FOREIGN KEY             │ Verwijzing naar een andere tabel           │
   │ AUTO_INCREMENT          │ Automatisch ophogend nummer (1, 2, 3...)   │
   │ NOT NULL                │ Veld mag niet leeg zijn (verplicht)        │
   │ UNIQUE                  │ Waarde mag maar 1x voorkomen in de tabel   │
   │ DEFAULT                 │ Standaardwaarde als er niets wordt opgeven │
   │ ON DELETE CASCADE       │ Verwijder gerelateerde data automatisch    │
   │ TIMESTAMP               │ Datum + tijd waarde                        │
   │ ON UPDATE CURRENT_STAMP │ Automatisch bijwerken bij wijziging        │
   │ CHARACTER SET / COLLATE │ Tekencodering en sorteervolgorde instellen │
   │ ENGINE=InnoDB           │ Database-engine met transactie-ondersteuning│
   └─────────────────────────┴────────────────────────────────────────────┘

   ==========================================================================
   DATA TYPES GEBRUIKT IN DIT BESTAND
   ==========================================================================
   ┌─────────────────┬──────────┬─────────────────────────────────────────┐
   │ Data Type       │ Voorbeeld│ Uitleg                                  │
   ├─────────────────┼──────────┼─────────────────────────────────────────┤
   │ INT             │ 42       │ Geheel getal (geen decimalen)           │
   │ VARCHAR(n)      │ "Harsha" │ Tekst van maximaal n tekens             │
   │ TEXT            │ (lang)   │ Grote tekst tot 65.535 tekens           │
   │ DATE            │ 2025-10-15│ Alleen datum (jaar-maand-dag)          │
   │ TIME            │ 20:00:00 │ Alleen tijd (uur:minuut:seconde)        │
   │ TIMESTAMP       │ (datum+tijd)│ Datum EN tijd samen                  │
   └─────────────────┴──────────┴─────────────────────────────────────────┘

   ==========================================================================
   BESTANDSSTRUCTUUR (9 stappen)
   ==========================================================================
   Het bestand is opgebouwd in 9 logische stappen:

   STAP 1: Database aanmaken        → CREATE DATABASE gameplan_db
   STAP 2: Users tabel               → Gebruikers met inlog + soft delete
   STAP 3: Games tabel               → Beschikbare spellen
   STAP 4: UserGames koppeltabel     → N:M relatie users-games (favorieten)
   STAP 5: Friends tabel             → Gaming vrienden per gebruiker
   STAP 6: Schedules tabel           → Speelschema's met datum/tijd
   STAP 7: Events tabel              → Evenementen met herinnering/link
   STAP 8: Indexen aanmaken          → 3 indexen voor snellere queries
   STAP 9: Voorbeelddata invoegen    → 3 populaire spellen

   ==========================================================================
   HOE GEBRUIK JE DIT BESTAND?
   ==========================================================================
   1. Open XAMPP Control Panel en start Apache en MySQL
   2. Ga naar http://localhost/phpmyadmin in je browser
   3. Klik op "Importeren" in het bovenste menu
   4. Klik op "Bestand kiezen" en selecteer dit database.sql bestand
   5. Klik op "Start" onderaan de pagina
   6. Wacht tot je de melding "Import is succesvol uitgevoerd" ziet
   7. Ga nu naar http://localhost/gameplan-scheduler/ om de app te gebruiken

   LET OP: Als de database al bestaat, worden de tabellen NIET overschreven
   (dankzij IF NOT EXISTS). De INSERT voor spellen kan dan wel een fout geven
   als dezelfde spellen al bestaan. Dit is normaal en geen probleem.
   ========================================================================== */


/* --------------------------------------------------------------------------
   STAP 1: DATABASE AANMAKEN
   --------------------------------------------------------------------------
   Hier maken we de database zelf aan.
   Een database is een verzameling van tabellen (denk aan een Excel-bestand
   met meerdere werkbladen).

   CREATE DATABASE = maak een nieuwe database aan
   IF NOT EXISTS   = alleen als deze nog niet bestaat (voorkomt fouten)
   gameplan_db     = de naam die we aan onze database geven

   CHARACTER SET utf8mb4 = de tekencodering die we gebruiken
   Dit zorgt ervoor dat ALLE tekens werken: letters, cijfers, speciale tekens,
   en zelfs emoji's zoals 🎮. Zonder dit zouden sommige tekens niet werken.

   COLLATE utf8mb4_unicode_ci = hoe tekst wordt vergeleken en gesorteerd
   'ci' staat voor 'case insensitive', wat betekent dat 'A' en 'a' als
   hetzelfde worden gezien bij het zoeken.
   -------------------------------------------------------------------------- */
CREATE DATABASE
IF NOT EXISTS gameplan_db
    CHARACTER
SET utf8mb4
COLLATE utf8mb4_unicode_ci;

/* USE = selecteer deze database zodat alle volgende opdrachten
   in DEZE database worden uitgevoerd en niet in een andere */
USE gameplan_db;


/* --------------------------------------------------------------------------
   STAP 2: GEBRUIKERS TABEL (Users)
   --------------------------------------------------------------------------
   Dit is de BELANGRIJKSTE tabel van de hele applicatie.
   Hier worden alle gebruikers opgeslagen die een account aanmaken.
   Zonder deze tabel kan niemand inloggen of de app gebruiken.

   CREATE TABLE = maak een nieuwe tabel aan
   IF NOT EXISTS = alleen als deze nog niet bestaat

   UITLEG VAN ELKE KOLOM:

   user_id (INT AUTO_INCREMENT PRIMARY KEY):
   - INT = geheel getal (1, 2, 3, enz.)
   - AUTO_INCREMENT = het getal gaat automatisch omhoog bij elke nieuwe gebruiker
     Eerste gebruiker krijgt 1, tweede krijgt 2, enzovoort
   - PRIMARY KEY = dit is het unieke identificatienummer van elke gebruiker
     Geen twee gebruikers kunnen hetzelfde user_id hebben

   username (VARCHAR(50) NOT NULL):
   - VARCHAR(50) = tekst van maximaal 50 tekens
   - NOT NULL = dit veld MAG NIET leeg zijn, het is verplicht
   - Dit is de naam die de gebruiker kiest, bijvoorbeeld "GamerHarsha"

   email (VARCHAR(100) UNIQUE NOT NULL):
   - VARCHAR(100) = tekst van maximaal 100 tekens
   - UNIQUE = geen twee gebruikers mogen hetzelfde e-mailadres hebben
   - NOT NULL = verplicht veld
   - Het e-mailadres wordt gebruikt om in te loggen

   password_hash (VARCHAR(255) NOT NULL):
   - VARCHAR(255) = tekst van maximaal 255 tekens
   - Hier wordt het VERSLEUTELDE wachtwoord opgeslagen
   - Het echte wachtwoord wordt NOOIT opgeslagen! In plaats daarvan
     wordt het omgezet naar een onleesbare code (hash) met bcrypt
   - Voorbeeld: "MijnWachtwoord123" wordt "$2y$10$abc123xyz..."
   - Dit is voor de veiligheid: als iemand de database hackt,
     kan hij de wachtwoorden niet lezen

   last_activity (TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP):
   - TIMESTAMP = datum en tijd samen (bijv. "2025-09-30 14:30:00")
   - DEFAULT CURRENT_TIMESTAMP = bij aanmaken wordt automatisch de huidige
     datum en tijd ingevuld
   - ON UPDATE CURRENT_TIMESTAMP = elke keer als de rij wordt gewijzigd,
     wordt de datum/tijd automatisch bijgewerkt
   - Dit wordt gebruikt om te checken wanneer de gebruiker voor het laatst
     actief was. Na 30 minuten inactiviteit wordt de sessie beeindigd.

   deleted_at (TIMESTAMP NULL):
   - TIMESTAMP NULL = datum en tijd, MAG leeg (NULL) zijn
   - NULL = de gebruiker is ACTIEF (niet verwijderd)
   - Als er een datum in staat = de gebruiker is VERWIJDERD op die datum
   - Dit heet "soft delete": we verwijderen de data niet echt,
     maar markeren het als verwijderd. Zo kan het eventueel hersteld worden.

   ENGINE=InnoDB = het type database-engine dat we gebruiken
   InnoDB ondersteunt transacties en foreign keys (verwijzingen tussen tabellen)

   DEFAULT CHARSET=utf8mb4 = standaard tekencodering voor deze tabel
   -------------------------------------------------------------------------- */
CREATE TABLE
IF NOT EXISTS Users
(
    user_id       INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR
(50) NOT NULL,
    email         VARCHAR
(100) UNIQUE NOT NULL,
    password_hash VARCHAR
(255) NOT NULL,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
UPDATE CURRENT_TIMESTAMP,
    deleted_at    TIMESTAMP
NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 3: SPELLEN TABEL (Games)
   --------------------------------------------------------------------------
   Deze tabel slaat alle spellen op die in de applicatie beschikbaar zijn.
   Gebruikers kunnen spellen uit deze tabel als favoriet kiezen.
   Er kunnen ook nieuwe spellen worden toegevoegd door gebruikers.

   UITLEG VAN ELKE KOLOM:

   game_id (INT AUTO_INCREMENT PRIMARY KEY):
   - Uniek nummer per spel, gaat automatisch omhoog
   - Eerste spel = 1, tweede spel = 2, enz.

   titel (VARCHAR(100) NOT NULL):
   - De naam van het spel, maximaal 100 tekens
   - Verplicht veld (NOT NULL)
   - Voorbeeld: "Fortnite", "Minecraft", "League of Legends"

   description (TEXT):
   - Een beschrijving van het spel
   - TEXT = kan veel tekst bevatten (tot 65.535 tekens)
   - Dit veld is NIET verplicht (geen NOT NULL), dus het mag leeg zijn
   - Voorbeeld: "Battle Royale spel waar je bouwt en vecht"

   deleted_at (TIMESTAMP NULL):
   - Zelfde als bij Users: NULL = actief, datum = verwijderd
   - Soft delete zodat verwijderde spellen hersteld kunnen worden
   -------------------------------------------------------------------------- */
CREATE TABLE
IF NOT EXISTS Games
(
    game_id     INT AUTO_INCREMENT PRIMARY KEY,
    titel       VARCHAR
(100) NOT NULL,
    description TEXT,
    deleted_at  TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 4: FAVORIETE SPELLEN KOPPELTABEL (UserGames)
   --------------------------------------------------------------------------
   Dit is een KOPPELTABEL (ook wel "tussentabel" of "junction table" genoemd).
   Deze tabel verbindt gebruikers met hun favoriete spellen.

   WAAROM EEN KOPPELTABEL?
   -----------------------
   Omdat er een "veel-op-veel" relatie is:
   - EEN gebruiker kan MEERDERE favoriete spellen hebben
   - EEN spel kan door MEERDERE gebruikers als favoriet gekozen zijn

   Voorbeeld:
   - Gebruiker "Harsha" (user_id=1) vindt Fortnite (game_id=1) en Minecraft (game_id=2) leuk
   - Gebruiker "Ali" (user_id=2) vindt ook Minecraft (game_id=2) en League of Legends (game_id=3) leuk

   In de tabel ziet dat er zo uit:
   | user_id | game_id | note               |
   |---------|---------|---------------------|
   | 1       | 1       | "Mijn #1 spel!"    |
   | 1       | 2       | "Leuk om te bouwen" |
   | 2       | 2       | "Speel ik dagelijks"|
   | 2       | 3       | "Ranked spelen"     |

   UITLEG VAN ELKE KOLOM:

   user_id (INT NOT NULL):
   - Verwijst naar de Users tabel (welke gebruiker)
   - NOT NULL = verplicht

   game_id (INT NOT NULL):
   - Verwijst naar de Games tabel (welk spel)
   - NOT NULL = verplicht

   note (TEXT):
   - Een persoonlijke notitie over dit spel
   - Niet verplicht (de gebruiker hoeft geen notitie te schrijven)
   - Voorbeeld: "Dit is mijn favoriete spel om met vrienden te spelen!"

   PRIMARY KEY (user_id, game_id):
   - Dit is een SAMENGESTELDE primaire sleutel
   - De combinatie van user_id EN game_id moet uniek zijn
   - Dit voorkomt dat dezelfde gebruiker hetzelfde spel twee keer
     als favoriet kan toevoegen

   FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE:
   - FOREIGN KEY = een verwijzing naar een andere tabel
   - Dit zorgt ervoor dat user_id MOET bestaan in de Users tabel
   - ON DELETE CASCADE = als een gebruiker wordt verwijderd,
     worden automatisch ook al zijn favoriete spellen verwijderd
     (CASCADE = waterval, het "valt door" naar gerelateerde gegevens)

   FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE:
   - Zelfde als hierboven maar dan voor spellen
   - Als een spel wordt verwijderd, worden alle koppelingen met
     gebruikers automatisch verwijderd
   -------------------------------------------------------------------------- */
CREATE TABLE
IF NOT EXISTS UserGames
(
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    note    TEXT,
    PRIMARY KEY
(user_id, game_id),
    FOREIGN KEY
(user_id) REFERENCES Users
(user_id) ON
DELETE CASCADE,
    FOREIGN KEY (game_id)
REFERENCES Games
(game_id) ON
DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 5: VRIENDEN TABEL (Friends)
   --------------------------------------------------------------------------
   Hier worden de gaming vrienden van elke gebruiker opgeslagen.
   Let op: vrienden hoeven GEEN account te hebben in dit systeem.
   Je slaat alleen hun gebruikersnaam op (bijvoorbeeld hun gaming naam).

   UITLEG VAN ELKE KOLOM:

   friend_id (INT AUTO_INCREMENT PRIMARY KEY):
   - Uniek nummer per vriendvermelding
   - Gaat automatisch omhoog: 1, 2, 3, enz.

   user_id (INT NOT NULL):
   - Welke gebruiker heeft deze vriend toegevoegd?
   - Verwijst naar de Users tabel
   - Voorbeeld: als Harsha (user_id=1) een vriend toevoegt,
     staat hier "1"

   friend_username (VARCHAR(50) NOT NULL):
   - De gaming gebruikersnaam van de vriend
   - Maximaal 50 tekens
   - Verplicht veld
   - Voorbeeld: "ProGamer123" of "NinjaPvP"

   note (TEXT):
   - Een optionele notitie over deze vriend
   - Voorbeeld: "Goed in Fortnite, speelt elke avond"
   - Mag leeg zijn

   status (VARCHAR(50) DEFAULT 'Offline'):
   - De huidige online status van de vriend
   - DEFAULT 'Offline' = als er geen status wordt opgegeven,
     staat deze automatisch op "Offline"
   - Mogelijke waarden: "Online", "Offline", "Playing" (aan het spelen),
     "Away" (afwezig)

   deleted_at (TIMESTAMP NULL):
   - Soft delete: NULL = actief, datum = verwijderd

   FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE:
   - Als de gebruiker wordt verwijderd, worden al zijn vrienden
     ook automatisch verwijderd uit deze tabel
   -------------------------------------------------------------------------- */
CREATE TABLE
IF NOT EXISTS Friends
(
    friend_id       INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL,
    friend_username VARCHAR
(50) NOT NULL,
    note            TEXT,
    status          VARCHAR
(50) DEFAULT 'Offline',
    deleted_at      TIMESTAMP NULL,
    FOREIGN KEY
(user_id) REFERENCES Users
(user_id) ON
DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 6: SPEELSCHEMA'S TABEL (Schedules)
   --------------------------------------------------------------------------
   Hier worden gaming speelschema's opgeslagen.
   Een speelschema is een afspraak om op een bepaald moment een spel te spelen.
   Bijvoorbeeld: "Ik ga op 15 oktober om 20:00 Fortnite spelen met Ali en Sam"

   UITLEG VAN ELKE KOLOM:

   schedule_id (INT AUTO_INCREMENT PRIMARY KEY):
   - Uniek nummer per speelschema

   user_id (INT NOT NULL):
   - Wie heeft dit schema aangemaakt?
   - Verwijst naar de Users tabel

   game_id (INT NOT NULL):
   - Welk spel gaat gespeeld worden?
   - Verwijst naar de Games tabel

   date (DATE NOT NULL):
   - De datum waarop gespeeld gaat worden
   - Formaat: JJJJ-MM-DD (jaar-maand-dag), bijv. "2025-10-15"
   - Verplicht veld

   time (TIME NOT NULL):
   - Het tijdstip waarop gespeeld gaat worden
   - Formaat: UU:MM:SS (uren:minuten:seconden), bijv. "20:00:00"
   - Verplicht veld

   friends (TEXT):
   - Een lijst van vrienden die meespelen
   - Komma-gescheiden, bijv. "Ali, Sam, Jess"
   - Optioneel (mag leeg zijn)

   shared_with (TEXT):
   - Een lijst van gebruikers die dit schema mogen zien
   - Komma-gescheiden, bijv. "user1, user2"
   - Optioneel (mag leeg zijn)

   deleted_at (TIMESTAMP NULL):
   - Soft delete: NULL = actief, datum = verwijderd

   FOREIGN KEYS:
   - user_id verwijst naar Users (CASCADE: als gebruiker weg, schema ook weg)
   - game_id verwijst naar Games (CASCADE: als spel weg, schema ook weg)
   -------------------------------------------------------------------------- */
CREATE TABLE
IF NOT EXISTS Schedules
(
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    game_id     INT NOT NULL,
    date        DATE NOT NULL,
    time        TIME NOT NULL,
    friends     TEXT,
    shared_with TEXT,
    deleted_at  TIMESTAMP NULL,
    FOREIGN KEY
(user_id) REFERENCES Users
(user_id) ON
DELETE CASCADE,
    FOREIGN KEY (game_id)
REFERENCES Games
(game_id) ON
DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 7: EVENEMENTEN TABEL (Events)
   --------------------------------------------------------------------------
   Hier worden gaming evenementen opgeslagen, zoals toernooien of livestreams.
   Evenementen zijn anders dan schema's: ze hebben extra velden voor
   herinneringen, externe links en beschrijvingen.

   Voorbeeld van een evenement:
   "Fortnite Toernooi op 20 oktober om 18:00, herinnering 1 uur ervoor,
    link naar de toernooi pagina, gedeeld met Ali en Sam"

   UITLEG VAN ELKE KOLOM:

   event_id (INT AUTO_INCREMENT PRIMARY KEY):
   - Uniek nummer per evenement

   user_id (INT NOT NULL):
   - Wie heeft dit evenement aangemaakt?
   - Verwijst naar de Users tabel

   title (VARCHAR(100) NOT NULL):
   - De naam van het evenement
   - Maximaal 100 tekens, verplicht
   - Voorbeeld: "Fortnite Toernooi" of "Minecraft Bouwwedstrijd"

   date (DATE NOT NULL):
   - De datum van het evenement
   - Formaat: JJJJ-MM-DD

   time (TIME NOT NULL):
   - Het starttijdstip van het evenement
   - Formaat: UU:MM:SS

   description (TEXT):
   - Een beschrijving van het evenement
   - Optioneel (mag leeg zijn)
   - Voorbeeld: "Toernooi met prijzen, iedereen mag meedoen"

   reminder (VARCHAR(50)):
   - Herinnering instelling
   - Mogelijke waarden: 'none' (geen), '1_hour' (1 uur ervoor),
     '1_day' (1 dag ervoor)
   - De applicatie toont een pop-up op het juiste moment

   external_link (VARCHAR(255)):
   - Een link naar een externe pagina over het evenement
   - Maximaal 255 tekens
   - Optioneel (mag leeg zijn)
   - Voorbeeld: "https://www.fortnite-toernooi.nl"

   shared_with (TEXT):
   - Met wie dit evenement gedeeld is
   - Komma-gescheiden gebruikersnamen
   - Optioneel

   deleted_at (TIMESTAMP NULL):
   - Soft delete: NULL = actief, datum = verwijderd

   FOREIGN KEY:
   - user_id verwijst naar Users (CASCADE: als gebruiker weg, evenement ook weg)
   -------------------------------------------------------------------------- */
CREATE TABLE
IF NOT EXISTS Events
(
    event_id      INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    title         VARCHAR
(100) NOT NULL,
    date          DATE NOT NULL,
    time          TIME NOT NULL,
    description   TEXT,
    reminder      VARCHAR
(50),
    external_link VARCHAR
(255),
    shared_with   TEXT,
    deleted_at    TIMESTAMP NULL,
    FOREIGN KEY
(user_id) REFERENCES Users
(user_id) ON
DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;


/* --------------------------------------------------------------------------
   STAP 8: INDEXEN VOOR SNELHEID
   --------------------------------------------------------------------------
   WAT IS EEN INDEX?
   -----------------
   Een index in een database werkt precies als een index achter in een boek.
   Stel je voor: je hebt een boek van 500 pagina's en je zoekt het woord "PHP".
   ZONDER index: je moet ALLE 500 pagina's doorlezen om het te vinden.
   MET index: je kijkt achter in het boek bij "P" en vindt meteen het paginanummer.

   In een database werkt het hetzelfde:
   ZONDER index: de database doorzoekt ELKE rij in de tabel (langzaam bij veel data)
   MET index: de database springt direct naar de juiste rijen (heel snel)

   CREATE INDEX = maak een index aan
   idx_ = afkorting voor "index" (naamgeving conventie)

   WELKE INDEXEN MAKEN WE?
   -----------------------
   1. idx_users_email = versnelt het zoeken op e-mailadres
      Dit wordt gebruikt bij elke keer inloggen (de app zoekt de gebruiker
      op basis van e-mail). Zonder index zou dit langzaam worden bij
      duizenden gebruikers.

   2. idx_schedules_user_date = versnelt het ophalen van schema's
      per gebruiker en datum. Dit is een samengestelde index op
      twee kolommen tegelijk, wat betekent dat het zoeken op
      "alle schema's van gebruiker X op datum Y" heel snel gaat.

   3. idx_events_user_date = versnelt het ophalen van evenementen
      per gebruiker en datum. Zelfde principe als hierboven.
   -------------------------------------------------------------------------- */

/* Index op e-mail in de Users tabel: maakt inloggen sneller */
CREATE INDEX idx_users_email ON Users(email);

/* Index op schema's: maakt het ophalen van schema's per gebruiker en datum sneller */
CREATE INDEX idx_schedules_user_date ON Schedules(user_id, date);

/* Index op evenementen: maakt het ophalen van evenementen per gebruiker en datum sneller */
CREATE INDEX idx_events_user_date ON Events(user_id, date);


/* --------------------------------------------------------------------------
   STAP 9: VOORBEELDSPELLEN TOEVOEGEN
   --------------------------------------------------------------------------
   INSERT INTO = voeg nieuwe rijen toe aan een tabel
   Games (titel, description) = in welke kolommen we data invoegen
   VALUES (...) = de daadwerkelijke data die we invoegen

   We voegen 3 populaire spellen toe zodat de applicatie meteen
   bruikbaar is nadat de database is aangemaakt.
   Gebruikers kunnen later ook zelf spellen toevoegen.

   De 3 spellen die we toevoegen:
   1. Fortnite - Een heel populair Battle Royale spel
   2. Minecraft - Een sandbox bouwspel waar je alles kunt bouwen
   3. League of Legends - Een MOBA (team strategie) spel
   -------------------------------------------------------------------------- */
INSERT INTO Games
   (titel, description)
VALUES
   ('Fortnite', 'Battle Royale spel - Bouw, vecht en wees de laatste die overeind staat!'),
   ('Minecraft', 'Sandbox bouwspel - Maak alles wat je kunt bedenken!'),
   ('League of Legends', 'MOBA strategie spel - Werk samen en vernietig de vijandige nexus!');


/* ==========================================================================
   EINDE VAN DE DATABASE STRUCTUUR
   ==========================================================================
   SAMENVATTING VAN WAT ER IS AANGEMAAKT:
   --------------------------------------
   - 1 database:    gameplan_db
   - 6 tabellen:    Users, Games, UserGames, Friends, Schedules, Events
   - 3 indexen:     op email, schema's en evenementen (voor snelheid)
   - 3 spellen:     Fortnite, Minecraft, League of Legends (voorbeelddata)
   - Foreign keys:  verwijzingen tussen tabellen met CASCADE verwijdering

   RELATIES TUSSEN TABELLEN:
   -------------------------
   Users ---< UserGames >--- Games    (veel-op-veel: gebruikers en spellen)
   Users ---< Friends                 (een-op-veel: 1 gebruiker, veel vrienden)
   Users ---< Schedules >--- Games    (een-op-veel: 1 gebruiker, veel schema's)
   Users ---< Events                  (een-op-veel: 1 gebruiker, veel evenementen)

   INSTALLATIE STAPPEN:
   --------------------
   1. Open XAMPP Control Panel en start Apache en MySQL
   2. Open je browser en ga naar http://localhost/phpmyadmin
   3. Klik op "Importeren" in het bovenste menu
   4. Klik op "Bestand kiezen" en selecteer dit database.sql bestand
   5. Klik op "Start" onderaan de pagina
   6. Wacht tot je de melding "Import is succesvol uitgevoerd" ziet
   7. Ga nu naar http://localhost/gameplan-scheduler/ om de app te gebruiken
   ========================================================================== */
