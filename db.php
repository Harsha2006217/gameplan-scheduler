<?php
/**
 * ============================================================================
 * db.php - Database Verbinding Script (Database Connection Script)
 * ============================================================================
 * 
 * @author      Harsha Kanaparthi
 * @student     2195344
 * @date        30-09-2025
 * @version     1.0
 * @project     GamePlan Scheduler
 * 
 * ============================================================================
 * BESCHRIJVING / DESCRIPTION:
 * ============================================================================
 * Dit bestand is verantwoordelijk voor het maken van een veilige verbinding
 * met de MySQL database. Het gebruikt PDO (PHP Data Objects) voor database
 * communicatie, wat veiliger is dan de oudere mysql_* functies.
 * 
 * This file is responsible for creating a secure connection to the MySQL 
 * database. It uses PDO (PHP Data Objects) for database communication,
 * which is safer than the older mysql_* functions.
 * 
 * ============================================================================
 * WAAROM PDO? / WHY PDO?
 * ============================================================================
 * PDO biedt:
 * - Prepared statements (bescherming tegen SQL-injectie)
 * - Object-georiënteerde interface
 * - Ondersteuning voor meerdere database types
 * - Betere foutafhandeling met exceptions
 * 
 * PDO provides:
 * - Prepared statements (protection against SQL injection)
 * - Object-oriented interface
 * - Support for multiple database types
 * - Better error handling with exceptions
 * 
 * ============================================================================
 * SINGLETON PATTERN UITLEG / SINGLETON PATTERN EXPLANATION:
 * ============================================================================
 * We gebruiken het Singleton pattern: er wordt slechts één database 
 * verbinding gemaakt en hergebruikt. Dit bespaart resources en voorkomt
 * problemen met te veel open verbindingen.
 * 
 * We use the Singleton pattern: only one database connection is created
 * and reused. This saves resources and prevents issues with too many
 * open connections.
 * 
 * ============================================================================
 */

// ============================================================================
// DATABASE CONFIGURATIE CONSTANTEN / DATABASE CONFIGURATION CONSTANTS
// ============================================================================
// We gebruiken define() om constanten te maken. Constanten kunnen niet 
// worden gewijzigd nadat ze zijn ingesteld, wat de veiligheid verhoogt.
// 
// We use define() to create constants. Constants cannot be changed after
// they are set, which increases security.
// ============================================================================

/**
 * DB_HOST - De hostnaam van de database server
 * 'localhost' betekent dat de database op dezelfde computer draait als de webserver
 * In productie zou dit een apart IP-adres of hostnaam kunnen zijn
 */
define('DB_HOST', 'localhost');

/**
 * DB_USER - De gebruikersnaam voor database toegang
 * 'root' is de standaard XAMPP gebruiker
 * WAARSCHUWING: Gebruik een andere gebruiker met beperkte rechten in productie!
 */
define('DB_USER', 'root');

/**
 * DB_PASS - Het wachtwoord voor database toegang
 * Leeg voor standaard XAMPP installatie
 * WAARSCHUWING: Gebruik een sterk wachtwoord in productie!
 */
define('DB_PASS', '');

/**
 * DB_NAME - De naam van onze database
 * Dit is de database die we hebben aangemaakt met database.sql
 */
define('DB_NAME', 'gameplan_db');

/**
 * DB_CHARSET - De karakterset voor de database verbinding
 * 'utf8mb4' ondersteunt alle Unicode karakters inclusief emoji's
 * Dit is belangrijk voor internationale tekst en moderne communicatie
 */
define('DB_CHARSET', 'utf8mb4');

// ============================================================================
// FUNCTIE: getDBConnection() - Haalt de database verbinding op
// ============================================================================
/**
 * getDBConnection - Maakt of hergebruikt de database verbinding
 * 
 * Deze functie implementeert het Singleton pattern:
 * - Bij eerste aanroep: maakt nieuwe verbinding
 * - Bij volgende aanroepen: hergebruikt bestaande verbinding
 * 
 * @return PDO De PDO database verbinding object
 * 
 * VOORBEELD GEBRUIK / EXAMPLE USAGE:
 * $pdo = getDBConnection();
 * $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = :id");
 * $stmt->execute(['id' => 1]);
 * $user = $stmt->fetch();
 */
function getDBConnection()
{
    // ========================================================================
    // STATIC VARIABELE - Houdt de verbinding vast tussen functie aanroepen
    // ========================================================================
    // 'static' betekent dat $pdo behouden blijft na de eerste keer
    // dat de functie wordt aangeroepen. Dit is het Singleton pattern.
    // ========================================================================
    static $pdo = null;

    // ========================================================================
    // CONTROLEER OF ER AL EEN VERBINDING IS
    // ========================================================================
    // Als $pdo nog null is, dan moeten we een nieuwe verbinding maken
    // Als $pdo al een waarde heeft, slaan we het maken over en geven
    // we de bestaande verbinding terug
    // ========================================================================
    if ($pdo === null) {

        // ====================================================================
        // DATA SOURCE NAME (DSN) - De verbindingsstring
        // ====================================================================
        // DSN bevat alle informatie die PDO nodig heeft om te verbinden:
        // - mysql: het type database
        // - host: waar de database is
        // - dbname: welke database we willen
        // - charset: welke karakterset we gebruiken
        // ====================================================================
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        // ====================================================================
        // PDO OPTIES - Configuratie voor veilige en efficiënte werking
        // ====================================================================
        $options = [
            // ----------------------------------------------------------------
            // ERRMODE_EXCEPTION: Gooit exceptions bij fouten
            // ----------------------------------------------------------------
            // Dit zorgt ervoor dat database fouten als PHP exceptions
            // worden gegooid, zodat we ze kunnen opvangen met try-catch
            // ----------------------------------------------------------------
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // ----------------------------------------------------------------
            // FETCH_ASSOC: Geeft resultaten als associatieve arrays
            // ----------------------------------------------------------------
            // Resultaten komen terug als ['kolom_naam' => 'waarde']
            // in plaats van genummerde arrays [0 => 'waarde']
            // Dit maakt code leesbaarder: $row['username'] vs $row[0]
            // ----------------------------------------------------------------
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // ----------------------------------------------------------------
            // EMULATE_PREPARES = false: Echte prepared statements
            // ----------------------------------------------------------------
            // Dit zorgt ervoor dat MySQL zelf de prepared statements
            // uitvoert, niet PHP. Dit is veiliger tegen SQL-injectie.
            // ----------------------------------------------------------------
            PDO::ATTR_EMULATE_PREPARES => false,

            // ----------------------------------------------------------------
            // PERSISTENT = true: Hergebruik verbindingen
            // ----------------------------------------------------------------
            // Houdt de verbinding open tussen requests voor betere
            // performance. Dit is vooral nuttig bij veel bezoekers.
            // ----------------------------------------------------------------
            PDO::ATTR_PERSISTENT => true,
        ];

        // ====================================================================
        // PROBEER DE VERBINDING TE MAKEN MET TRY-CATCH
        // ====================================================================
        // try-catch vangt fouten op zodat we ze netjes kunnen afhandelen
        // zonder dat de gebruiker technische foutmeldingen ziet
        // ====================================================================
        try {
            // ================================================================
            // MAAK NIEUWE PDO VERBINDING
            // ================================================================
            // PDO constructor krijgt:
            // 1. $dsn - waar en welke database
            // 2. DB_USER - gebruikersnaam
            // 3. DB_PASS - wachtwoord
            // 4. $options - configuratie opties
            // ================================================================
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch (PDOException $e) {
            // ================================================================
            // FOUTAFHANDELING BIJ MISLUKTE VERBINDING
            // ================================================================
            // Als de verbinding mislukt (verkeerde wachtwoord, database
            // niet beschikbaar, etc.), loggen we de fout en tonen een
            // vriendelijke melding aan de gebruiker
            // ================================================================

            // ----------------------------------------------------------------
            // LOG DE FOUT NAAR DE SERVER LOG
            // ----------------------------------------------------------------
            // error_log() schrijft naar PHP's error log bestand
            // Dit is belangrijk voor debugging, maar tonen we niet
            // aan gebruikers om veiligheidsredenen
            // ----------------------------------------------------------------
            error_log("Database Connection Failed: " . $e->getMessage(), 0);

            // ----------------------------------------------------------------
            // STOP HET SCRIPT MET VRIENDELIJKE MELDING
            // ----------------------------------------------------------------
            // die() stopt alle verdere uitvoering van het script
            // De gebruiker ziet alleen een vriendelijke foutmelding,
            // geen technische details die hackers kunnen helpen
            // ----------------------------------------------------------------
            die("Sorry, there was an issue connecting to the database. Please try again later.");
        }
    }

    // ========================================================================
    // GEEF DE PDO VERBINDING TERUG
    // ========================================================================
    // Nu kunnen andere delen van de applicatie deze verbinding gebruiken
    // om queries uit te voeren op de database
    // ========================================================================
    return $pdo;
}

// ============================================================================
// OPMERKING: GEEN SLUITENDE PHP TAG
// ============================================================================
// We laten de sluitende  tag weg. Dit is een PHP best practice omdat
// het voorkomt dat er per ongeluk witruimte wordt toegevoegd na de tag,
// wat problemen kan veroorzaken met headers (sessies, redirects, etc.)
// ============================================================================