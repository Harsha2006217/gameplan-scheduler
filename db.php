<?php
/**
 * ============================================================================
 * DB.PHP - DATABASE VERBINDING
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand maakt een veilige verbinding met de MySQL database.
 * Het gebruikt PDO (PHP Data Objects) wat de veiligste manier is om met
 * databases te verbinden. PDO beschermt tegen SQL-injectie aanvallen
 * door prepared statements te gebruiken.
 *
 * Ontwerppatroon: Singleton (slechts 1 verbinding per sessie)
 * ============================================================================
 */

// ============================================================================
// SECTIE 1: DATABASE CONFIGURATIE CONSTANTEN
// ============================================================================

/**
 * Deze constanten slaan de database verbindingsinstellingen op.
 * Het gebruik van constanten (define) betekent dat deze waarden later
 * niet meer kunnen worden gewijzigd. Dit maakt de code veiliger en
 * voorkomt onbedoelde wijzigingen.
 */

// DB_HOST: De server waar de database zich bevindt.
// 'localhost' betekent dat de database op dezelfde computer staat als de webserver.
define('DB_HOST', 'localhost');

// DB_USER: De gebruikersnaam om in te loggen op de database.
// 'root' is de standaard XAMPP gebruikersnaam — verander dit in productie!
define('DB_USER', 'root');

// DB_PASS: Het wachtwoord voor de database gebruiker.
// Leeg voor XAMPP standaard — ALTIJD een sterk wachtwoord instellen in productie!
define('DB_PASS', '');

// DB_NAME: De naam van de database voor deze applicatie.
// Deze database slaat alle GamePlan Scheduler data op.
define('DB_NAME', 'gameplan_db');

// DB_CHARSET: Tekencodering voor de database.
// 'utf8mb4' ondersteunt alle tekens inclusief emoji's 😀
define('DB_CHARSET', 'utf8mb4');


// ============================================================================
// SECTIE 2: DATABASE VERBINDING FUNCTIE
// ============================================================================

/**
 * getDBConnection() - Maakt en retourneert een PDO database verbinding.
 *
 * Deze functie gebruikt het "Singleton Pattern" wat betekent:
 * - De verbinding wordt slechts ÉÉN KEER gemaakt, de eerste keer dat
 *   deze functie wordt aangeroepen.
 * - Daarna wordt dezelfde verbinding elke keer hergebruikt.
 * - Dit is efficiënter omdat het maken van database verbindingen langzaam is.
 *
 * @return PDO Het database verbindingsobject
 */
function getDBConnection()
{
    // ========================================================================
    // STAP 1: STATISCHE VARIABELE VOOR SINGLETON PATTERN
    // ========================================================================
    // 'static' betekent dat deze variabele zijn waarde behoudt tussen functie-aanroepen.
    // Eerste aanroep: $pdo is null, dus we maken een nieuwe verbinding.
    // Volgende aanroepen: $pdo heeft al een verbinding, dus we retourneren die.
    static $pdo = null;

    // ========================================================================
    // STAP 2: CONTROLEER OF VERBINDING AL BESTAAT
    // ========================================================================
    // Maak alleen een nieuwe verbinding als we er nog geen hebben.
    // Dit bespaart tijd en servercapaciteit.
    if ($pdo === null) {

        // ====================================================================
        // STAP 3: MAAK DATA SOURCE NAME (DSN)
        // ====================================================================
        // DSN is een string die PDO vertelt hoe te verbinden met de database.
        // Bevat: mysql (database type), host, database naam en tekenset.
        // Voorbeeld: "mysql:host=localhost;dbname=gameplan_db;charset=utf8mb4"
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        // ====================================================================
        // STAP 4: STEL PDO OPTIES IN VOOR VEILIGHEID EN PRESTATIES
        // ====================================================================
        $options = [
            // ERRMODE_EXCEPTION: Als er een database fout optreedt, gooi een exception.
            // Dit stelt ons in staat om fouten op te vangen met try-catch blokken.
            // Zonder dit worden fouten mogelijk stilzwijgend genegeerd!
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // FETCH_ASSOC: Bij het ophalen van data, retourneer als associatieve arrays.
            // Dit betekent dat we data kunnen benaderen als: $row['username']
            // In plaats van: $row[0] (wat verwarrend is)
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // EMULATE_PREPARES: Zet op FALSE om ECHTE prepared statements te gebruiken.
            // Echte prepared statements zijn veiliger dan geëmuleerde.
            // De database handelt de beveiliging af, niet PHP.
            PDO::ATTR_EMULATE_PREPARES => false,

            // PERSISTENT: Houd de verbinding in leven voor hergebruik.
            // Dit is sneller maar gebruikt meer servergeheugen.
            // Goed voor applicaties met veel database queries.
            PDO::ATTR_PERSISTENT => true,
        ];

        // ====================================================================
        // STAP 5: PROBEER DE VERBINDING TE MAKEN
        // ====================================================================
        // We gebruiken try-catch om fouten netjes af te handelen.
        // Als de verbinding mislukt, loggen we de fout en tonen een vriendelijke melding.
        // We tonen NOOIT de echte fout aan gebruikers (veiligheidsrisico!).
        try {
            // Maak nieuw PDO object met DSN, gebruikersnaam, wachtwoord en opties.
            // Als dit lukt, bevat $pdo onze database verbinding.
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch (PDOException $e) {

            // ================================================================
            // STAP 6: FOUTAFHANDELING — VEILIGE LOGGING
            // ================================================================
            // Als de verbinding mislukt:
            // 1. Log de ECHTE fout naar de server error log (voor ontwikkelaars).
            // 2. Toon een ALGEMENE melding aan gebruikers (verbergt gevoelige info).
            //
            // Waarom? Foutmeldingen kunnen bevatten:
            // - Database gebruikersnaam/wachtwoord
            // - Serverpaden
            // - Database structuur informatie
            // Kwaadwillenden kunnen deze informatie misbruiken!
            error_log("Database verbinding mislukt: " . $e->getMessage(), 0);

            // die() stopt het script en toont een gebruiksvriendelijke melding.
            // De gebruiker ziet de technische foutdetails niet.
            die("Sorry, er is een probleem met de databaseverbinding. Probeer het later opnieuw.");
        }
    }

    // ========================================================================
    // STAP 7: RETOURNEER DE VERBINDING
    // ========================================================================
    // Retourneer het PDO verbindingsobject.
    // Andere bestanden kunnen dit gebruiken om database queries uit te voeren.
    return $pdo;
}

// ============================================================================
// EINDE VAN BESTAND
// ============================================================================
// Geen afsluitende ?> tag is met opzet.
// Dit voorkomt onbedoelde witruimte output wat "headers already sent"
// fouten in PHP kan veroorzaken.