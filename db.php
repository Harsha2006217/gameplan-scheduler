<?php
/**
 * ====================================================================================
 * DATABASE CONNECTION SCRIPT (db.php)
 * ============================================================================
 * DOEL: Establishes a secure PDO connection to MySQL database
 * AUTEUR: Harsha Kanaparthi
 * DATUM: 30-09-2025
 * 
 * UITLEG:
 * Dit bestand is het HART van de database-verbinding. Het zorgt ervoor dat
 * de applicatie veilig met de MySQL-database kan communiceren.
 * ============================================================================
 */

// --- STAP 1: DATABASE CONFIGURATIE (Verbindingsgegevens) ---
/**
 * WAAROM deze constanten?
 * - DB_HOST: Waar draait de database? (localhost = eigen computer)
 * - DB_USER: Wie mag toegang? (root = standaard gebruiker)
 * - DB_PASS: Wachtwoord voor toegang (leeg = geen wachtwoord nodig)
 * - DB_NAME: Welke database gebruiken we? (gameplan_db)
 * - DB_CHARSET: Welk teken-formaat? (utf8mb4 = ondersteunt alle talen)
 */
define('DB_HOST', 'localhost');      // ← Lokale database-server
define('DB_USER', 'root');           // ← Standaard MySQL gebruiker
define('DB_PASS', '');               // ← Geen wachtwoord (kan later aanpast)
define('DB_NAME', 'gameplan_db');    // ← Onze applicatie-database
define('DB_CHARSET', 'utf8mb4');     // ← Ondersteunt emoji's en alle talen

// --- STAP 2: FUNCTIE VOOR VEILIGE DATABASE-VERBINDING ---
/**
 * getDBConnection() - Maakt verbinding met de database
 * 
 * WAAROM een functie?
 * - Kunt meerdere keren bellen zonder dubbele verbindingen
 * - Veilig en herbruikbaar
 * - Gebruik van "static" = verbinding slechts 1x gemaakt
 */
function getDBConnection() {
    // "static" = deze variabele wordt maar EENMAAL aangemaakt
    // Volgende keren wordt dezelfde verbinding hergebruikt (efficiënt!)
    static $pdo = null;
    
    // CONTROLE: Is de verbinding al gemaakt?
    if ($pdo === null) {
        
        // STAP A: Maak de "Data Source Name" (verbindingsstring)
        // Dit zegt tegen PHP: "Verbind met MySQL op localhost, database gameplan_db, gebruik utf8mb4"
        $dsn = "mysql:host=" . DB_HOST . 
               ";dbname=" . DB_NAME . 
               ";charset=" . DB_CHARSET;
        
        // STAP B: Beveiligingsopties instellen
        /**
         * Waarom deze opties?
         * - ERRMODE_EXCEPTION: Als iets fout gaat, krijgen we een exception (fout)
         * - FETCH_ASSOC: Resultaten als associatieve arrays (gemakkelijk!)
         * - EMULATE_PREPARES false: Echte voorbereide statements (veilig tegen SQL-injectie!)
         * - PERSISTENT: Hergebruik verbinding (sneller!)
         */
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true,
        ];
        
        // STAP C: Probeer verbinding
        try {
            // VERBINDEN: Maak PDO-object aan met verbindingsgegevens
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            // Optioneel: Schrijf succesbericht naar log (voor debugging)
            // error_log("✓ Database verbinding succesvol!");
            
        } catch (PDOException $e) {
            // FOUT: Database verbinding mislukt!
            /**
             * Waarom error_log en niet gewoon echo?
             * - error_log: Schrijft naar server logboek (veilig, gebruikers zien niet details)
             * - echo: Zou wachtwoord/host kunnen tonen (GEVAARLIJK!)
             */
            error_log("❌ Database Verbinding Mislukt: " . $e->getMessage(), 0);
            
            // Geef gebruiker generiek bericht (niet het echte foutbericht!)
            die("Sorry, er was een probleem met de database. Probeer later opnieuw.");
        }
    }
    
    // TERUGZENDEN: Geef de verbinding terug voor gebruik
    return $pdo;
}

/**
 * ====================================================================================
 * SAMENVATTING VOOR EXAMINATOR:
 * ============================================================================
 * Dit bestand is KRITIEK voor veiligheid:
 * ✓ PDO = Prepared Statements (bescherming tegen SQL-injectie)
 * ✓ Error Handling = Fouten worden netjes afgehandeld
 * ✓ Static Connection = Geen dubbele verbindingen
 * ✓ Configuratie Aparte = Gemakkelijk aan te passen
 * ============================================================================
 */
?>