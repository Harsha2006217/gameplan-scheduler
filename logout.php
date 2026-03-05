<?php
/**
 * ==========================================================================
 * LOGOUT.PHP - UITLOG SCRIPT
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * WAT DOET DIT BESTAND?
 * ---------------------
 * Dit bestand logt de gebruiker uit door ALLES op te ruimen dat met
 * de inlogsessie te maken heeft. Na het uitloggen wordt de gebruiker
 * doorgestuurd naar de inlogpagina.
 *
 * EEN SESSIE UITGELEGD:
 * Wanneer je inlogt, maakt PHP een "sessie" aan. Een sessie is als een
 * tijdelijk mapje op de server met jouw gegevens (zoals je user_id en
 * gebruikersnaam). De browser krijgt een cookie (klein tekstbestandje)
 * met een uniek sessie-nummer. Elke keer als je een pagina bezoekt,
 * stuurt de browser dit cookie mee, zodat de server weet wie je bent.
 *
 * Bij het uitloggen moeten we ALLES opruimen:
 * 1. De sessie variabelen (het mapje leegmaken)
 * 2. Het sessie cookie (het nummertje in de browser verwijderen)
 * 3. De sessie zelf vernietigen (het mapje weggooien)
 *
 * STAPPEN IN DIT BESTAND:
 * Stap 1: Laad de functies (functions.php)
 * Stap 2: Start de sessie als die nog niet is gestart
 * Stap 3: Maak alle sessie variabelen leeg
 * Stap 4: Verwijder het sessie cookie uit de browser
 * Stap 5: Vernietig de sessie op de server
 * Stap 6: Stuur de gebruiker door naar de inlogpagina
 * ==========================================================================
 */

// Laad het functions.php bestand dat alle functies bevat
// require_once zorgt ervoor dat het bestand maar 1 keer wordt geladen
// (zelfs als het al eerder is geladen door een ander bestand)
require_once 'functions.php';

// Controleer of er al een sessie actief is
// session_status() geeft de status van de huidige sessie terug
// PHP_SESSION_NONE betekent: er is nog geen sessie gestart
// Als er geen sessie is, starten we er een zodat we hem kunnen vernietigen
if (session_status() === PHP_SESSION_NONE) {
    // Start een sessie zodat we bij de sessie gegevens kunnen
    session_start();
}

// STAP 3: Maak alle sessie variabelen leeg
// $_SESSION is een speciale PHP array die alle sessie gegevens bevat
// Door het een lege array [] te maken, verwijderen we ALLES:
// user_id, username, last_activity, berichten, enz.
$_SESSION = [];

// STAP 4: Verwijder het sessie cookie uit de browser
// ini_get("session.use_cookies") controleert of PHP cookies gebruikt voor sessies
// (dat is bijna altijd het geval)
if (ini_get("session.use_cookies")) {
    // Haal de huidige cookie instellingen op
    // Dit geeft een array terug met: path, domain, secure, httponly
    $params = session_get_cookie_params();

    // Overschrijf het sessie cookie met een VERLOPEN datum
    // setcookie() stuurt een cookie naar de browser
    // session_name() = de naam van het sessie cookie (standaard "PHPSESSID")
    // '' = lege waarde (we willen het cookie leegmaken)
    // time() - 42000 = de verloopdatum, 42000 seconden IN HET VERLEDEN
    //   Dit zorgt ervoor dat de browser het cookie METEEN verwijdert
    //   (een cookie met een datum in het verleden wordt automatisch gewist)
    // De rest zijn de instellingen die overeenkomen met de originele cookie
    setcookie(
        session_name(),         // Naam van het cookie (bijv. "PHPSESSID")
        '',                     // Lege waarde
        time() - 42000,         // Verloopdatum in het verleden (= verwijder het)
        $params["path"],        // Pad waarvoor het cookie geldt
        $params["domain"],      // Domein waarvoor het cookie geldt
        $params["secure"],      // Of het cookie alleen via HTTPS verstuurd mag worden
        $params["httponly"]     // Of JavaScript bij het cookie kan (httponly = nee)
    );
}

// STAP 5: Vernietig de sessie op de server
// Dit verwijdert het sessie bestand van de server
// Na deze regel bestaat de sessie niet meer
session_destroy();

// STAP 6: Stuur de gebruiker door naar de inlogpagina
// header("Location: ...") stuurt een HTTP redirect naar de browser
// De browser gaat dan automatisch naar de opgegeven pagina
// ?msg=logged_out is een URL parameter die de inlogpagina kan gebruiken
// om een "Je bent uitgelogd" bericht te tonen
header("Location: login.php?msg=logged_out");

// exit stopt de uitvoering van dit PHP script ONMIDDELLIJK
// Dit is VERPLICHT na een header redirect, anders gaat PHP door
// met het uitvoeren van code die na de redirect staat
exit;
?>