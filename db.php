<?php
/**
 * ============================================================================
 * db.php - DATABASE CONNECTIE | HARSHA KANAPARTHI | 2195344
 * ============================================================================
 * 
 * WAT DOET DIT? Dit bestand maakt verbinding met de MySQL database.
 * WAAROM? Zonder database kunnen we geen data opslaan of ophalen.
 * 
 * TECHNOLOGIE: PDO (PHP Data Objects) - veilige database communicatie
 * BEVEILIGING: Prepared statements tegen SQL injectie
 * ============================================================================
 */

// === DATABASE INSTELLINGEN ===
// Deze constanten bevatten de verbindingsgegevens
define('DB_HOST', 'localhost');     // Server waar database draait
define('DB_USER', 'root');          // Gebruikersnaam (wijzig in productie!)
define('DB_PASS', '');              // Wachtwoord (wijzig in productie!)
define('DB_NAME', 'gameplan_db');   // Naam van onze database
define('DB_CHARSET', 'utf8mb4');    // Tekenset voor emoji's en speciale tekens

/**
 * getDBConnection() - Haalt de database verbinding op
 * 
 * SINGLETON PATTERN: Maakt maar ÉÉN verbinding (efficiënt)
 * static $pdo = null betekent: onthoud deze variabele tussen aanroepen
 * 
 * @return PDO De database verbinding
 */
function getDBConnection()
{
    static $pdo = null;  // Singleton: hergebruik bestaande verbinding

    if ($pdo === null) {
        // DSN = Data Source Name (het "adres" van de database)
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        // Beveiligingsopties voor PDO
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // Gooi fouten als exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Resultaten als array
            PDO::ATTR_EMULATE_PREPARES => false,              // Echte prepared statements
            PDO::ATTR_PERSISTENT => true,                     // Hergebruik verbinding
        ];

        try {
            // Maak de verbinding
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log de fout (voor ontwikkelaar) maar toon vriendelijke melding
            error_log("Database Connection Failed: " . $e->getMessage(), 0);
            die("Sorry, database niet beschikbaar. Probeer later opnieuw.");
        }
    }
    return $pdo;
}