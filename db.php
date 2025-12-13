<?php
/**
 * ============================================================================
 * db.php - DATABASE CONNECTIE BESTAND
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi
 * STUDENTNUMMER: 2195344
 * DATUM: 30-09-2025
 * VERSIE: 1.0
 * 
 * ============================================================================
 * WAT DOET DIT BESTAND? (WHAT DOES THIS FILE DO?)
 * ============================================================================
 * 
 * Dit bestand maakt een VEILIGE verbinding met de MySQL database.
 * Zonder dit bestand kan de applicatie GEEN data opslaan of ophalen.
 * 
 * VERGELIJK HET MET: Een sleutel die de deur naar de database opent.
 * Elke keer als we data willen lezen of schrijven, gebruiken we deze sleutel.
 * 
 * ============================================================================
 * TECHNISCHE CONCEPTEN UITGELEGD (TECHNICAL CONCEPTS EXPLAINED)
 * ============================================================================
 * 
 * 1. PDO (PHP Data Objects):
 *    - Dit is een VEILIGE manier om met databases te praten
 *    - PDO beschermt tegen SQL-injectie aanvallen (hackers)
 *    - PDO werkt met MySQL, PostgreSQL, SQLite, etc.
 * 
 * 2. SINGLETON PATTERN:
 *    - We maken MAAR ÉÉN database verbinding
 *    - Dit bespaart geheugen en is sneller
 *    - static $pdo = null; zorgt hiervoor
 * 
 * 3. TRY-CATCH:
 *    - "try" = probeer dit te doen
 *    - "catch" = als het mislukt, vang de fout op
 *    - Voorkomt dat de gebruiker technische fouten ziet
 * 
 * ============================================================================
 */

// ============================================================================
// STAP 1: DATABASE CONFIGURATIE CONSTANTEN
// ============================================================================
// 
// WAT ZIJN CONSTANTEN?
// Constanten zijn waarden die NOOIT veranderen tijdens het draaien van de app.
// We gebruiken define() om ze te maken. Ze zijn HOOFDLETTERGEVOELIG.
// 
// WAAROM CONSTANTEN?
// - Makkelijk te wijzigen op één plek
// - Veiliger dan variabelen (kunnen niet per ongeluk overschreven worden)
// - Duidelijke namen maken code leesbaar
// ============================================================================

/**
 * DB_HOST - De locatie van de database server
 * 
 * 'localhost' betekent: de database draait op DEZELFDE computer als de webserver.
 * In productie zou dit een IP-adres of domeinnaam kunnen zijn.
 * 
 * VOORBEELD: Als je database op een andere server staat:
 * define('DB_HOST', '192.168.1.100'); of define('DB_HOST', 'db.myhost.com');
 */
define('DB_HOST', 'localhost');

/**
 * DB_USER - De gebruikersnaam voor database toegang
 * 
 * 'root' is de standaard beheerder in XAMPP/MySQL.
 * 
 * BELANGRIJK VOOR PRODUCTIE:
 * - Maak een APART account aan voor de applicatie
 * - Geef alleen de rechten die nodig zijn (SELECT, INSERT, UPDATE, DELETE)
 * - Nooit 'root' gebruiken in productie!
 */
define('DB_USER', 'root');

/**
 * DB_PASS - Het wachtwoord voor de database gebruiker
 * 
 * Leeg ('') is standaard in XAMPP, maar ONVEILIG voor productie!
 * 
 * BEVEILIGINGSTIP:
 * - Gebruik een sterk wachtwoord (minimaal 12 tekens, letters, cijfers, symbolen)
 * - Sla wachtwoorden NOOIT op in code die naar Git gaat
 * - Gebruik environment variabelen in productie
 */
define('DB_PASS', '');

/**
 * DB_NAME - De naam van onze database
 * 
 * 'gameplan_db' is de database die we aanmaken in database.sql
 * Alle tabellen (Users, Games, Friends, etc.) staan hierin.
 */
define('DB_NAME', 'gameplan_db');

/**
 * DB_CHARSET - De tekenset voor de database
 * 
 * 'utf8mb4' ondersteunt ALLE tekens inclusief emoji's! 🎮
 * Dit is belangrijk voor internationale gebruikers en moderne tekst.
 * 
 * WAAROM utf8mb4 EN NIET utf8?
 * - utf8 in MySQL ondersteunt maar 3 bytes per teken
 * - utf8mb4 ondersteunt 4 bytes (volledige Unicode)
 * - emoji's en speciale tekens vereisen 4 bytes
 */
define('DB_CHARSET', 'utf8mb4');


// ============================================================================
// STAP 2: DE DATABASE CONNECTIE FUNCTIE
// ============================================================================
// 
// Deze functie is het HART van onze database communicatie.
// Elke keer als we data nodig hebben, roepen we getDBConnection() aan.
// 
// HOE WERKT HET?
// 1. Controleer of er al een verbinding is (singleton)
// 2. Zo niet, maak een nieuwe verbinding
// 3. Stel veiligheidsopties in
// 4. Geef de verbinding terug
// ============================================================================

/**
 * getDBConnection() - Haalt de database verbinding op
 * 
 * @return PDO - Een PDO object waarmee we queries kunnen uitvoeren
 * 
 * VOORBEELD GEBRUIK:
 * $pdo = getDBConnection();
 * $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
 * $stmt->execute([$email]);
 * $user = $stmt->fetch();
 */
function getDBConnection()
{

    // ========================================================================
    // SINGLETON PATTERN: Sla de verbinding op in een statische variabele
    // ========================================================================
    // 
    // 'static' betekent: deze variabele behoudt zijn waarde tussen functie-aanroepen
    // De EERSTE keer is $pdo null, daarna bevat het de verbinding
    // 
    // VOORDELEN:
    // - Efficiënt: we maken maar ÉÉN verbinding per pagina-aanvraag
    // - Snel: we hoeven niet elke keer opnieuw te verbinden
    // - Betrouwbaar: dezelfde verbinding voor alle queries
    // ========================================================================
    static $pdo = null;

    // ========================================================================
    // CONTROLEER: Is er al een verbinding?
    // ========================================================================
    // 
    // Als $pdo nog steeds null is, moeten we een nieuwe verbinding maken.
    // Als $pdo al een PDO object bevat, slaan we het maken over.
    // ========================================================================
    if ($pdo === null) {

        // ====================================================================
        // DSN (Data Source Name) - Het "adres" van de database
        // ====================================================================
        // 
        // De DSN vertelt PDO:
        // - Welk type database (mysql:)
        // - Waar de database is (host=localhost)
        // - Welke database (dbname=gameplan_db)
        // - Welke tekenset (charset=utf8mb4)
        // 
        // VOORBEELD DSN: "mysql:host=localhost;dbname=gameplan_db;charset=utf8mb4"
        // ====================================================================
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        // ====================================================================
        // PDO OPTIES - BELANGRIJKE BEVEILIGINGSINSTELLINGEN
        // ====================================================================
        // 
        // Deze opties maken onze database verbinding VEILIG en BETROUWBAAR.
        // ====================================================================
        $options = [

            /**
             * ATTR_ERRMODE => ERRMODE_EXCEPTION
             * 
             * WAT DOET HET?
             * Als er iets misgaat met een query, wordt er een "exception" gegooid.
             * Dit betekent dat de code STOPT en we de fout kunnen afhandelen.
             * 
             * WAAROM?
             * - Fouten worden NIET stilletjes genegeerd
             * - We kunnen fouten loggen voor debugging
             * - Voorkomt corrupte data door half-uitgevoerde queries
             */
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            /**
             * ATTR_DEFAULT_FETCH_MODE => FETCH_ASSOC
             * 
             * WAT DOET HET?
             * Resultaten komen terug als associatieve arrays (met kolomnamen als keys).
             * 
             * VOORBEELD:
             * Zonder FETCH_ASSOC: $row[0], $row[1], $row[2]
             * Met FETCH_ASSOC: $row['username'], $row['email'], $row['password']
             * 
             * WAAROM?
             * - Code is LEESBAARDER: $user['email'] vs $user[1]
             * - Code breekt niet als kolomvolgorde verandert
             */
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            /**
             * ATTR_EMULATE_PREPARES => false
             * 
             * WAT DOET HET?
             * Gebruikt ECHTE prepared statements van de MySQL server.
             * 
             * WAAROM IS DIT BELANGRIJK VOOR BEVEILIGING?
             * - Emulated prepares: PHP vervangt de ? zelf (minder veilig)
             * - Native prepares: MySQL doet de vervanging (veiliger)
             * - Native prepares geven ook betere foutmeldingen
             * 
             * DIT BESCHERMT TEGEN SQL INJECTIE!
             */
            PDO::ATTR_EMULATE_PREPARES => false,

            /**
             * ATTR_PERSISTENT => true
             * 
             * WAT DOET HET?
             * Houdt de database verbinding open tussen pagina-aanvragen.
             * 
             * VOORDELEN:
             * - Sneller: geen nieuwe verbinding nodig per aanvraag
             * - Efficiënter: minder overhead voor de database server
             * 
             * LET OP: In sommige hosting omgevingen is dit niet beschikbaar
             */
            PDO::ATTR_PERSISTENT => true,
        ];

        // ====================================================================
        // TRY-CATCH: PROBEER VERBINDING TE MAKEN, VANG FOUTEN OP
        // ====================================================================
        // 
        // try { ... } = "Probeer dit uit te voeren"
        // catch (PDOException $e) { ... } = "Als het mislukt, doe dit"
        // 
        // WAAROM TRY-CATCH?
        // - Database kan offline zijn
        // - Wachtwoord kan verkeerd zijn
        // - Database naam kan niet bestaan
        // - We willen NOOIT technische fouten aan gebruikers tonen!
        // ====================================================================
        try {

            /**
             * MAAK DE PDO VERBINDING
             * 
             * new PDO() maakt een nieuw PDO object met:
             * - $dsn: waar de database is
             * - DB_USER: de gebruikersnaam
             * - DB_PASS: het wachtwoord
             * - $options: de beveiligingsinstellingen
             */
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch (PDOException $e) {

            // ================================================================
            // FOUT AFHANDELING
            // ================================================================
            // 
            // Als de verbinding MISLUKT:
            // 1. Log de ECHTE fout (voor de ontwikkelaar)
            // 2. Toon een VRIENDELIJKE melding (voor de gebruiker)
            // 3. STOP de applicatie (die() of exit)
            // ================================================================

            /**
             * error_log() - Schrijf de fout naar het server log
             * 
             * Dit is ALLEEN zichtbaar voor de ontwikkelaar/beheerder.
             * De gebruiker ziet dit NOOIT!
             * 
             * Het log bestand staat meestal in:
             * - XAMPP: xampp/apache/logs/error.log
             * - Linux: /var/log/apache2/error.log
             */
            error_log("Database Connection Failed: " . $e->getMessage(), 0);

            /**
             * die() - Stop de applicatie met een bericht
             * 
             * We tonen een VRIENDELIJKE foutmelding aan de gebruiker.
             * NOOIT de echte fout tonen! Hackers kunnen die informatie misbruiken.
             * 
             * SLECHT: die("Error: " . $e->getMessage());
             *         Dit kan wachtwoorden, database namen, etc. onthullen!
             * 
             * GOED: Een generieke, vriendelijke melding zoals hieronder.
             */
            die("Sorry, there was an issue connecting to the database. Please try again later.");
        }
    }

    // ========================================================================
    // GEEF DE VERBINDING TERUG
    // ========================================================================
    // 
    // Nu kan de aanroepende code de verbinding gebruiken om queries uit te voeren.
    // 
    // VOORBEELD:
    // $pdo = getDBConnection();
    // $pdo->prepare("SELECT * FROM Users")->execute();
    // ========================================================================
    return $pdo;
}

// ============================================================================
// BELANGRIJK: GEEN SLUITENDE PHP TAG
// ============================================================================
// 
// We laten de BEWUST weg aan het einde van dit bestand.
//
// WAAROM?
// - Voorkomt onbedoelde whitespace na de sluitende tag
// - Whitespace kan "headers already sent" fouten veroorzaken
// - Dit is een PHP best practice voor bestanden die alleen PHP bevatten
//
// ============================================================================