<?php
/**
 * ==========================================================================
 * LOGOUT.PHP - UITLOG SCRIPT
 * ==========================================================================
 * Bestandsnaam : logout.php
 * Auteur       : Harsha Kanaparthi
 * Studentnummer: 2195344
 * Opleiding    : MBO-4 Software Developer (Crebo 25998)
 * Datum        : 30-09-2025
 * Versie       : 1.0
 * PHP-versie   : 8.1+
 * Encoding     : UTF-8
 *
 * ==========================================================================
 * BESCHRIJVING
 * ==========================================================================
 * Dit bestand logt de gebruiker UIT door ALLE sessiegegevens op te ruimen:
 *   - Sessie variabelen leegmaken
 *   - Sessie cookie verwijderen
 *   - Sessie vernietigen op de server
 *   - Redirect naar login.php met een uitlogbericht
 *
 * ==========================================================================
 * STRUCTUUR EN FLOW
 * ==========================================================================
 * ┌─────────────────────────────────────────────────────────────────────┐
 * │ 1. Laad functions.php                                              │
 * │ 2. Start sessie indien nodig                                       │
 * │ 3. Maak $_SESSION leeg                                             │
 * │ 4. Verwijder sessie cookie                                         │
 * │ 5. session_destroy()                                               │
 * │ 6. Redirect naar login.php?msg=logged_out                          │
 * └─────────────────────────────────────────────────────────────────────┘
 *
 * ==========================================================================
 * BEVEILIGING (Security)
 * ==========================================================================
 * 1. SESSIE-BEHEER: alle sessiegegevens worden veilig verwijderd
 * 2. COOKIE-BEHEER: sessie cookie wordt overschreven met verlopen datum
 * 3. REDIRECT: gebruiker wordt direct doorgestuurd na uitloggen
 * 4. OWASP A05: Security Misconfiguration voorkomen (geen restdata)
 * 5. OWASP A01: Broken Access Control voorkomen (geen toegang na uitloggen)
 *
 * ==========================================================================
 * SESSIE-UITLEG
 * ==========================================================================
 * Een PHP-sessie is een tijdelijk mapje op de server met gebruikersdata.
 * Bij uitloggen wordt alles verwijderd zodat niemand meer toegang heeft.
 *
 * ==========================================================================
 * DATABASE TABELLEN
 * ==========================================================================
 * Geen directe database interactie in logout.php, maar sessiegegevens zijn
 * afkomstig uit de Users-tabel (user_id, email, naam).
 *
 * ==========================================================================
 * VERGELIJKING MET ANDERE PAGINA'S
 * ==========================================================================
 * ┌───────────────┬───────────────┬───────────────┬───────────────┐
 * │ Eigenschap    │ logout.php    │ login.php     │ index.php     │
 * ├───────────────┼───────────────┼───────────────┼───────────────┤
 * │ Doel          │ uitloggen     │ inloggen      │ dashboard     │
 * │ Sessie check  │ ja            │ ja            │ ja            │
 * │ Data ophalen  │ n.v.t.        │ gebruiker     │ alles         │
 * │ Security      │ hoog          │ hoog          │ hoog          │
 * │ Redirect      │ login.php     │ index.php     │ n.v.t.        │
 * └───────────────┴───────────────┴───────────────┴───────────────┘
 *
 * ==========================================================================
 * GEBRUIKTE CONCEPTEN
 * ==========================================================================
 * PHP:
 *   - session_start(), session_destroy(), $_SESSION, setcookie()
 *   - header("Location: ..."), exit
 *   - session_get_cookie_params(), session_name(), ini_get()
 * ==========================================================================
 * EXAMENNIVEAU: VOLLEDIG GEDOCUMENTEERD, OWASP, SESSIE, FLOW, VERGELIJKING
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