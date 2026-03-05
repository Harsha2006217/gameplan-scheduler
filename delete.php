<?php
/**
 * ==========================================================================
 * DELETE.PHP - VERWIJDER HANDLER (BACKEND SCRIPT)
 * ==========================================================================
 * Bestandsnaam : delete.php
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
 * Dit bestand is een PUUR BACKEND SCRIPT - het bevat GEEN HTML-code.
 * Het enige doel van dit bestand is: ontvang een verwijderverzoek via de URL,
 * voer de juiste verwijder-functie uit, en stuur de gebruiker terug naar
 * de juiste pagina met een succes- of foutmelding.
 *
 * UNIEK AAN DIT BESTAND:
 * - Bevat GEEN HTML (puur PHP server-side logica)
 * - Ontvangt gegevens via GET (URL-parameters), niet via POST (formulier)
 * - Voert alleen SOFT DELETE uit (data wordt nooit echt gewist)
 * - Werkt als een "router": stuurt het verzoek door naar de juiste functie
 * - Toont zelf niets: stuurt altijd door (redirect) naar een andere pagina
 *
 * ==========================================================================
 * HOE HET WERKT (VERZOEK-STROOM / REQUEST FLOW)
 * ==========================================================================
 *
 * ┌─────────────────────────────────────────────────────────────────────┐
 * │ 1. Gebruiker klikt op een "Verwijder" knop op een pagina           │
 * │    Voorbeeld: op index.php bij een speelschema                     │
 * │                                                                     │
 * │ 2. Browser navigeert naar: delete.php?type=schedule&id=5           │
 * │    - type = welk soort item (schedule/event/favorite/friend)       │
 * │    - id   = welk specifiek item (database primary key)             │
 * │                                                                     │
 * │ 3. PHP leest de URL-parameters via $_GET                           │
 * │    $type = $_GET['type'] → "schedule"                              │
 * │    $id   = $_GET['id']   → 5                                      │
 * │                                                                     │
 * │ 4. if/elseif keten bepaalt welke functie wordt aangeroepen         │
 * │    → deleteSchedule($userId, $id) ← SOFT DELETE                   │
 * │                                                                     │
 * │ 5. Flash melding wordt klaargezet in $_SESSION                     │
 * │    → setMessage('success', 'Schema succesvol verwijderd!')         │
 * │                                                                     │
 * │ 6. Redirect naar de juiste pagina: header("Location: index.php")   │
 * │    De melding verschijnt daar 1x en verdwijnt daarna               │
 * └─────────────────────────────────────────────────────────────────────┘
 *
 * ==========================================================================
 * WAT IS SOFT DELETE?
 * ==========================================================================
 * Bij een "soft delete" wordt data NIET echt uit de database verwijderd.
 * In plaats daarvan wordt er een tijdstempel (deleted_at) gezet op het record.
 *
 * ┌──────────────────────────────────────────────────────────────────┐
 * │ HARD DELETE (NIET gebruikt):                                     │
 * │   DELETE FROM Schedules WHERE schedule_id = 5                    │
 * │   → Rij is PERMANENT weg, NIET meer te herstellen               │
 * │                                                                   │
 * │ SOFT DELETE (WEL gebruikt):                                      │
 * │   UPDATE Schedules SET deleted_at = NOW() WHERE schedule_id = 5  │
 * │   → Rij staat er NOG, maar deleted_at is niet meer NULL          │
 * │   → Alle queries filteren op WHERE deleted_at IS NULL            │
 * │   → Rij is dus "onzichtbaar" maar herstelbaar                    │
 * └──────────────────────────────────────────────────────────────────┘
 *
 * Voordelen van soft delete:
 * - Data kan later hersteld worden als de gebruiker zich bedenkt
 * - Er is een audit trail (je kunt zien wanneer iets verwijderd is)
 * - Referentiele integriteit blijft behouden (geen kapotte verwijzingen)
 *
 * ==========================================================================
 * ONDERSTEUNDE VERWIJDER-TYPES (4 types)
 * ==========================================================================
 * ┌────────────┬──────────────────────┬─────────────┬────────────────────┐
 * │ Type (URL) │ Functie              │ Tabel       │ Redirect naar      │
 * ├────────────┼──────────────────────┼─────────────┼────────────────────┤
 * │ schedule   │ deleteSchedule()     │ Schedules   │ index.php          │
 * │ event      │ deleteEvent()        │ Events      │ index.php          │
 * │ favorite   │ deleteFavoriteGame() │ UserGames   │ profile.php        │
 * │ friend     │ deleteFriend()       │ Friends     │ add_friend.php     │
 * │ (ongeldig) │ (geen functie)       │ (geen)      │ index.php          │
 * └────────────┴──────────────────────┴─────────────┴────────────────────┘
 *
 * ==========================================================================
 * DATABASE TABELLEN DIE BEÏNVLOED WORDEN
 * ==========================================================================
 * ┌─────────────┬──────────────┬────────────────────────────────────────┐
 * │ Tabel       │ Kolom        │ Wat er gebeurt bij soft delete         │
 * ├─────────────┼──────────────┼────────────────────────────────────────┤
 * │ Schedules   │ deleted_at   │ NULL → NOW() (huidige datum/tijd)      │
 * │ Events      │ deleted_at   │ NULL → NOW() (huidige datum/tijd)      │
 * │ UserGames   │ (hele rij)   │ DELETE (hard delete, geen soft delete) │
 * │ Friends     │ deleted_at   │ NULL → NOW() (huidige datum/tijd)      │
 * └─────────────┴──────────────┴────────────────────────────────────────┘
 *
 * LET OP: UserGames gebruikt een HARD DELETE omdat het een koppeltabel is.
 * De relatie (welke user welk spel favoriet heeft) wordt volledig verwijderd.
 *
 * ==========================================================================
 * BEVEILIGING (Security)
 * ==========================================================================
 * 1. INLOG-CONTROLE: isLoggedIn() controleert of de gebruiker een geldige
 *    sessie heeft. Zonder inlog → redirect naar login.php.
 *    → OWASP A01: Broken Access Control voorkomen
 *
 * 2. EIGENAARSCHAP-CONTROLE: Elke delete-functie controleert of het item
 *    daadwerkelijk van de ingelogde gebruiker is ($userId).
 *    Gebruiker A kan NIET de items van Gebruiker B verwijderen.
 *    → OWASP A01: Broken Access Control voorkomen
 *
 * 3. SESSIE-TIMEOUT: checkSessionTimeout() beëindigt inactieve sessies
 *    na 30 minuten. Voorkomt misbruik op onbeheerde computers.
 *
 * 4. PREPARED STATEMENTS: De delete-functies in functions.php gebruiken
 *    PDO prepared statements. Het ID wordt als parameter meegegeven,
 *    NIET direct in de SQL-query geplakt.
 *    → OWASP A03: Injection voorkomen
 *
 * 5. EXIT NA REDIRECT: Na elke header("Location:") volgt exit; zodat
 *    geen code meer wordt uitgevoerd na het doorsturen.
 *
 * 6. FLASH MESSAGES: setMessage() slaat meldingen op in $_SESSION.
 *    Geen gevoelige informatie in de URL (geen ?error=... parameters).
 *
 * ==========================================================================
 * BESTANDSSTRUCTUUR (6 stappen)
 * ==========================================================================
 * STAP 1: functions.php laden + sessie-timeout controleren
 * STAP 2: Inlog-controle (isLoggedIn) → redirect als niet ingelogd
 * STAP 3: URL-parameters ophalen ($_GET: type + id)
 * STAP 4: if/elseif keten → juiste delete-functie aanroepen
 * STAP 5: Flash melding klaarzetten (succes of fout)
 * STAP 6: Redirect naar de juiste pagina + exit
 *
 * ==========================================================================
 * GEBRUIKTE BESTANDEN
 * ==========================================================================
 * - functions.php : Alle delete-functies + isLoggedIn + getUserId + setMessage
 *   (functions.php laadt op zijn beurt db.php voor database-toegang)
 *
 * PAGINA'S DIE NAAR DIT BESTAND LINKEN:
 * - index.php     : Verwijderknoppen bij schema's en evenementen
 * - profile.php   : Verwijderknoppen bij favoriete spellen
 * - add_friend.php: Verwijderknoppen bij vrienden in de vriendenlijst
 *
 * ==========================================================================
 * PHP CONCEPTEN GEBRUIKT IN DIT BESTAND
 * ==========================================================================
 * - require_once          : Bestand laden (eenmalig, fatale fout als niet gevonden)
 * - $_GET                 : Superglobale array met URL-parameters
 * - ?? (null coalescing)  : Standaardwaarde als variabele null/niet-bestaand is
 * - if/elseif/else        : Voorwaardelijke logica (vertakkingsstructuur)
 * - == (vergelijking)     : Controleert of twee waarden gelijk zijn
 * - ! (logische NOT)      : Keert een boolean waarde om (true→false, false→true)
 * - header("Location:")   : HTTP redirect - stuurt browser naar andere pagina
 * - exit                  : Stopt het PHP-script onmiddellijk
 * - Associatieve array    : Array met naamsleutels ($typeNamen['schedule'] → 'Schema')
 * - . (concatenatie)      : Teksten aan elkaar plakken
 * - Flash messages        : Eenmalige meldingen via $_SESSION
 *
 * ==========================================================================
 * VERSCHIL MET ANDERE PAGINA'S
 * ==========================================================================
 * ┌──────────────────────┬──────────┬──────────┬──────────┬──────────────┐
 * │ Eigenschap           │ delete   │ add_event│ login    │ contact      │
 * ├──────────────────────┼──────────┼──────────┼──────────┼──────────────┤
 * │ Bevat HTML?          │ Nee      │ Ja       │ Ja       │ Ja           │
 * │ Ontvangt via GET?    │ Ja       │ Nee      │ Nee      │ Nee          │
 * │ Ontvangt via POST?   │ Nee      │ Ja       │ Ja       │ Nee          │
 * │ Heeft formulier?     │ Nee      │ Ja       │ Ja       │ Nee          │
 * │ Redirect altijd?     │ Ja       │ Na POST  │ Na POST  │ Nee          │
 * │ Database schrijven?  │ UPDATE   │ INSERT   │ SELECT   │ Nee          │
 * │ Toont zelf pagina?   │ Nee      │ Ja       │ Ja       │ Ja           │
 * └──────────────────────┴──────────┴──────────┴──────────┴──────────────┘
 *
 * delete.php is UNIEK omdat het de enige pagina is die:
 * - GEEN HTML bevat (puur backend logica)
 * - ALTIJD redirect (toont nooit zelf iets)
 * - GET-parameters gebruikt in plaats van POST-formulieren
 * ==========================================================================
 */

// ============================================================================
// STAP 1: LAAD FUNCTIES EN CONTROLEER SESSIE
// ============================================================================

// require_once laadt het bestand 'functions.php' precies 1 keer in.
// Dit bestand bevat ALLE hulpfuncties die we nodig hebben:
// - isLoggedIn() => controleert of de gebruiker is ingelogd
// - getUserId() => haalt het ID van de ingelogde gebruiker op
// - deleteSchedule() => verwijdert een speelschema (soft delete)
// - deleteEvent() => verwijdert een evenement (soft delete)
// - deleteFavoriteGame() => verwijdert een favoriet spel (soft delete)
// - deleteFriend() => verwijdert een vriend uit de lijst (soft delete)
// - setMessage() => zet een melding klaar in de sessie voor de volgende pagina
// - checkSessionTimeout() => controleert of de sessie niet verlopen is
// Het woord "once" zorgt ervoor dat het bestand niet dubbel geladen wordt,
// zelfs als require_once meerdere keren wordt aangeroepen.
require_once 'functions.php';

// checkSessionTimeout() controleert of de gebruiker niet te lang inactief is geweest.
// Als de sessie langer dan 30 minuten inactief is, wordt de gebruiker automatisch uitgelogd.
// Dit is een beveiligingsmaatregel: als iemand vergeet uit te loggen op een openbare computer,
// wordt de sessie automatisch beeindigd na 30 minuten zonder activiteit.
checkSessionTimeout();

// ============================================================================
// STAP 2: CONTROLEER OF DE GEBRUIKER INGELOGD IS
// ============================================================================

// isLoggedIn() controleert of er een geldige sessie bestaat (of er een user_id in $_SESSION staat).
// Als de gebruiker NIET is ingelogd, mag hij/zij geen items verwijderen.
// In dat geval sturen we de gebruiker door naar de loginpagina.
// Het uitroepteken (!) keert de waarde om: !true wordt false, !false wordt true.
// Dus: als isLoggedIn() false teruggeeft (niet ingelogd), wordt !false = true,
// en gaan we het if-blok in.
if (!isLoggedIn()) {
    // header("Location: ...") stuurt een HTTP 302 redirect naar de browser.
    // Dit vertelt de browser: "Ga naar login.php in plaats van deze pagina."
    // De browser laadt dan automatisch login.php.
    // Dit is een SERVER-SIDE redirect: de gebruiker ziet dit niet, de browser doet het automatisch.
    header("Location: login.php");

    // exit; is VERPLICHT na een header("Location: ...") redirect!
    // Zonder exit; zou PHP gewoon doorgaan met de rest van het script uitvoeren.
    // Dat betekent dat de verwijder-logica hieronder alsnog zou worden uitgevoerd,
    // ook al is de gebruiker niet ingelogd. Dit zou een GROOT beveiligingsrisico zijn!
    // exit; stopt het script ONMIDDELLIJK - er wordt geen enkele regel code meer uitgevoerd.
    exit;
}

// ============================================================================
// STAP 3: HAAL PARAMETERS UIT DE URL (via $_GET)
// ============================================================================

// $_GET is een superglobale array in PHP die alle URL-parameters bevat.
// Als de URL is: delete.php?type=schedule&id=5
// Dan is $_GET['type'] = "schedule" en $_GET['id'] = "5"
//
// Het vraagteken (?) in de URL scheidt het pad van de parameters.
// Het ampersand (&) scheidt meerdere parameters van elkaar.
//
// De ?? operator is de "null coalescing operator" (nul-samenvoeg-operator).
// Dit werkt als volgt: als $_GET['type'] NIET bestaat of null is,
// gebruik dan de waarde rechts van ?? (in dit geval een lege string '').
// Dit voorkomt een PHP-foutmelding als de parameter niet in de URL staat.
// Voorbeeld: als iemand alleen delete.php bezoekt zonder parameters,
// dan wordt $type een lege string in plaats van een foutmelding.
$type = $_GET['type'] ?? '';

// Haal het ID op van het item dat verwijderd moet worden.
// De ?? 0 zorgt ervoor dat als er geen 'id' parameter in de URL staat,
// het ID standaard 0 wordt (wat geen geldig ID is, dus zal de verwijdering falen).
$id = $_GET['id'] ?? 0;

// getUserId() haalt het ID van de INGELOGDE gebruiker op uit de sessie ($_SESSION).
// Dit ID wordt meegegeven aan de verwijder-functies zodat deze kunnen controleren
// of de gebruiker daadwerkelijk de EIGENAAR is van het item dat verwijderd wordt.
// Je mag alleen je EIGEN items verwijderen, niet die van andere gebruikers!
$userId = getUserId();

// $fout wordt gebruikt om foutmeldingen op te slaan.
// Als alles goed gaat, blijft deze string LEEG ('').
// Als er een fout optreedt (bijv. item niet gevonden, geen toestemming),
// dan wordt hier de foutmelding in opgeslagen.
// Later gebruiken we deze variabele om te bepalen of we een succes- of foutmelding tonen.
$fout = '';

// ============================================================================
// STAP 4: VERWIJDER LOGICA - IF/ELSEIF KETEN
// ============================================================================

/**
 * VERWIJDER LOGICA
 *
 * Hieronder staat een if/elseif keten. Dit is een reeks voorwaarden die
 * van boven naar beneden worden gecontroleerd:
 * - Als $type gelijk is aan 'schedule' => voer deleteSchedule() uit
 * - Anders, als $type gelijk is aan 'event' => voer deleteEvent() uit
 * - Anders, als $type gelijk is aan 'favorite' => voer deleteFavoriteGame() uit
 * - Anders, als $type gelijk is aan 'friend' => voer deleteFriend() uit
 * - Als GEEN van de bovenstaande waar is => ongeldig type, toon foutmelding
 *
 * BELANGRIJK: Elke verwijder-functie doet het volgende:
 * 1. Zoekt het item in de database op basis van het ID
 * 2. Controleert of het item van de ingelogde gebruiker is (eigenaarschap)
 * 3. Voert een SOFT DELETE uit: zet deleted_at = huidige datum/tijd
 * 4. Geeft een lege string terug als alles goed ging, of een foutmelding als er iets fout ging
 *
 * Op basis van het type wordt de juiste verwijder-functie aangeroepen.
 * Elke functie controleert eigenaarschap (je kunt alleen je eigen items verwijderen).
 */

// EERSTE VOORWAARDE: Is het type "schedule" (speelschema)?
// == is de vergelijkingsoperator: controleert of $type gelijk is aan de tekst 'schedule'.
if ($type == 'schedule') {
    // deleteSchedule() verwijdert een speelschema uit de database.
    // Parameters: $userId (wie verwijdert) en $id (welk schema).
    // De functie controleert of schema $id daadwerkelijk van gebruiker $userId is.
    // SOFT DELETE: de functie zet deleted_at = NOW() op het schema-record.
    // Retourwaarde: lege string '' als het lukte, of een foutmelding als het mislukte.
    $fout = deleteSchedule($userId, $id);

    // Na het verwijderen van een schema sturen we de gebruiker terug naar het dashboard (index.php).
    // Het dashboard toont alle schema's, dus daar kan de gebruiker zien dat het schema weg is.
    $doorstuurPagina = 'index.php';

    // TWEEDE VOORWAARDE: Is het type "event" (evenement)?
// elseif wordt alleen gecontroleerd als de VORIGE voorwaarde NIET waar was.
} elseif ($type == 'event') {
    // deleteEvent() verwijdert een evenement uit de database.
    // Een evenement is een specifieke gaming afspraak die aan een schema gekoppeld kan zijn.
    // SOFT DELETE: de functie zet deleted_at = NOW() op het evenement-record.
    // Retourwaarde: lege string '' als het lukte, of een foutmelding als het mislukte.
    $fout = deleteEvent($userId, $id);

    // Na het verwijderen van een evenement gaan we terug naar het dashboard (index.php).
    // Daar worden alle evenementen en schema's getoond.
    $doorstuurPagina = 'index.php';

    // DERDE VOORWAARDE: Is het type "favorite" (favoriet spel)?
} elseif ($type == 'favorite') {
    // deleteFavoriteGame() verwijdert een spel uit de favorietenlijst van de gebruiker.
    // De favorietenlijst staat op de profielpagina en toont spellen die de gebruiker leuk vindt.
    // SOFT DELETE: de functie zet deleted_at = NOW() op het favoriete-spel-record.
    // Retourwaarde: lege string '' als het lukte, of een foutmelding als het mislukte.
    $fout = deleteFavoriteGame($userId, $id);

    // Na het verwijderen van een favoriet spel sturen we de gebruiker naar de profielpagina.
    // Daar staat de favorietenlijst, zodat de gebruiker kan zien dat het spel verwijderd is.
    $doorstuurPagina = 'profile.php';

    // VIERDE VOORWAARDE: Is het type "friend" (vriend)?
} elseif ($type == 'friend') {
    // deleteFriend() verwijdert een vriend uit de vriendenlijst van de gebruiker.
    // Na verwijdering kan de gebruiker deze persoon opnieuw toevoegen als hij/zij wil.
    // SOFT DELETE: de functie zet deleted_at = NOW() op het vriendschaps-record.
    // Retourwaarde: lege string '' als het lukte, of een foutmelding als het mislukte.
    $fout = deleteFriend($userId, $id);

    // Na het verwijderen van een vriend gaan we naar de vrienden-toevoegpagina (add_friend.php).
    // Daar kan de gebruiker de vriendenlijst bekijken en eventueel nieuwe vrienden toevoegen.
    $doorstuurPagina = 'add_friend.php';

    // ELSE: Geen van de bovenstaande types kwam overeen.
// Dit kan gebeuren als iemand de URL handmatig aanpast met een ongeldig type,
// bijvoorbeeld: delete.php?type=banaan&id=5
} else {
    // Sla een foutmelding op: het opgegeven type is niet geldig.
    // Omdat $fout nu NIET leeg is, zal er verderop een foutmelding getoond worden.
    $fout = 'Ongeldig type opgegeven.';

    // Bij een ongeldig type sturen we de gebruiker terug naar het dashboard.
    // Dit is de meest logische standaard-pagina.
    $doorstuurPagina = 'index.php';
}

// ============================================================================
// STAP 5: ZET MELDING KLAAR EN STUUR GEBRUIKER DOOR
// ============================================================================

/**
 * ZET MELDING EN STUUR DOOR
 *
 * Hier wordt een melding (notificatie) klaargezet in de PHP-sessie.
 * setMessage() slaat de melding op in $_SESSION, zodat deze op de VOLGENDE pagina
 * getoond kan worden (na de redirect). Dit heet een "flash message":
 * een melding die 1 keer wordt getoond en daarna automatisch verdwijnt.
 *
 * Toon een succes- of foutmelding op de doorstuur pagina.
 */

// $typeNamen is een associatieve array (woordenboek) die Engelse type-namen
// vertaalt naar Nederlandse namen voor een gebruiksvriendelijke melding.
// Dit wordt gebruikt in de succesmelding, bijv: "Schema succesvol verwijderd!"
// De sleutel (links van =>) is de Engelse naam uit de URL.
// De waarde (rechts van =>) is de Nederlandse vertaling voor de melding.
$typeNamen = [
    'schedule' => 'Schema',     // "schedule" wordt "Schema" in de melding
    'event' => 'Evenement',     // "event" wordt "Evenement" in de melding
    'favorite' => 'Favoriet',   // "favorite" wordt "Favoriet" in de melding
    'friend' => 'Vriend',       // "friend" wordt "Vriend" in de melding
];

// Controleer of er een fout is opgetreden.
// Als $fout NIET leeg is (bijv. "Item niet gevonden"), dan is er een fout.
// In PHP is een niet-lege string "truthy" (waar), en een lege string '' is "falsy" (niet waar).
if ($fout) {
    // setMessage() slaat een melding op in de sessie ($_SESSION).
    // Parameter 1: 'danger' = het type melding. Dit is een Bootstrap CSS-klasse.
    //   'danger' maakt de melding ROOD, wat aangeeft dat er iets fout ging.
    // Parameter 2: $fout = de tekst van de foutmelding die de gebruiker ziet.
    // Deze melding wordt opgeslagen in $_SESSION en wordt op de volgende pagina getoond.
    setMessage('danger', $fout);
} else {
    // Als er GEEN fout is ($fout is leeg), dan is de verwijdering geslaagd.
    // We maken een succesmelding met de Nederlandse naam van het verwijderde type.
    //
    // $typeNamen[$type] zoekt de Nederlandse naam op in de array hierboven.
    // ?? $type is een fallback: als het type NIET in de array staat, gebruik dan het Engelse type.
    // De punt (.) is de samenvoeg-operator in PHP: plakt twee strings aan elkaar.
    // Resultaat: bijv. "Schema succesvol verwijderd!" of "Evenement succesvol verwijderd!"
    $naam = $typeNamen[$type] ?? $type;

    // setMessage() met 'success' maakt een GROENE melding (Bootstrap klasse).
    // Dit vertelt de gebruiker dat de verwijdering gelukt is.
    setMessage('success', $naam . ' succesvol verwijderd!');
}

// ============================================================================
// STAP 6: REDIRECT (DOORSTUREN) NAAR DE JUISTE PAGINA
// ============================================================================

// header("Location: ...") stuurt een HTTP 302 redirect header naar de browser.
// Dit is een instructie aan de browser om AUTOMATISCH naar een andere pagina te gaan.
// De gebruiker ziet dit als een directe navigatie naar de nieuwe pagina.
//
// $doorstuurPagina bevat de pagina die eerder is ingesteld op basis van het type:
// - schema/evenement => index.php (dashboard)
// - favoriet => profile.php (profielpagina)
// - vriend => add_friend.php (vrienden pagina)
// - ongeldig type => index.php (dashboard als fallback)
//
// BELANGRIJK: header() MOET worden aangeroepen VOORDAT er HTML-output naar de browser
// is gestuurd. Als er al HTML is gestuurd, werkt de redirect NIET en krijg je een fout:
// "Headers already sent". Daarom bevat dit bestand geen enkele HTML-code!
header("Location: " . $doorstuurPagina);

// exit; is ABSOLUUT NOODZAKELIJK na een header("Location: ...") redirect!
// Zonder exit; zou PHP doorgaan met het verwerken van de rest van het script.
// Hoewel de browser de redirect-instructie al heeft ontvangen, stopt PHP NIET automatisch.
// Dit kan leiden tot:
// 1. Onverwachte uitvoering van code na de redirect
// 2. Beveiligingsproblemen (code wordt uitgevoerd terwijl de gebruiker al weg is)
// 3. Extra serverbelasting door onnodige verwerking
// exit; stopt het PHP-script ONMIDDELLIJK en volledig.
exit;

// ==========================================================================
// EINDE VAN DELETE.PHP
// ==========================================================================
// LET OP: Er staat hier GEEN afsluitende PHP tag (  wordt WEGGELATEN).
// Dit is een BEST PRACTICE (PSR-12 standaard) omdat een afsluitende  tag
// onzichtbare witruimte kan toevoegen, wat "headers already sent" fouten
// veroorzaakt bij header("Location:") redirects.
// Aangezien dit bestand ALTIJD een redirect doet, is het extra belangrijk
// om geen onbedoelde uitvoer te genereren.
//
// SAMENVATTING VAN DIT BESTAND:
// ─────────────────────────────
// - 4 verwijder-types: schedule, event, favorite, friend
// - 4 delete-functies: deleteSchedule, deleteEvent, deleteFavoriteGame, deleteFriend
// - Soft delete patroon: deleted_at = NOW() (data blijft bewaard)
// - Flash messages: succes (groen) of fout (rood) melding via $_SESSION
// - Altijd redirect: toont zelf GEEN HTML, stuurt altijd door
// - Beveiliging: inlog-check + eigenaarschap-check + sessie-timeout
// ==========================================================================
?>