<?php
/**
 * ============================================================================
 * db.php - DATABASE CONNECTIE BESTAND / DATABASE CONNECTION FILE
 * ============================================================================
 * 
 * AUTEUR / AUTHOR: Harsha Kanaparthi
 * STUDENTNUMMER / STUDENT NUMBER: 2195344
 * DATUM / DATE: 30-09-2025
 * PROJECT: GamePlan Scheduler
 * 
 * ============================================================================
 * WAT DOET DIT BESTAND? / WHAT DOES THIS FILE DO?
 * ============================================================================
 * 
 * Dit bestand is het HART van de database communicatie. Het maakt een veilige
 * verbinding tussen de PHP-applicatie en de MySQL-database. Zonder dit bestand
 * kan de applicatie GEEN data opslaan of ophalen.
 * 
 * This file is the HEART of database communication. It creates a secure
 * connection between the PHP application and the MySQL database. Without this
 * file, the application CANNOT store or retrieve data.
 * 
 * ============================================================================
 * WAAROM IS DIT BELANGRIJK? / WHY IS THIS IMPORTANT?
 * ============================================================================
 * 
 * 1. VEILIGHEID: Wij gebruiken PDO (PHP Data Objects) met prepared statements
 *    Dit beschermt tegen SQL-injectie aanvallen (hackers die proberen 
 *    schadelijke code in te voeren via formulieren)
 * 
 * 2. EFFICIËNTIE: Wij gebruiken het "Singleton Pattern" - dit betekent dat we
 *    maar ÉÉN database connectie maken en deze hergebruiken. Dit is sneller
 *    dan elke keer een nieuwe connectie maken.
 * 
 * 3. FOUTAFHANDELING: Als er iets misgaat met de database, krijgt de gebruiker
 *    een nette foutmelding in plaats van technische details (die hackers 
 *    zouden kunnen misbruiken)
 * 
 * ============================================================================
 * TECHNOLOGIEËN GEBRUIKT / TECHNOLOGIES USED
 * ============================================================================
 * 
 * - PDO (PHP Data Objects): Moderne, veilige manier om met databases te werken
 * - MySQL: De database waar alle gegevens worden opgeslagen
 * - UTF8MB4: Tekenset die alle tekens ondersteunt, inclusief emoji's
 * 
 * ============================================================================
 */

// ============================================================================
// STAP 1: DATABASE CONFIGURATIE CONSTANTEN DEFINIËREN
// STEP 1: DEFINE DATABASE CONFIGURATION CONSTANTS
// ============================================================================

/**
 * define() maakt een CONSTANTE aan - een waarde die NIET kan veranderen.
 * Dit is veiliger dan variabelen omdat hackers deze niet kunnen overschrijven.
 * 
 * define() creates a CONSTANT - a value that CANNOT change.
 * This is safer than variables because hackers cannot overwrite them.
 */

/**
 * DB_HOST - De locatie van de database server
 * 'localhost' betekent: de database draait op dezelfde computer als de website
 * In productie zou dit een IP-adres of domeinnaam kunnen zijn
 */
define('DB_HOST', 'localhost');

/**
 * DB_USER - De gebruikersnaam om in te loggen op de database
 * 'root' is de standaard beheerder in XAMPP (alleen voor ontwikkeling!)
 * BELANGRIJK: In een echte productieomgeving gebruik je een specifieke 
 * gebruiker met beperkte rechten voor extra veiligheid
 */
define('DB_USER', 'root');

/**
 * DB_PASS - Het wachtwoord voor de database gebruiker
 * Leeg ('') is standaard in XAMPP voor ontwikkeling
 * BELANGRIJK: In productie MOET dit een sterk wachtwoord zijn!
 */
define('DB_PASS', '');

/**
 * DB_NAME - De naam van onze database
 * 'gameplan_db' is waar al onze tabellen (Users, Games, Events, etc.) staan
 */
define('DB_NAME', 'gameplan_db');

/**
 * DB_CHARSET - De tekenset die we gebruiken
 * 'utf8mb4' ondersteunt ALLE mogelijke tekens:
 * - Normale letters (A-Z, a-z)
 * - Speciale tekens (é, ñ, ü)
 * - Emoji's (😀, 🎮, 🎯)
 * Dit is belangrijk voor een internationale gaming community!
 */
define('DB_CHARSET', 'utf8mb4');


// ============================================================================
// STAP 2: DATABASE CONNECTIE FUNCTIE
// STEP 2: DATABASE CONNECTION FUNCTION
// ============================================================================

/**
 * getDBConnection() - Haalt de database verbinding op of maakt deze aan
 * 
 * UITLEG VAN HET SINGLETON PATTERN:
 * ================================
 * Stel je voor: je hebt 100 pagina's die allemaal data nodig hebben.
 * ZONDER Singleton: 100 nieuwe database verbindingen (TRAAG!)
 * MET Singleton: 1 verbinding die 100 keer wordt hergebruikt (SNEL!)
 * 
 * HOE WERKT HET?
 * ==============
 * 1. Eerste keer dat de functie wordt aangeroepen: maak nieuwe verbinding
 * 2. Elke volgende keer: gebruik dezelfde verbinding opnieuw
 * 
 * @return PDO De database connectie object
 */
function getDBConnection() {
    
    /**
     * STATIC VARIABELE - Dit is de "truc" van het Singleton Pattern
     * 
     * Een static variabele ONTHOUDT zijn waarde tussen functie aanroepen.
     * Normale variabelen worden vergeten zodra de functie eindigt.
     * 
     * Voorbeeld:
     * - Eerste aanroep: $pdo = null, dus we maken een nieuwe verbinding
     * - Tweede aanroep: $pdo bevat nog steeds de verbinding van de eerste keer!
     */
    static $pdo = null;
    
    /**
     * CONTROLE: Is er al een verbinding?
     * 
     * Als $pdo NIET null is, hebben we al een verbinding.
     * Dan slaan we alle onderstaande code over en geven direct de 
     * bestaande verbinding terug. Dit maakt de applicatie SNELLER.
     */
    if ($pdo === null) {
        
        /**
         * DSN (Data Source Name) - De "adres" van onze database
         * 
         * Dit is een speciale string die PDO vertelt:
         * - mysql: = We gebruiken MySQL
         * - host=localhost = De database staat op deze computer
         * - dbname=gameplan_db = Gebruik de gameplan_db database
         * - charset=utf8mb4 = Gebruik UTF8MB4 tekenset
         * 
         * We bouwen deze string met de constanten die we eerder definieerden
         */
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        /**
         * PDO OPTIONS - Instellingen voor hoe PDO moet werken
         * 
         * Dit is een array (lijst) met sleutel-waarde paren die het
         * gedrag van PDO bepalen. Elke optie heeft een specifiek doel:
         */
        $options = [
            
            /**
             * ERRMODE_EXCEPTION - Foutafhandeling modus
             * 
             * Als er iets misgaat (bijvoorbeeld: tabel bestaat niet),
             * gooi een "Exception" (foutmelding die we kunnen opvangen).
             * 
             * ALTERNATIEVEN (die we NIET gebruiken):
             * - ERRMODE_SILENT: Negeer fouten (GEVAARLIJK!)
             * - ERRMODE_WARNING: Toon waarschuwing maar ga door
             * 
             * EXCEPTION is het beste omdat we fouten netjes kunnen afhandelen
             */
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            
            /**
             * DEFAULT_FETCH_MODE - Hoe data wordt teruggegeven
             * 
             * FETCH_ASSOC betekent: geef data als associatieve array
             * Bijvoorbeeld: ['username' => 'Harsha', 'email' => 'test@test.nl']
             * 
             * Dit is makkelijker te lezen dan:
             * [0 => 'Harsha', 1 => 'test@test.nl'] (FETCH_NUM)
             */
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            
            /**
             * EMULATE_PREPARES - Echte prepared statements gebruiken
             * 
             * false = Gebruik ECHTE prepared statements van MySQL
             * true = PHP simuleert prepared statements (MINDER VEILIG)
             * 
             * Echte prepared statements zijn veiliger tegen SQL injectie
             * omdat de query en data APART naar de database gaan
             */
            PDO::ATTR_EMULATE_PREPARES => false,
            
            /**
             * PERSISTENT - Verbinding open houden
             * 
             * true = Houd de verbinding open voor volgende pagina's
             * Dit is sneller omdat we niet steeds opnieuw hoeven te verbinden
             * 
             * NADEEL: Gebruikt meer server geheugen
             * VOORDEEL: Snellere responstijden voor gebruikers
             */
            PDO::ATTR_PERSISTENT => true,
        ];
        
        /**
         * TRY-CATCH BLOK - Foutafhandeling
         * 
         * Dit is vergelijkbaar met een vangnet:
         * - TRY: Probeer dit te doen...
         * - CATCH: Als het mislukt, vang de fout op en doe iets anders
         * 
         * Zonder try-catch zou een fout de hele website laten crashen
         * en mogelijk gevoelige informatie tonen aan hackers
         */
        try {
            
            /**
             * NIEUWE PDO VERBINDING MAKEN
             * 
             * new PDO() maakt een nieuwe database verbinding met:
             * - $dsn: Het adres van de database
             * - DB_USER: De gebruikersnaam (root)
             * - DB_PASS: Het wachtwoord (leeg in ontwikkeling)
             * - $options: Onze instellingen voor beveiliging en gedrag
             * 
             * Als dit lukt, wordt $pdo gevuld met het verbindingsobject
             * Als dit mislukt, wordt een PDOException gegooid (zie catch)
             */
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            
            /**
             * FOUT OPGETREDEN - Database verbinding mislukt
             * 
             * Mogelijke oorzaken:
             * - Database server draait niet (start MySQL in XAMPP!)
             * - Verkeerde gebruikersnaam of wachtwoord
             * - Database 'gameplan_db' bestaat niet
             * - Netwerk probleem
             */
            
            /**
             * error_log() - Schrijf de fout naar het logbestand
             * 
             * Dit is VEILIG: de foutdetails gaan naar een privé logbestand
             * dat alleen de beheerder kan zien. Hackers kunnen dit niet lezen.
             * 
             * Het logbestand staat meestal in: xampp/php/logs/php_error_log
             */
            error_log("Database Connection Failed: " . $e->getMessage(), 0);
            
            /**
             * die() - Stop de applicatie en toon een bericht
             * 
             * BELANGRIJK: We tonen GEEN technische details aan de gebruiker!
             * 
             * FOUT: die("Error: " . $e->getMessage())
             *       Dit zou tonen: "SQLSTATE[HY000] [1049] Unknown database"
             *       Hackers kunnen dit gebruiken om het systeem te kraken
             * 
             * GOED: Een vriendelijke, generieke boodschap zonder details
             */
            die("Sorry, there was an issue connecting to the database. Please try again later.");
        }
    }
    
    /**
     * RETURN - Geef de verbinding terug aan de code die deze functie aanriep
     * 
     * Nu kan andere code (zoals functions.php) deze verbinding gebruiken
     * om queries uit te voeren zoals:
     * - Gebruikers ophalen
     * - Events toevoegen
     * - Vrienden verwijderen
     */
    return $pdo;
}

/**
 * ============================================================================
 * EINDE VAN HET BESTAND
 * ============================================================================
 * 
 * LET OP: Er staat GEEN ?> (PHP afsluit tag) aan het einde!
 * 
 * WAAROM NIET?
 * ============
 * Als er per ongeluk een spatie of lege regel NA ?> staat, kan dit
 * "Headers already sent" fouten veroorzaken bij redirects.
 * 
 * Het is een PHP best practice om de afsluit tag weg te laten in
 * bestanden die alleen PHP bevatten (geen HTML).
 * 
 * ============================================================================
 * SAMENVATTING / SUMMARY
 * ============================================================================
 * 
 * Dit bestand doet het volgende:
 * 
 * 1. Definieert database inloggegevens als veilige constanten
 * 2. Maakt een herbruikbare functie voor de database verbinding
 * 3. Gebruikt het Singleton pattern voor efficiëntie
 * 4. Implementeert veilige foutafhandeling
 * 5. Beschermt tegen SQL injectie met echte prepared statements
 * 
 * VERBINDINGSFLOW:
 * ================
 *    [PHP Script] 
 *         |
 *         v
 *    getDBConnection()
 *         |
 *         v
 *    [Eerste keer?] --JA--> [Maak nieuwe PDO verbinding]
 *         |                          |
 *         |                          v
 *         NO                  [Sla op in static $pdo]
 *         |                          |
 *         v                          v
 *    [Return bestaande $pdo] <-------+
 *         |
 *         v
 *    [Database queries uitvoeren]
 * 
 * ============================================================================
 */