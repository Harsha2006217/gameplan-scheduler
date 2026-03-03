<?php
/**
 * ==========================================================================
 * DB.PHP - DATABASE VERBINDING
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand maakt een veilige verbinding met de MySQL database.
 * Het gebruikt PDO (PHP Data Objects) voor veilige database communicatie.
 * PDO beschermt tegen SQL-injectie door prepared statements te gebruiken.
 *
 * Ontwerppatroon: Singleton - er wordt maar 1 verbinding per sessie gemaakt.
 * ==========================================================================
 */

// --------------------------------------------------------------------------
// DATABASE INSTELLINGEN
// --------------------------------------------------------------------------
// Deze waarden worden gebruikt om verbinding te maken met de database.
// Constanten (define) kunnen na het instellen niet meer veranderd worden.
// --------------------------------------------------------------------------

// De server waar de database staat ('localhost' = dezelfde computer)
define('DB_HOST', 'localhost');

// Gebruikersnaam voor de database (standaard bij XAMPP is 'root')
define('DB_USER', 'root');

// Wachtwoord voor de database (standaard bij XAMPP is leeg)
define('DB_PASS', '');

// Naam van de database
define('DB_NAME', 'gameplan_db');

// Tekencodering (utf8mb4 ondersteunt alle tekens en speciale tekens)
define('DB_CHARSET', 'utf8mb4');


// --------------------------------------------------------------------------
// DATABASE VERBINDING FUNCTIE
// --------------------------------------------------------------------------

/**
 * getDBConnection - Maakt en retourneert een PDO database verbinding.
 *
 * Deze functie gebruikt het Singleton patroon:
 * - De eerste keer wordt een nieuwe verbinding gemaakt
 * - Daarna wordt dezelfde verbinding steeds hergebruikt
 * - Dit is sneller dan elke keer een nieuwe verbinding te maken
 *
 * @return PDO  Het database verbindingsobject
 */
function getDBConnection()
{
    // Statische variabele behoudt de waarde tussen functie-aanroepen
    // Eerste keer: $pdo is null, dus we maken een nieuwe verbinding
    // Daarna: $pdo heeft al een verbinding, dus we geven die terug
    static $pdo = null;

    // Maak alleen een nieuwe verbinding als er nog geen is
    if ($pdo === null) {

        // DSN (Data Source Name) vertelt PDO waar de database is
        // Bevat: type (mysql), server, database naam en tekenset
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        // PDO opties voor veiligheid en goede werking
        $options = [
            // Bij een fout: gooi een exception (zodat we fouten kunnen opvangen)
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // Resultaten ophalen als array met kolomnamen (bijv. $rij['username'])
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Gebruik echte prepared statements (veiliger dan geëmuleerde)
            PDO::ATTR_EMULATE_PREPARES => false,

            // Houd verbinding in leven voor hergebruik (sneller)
            PDO::ATTR_PERSISTENT => true,
        ];

        // Probeer verbinding te maken met de database
        try {
            // Maak een nieuw PDO object (= de verbinding)
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch (PDOException $e) {
            // Als de verbinding mislukt:
            // 1. Log de echte fout in het serverlogboek (voor de ontwikkelaar)
            error_log("Database verbinding mislukt: " . $e->getMessage(), 0);

            // 2. Toon een veilige melding aan de gebruiker
            // We tonen NOOIT de echte fout (bevat gevoelige informatie!)
            die("Sorry, er is een probleem met de database verbinding. Probeer het later opnieuw.");
        }
    }

    // Geef de database verbinding terug
    return $pdo;
}

// ==========================================================================
// EINDE VAN DB.PHP
// ==========================================================================
// Geen afsluitende PHP tag - dit voorkomt onbedoelde witruimte fouten.