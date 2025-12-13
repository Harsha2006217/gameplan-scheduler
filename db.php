<?php
/**
 * ============================================================================
 * DB.PHP - DATABASE CONNECTION SCRIPT / DATABASE VERBINDING SCRIPT
 * ============================================================================
 * 
 * ENGLISH:
 * This file creates a secure connection to the MySQL database.
 * It uses PDO (PHP Data Objects) which is the safest way to connect to databases.
 * PDO protects against SQL injection attacks by using prepared statements.
 * 
 * DUTCH / NEDERLANDS:
 * Dit bestand maakt een veilige verbinding met de MySQL database.
 * Het gebruikt PDO (PHP Data Objects) wat de veiligste manier is om met databases te verbinden.
 * PDO beschermt tegen SQL-injectie aanvallen door prepared statements te gebruiken.
 * 
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi
 * Student Number / Studentnummer: 2195344
 * Date / Datum: 30-09-2025
 * Project: GamePlan Scheduler
 * ============================================================================
 */

// ============================================================================
// SECTION 1: DATABASE CONFIGURATION CONSTANTS
// SECTIE 1: DATABASE CONFIGURATIE CONSTANTEN
// ============================================================================

/**
 * ENGLISH:
 * These constants store the database connection settings.
 * Using constants (define) means these values cannot be changed later in the code.
 * This makes the code more secure and prevents accidental changes.
 * 
 * DUTCH / NEDERLANDS:
 * Deze constanten slaan de database verbindingsinstellingen op.
 * Het gebruik van constanten (define) betekent dat deze waarden later niet meer kunnen worden gewijzigd.
 * Dit maakt de code veiliger en voorkomt onbedoelde wijzigingen.
 */

// DB_HOST: The server where the database is located
// DB_HOST: De server waar de database zich bevindt
// 'localhost' means the database is on the same computer as the web server
// 'localhost' betekent dat de database op dezelfde computer staat als de webserver
define('DB_HOST', 'localhost');

// DB_USER: The username to log into the database
// DB_USER: De gebruikersnaam om in te loggen op de database
// 'root' is the default XAMPP username - change this in production!
// 'root' is de standaard XAMPP gebruikersnaam - verander dit in productie!
define('DB_USER', 'root');

// DB_PASS: The password for the database user
// DB_PASS: Het wachtwoord voor de database gebruiker
// Empty for XAMPP default - ALWAYS set a strong password in production!
// Leeg voor XAMPP standaard - ALTIJD een sterk wachtwoord instellen in productie!
define('DB_PASS', '');

// DB_NAME: The name of our database for this application
// DB_NAME: De naam van onze database voor deze applicatie
// This database stores all GamePlan Scheduler data
// Deze database slaat alle GamePlan Scheduler data op
define('DB_NAME', 'gameplan_db');

// DB_CHARSET: Character encoding for the database
// DB_CHARSET: Tekencodering voor de database
// 'utf8mb4' supports all characters including emojis 😀
// 'utf8mb4' ondersteunt alle tekens inclusief emoji's 😀
define('DB_CHARSET', 'utf8mb4');


// ============================================================================
// SECTION 2: DATABASE CONNECTION FUNCTION
// SECTIE 2: DATABASE VERBINDING FUNCTIE
// ============================================================================

/**
 * getDBConnection() - Creates and returns a PDO database connection
 * getDBConnection() - Maakt en retourneert een PDO database verbinding
 * 
 * ENGLISH:
 * This function uses the "Singleton Pattern" which means:
 * - The connection is created only ONCE, the first time this function is called
 * - After that, the same connection is reused every time
 * - This is more efficient because creating database connections is slow
 * 
 * DUTCH / NEDERLANDS:
 * Deze functie gebruikt het "Singleton Pattern" wat betekent:
 * - De verbinding wordt slechts ÉÉN KEER gemaakt, de eerste keer dat deze functie wordt aangeroepen
 * - Daarna wordt dezelfde verbinding elke keer hergebruikt
 * - Dit is efficiënter omdat het maken van database verbindingen langzaam is
 * 
 * @return PDO The database connection object / Het database verbindingsobject
 */
function getDBConnection()
{

    // ========================================================================
    // STEP 1: STATIC VARIABLE FOR SINGLETON PATTERN
    // STAP 1: STATISCHE VARIABELE VOOR SINGLETON PATTERN
    // ========================================================================

    /**
     * ENGLISH:
     * 'static' means this variable keeps its value between function calls.
     * First call: $pdo is null, so we create a new connection
     * Next calls: $pdo already has a connection, so we just return it
     * 
     * DUTCH / NEDERLANDS:
     * 'static' betekent dat deze variabele zijn waarde behoudt tussen functie-aanroepen.
     * Eerste aanroep: $pdo is null, dus we maken een nieuwe verbinding
     * Volgende aanroepen: $pdo heeft al een verbinding, dus we retourneren die gewoon
     */
    static $pdo = null;

    // ========================================================================
    // STEP 2: CHECK IF CONNECTION ALREADY EXISTS
    // STAP 2: CONTROLEER OF VERBINDING AL BESTAAT
    // ========================================================================

    /**
     * ENGLISH:
     * Only create a new connection if we don't have one yet.
     * This saves time and server resources.
     * 
     * DUTCH / NEDERLANDS:
     * Maak alleen een nieuwe verbinding als we er nog geen hebben.
     * Dit bespaart tijd en servercapaciteit.
     */
    if ($pdo === null) {

        // ====================================================================
        // STEP 3: CREATE DATA SOURCE NAME (DSN)
        // STAP 3: MAAK DATA SOURCE NAME (DSN)
        // ====================================================================

        /**
         * ENGLISH:
         * DSN is a string that tells PDO how to connect to the database.
         * It contains: mysql (database type), host, database name, and charset.
         * Example: "mysql:host=localhost;dbname=gameplan_db;charset=utf8mb4"
         * 
         * DUTCH / NEDERLANDS:
         * DSN is een string die PDO vertelt hoe te verbinden met de database.
         * Het bevat: mysql (database type), host, database naam, en tekenset.
         * Voorbeeld: "mysql:host=localhost;dbname=gameplan_db;charset=utf8mb4"
         */
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        // ====================================================================
        // STEP 4: SET PDO OPTIONS FOR SECURITY AND PERFORMANCE
        // STAP 4: STEL PDO OPTIES IN VOOR VEILIGHEID EN PRESTATIES
        // ====================================================================

        /**
         * ENGLISH:
         * These options configure how PDO behaves. Each option is important:
         * 
         * DUTCH / NEDERLANDS:
         * Deze opties configureren hoe PDO zich gedraagt. Elke optie is belangrijk:
         */
        $options = [

            /**
             * ERRMODE_EXCEPTION: Error Handling Mode
             * ENGLISH: When a database error occurs, throw an exception.
             *          This allows us to catch errors with try-catch blocks.
             *          Without this, errors might be silently ignored!
             * DUTCH: Als er een database fout optreedt, gooi een exception.
             *        Dit stelt ons in staat om fouten op te vangen met try-catch blokken.
             *        Zonder dit worden fouten mogelijk stilzwijgend genegeerd!
             */
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            /**
             * FETCH_ASSOC: Default Fetch Mode
             * ENGLISH: When getting data, return it as associative arrays.
             *          This means we can access data like: $row['username']
             *          Instead of: $row[0] (which is confusing)
             * DUTCH: Bij het ophalen van data, retourneer als associatieve arrays.
             *        Dit betekent dat we data kunnen benaderen als: $row['username']
             *        In plaats van: $row[0] (wat verwarrend is)
             */
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            /**
             * EMULATE_PREPARES: Prepared Statement Mode
             * ENGLISH: Set to FALSE to use REAL prepared statements.
             *          Real prepared statements are more secure than emulated ones.
             *          The database handles the security, not PHP.
             * DUTCH: Zet op FALSE om ECHTE prepared statements te gebruiken.
             *        Echte prepared statements zijn veiliger dan geëmuleerde.
             *        De database handelt de beveiliging af, niet PHP.
             */
            PDO::ATTR_EMULATE_PREPARES => false,

            /**
             * PERSISTENT: Connection Persistence
             * ENGLISH: Keep the connection alive for reuse.
             *          This is faster but uses more server memory.
             *          Good for applications with many database queries.
             * DUTCH: Houd de verbinding in leven voor hergebruik.
             *        Dit is sneller maar gebruikt meer servergeheugen.
             *        Goed voor applicaties met veel database queries.
             */
            PDO::ATTR_PERSISTENT => true,
        ];

        // ====================================================================
        // STEP 5: TRY TO CREATE THE CONNECTION
        // STAP 5: PROBEER DE VERBINDING TE MAKEN
        // ====================================================================

        /**
         * ENGLISH:
         * We use try-catch to handle errors gracefully.
         * If connection fails, we log the error and show a friendly message.
         * We NEVER show the actual error to users (security risk!).
         * 
         * DUTCH / NEDERLANDS:
         * We gebruiken try-catch om fouten netjes af te handelen.
         * Als de verbinding mislukt, loggen we de fout en tonen een vriendelijke melding.
         * We tonen NOOIT de echte fout aan gebruikers (veiligheidsrisico!).
         */
        try {

            /**
             * ENGLISH:
             * Create new PDO object with DSN, username, password, and options.
             * If successful, $pdo contains our database connection.
             * 
             * DUTCH / NEDERLANDS:
             * Maak nieuw PDO object met DSN, gebruikersnaam, wachtwoord, en opties.
             * Als dit lukt, bevat $pdo onze database verbinding.
             */
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch (PDOException $e) {

            // ================================================================
            // STEP 6: ERROR HANDLING - SECURE LOGGING
            // STAP 6: FOUTAFHANDELING - VEILIGE LOGGING
            // ================================================================

            /**
             * ENGLISH:
             * If connection fails, we:
             * 1. Log the REAL error to the server's error log (for developers)
             * 2. Show a GENERIC message to users (hides sensitive info)
             * 
             * Why? Error messages might contain:
             * - Database username/password
             * - Server paths
             * - Database structure info
             * Hackers could use this information!
             * 
             * DUTCH / NEDERLANDS:
             * Als de verbinding mislukt, doen we:
             * 1. Log de ECHTE fout naar de server's error log (voor ontwikkelaars)
             * 2. Toon een ALGEMENE melding aan gebruikers (verbergt gevoelige info)
             * 
             * Waarom? Foutmeldingen kunnen bevatten:
             * - Database gebruikersnaam/wachtwoord
             * - Serverpaden
             * - Database structuur informatie
             * Hackers kunnen deze informatie gebruiken!
             */
            error_log("Database Connection Failed: " . $e->getMessage(), 0);

            /**
             * ENGLISH:
             * die() stops the script and shows a user-friendly message.
             * The user doesn't see the technical error details.
             * 
             * DUTCH / NEDERLANDS:
             * die() stopt het script en toont een gebruiksvriendelijke melding.
             * De gebruiker ziet de technische foutdetails niet.
             */
            die("Sorry, there was an issue connecting to the database. Please try again later. / Sorry, er was een probleem met de database verbinding. Probeer het later opnieuw.");
        }
    }

    // ========================================================================
    // STEP 7: RETURN THE CONNECTION
    // STAP 7: RETOURNEER DE VERBINDING
    // ========================================================================

    /**
     * ENGLISH:
     * Return the PDO connection object.
     * Other files can use this to run database queries.
     * 
     * DUTCH / NEDERLANDS:
     * Retourneer het PDO verbindingsobject.
     * Andere bestanden kunnen dit gebruiken om database queries uit te voeren.
     */
    return $pdo;
}

// ============================================================================
// END OF FILE - EINDE VAN BESTAND
// ============================================================================
/**
 * ENGLISH:
 * No closing ?> tag is intentional.
 * This prevents accidental whitespace output which can cause
 * "headers already sent" errors in PHP.
 * 
 * DUTCH / NEDERLANDS:
 * Geen afsluitende ?> tag is met opzet.
 * Dit voorkomt onbedoelde witruimte output wat
 * "headers already sent" fouten in PHP kan veroorzaken.
 */