<?php
/**
 * ==========================================================================
 * EDIT_SCHEDULE.PHP - SCHEMA BEWERKEN PAGINA
 * ==========================================================================
 * Bestandsnaam : edit_schedule.php
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
 * Bewerk een bestaand gaming speelschema. Dit is de BEWERKPAGINA voor
 * speelschema's - de "Update" in CRUD (Create, Read, Update, Delete).
 *
 * Het formulier bevat 5 velden: speltitel, datum, tijd, meespelende
 * vrienden en gedeeld-met. Alle velden worden VOORAF INGEVULD met de
 * bestaande waarden uit de database, zodat de gebruiker alleen hoeft
 * te wijzigen wat nodig is.
 *
 * Gebruikersverhaal: "Als ingelogde gebruiker wil ik mijn speelschema's
 * kunnen bewerken, zodat ik de datum, tijd of vrienden kan aanpassen
 * wanneer plannen veranderen."
 *
 * ==========================================================================
 * HOE DEZE PAGINA WERKT (VERZOEK-STROOM / REQUEST FLOW)
 * ==========================================================================
 *
 * ┌─────────────────────────────────────────────────────────────────────┐
 * │ GET-VERZOEK (pagina laden - formulier tonen):                      │
 * │                                                                     │
 * │ 1. Gebruiker klikt op "Bewerken" bij een schema op index.php       │
 * │ 2. Browser gaat naar: edit_schedule.php?id=5                       │
 * │ 3. PHP controleert: ingelogd? → ID geldig? → schema gevonden?     │
 * │ 4. Formulier wordt getoond met bestaande gegevens vooraf ingevuld  │
 * │                                                                     │
 * │ POST-VERZOEK (formulier verzenden - wijzigingen opslaan):          │
 * │                                                                     │
 * │ 1. JavaScript validateScheduleForm() controleert velden client-side│
 * │ 2. Formulier wordt via POST verzonden naar edit_schedule.php?id=5  │
 * │ 3. PHP leest $_POST en roept editSchedule() aan (7 parameters)    │
 * │ 4. editSchedule() valideert, trimt en voert UPDATE query uit      │
 * │ 5. Bij succes → redirect naar index.php met succesmelding         │
 * │ 6. Bij fout → formulier opnieuw tonen met foutmelding             │
 * └─────────────────────────────────────────────────────────────────────┘
 *
 * ==========================================================================
 * DATABASE KOPPELING
 * ==========================================================================
 * Dit bestand werkt met de Schedules tabel:
 *
 * ┌──────────────────┬─────────┬────────────────────────────────────────┐
 * │ Kolom            │ Type    │ Formulierveld                          │
 * ├──────────────────┼─────────┼────────────────────────────────────────┤
 * │ schedule_id (PK) │ INT     │ URL-parameter (?id=)                   │
 * │ user_id (FK)     │ INT     │ Automatisch via getUserId()            │
 * │ game_titel       │ VARCHAR │ Veld 1: Speltitel (verplicht)          │
 * │ date             │ DATE    │ Veld 2: Datum (verplicht, min=vandaag) │
 * │ time             │ TIME    │ Veld 3: Tijd (verplicht)               │
 * │ friends          │ TEXT    │ Veld 4: Meespelende vrienden (optioneel)│
 * │ shared_with      │ TEXT    │ Veld 5: Gedeeld met (optioneel)        │
 * └──────────────────┴─────────┴────────────────────────────────────────┘
 *
 * De velden friends en shared_with worden als komma-gescheiden strings
 * opgeslagen in de database en in het formulier als tekst getoond.
 * Voorbeeld: "speler1, speler2, speler3"
 *
 * ==========================================================================
 * VERSCHIL MET ANDERE PAGINA'S
 * ==========================================================================
 * ┌──────────────────────┬───────────────┬───────────────┬───────────────┐
 * │ Eigenschap           │ edit_schedule │ add_schedule  │ edit_event    │
 * ├──────────────────────┼───────────────┼───────────────┼───────────────┤
 * │ Bewerkt tabel        │ Schedules     │ Schedules     │ Events        │
 * │ Actie                │ UPDATE        │ INSERT        │ UPDATE        │
 * │ Aantal velden        │ 5             │ 5             │ 7             │
 * │ Vooraf ingevuld?     │ Ja            │ Nee           │ Ja            │
 * │ JS-validatie?        │ Ja            │ Ja            │ Ja            │
 * │ Datum min-attribuut? │ Ja (vandaag)  │ Ja (vandaag)  │ Ja (vandaag)  │
 * │ Komma-gescheiden?    │ Ja (2 velden) │ Ja (2 velden) │ Nee           │
 * │ Redirect na succes   │ index.php     │ index.php     │ index.php     │
 * │ PHP-functie          │ editSchedule  │ addSchedule   │ editEvent     │
 * │ Extra parameter      │ $id (schema)  │ Geen          │ $id (event)   │
 * └──────────────────────┴───────────────┴───────────────┴───────────────┘
 *
 * VERSCHIL editSchedule() vs addSchedule():
 *   - editSchedule() heeft een extra parameter: $id (het schema-ID)
 *   - editSchedule() voert een UPDATE query uit (bestaand record wijzigen)
 *   - addSchedule() voert een INSERT query uit (nieuw record aanmaken)
 *   - editSchedule() controleert eigenaarschap via user_id + schedule_id
 *
 * ==========================================================================
 * BEVEILIGING (Security)
 * ==========================================================================
 * 1. INLOG-CONTROLE: isLoggedIn() verifieert geldige sessie.
 *    Zonder inlog → redirect naar login.php.
 *    → OWASP A01: Broken Access Control voorkomen
 *
 * 2. ID-VALIDATIE: is_numeric($id) verifieert dat het URL-parameter
 *    een geldig getal is. Voorkomt SQL-injectie en onverwachte invoer.
 *    → OWASP A03: Injection voorkomen
 *
 * 3. EIGENAARSCHAP-CONTROLE: getSchedules($userId) haalt alleen schema's
 *    op van de ingelogde gebruiker. Andere gebruikers' schema's zijn
 *    onbereikbaar, zelfs als het ID geraden wordt.
 *    → OWASP A01: Broken Access Control voorkomen
 *
 * 4. SESSIE-TIMEOUT: checkSessionTimeout() beëindigt inactieve sessies.
 *
 * 5. XSS-BESCHERMING: safeEcho() escaped alle HTML-speciale tekens in
 *    de vooraf ingevulde formuliervelden (5 velden).
 *    → OWASP A07: Cross-Site Scripting (XSS) voorkomen
 *
 * 6. PREPARED STATEMENTS: editSchedule() gebruikt PDO prepared statements.
 *    Gegevens worden NOOIT direct in SQL geplakt.
 *    → OWASP A03: Injection voorkomen
 *
 * 7. CLIENT-SIDE VALIDATIE: validateScheduleForm() in JavaScript
 *    controleert velden voordat het formulier wordt verzonden.
 *    Dit is een EXTRA laag, NIET een vervanging van server-side validatie.
 *
 * 8. DATUM-MINIMUM: min="vandaag" voorkomt dat verlopen datums worden gekozen.
 *
 * 9. EXIT NA REDIRECT: Na elke header("Location:") volgt exit;
 *
 * ==========================================================================
 * BESTANDSSTRUCTUUR
 * ==========================================================================
 * PHP-GEDEELTE:
 *   - functions.php laden (require_once)
 *   - Sessie-timeout controleren
 *   - Inlog-controle → redirect als niet ingelogd
 *   - User-ID + Schema-ID ophalen ($_GET)
 *   - ID-validatie (is_numeric)
 *   - Schema opzoeken (getSchedules + array_filter + reset)
 *   - Controle of schema gevonden is
 *   - Foutvariabele initialiseren
 *   - POST-verwerking (editSchedule met 7 parameters)
 *     → Formuliergegevens ophalen (5 velden uit $_POST)
 *     → editSchedule() aanroepen
 *     → Succes-controle + redirect naar index.php
 *
 * HTML-GEDEELTE:
 *   - DOCTYPE + head (meta, title, Bootstrap CSS, style.css)
 *   - Body met donker thema + header navigatiebalk
 *   - Main container + flash message + foutmelding
 *   - Sectie + card + formulier (method="POST", onsubmit JS-validatie)
 *   - Veld 1: Speltitel (text, required, maxlength=100, vooraf ingevuld)
 *   - Veld 2: Datum (date, required, min=vandaag, vooraf ingevuld)
 *   - Veld 3: Tijd (time, required, vooraf ingevuld)
 *   - Veld 4: Meespelende vrienden (text, optioneel, komma-gescheiden)
 *   - Veld 5: Gedeeld met (text, optioneel, komma-gescheiden)
 *   - Knoppen: Bijwerken + Annuleren → index.php
 *   - Footer + Bootstrap JS + script.js
 *
 * ==========================================================================
 * GEBRUIKTE BESTANDEN
 * ==========================================================================
 * - functions.php  : editSchedule(), getSchedules(), isLoggedIn(),
 *                    getUserId(), setMessage(), getMessage(), safeEcho(),
 *                    checkSessionTimeout()
 * - header.php     : Navigatiebalk (wordt ge-include)
 * - footer.php     : Voettekst (wordt ge-include)
 * - style.css      : Eigen CSS-stijlen (donker gaming thema)
 * - script.js      : validateScheduleForm() + overige JavaScript functies
 * - Bootstrap 5.3.3: CSS + JS framework via CDN
 *
 * WELKE PAGINA LINKT NAAR DIT BESTAND?
 * - index.php      : "Bewerken" knop bij elk speelschema op het dashboard
 *
 * ==========================================================================
 * PHP CONCEPTEN GEBRUIKT IN DIT BESTAND
 * ==========================================================================
 * - require_once           : Bestand laden (eenmalig)
 * - $_GET / $_POST         : Superglobale arrays (URL-params / formulierdata)
 * - ?? (null coalescing)   : Standaardwaarde als variabele null is
 * - is_numeric()           : Controleert of waarde een geldig getal is
 * - array_filter()         : Filtert een array op basis van callback-functie
 * - Anonieme functie       : function($s) use ($id) { ... } (closure)
 * - use ($id)              : Maakt buitenvariabele beschikbaar in closure
 * - reset()                : Pakt het eerste element uit een array
 * - header("Location:")    : HTTP redirect naar andere pagina
 * - exit                   : Script onmiddellijk stoppen
 * - $_SERVER['REQUEST_METHOD'] : HTTP-methode detecteren (GET vs POST)
 * - date('Y-m-d')          : Huidige datum genereren (voor min-attribuut)
 *
 * HTML CONCEPTEN GEBRUIKT IN DIT BESTAND
 * ==========================================================================
 * - form method="POST"     : Formulier met POST-verzending
 * - onsubmit="return ..."  : JavaScript-validatie bij verzending
 * - input type="text"      : Tekst-invoerveld (speltitel, vrienden)
 * - input type="date"      : Datumkiezer met kalenderwidget
 * - input type="time"      : Tijdkiezer met uren:minuten widget
 * - value="..."            : Vooraf ingevulde waarden (edit-specifiek)
 * - min="..." (date)       : Minimum-datum om verleden te blokkeren
 * - required / maxlength   : HTML5 formuliervalidatie-attributen
 * - small.text-secondary   : Hulptekst onder invoervelden
 * - Bootstrap: card, form-control, btn, alert, container
 * ==========================================================================
 */

/* --------------------------------------------------------------------------
 * require_once 'functions.php' - Laad het bestand functions.php exact een keer.
 * Dit bestand bevat ALLE hulpfuncties die we nodig hebben:
 *   - isLoggedIn()          : controleert of de gebruiker is ingelogd via de sessie
 *   - getUserId()           : haalt het gebruikers-ID op uit de sessie
 *   - getSchedules()        : haalt alle schema's op van een bepaalde gebruiker
 *   - editSchedule()        : valideert invoer en werkt een bestaand schema bij
 *   - setMessage()          : slaat een flashbericht op in de sessie
 *   - getMessage()          : haalt een flashbericht op en toont het als HTML-alert
 *   - safeEcho()            : beveiligt tekst tegen XSS-aanvallen met htmlspecialchars()
 *   - checkSessionTimeout() : controleert of de sessie is verlopen (time-out)
 * require_once zorgt ervoor dat het bestand maar een keer wordt geladen,
 * zelfs als het per ongeluk meerdere keren wordt aangeroepen.
 * -------------------------------------------------------------------------- */
require_once 'functions.php';

/* --------------------------------------------------------------------------
 * checkSessionTimeout() - Controleer of de sessie van de gebruiker is verlopen.
 * Als de gebruiker te lang inactief is geweest, wordt de sessie vernietigd
 * en wordt de gebruiker automatisch uitgelogd. Dit is een beveiligingsmaatregel.
 * -------------------------------------------------------------------------- */
checkSessionTimeout();

/* --------------------------------------------------------------------------
 * INLOG-CONTROLE: Controleer of de gebruiker is ingelogd.
 * isLoggedIn() kijkt of er een geldige gebruikerssessie bestaat.
 * Als de gebruiker NIET is ingelogd (!isLoggedIn() geeft true):
 *   - header("Location: login.php") stuurt de browser door naar de inlogpagina.
 *     Dit is een HTTP 302-redirect die de browser vertelt een andere pagina te laden.
 *   - exit; stopt de uitvoering van dit script ONMIDDELLIJK.
 *     Zonder exit zou de rest van de PHP-code nog steeds worden uitgevoerd,
 *     wat een beveiligingsrisico is (de gebruiker zou dan zonder inloggen
 *     toch gegevens kunnen bekijken of wijzigen).
 * -------------------------------------------------------------------------- */
if (!isLoggedIn()) {
     header("Location: login.php");
     exit;
}

/* --------------------------------------------------------------------------
 * $userId = getUserId() - Haal het unieke gebruikers-ID op uit de sessie.
 * Dit ID wordt later gebruikt om te controleren dat de gebruiker alleen
 * zijn EIGEN schema's kan bewerken (beveiliging tegen onbevoegde toegang).
 * -------------------------------------------------------------------------- */
$userId = getUserId();

/* --------------------------------------------------------------------------
 * $id = $_GET['id'] ?? 0 - Haal het schema-ID uit de URL (querystring).
 *
 * HOE DIT WERKT:
 *   De URL ziet er zo uit: edit_schedule.php?id=5
 *   $_GET is een PHP superglobal array die alle URL-parameters bevat.
 *   $_GET['id'] haalt de waarde van de 'id' parameter op (in dit voorbeeld: "5").
 *
 * De ?? operator (null coalescing):
 *   Als 'id' niet in de URL staat (bijv. edit_schedule.php zonder ?id=),
 *   dan zou $_GET['id'] een fout geven. De ?? 0 zorgt ervoor dat in dat
 *   geval de waarde 0 wordt gebruikt als standaardwaarde.
 *   0 is geen geldig schema-ID, dus de is_numeric() check hieronder
 *   zal dit afvangen.
 * -------------------------------------------------------------------------- */
$id = $_GET['id'] ?? 0;

/* --------------------------------------------------------------------------
 * VEILIGHEIDSCONTROLE: Controleer of het ID een geldig getal is.
 *
 * is_numeric($id) controleert of de waarde een getal is (integer of float).
 * !is_numeric($id) is true als het GEEN geldig getal is.
 *
 * WAAROM IS DIT BELANGRIJK?
 *   Een kwaadwillende gebruiker zou de URL kunnen manipuleren, bijvoorbeeld:
 *   - edit_schedule.php?id=abc         (tekst in plaats van getal)
 *   - edit_schedule.php?id=<script>    (XSS-aanvalspoging)
 *   - edit_schedule.php?id=1;DROP TABLE (SQL-injectiepoging)
 *
 *   Door te controleren of het ID numeriek is, blokkeren we al deze aanvallen
 *   voordat ze schade kunnen aanrichten. Als het ID niet numeriek is:
 *   - header("Location: index.php") stuurt de gebruiker terug naar het dashboard.
 *   - exit; stopt de uitvoering onmiddellijk.
 * -------------------------------------------------------------------------- */
if (!is_numeric($id)) {
     header("Location: index.php");
     exit;
}

/* --------------------------------------------------------------------------
 * SCHEMA OPHALEN: Zoek het specifieke schema dat bewerkt moet worden.
 *
 * STAP 1: getSchedules($userId)
 *   Haal ALLE schema's op die bij deze gebruiker horen.
 *   Dit retourneert een array van schema's, bijv.:
 *   [
 *     ['schedule_id' => 1, 'game_titel' => 'Fortnite', 'date' => '2025-10-01', ...],
 *     ['schedule_id' => 5, 'game_titel' => 'Minecraft', 'date' => '2025-10-03', ...],
 *     ['schedule_id' => 8, 'game_titel' => 'FIFA', 'date' => '2025-10-05', ...],
 *   ]
 *
 * STAP 2: array_filter($schemas, function($s) use ($id) { ... })
 *   Filteren: ga door alle schema's heen en bewaar ALLEEN het schema
 *   waarvan het schedule_id gelijk is aan het gevraagde $id.
 *
 *   HOE array_filter() WERKT:
 *   - Het loopt elk element in de array langs.
 *   - Voor elk element roept het de callback-functie aan.
 *   - Als de callback 'true' retourneert, wordt het element bewaard.
 *   - Als de callback 'false' retourneert, wordt het element verwijderd.
 *
 *   De callback: function($s) use ($id)
 *   - $s is het huidige schema-element uit de array.
 *   - use ($id) maakt de $id variabele beschikbaar BINNEN de anonieme functie.
 *     Zonder 'use' zou $id niet zichtbaar zijn in de functie (PHP scope-regel).
 *   - return $s['schedule_id'] == $id; vergelijkt het schedule_id met het
 *     gevraagde ID. == is een losse vergelijking (string "5" == int 5 is true).
 *
 *   Resultaat: een array met 0 of 1 element (het gevonden schema of leeg).
 *   LET OP: array_filter behoudt de oorspronkelijke array-sleutels. Dus als
 *   het gevonden schema op index 2 stond, is het resultaat: [2 => {...}].
 * -------------------------------------------------------------------------- */
// Haal het speelschema op
$schemas = getSchedules($userId);
$schema = array_filter($schemas, function ($s) use ($id) {
     return $s['schedule_id'] == $id;
});

/* --------------------------------------------------------------------------
 * STAP 3: reset($schema)
 *   Haal het EERSTE (en enige) element op uit de gefilterde array.
 *
 *   WAAROM reset() EN NIET $schema[0]?
 *   Omdat array_filter de oorspronkelijke sleutels behoudt.
 *   Als het gevonden schema op index 5 stond in de originele array,
 *   dan is de gefilterde array: [5 => {...}].
 *   $schema[0] zou dan NIET werken (want sleutel 0 bestaat niet).
 *   $schema[5] zou werken, maar we weten de sleutel niet van tevoren.
 *
 *   reset() verplaatst de interne array-pointer naar het EERSTE element
 *   en retourneert de WAARDE van dat element, ongeacht de sleutel.
 *   Als de array leeg is, retourneert reset() 'false'.
 *
 *   Na deze regel bevat $schema:
 *   - Het complete schema-array als het is gevonden, bijv.:
 *     ['schedule_id' => 5, 'game_titel' => 'Minecraft', 'date' => '2025-10-03', ...]
 *   - Of 'false' als het schema niet is gevonden.
 * -------------------------------------------------------------------------- */
$schema = reset($schema);

/* --------------------------------------------------------------------------
 * CONTROLE: Bestaat het schema?
 * Als $schema 'false' is (schema niet gevonden), dan:
 *   - setMessage('danger', 'Schema niet gevonden.') slaat een rode foutmelding
 *     op in de sessie. 'danger' is het Bootstrap-alerttype (rood).
 *   - header("Location: index.php") stuurt de gebruiker terug naar het dashboard.
 *   - exit; stopt de uitvoering onmiddellijk.
 *
 * Dit kan gebeuren als:
 *   - Het schema is verwijderd door een andere sessie.
 *   - De gebruiker heeft een ID ingetypt dat niet van hem is.
 *   - De gebruiker heeft een willekeurig/verzonnen ID in de URL gezet.
 * -------------------------------------------------------------------------- */
if (!$schema) {
     setMessage('danger', 'Schema niet gevonden.');
     header("Location: index.php");
     exit;
}

/* --------------------------------------------------------------------------
 * $fout = '' - Initialiseer de foutvariabele als een lege string.
 * Deze variabele wordt gebruikt om foutmeldingen op te slaan bij het verwerken
 * van het formulier. Als $fout leeg blijft, is er geen fout opgetreden.
 * Als $fout een tekst bevat, wordt deze als rode foutmelding getoond.
 * -------------------------------------------------------------------------- */
$fout = '';

/* --------------------------------------------------------------------------
 * FORMULIERVERWERKING: Controleer of het formulier is verzonden.
 * $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode van het huidige verzoek.
 * 'POST' betekent dat het formulier is verzonden (de gebruiker heeft op
 * "Bijwerken" geklikt).
 * 'GET' betekent dat de pagina voor het eerst wordt geopend (formulier tonen).
 *
 * Bij het eerste bezoek (GET) wordt dit hele if-blok overgeslagen en wordt
 * het formulier getoond met de HUIDIGE waarden van het schema.
 * -------------------------------------------------------------------------- */
// Verwerk formulier verzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

     /* ----------------------------------------------------------------------
      * FORMULIERVELDEN UITLEZEN met de null-coalescing operator (??).
      *
      * $_POST is een PHP superglobal array die alle formuliergegevens bevat
      * die via de POST-methode zijn verzonden. De sleutels komen overeen met
      * de 'name' attributen van de HTML-invoervelden.
      *
      * De ?? operator: gebruik de POST-waarde als die bestaat, anders ''.
      * ---------------------------------------------------------------------- */

     /* $spelTitel - De (eventueel gewijzigde) titel van het spel. */
     $spelTitel = $_POST['game_title'] ?? '';

     /* $datum - De (eventueel gewijzigde) datum in formaat JJJJ-MM-DD. */
     $datum = $_POST['date'] ?? '';

     /* $tijd - Het (eventueel gewijzigde) tijdstip in formaat UU:MM. */
     $tijd = $_POST['time'] ?? '';

     /* $vriendenStr - Komma-gescheiden lijst van meespelende vrienden.
      * Voorbeeld: "speler1, speler2" wordt door de server gesplitst naar een array. */
     $vriendenStr = $_POST['friends_str'] ?? '';

     /* $gedeeldMetStr - Komma-gescheiden lijst van gebruikers om mee te delen.
      * Voorbeeld: "gebruiker1, gebruiker2" wordt door de server gesplitst naar een array. */
     $gedeeldMetStr = $_POST['shared_with_str'] ?? '';

     /* ------------------------------------------------------------------
      * editSchedule() AANROEPEN - De hoofdfunctie voor het bijwerken van het schema.
      *
      * Parameters die worden meegegeven:
      *   1. $userId        - Het ID van de ingelogde gebruiker (om eigenaarschap te verifiëren)
      *   2. $id            - Het ID van het schema dat bewerkt wordt (uit de URL)
      *   3. $spelTitel      - De nieuwe/gewijzigde speltitel
      *   4. $datum          - De nieuwe/gewijzigde datum
      *   5. $tijd           - Het nieuwe/gewijzigde tijdstip
      *   6. $vriendenStr    - De nieuwe/gewijzigde komma-gescheiden vriendenlijst
      *   7. $gedeeldMetStr  - De nieuwe/gewijzigde komma-gescheiden deellijst
      *
      * VERSCHIL MET addSchedule():
      *   - editSchedule() heeft een extra parameter: $id (het schema-ID).
      *   - editSchedule() OVERSCHRIJFT een bestaand schema in plaats van een nieuw aan te maken.
      *   - editSchedule() controleert ook of de gebruiker de eigenaar is van het schema.
      *
      * Wat editSchedule() intern doet:
      *   - Trimt alle invoervelden (spaties verwijderen aan begin/einde)
      *   - Controleert of verplichte velden niet leeg zijn (BUG FIX #1001)
      *   - Controleert of de datum niet in het verleden ligt (BUG FIX #1004)
      *   - Splitst de komma-gescheiden strings op in arrays
      *   - Werkt het schema bij in de database/JSON-bestand
      *
      * Retourwaarde:
      *   - Lege string '' als alles goed is gegaan (geen fouten)
      *   - Een foutmelding als string als er iets mis is
      * ------------------------------------------------------------------ */
     $fout = editSchedule($userId, $id, $spelTitel, $datum, $tijd, $vriendenStr, $gedeeldMetStr);

     /* ------------------------------------------------------------------
      * SUCCES-CONTROLE: Als $fout GEEN foutmelding bevat (leeg = geen fout).
      * !$fout is true als $fout een lege string is.
      *
      * Bij succes:
      *   1. setMessage('success', 'Schema bijgewerkt!') slaat een groene
      *      succesmelding op in de sessie via een flashbericht.
      *      Dit bericht wordt op de VOLGENDE pagina (index.php) getoond
      *      door getMessage() en daarna automatisch verwijderd.
      *   2. header("Location: index.php") stuurt de browser door naar het dashboard.
      *   3. exit; stopt de scriptuitvoering zodat er niets meer wordt uitgevoerd.
      *
      * Als er WEL een fout is, wordt dit blok overgeslagen en wordt het
      * formulier opnieuw getoond met de foutmelding EN de ingevoerde waarden.
      * ------------------------------------------------------------------ */
     if (!$fout) {
          setMessage('success', 'Schema bijgewerkt!');
          header("Location: index.php");
          exit;
     }
}
?>
<!-- ==========================================================================
     HTML-GEDEELTE: Hier begint de visuele pagina die de gebruiker ziet.
     Het PHP-gedeelte hierboven heeft de logica afgehandeld; nu volgt de opmaak.
     BELANGRIJK: Alle formuliervelden zijn VOORAF INGEVULD met de bestaande
     waarden van het schema via value="<?php //echo safeEcho($value) ?>".
     ========================================================================== -->

<!-- DOCTYPE html - Vertelt de browser dat dit een HTML5-document is.
     Zonder deze declaratie kan de browser in "quirks mode" gaan. -->
<!DOCTYPE html>

<!-- <html lang="nl"> - Het root-element van de HTML-pagina.
     lang="nl" geeft aan dat de taal Nederlands is (voor schermlezers en zoekmachines). -->
<html lang="nl">

<head>
     <!-- meta charset="UTF-8" - Stel de tekencodering in op UTF-8.
         Ondersteunt alle internationale tekens inclusief Nederlandse speciale tekens. -->
     <meta charset="UTF-8">

     <!-- meta viewport - Maakt de pagina responsief (past zich aan aan het scherm).
         width=device-width: breedte past zich aan het apparaat aan.
         initial-scale=1.0: geen in- of uitzoom bij het laden. -->
     <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <!-- title - De titel in het browsertabblad.
         "Schema Bewerken" zodat de gebruiker weet dat dit de bewerkpagina is. -->
     <title>Schema Bewerken - GamePlan Scheduler</title>

     <!-- Bootstrap 5.3.3 CSS - Populair CSS-framework via CDN.
         Biedt vooraf gemaakte stijlklassen (container, btn, form-control, etc.). -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

     <!-- style.css - Ons eigen aangepaste CSS-bestand met projectspecifieke stijlen
         voor de donkere gaming-look van de applicatie. -->
     <link rel="stylesheet" href="style.css">
</head>

<!-- body class="bg-dark text-light" - Het zichtbare deel van de pagina.
     bg-dark  : Bootstrap klasse voor donkere/zwarte achtergrond.
     text-light: Bootstrap klasse voor witte/lichte standaard tekstkleur.
     Samen creeren deze het donkere gaming-thema. -->

<body class="bg-dark text-light">

     <!-- include 'header.php' - Voeg de navigatiebalk in (logo, menu, uitlog-knop).
         Wordt vanuit een apart bestand geladen zodat wijzigingen op alle pagina's
         automatisch doorwerken (DRY-principe: Don't Repeat Yourself). -->
     <?php include 'header.php'; ?>

     <!-- main class="container mt-5 pt-5" - Het hoofdgedeelte van de pagina.
         container: centreert de inhoud met automatische marges en max-breedte.
         mt-5     : margin-top: 3rem (48px) ruimte boven het element.
         pt-5     : padding-top: 3rem (48px) binnenruimte aan bovenkant.
         mt-5 + pt-5 zorgen dat de inhoud niet achter de navigatiebalk verborgen wordt. -->
     <main class="container mt-5 pt-5">

          <!-- getMessage() - Toon eventuele flashberichten uit de sessie.
             Bijv. als een ander proces een bericht heeft opgeslagen met setMessage(),
             wordt het hier als een Bootstrap-alert getoond en daarna verwijderd. -->
          <?php echo getMessage(); ?>

          <!-- FOUTMELDING WEERGEVEN: Als $fout niet leeg is, toon een rode foutmelding.
             alert         : Bootstrap klasse voor een opvallend meldingsblok.
             alert-danger  : maakt het blok rood (voor foutmeldingen).
             safeEcho($fout): toont de fout VEILIG door HTML-tekens te escapen
                              met htmlspecialchars() (voorkomt XSS-aanvallen). -->
          <?php if ($fout): ?>
               <div class="alert alert-danger"><?php echo safeEcho($fout); ?></div>
          <?php endif; ?>

          <!-- section class="mb-5" - Semantische HTML5-sectie voor het formulier.
             mb-5: margin-bottom: 3rem (48px) ruimte onder de sectie. -->
          <section class="mb-5">

               <!-- h2 - Koptekst niveau 2 met potlood-emoji en paginatitel. -->
               <h2>✏️ Schema Bewerken</h2>

               <!-- div class="card" - Bootstrap-kaartcomponent.
                 Geeft het formulier een nette, afgekaderde uitstraling met rand
                 en afgeronde hoeken. -->
               <div class="card">

                    <!-- div class="card-body" - Het inhoudsgebied van de kaart.
                     Geeft padding (binnenruimte) aan alle inhoud binnen de kaart. -->
                    <div class="card-body">

                         <!-- form method="POST" - Het bewerkformulier.
                         method="POST": verstuurt gegevens veilig via HTTP POST.
                         onsubmit="return validateScheduleForm();": client-side validatie
                             via JavaScript voordat het formulier wordt verzonden.
                         Geen action-attribuut: formulier wordt naar DEZELFDE pagina verzonden.
                         BELANGRIJK: De formuliervelden hieronder zijn VOORAF INGEVULD
                         met de waarden uit $schema (het opgehaalde speelschema). -->
                         <form method="POST" onsubmit="return validateScheduleForm();">

                              <!-- ============================================================
                             VELD 1: SPELTITEL (game_title) - VOORAF INGEVULD
                             Het verschil met add_schedule.php: hier staat een
                             value-attribuut met de HUIDIGE speltitel uit de database.
                             ============================================================ -->
                              <!-- Speltitel -->
                              <div class="mb-3">
                                   <!-- mb-3: margin-bottom: 1rem (16px) ruimte onder dit veld. -->

                                   <!-- label voor het speltitelveld. De * geeft aan dat het verplicht is.
                                 for="game_title": koppelt het label aan het invoerveld. -->
                                   <label for="game_title" class="form-label">🎮 Speltitel *</label>

                                   <!-- input type="text" - Tekstveld voor de speltitel.
                                 type="text"     : standaard tekstinvoer.
                                 id="game_title" : uniek ID voor label-koppeling en JavaScript.
                                 name="game_title": sleutel in $_POST (voor PHP-verwerking).
                                 class="form-control": Bootstrap-stijl (volle breedte, nette rand).
                                 required        : veld mag niet leeg zijn (browser-validatie).
                                 maxlength="100" : maximaal 100 tekens.
                                 value="<?php echo safeEcho($schema['game_titel']); ?>":
                                     VOORAF INVULLEN - Dit is het cruciale verschil met add_schedule.php!
                                     $schema['game_titel'] bevat de huidige speltitel uit de database.
                                     safeEcho() beveiligt de waarde tegen XSS door htmlspecialchars()
                                     toe te passen. Bijv. als de titel <script> bevat, wordt het
                                     omgezet naar &lt;script&gt; zodat het als tekst wordt getoond
                                     en niet als uitvoerbare code.
                                     De gebruiker ziet de huidige titel in het veld en kan die
                                     aanpassen of ongewijzigd laten. -->
                                   <input type="text" id="game_title" name="game_title" class="form-control" required
                                        maxlength="100" value="<?php echo safeEcho($schema['game_titel']); ?>">
                              </div>

                              <!-- ============================================================
                             VELD 2: DATUM (date) - VOORAF INGEVULD
                             Bevat zowel het min-attribuut (BUG FIX #1004) als een
                             value-attribuut met de huidige datum van het schema.
                             ============================================================ -->
                              <!-- Datum -->
                              <div class="mb-3">
                                   <!-- mb-3: margin-bottom: 1rem (16px). -->

                                   <!-- label voor het datumveld. -->
                                   <label for="date" class="form-label">📆 Datum *</label>

                                   <!-- input type="date" - Datumkiezer invoerveld.
                                 type="date"    : toont een kalenderwidget in de browser.
                                 id="date"      : uniek ID voor label-koppeling.
                                 name="date"    : sleutel in $_POST.
                                 class="form-control": Bootstrap-stijl.
                                 required       : veld mag niet leeg zijn.
                                 min="<?php echo date('Y-m-d'); ?>": BUG FIX #1004!
                                     date('Y-m-d') genereert de datum van vandaag (bijv. "2025-09-30").
                                     Het min-attribuut voorkomt dat datums in het verleden worden gekozen.
                                     Eerdere datums worden grijs/uitgeschakeld in de datumkiezer.
                                 value="<?php echo safeEcho($schema['date']); ?>":
                                     Vul het veld VOORAF IN met de huidige datum van het schema.
                                     $schema['date'] bevat de opgeslagen datum (bijv. "2025-10-03").
                                     safeEcho() beveiligt de waarde tegen XSS-aanvallen.
                                     De gebruiker ziet de huidige datum en kan die aanpassen. -->
                                   <input type="date" id="date" name="date" class="form-control" required
                                        min="<?php echo date('Y-m-d'); ?>"
                                        value="<?php echo safeEcho($schema['date']); ?>">
                              </div>

                              <!-- ============================================================
                             VELD 3: TIJD (time) - VOORAF INGEVULD
                             ============================================================ -->
                              <!-- Tijd -->
                              <div class="mb-3">
                                   <!-- mb-3: margin-bottom: 1rem (16px). -->

                                   <!-- label voor het tijdveld. -->
                                   <label for="time" class="form-label">⏰ Tijd *</label>

                                   <!-- input type="time" - Tijdkiezer invoerveld.
                                 type="time"    : toont een tijdselectiewidget (UU:MM).
                                 id="time"      : uniek ID.
                                 name="time"    : sleutel in $_POST.
                                 class="form-control": Bootstrap-stijl.
                                 required       : veld mag niet leeg zijn.
                                 value="<?php echo safeEcho($schema['time']); ?>":
                                     Vul VOORAF IN met het huidige tijdstip (bijv. "14:30").
                                     safeEcho() beveiligt de waarde tegen XSS. -->
                                   <input type="time" id="time" name="time" class="form-control" required
                                        value="<?php echo safeEcho($schema['time']); ?>">
                              </div>

                              <!-- ============================================================
                             VELD 4: MEESPELENDE VRIENDEN (friends_str) - VOORAF INGEVULD
                             Optioneel veld met komma-gescheiden invoer.
                             De bestaande vriendenlijst wordt als tekst getoond.
                             ============================================================ -->
                              <!-- Meespelende vrienden -->
                              <div class="mb-3">
                                   <!-- mb-3: margin-bottom: 1rem (16px). -->

                                   <!-- label voor het vriendenveld. Geen * want optioneel. -->
                                   <label for="friends_str" class="form-label">👥 Meespelende Vrienden</label>

                                   <!-- input type="text" - Tekstveld voor komma-gescheiden vriendenlijst.
                                 id="friends_str"  : uniek ID.
                                 name="friends_str" : sleutel in $_POST.
                                 class="form-control": Bootstrap-stijl.
                                 GEEN required: dit veld is optioneel.
                                 value="<?php echo safeEcho($schema['friends']); ?>":
                                     Vul VOORAF IN met de bestaande vriendenlijst.
                                     $schema['friends'] bevat bijv. "speler1, speler2, speler3".
                                     Dit is de komma-gescheiden string zoals opgeslagen in de database.
                                     De gebruiker kan namen toevoegen, verwijderen of wijzigen.
                                     safeEcho() beveiligt de waarde tegen XSS-aanvallen.
                                 KOMMA-GESCHEIDEN INVOER UITLEG:
                                     De gebruiker ziet bijv. "Jan, Piet, Klaas" in het veld.
                                     Ze kunnen dit aanpassen naar "Jan, Piet, Klaas, Marie".
                                     De server splitst de string op komma's en trimt elke naam. -->
                                   <input type="text" id="friends_str" name="friends_str" class="form-control"
                                        value="<?php echo safeEcho($schema['friends']); ?>">

                                   <!-- Hulptekst die het invoerformaat uitlegt. -->
                                   <small class="text-secondary">Komma-gescheiden gebruikersnamen</small>
                              </div>

                              <!-- ============================================================
                             VELD 5: GEDEELD MET (shared_with_str) - VOORAF INGEVULD
                             Optioneel veld dat bepaalt wie het schema mogen bekijken.
                             ============================================================ -->
                              <!-- Gedeeld met -->
                              <div class="mb-3">
                                   <!-- mb-3: margin-bottom: 1rem (16px). -->

                                   <!-- label voor het gedeeld-met-veld. Geen * want optioneel. -->
                                   <label for="shared_with_str" class="form-label">👀 Gedeeld Met</label>

                                   <!-- input type="text" - Tekstveld voor komma-gescheiden deellijst.
                                 id="shared_with_str"  : uniek ID.
                                 name="shared_with_str" : sleutel in $_POST.
                                 class="form-control"   : Bootstrap-stijl.
                                 GEEN required: dit veld is optioneel.
                                 value="<?php echo safeEcho($schema['shared_with']); ?>":
                                     Vul VOORAF IN met de huidige lijst van gebruikers die dit
                                     schema mogen zien. Bijv. "gebruiker1, gebruiker2".
                                     safeEcho() beveiligt de waarde tegen XSS-aanvallen. -->
                                   <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                        value="<?php echo safeEcho($schema['shared_with']); ?>">

                                   <!-- Hulptekst die uitlegt waarvoor dit veld dient. -->
                                   <small class="text-secondary">Wie kan dit schema zien</small>
                              </div>

                              <!-- BIJWERKEN-KNOP: button type="submit" - Verstuurt het formulier.
                             type="submit" : een klik op deze knop verzendt het formulier.
                             class="btn btn-primary":
                                 btn         : Bootstrap basisklasse voor knoppen.
                                 btn-primary : blauwe/hoofdkleur knop (de belangrijkste actie).
                             De tekst "Bijwerken" met diskette-emoji maakt duidelijk dat
                             het gaat om het OPSLAAN van de wijzigingen. -->
                              <button type="submit" class="btn btn-primary">💾 Bijwerken</button>

                              <!-- ANNULEERLINK: a href="index.php" - Ga terug zonder op te slaan.
                             class="btn btn-secondary":
                                 btn           : Bootstrap basisklasse voor knoppen.
                                 btn-secondary : grijze knop (secundaire/minder belangrijke actie).
                             href="index.php" : navigeert naar het dashboard.
                             Alle wijzigingen in het formulier worden NIET opgeslagen. -->
                              <a href="index.php" class="btn btn-secondary">↩️ Annuleren</a>

                              <!-- Einde van het formulier. -->
                         </form>

                         <!-- Einde van card-body. -->
                    </div>

                    <!-- Einde van de card-container. -->
               </div>

               <!-- Einde van de sectie. -->
          </section>

          <!-- Einde van het main-element. -->
     </main>

     <!-- include 'footer.php' - Voeg de voettekst in (copyright, links, etc.).
         Wordt vanuit een apart bestand geladen (DRY-principe). -->
     <?php include 'footer.php'; ?>

     <!-- Bootstrap 5.3.3 JavaScript Bundle - Nodig voor interactieve componenten
         zoals dropdown-menu's, modals, tooltips en de hamburger-menu knop.
         "bundle" bevat ook Popper.js (nodig voor dropdowns). -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

     <!-- script.js - Eigen JavaScript met o.a. validateScheduleForm() voor
         client-side formuliervalidatie voordat het naar de server gaat. -->
     <script src="script.js"></script>

     <!-- Einde van het body-element. -->
</body>

<!-- Einde van het HTML-document. -->

</html>