<?php
/**
 * ==========================================================================
 * DB.PHP - DATABASE VERBINDING
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * WAT DOET DIT BESTAND?
 * ---------------------
 * Dit bestand maakt een veilige verbinding met de MySQL database.
 * Vergelijk het met het openen van een telefoonlijn naar de database:
 * voordat je gegevens kunt ophalen of opslaan, moet je eerst
 * "verbinding maken" (de telefoon oppakken en het nummer bellen).
 *
 * Dit bestand wordt geladen door functions.php, en functions.php
 * wordt vervolgens geladen door alle andere pagina's.
 * Zo heeft elke pagina toegang tot de database.
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
 * ==========================================================================
 */

// --------------------------------------------------------------------------
// DATABASE INSTELLINGEN (CONSTANTEN)
// --------------------------------------------------------------------------
// Hieronder stellen we de inloggegevens in voor de database.
// We gebruiken 'define' om CONSTANTEN te maken.
// Een constante is een waarde die je instelt en die NOOIT meer verandert
// tijdens het draaien van het programma (in tegenstelling tot een variabele).
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


// --------------------------------------------------------------------------
// DATABASE VERBINDING FUNCTIE
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
// EINDE VAN DB.PHP
// ==========================================================================
// LET OP: Er staat hier GEEN afsluitende PHP tag 
// Dit is met opzet! Een afsluitende tag kan onbedoelde witruimte of
// lege regels toevoegen aan de uitvoer, wat kan leiden tot de fout
// "headers already sent" bij het gebruik van header() redirects.
// Het weglaten van de afsluitende tag is een best practice in PHP.
?>