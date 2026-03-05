<?php
/**
 * ==========================================================================
 * DB.PHP - DATABASE VERBINDING (Database Connection)
 * ==========================================================================
 * Bestandsnaam : db.php
 * Auteur       : Harsha Kanaparthi
 * Studentnummer: 2195344
 * Opleiding    : MBO-4 Software Developer (Crebo 25998)
 * Datum        : 30-09-2025
 * Versie       : 1.0
 * PHP-versie   : 8.1+
 * Encoding     : UTF-8
 * Database     : MySQL 8.0+ (InnoDB engine)
 *
 * ==========================================================================
 * BESCHRIJVING
 * ==========================================================================
 * Dit bestand maakt een veilige verbinding met de MySQL database.
 * Vergelijk het met het openen van een telefoonlijn naar de database:
 * voordat je gegevens kunt ophalen of opslaan, moet je eerst
 * "verbinding maken" (de telefoon oppakken en het nummer bellen).
 *
 * Dit is het FUNDAMENT van de hele applicatie. Zonder een werkende
 * database-verbinding kan GEEN ENKELE pagina gegevens ophalen of opslaan.
 * Het is het eerste bestand dat geladen wordt in de keten:
 *
 * db.php → functions.php → alle andere pagina's
 *
 * TECHNOLOGIE: PDO (PHP Data Objects)
 * PDO is een veilige manier om met de database te praten vanuit PHP.
 * Het beschermt automatisch tegen SQL-injectie aanvallen
 * (dat is wanneer een hacker probeert kwaadaardige code in te voeren
 * via formulieren om de database te hacken).
 *
 * ONTWERPPATROON: Singleton
 * Er wordt maar 1 verbinding per sessie gemaakt en die wordt hergebruikt.
 * Dit is sneller dan elke keer een nieuwe verbinding te openen en te sluiten.
 *
 * ==========================================================================
 * HOE DIT BESTAND WORDT GEBRUIKT (LAADKETEN)
 * ==========================================================================
 * db.php wordt NIET rechtstreeks door pagina's geladen. Het wordt
 * indirect geladen via functions.php:
 *
 * ┌─────────────────────────────────────────────────────────────────────┐
 * │                                                                     │
 * │   db.php                                                            │
 * │     ↑                                                               │
 * │     │ require_once (geladen door functions.php)                      │
 * │     │                                                               │
 * │   functions.php                                                     │
 * │     ↑                                                               │
 * │     │ require_once (geladen door ELKE pagina)                        │
 * │     │                                                               │
 * │   ┌─┴──────────────────────────────────────────────────────────┐    │
 * │   │ index.php │ login.php │ register.php │ profile.php │ ...   │    │
 * │   └───────────────────────────────────────────────────────────────┘  │
 * │                                                                     │
 * │   Resultaat: ELKE pagina heeft toegang tot getDBConnection()        │
 * │   zonder db.php zelf te hoeven laden                                │
 * └─────────────────────────────────────────────────────────────────────┘
 *
 * ==========================================================================
 * DATABASE CONFIGURATIE
 * ==========================================================================
 * De verbinding gebruikt 5 constanten (instellingen):
 *
 * ┌──────────────┬────────────────┬────────────────────────────────────────┐
 * │ Constante    │ Waarde         │ Uitleg                                 │
 * ├──────────────┼────────────────┼────────────────────────────────────────┤
 * │ DB_HOST      │ localhost      │ Server waar MySQL draait (eigen PC)    │
 * │ DB_USER      │ root           │ Database gebruikersnaam (XAMPP default) │
 * │ DB_PASS      │ (leeg)         │ Database wachtwoord (XAMPP default)     │
 * │ DB_NAME      │ gameplan_db    │ Naam van de database (uit database.sql)│
 * │ DB_CHARSET   │ utf8mb4        │ Tekencodering (alle tekens + emoji's)  │
 * └──────────────┴────────────────┴────────────────────────────────────────┘
 *
 * ==========================================================================
 * PDO OPTIES (verbindingsinstellingen)
 * ==========================================================================
 * ┌─────────────────────────────┬──────────────────────────────────────────┐
 * │ PDO Optie                   │ Uitleg                                   │
 * ├─────────────────────────────┼──────────────────────────────────────────┤
 * │ ERRMODE_EXCEPTION           │ Gooi een exception bij database fouten   │
 * │ FETCH_ASSOC                 │ Haal resultaten op als associatieve array│
 * │ EMULATE_PREPARES = false    │ Gebruik echte prepared statements (veilig)│
 * │ PERSISTENT = true           │ Hergebruik verbinding tussen verzoeken   │
 * └─────────────────────────────┴──────────────────────────────────────────┘
 *
 * ==========================================================================
 * BEVEILIGING (Security)
 * ==========================================================================
 * 1. PREPARED STATEMENTS: EMULATE_PREPARES = false zorgt ervoor dat
 *    de database zelf de statements voorbereidt, wat de sterkste
 *    bescherming biedt tegen SQL-injectie aanvallen.
 *    → OWASP A03: Injection voorkomen
 *
 * 2. FOUTAFHANDELING: error_log() schrijft fouten naar het server-log.
 *    De echte foutmelding wordt NOOIT aan de gebruiker getoond.
 *    Gebruikers zien alleen een generieke melding.
 *    → OWASP A09: Security Logging and Monitoring
 *
 * 3. GEEN WACHTWOORD IN FOUTMELDINGEN: Bij een connectie-fout wordt
 *    alleen een veilige tekst getoond, geen technische details.
 *    → OWASP A01: Broken Access Control voorkomen
 *
 * 4. SINGLETON PATROON: Eén verbinding per sessie voorkomt dat
 *    er te veel verbindingen worden geopend (resource exhaustion).
 *
 * 5. GEEN AFSLUITENDE PHP TAG: Voorkomt onbedoelde witruimte die
 *    "headers already sent" fouten veroorzaakt bij redirects.
 *
 * ==========================================================================
 * BESTANDSSTRUCTUUR (3 secties)
 * ==========================================================================
 * Het bestand is opgebouwd in 3 logische secties:
 *
 * SECTIE 1: Constanten definieren (5 database instellingen)
 *   → DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_CHARSET
 *   → Worden ingesteld met define() (onveranderlijk)
 *
 * SECTIE 2: getDBConnection() functie
 *   → Singleton patroon met static variabele $pdo
 *   → DSN string samenstellen (Data Source Name)
 *   → PDO object aanmaken met opties
 *   → try-catch voor foutafhandeling
 *
 * SECTIE 3: Einde van bestand
 *   → Geen afsluitende PHP tag (best practice PSR-12)
 *
 * ==========================================================================
 * PHP CONCEPTEN GEBRUIKT IN DIT BESTAND
 * ==========================================================================
 * - define()          : Constante definieren (waarde kan niet meer veranderen)
 * - function           : Eigen functie aanmaken (getDBConnection)
 * - static variabele   : Variabele die zijn waarde behoudt tussen aanroepen
 * - new PDO()          : Object-georienteerd programmeren (OOP) - object maken
 * - try-catch          : Foutafhandeling (exception handling)
 * - PDOException       : Specifieke foutklasse voor database-fouten
 * - error_log()        : Fout schrijven naar server logbestand
 * - die()              : Script onmiddellijk stoppen met foutmelding
 * - === (identiek)     : Strikte vergelijking (waarde EN type moeten gelijk zijn)
 * - . (concatenatie)   : Teksten aan elkaar plakken (string concatenation)
 * - return             : Waarde teruggeven aan de aanroepende code
 * - null               : Speciale waarde die "niets" of "leeg" betekent
 *
 * ==========================================================================
 * WIE ROEPT DIT BESTAND AAN?
 * ==========================================================================
 * - functions.php : Laadt db.php met require_once aan het begin
 *
 * WIE ROEPT getDBConnection() AAN?
 * - functions.php : In ELKE functie die database-toegang nodig heeft
 *   Voorbeelden: getUserByEmail(), createUser(), getSchedules(),
 *   getEvents(), getFriends(), softDelete(), enz.
 *
 * ==========================================================================
 * VERSCHIL MET ANDERE BESTANDEN
 * ==========================================================================
 * ┌──────────────────┬──────────┬──────────────┬──────────────────────────┐
 * │ Eigenschap       │ db.php   │ functions.php│ Pagina's (index, etc.)   │
 * ├──────────────────┼──────────┼──────────────┼──────────────────────────┤
 * │ Database config   │ ✅ Ja   │ Nee          │ Nee                      │
 * │ Database connectie│ ✅ Ja   │ Nee          │ Nee                      │
 * │ Helperfuncties    │ Nee     │ ✅ Ja        │ Nee                      │
 * │ HTML output       │ Nee     │ Nee          │ ✅ Ja                    │
 * │ Formulierlogica   │ Nee     │ Nee          │ ✅ Ja                    │
 * │ Sessie beheer     │ Nee     │ ✅ Ja        │ Nee                      │
 * └──────────────────┴──────────┴──────────────┴──────────────────────────┘
 *
 * db.php is het KLEINSTE maar MEEST KRITIEKE bestand: als dit niet werkt,
 * werkt NIETS in de hele applicatie.
 * ==========================================================================
 */

// ==========================================================================
// SECTIE 1: DATABASE INSTELLINGEN (CONSTANTEN)
// ==========================================================================
// Hieronder stellen we de inloggegevens in voor de database.
// We gebruiken 'define' om CONSTANTEN te maken.
// Een constante is een waarde die je instelt en die NOOIT meer verandert
// tijdens het draaien van het programma (in tegenstelling tot een variabele).
//
// define('NAAM', 'waarde') → maakt een constante aan
// - NAAM: altijd in HOOFDLETTERS (conventie voor constanten)
// - waarde: de vaste waarde die de constante krijgt
// - Na het definieren kun je de waarde NIET meer wijzigen
// - Constanten zijn overal beschikbaar (global scope)
//
// ELKE CONSTANTE UITGELEGD:
// --------------------------------------------------------------------------

// DB_HOST = het adres van de server waar de database draait
// 'localhost' betekent: op DEZELFDE computer als waar de website draait
// Als je XAMPP gebruikt, draait alles op jouw eigen computer,
// dus localhost is het juiste adres
define('DB_HOST', 'localhost');

// DB_USER = de gebruikersnaam om in te loggen op de database
// Bij een standaard XAMPP installatie is de gebruikersnaam altijd 'root'
// 'root' is de beheerder met alle rechten op de database
define('DB_USER', 'root');

// DB_PASS = het wachtwoord om in te loggen op de database
// Bij een standaard XAMPP installatie is het wachtwoord LEEG (geen wachtwoord)
// In een echte productie-omgeving zou hier een sterk wachtwoord staan!
define('DB_PASS', '');

// DB_NAME = de naam van de database waarmee we verbinding willen maken
// Dit moet EXACT overeenkomen met de naam in database.sql
// (die we daar aanmaken met CREATE DATABASE gameplan_db)
define('DB_NAME', 'gameplan_db');

// DB_CHARSET = de tekencodering voor de verbinding
// utf8mb4 ondersteunt ALLE tekens: letters, cijfers, speciale tekens,
// en zelfs emoji's. Dit moet overeenkomen met de database instelling.
define('DB_CHARSET', 'utf8mb4');


// ==========================================================================
// SECTIE 2: DATABASE VERBINDING FUNCTIE (getDBConnection)
// ==========================================================================
// Dit is de ENIGE functie in dit bestand.
// Het Singleton-patroon zorgt ervoor dat er maar 1 verbinding wordt gemaakt,
// ongeacht hoe vaak de functie wordt aangeroepen.
//
// HOE HET SINGLETON PATROON WERKT:
// ┌──────────────────────────────────────────────────────────────────┐
// │ Eerste aanroep:  $pdo = null → MAAK nieuwe verbinding → return  │
// │ Tweede aanroep:  $pdo = PDO  → HERGEBRUIK bestaande → return    │
// │ Derde aanroep:   $pdo = PDO  → HERGEBRUIK bestaande → return    │
// │ ... enzovoort                                                    │
// └──────────────────────────────────────────────────────────────────┘
// --------------------------------------------------------------------------

/**
 * getDBConnection - Maakt en geeft een PDO database verbinding terug
 *
 * UITLEG IN EENVOUDIGE WOORDEN:
 * Deze functie is als een telefonist die een lijn opent naar de database.
 * De eerste keer dat je belt, maakt hij een nieuwe verbinding.
 * Daarna hergebruikt hij dezelfde lijn (dit heet het Singleton patroon).
 *
 * HOE WERKT HET?
 * 1. Kijk of er al een verbinding is (statische variabele)
 * 2. Zo nee: maak een nieuwe PDO verbinding met de instellingen hierboven
 * 3. Zo ja: geef de bestaande verbinding terug
 *
 * @return PDO  Het database verbindingsobject waarmee je queries kunt uitvoeren
 */
function getDBConnection()
{
    // 'static' betekent: deze variabele onthoudt zijn waarde TUSSEN
    // verschillende aanroepen van deze functie door.
    // Normaal wordt een variabele in een functie elke keer opnieuw aangemaakt.
    // Met 'static' blijft de waarde bewaard.
    // De eerste keer is $pdo null (leeg), daarna bevat het de verbinding.
    static $pdo = null;

    // Controleer of er al een verbinding bestaat
    // Als $pdo nog steeds null is, moeten we een NIEUWE verbinding maken
    if ($pdo === null) {

        // DSN = Data Source Name = het "adres" van de database
        // Dit is een tekstregel die PDO vertelt:
        // - Welk type database (mysql)
        // - Op welke server (host)
        // - Welke database (dbname)
        // - Welke tekencodering (charset)
        // De punt (.) is de PHP operator om teksten aan elkaar te plakken
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        // PDO opties: dit zijn instellingen die bepalen HOE de verbinding werkt
        // We zetten deze in een array (een lijst van instellingen)
        $options = [
            // ATTR_ERRMODE => ERRMODE_EXCEPTION
            // Dit betekent: als er een fout optreedt bij een database actie,
            // gooi dan een "exception" (een foutmelding die we kunnen opvangen).
            // Zonder dit zou de fout stilletjes worden genegeerd!
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // ATTR_DEFAULT_FETCH_MODE => FETCH_ASSOC
            // Dit bepaalt HOE resultaten worden teruggegeven.
            // FETCH_ASSOC betekent: als een array met kolomnamen als sleutels.
            // Voorbeeld: $rij['username'] geeft de gebruikersnaam terug,
            // $rij['email'] geeft het e-mailadres terug.
            // Dit is makkelijker te lezen dan genummerde arrays.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // ATTR_EMULATE_PREPARES => false
            // Dit schakelt "echte" prepared statements in (geen geemuleerde).
            // Prepared statements zijn een beveiligingsmaatregel tegen SQL-injectie.
            // Met 'false' worden de statements door de database zelf voorbereid,
            // wat veiliger is dan als PHP het simuleert.
            PDO::ATTR_EMULATE_PREPARES => false,

            // ATTR_PERSISTENT => true
            // Dit houdt de verbinding "in leven" na het afronden van een pagina.
            // Bij het volgende verzoek kan dezelfde verbinding hergebruikt worden.
            // Dit maakt de applicatie sneller omdat het opzetten van een
            // database verbinding relatief langzaam is.
            PDO::ATTR_PERSISTENT => true,
        ];

        // try-catch blok: probeer iets uit, en als het mislukt, vang de fout op
        // Dit voorkomt dat de hele website crasht als de database niet beschikbaar is
        try {
            // Maak een nieuw PDO object aan (= open de verbinding)
            // We geven mee: het adres (DSN), gebruikersnaam, wachtwoord en opties
            // Als dit lukt, zit de verbinding in de variabele $pdo
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch (PDOException $e) {
            // Dit blok wordt ALLEEN uitgevoerd als de verbinding MISLUKT
            // Mogelijke oorzaken: database draait niet, verkeerd wachtwoord,
            // database bestaat niet, enz.

            // Log de echte foutmelding naar het server logbestand
            // Dit is alleen zichtbaar voor de ontwikkelaar (niet voor de gebruiker)
            // $e->getMessage() geeft de technische foutomschrijving
            error_log("Database verbinding mislukt: " . $e->getMessage(), 0);

            // Toon een VEILIGE melding aan de gebruiker
            // We tonen NOOIT de echte fout aan de gebruiker, want die
            // kan gevoelige informatie bevatten (servernamen, paden, enz.)
            // die een hacker zou kunnen misbruiken
            // 'die' stopt de hele pagina en toont alleen dit bericht
            die("Sorry, er is een probleem met de database verbinding. Probeer het later opnieuw.");
        }
    }

    // Geef de database verbinding terug naar de code die deze functie aanroept
    // Elke pagina die getDBConnection() aanroept, krijgt hetzelfde PDO object
    return $pdo;
}

// ==========================================================================
// SECTIE 3: EINDE VAN DB.PHP
// ==========================================================================
// LET OP: Er staat hier GEEN afsluitende PHP tag ( wordt WEGGELATEN).
// Dit is met opzet en een BEST PRACTICE in PHP!
//
// WAAROM GEEN AFSLUITENDE TAG?
// ─────────────────────────────────
// Een afsluitende  tag kan onbedoelde witruimte of lege regels
// toevoegen aan de uitvoer. Dit kan leiden tot de beruchte fout:
// "Cannot modify header information - headers already sent"
//
// Deze fout treedt op wanneer:
// 1. PHP probeert een header() redirect uit te voeren
// 2. Maar er is al (onzichtbare) uitvoer naar de browser gestuurd
// 3. Die uitvoer was een spatie of lege regel NA de  tag
//
// Door de  tag weg te laten voorkom je dit probleem volledig.
// Dit wordt aanbevolen door de PHP-FIG standaard (PSR-12).
//
// SAMENVATTING VAN DIT BESTAND:
// ─────────────────────────────
// - 5 constanten: DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_CHARSET
// - 1 functie: getDBConnection() met Singleton patroon
// - 1 return: PDO object voor database-toegang
// - Beveiliging: Prepared statements, error logging, veilige foutmelding
// ==========================================================================
?>