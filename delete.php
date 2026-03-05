<?php
/**
 * ==========================================================================
 * DELETE.PHP - VERWIJDER HANDLER (BACKEND SCRIPT)
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand is een PUUR BACKEND SCRIPT - het bevat GEEN HTML-code.
 * Het enige doel van dit bestand is: ontvang een verwijderverzoek via de URL,
 * voer de juiste verwijder-functie uit, en stuur de gebruiker terug naar
 * de juiste pagina met een succes- of foutmelding.
 *
 * HOE HET WERKT (stap voor stap):
 * 1. De gebruiker klikt op een "verwijder" knop ergens in de applicatie
 * 2. Die knop stuurt de browser naar: delete.php?type=schedule&id=5
 * 3. Dit script leest "type" en "id" uit de URL ($_GET parameters)
 * 4. Op basis van het type wordt de juiste verwijder-functie aangeroepen
 * 5. De functie voert een SOFT DELETE uit (zet deleted_at timestamp)
 * 6. Er wordt een succes- of foutmelding klaargezet in de sessie
 * 7. De gebruiker wordt doorgestuurd (redirect) naar de juiste pagina
 *
 * WAT IS SOFT DELETE?
 * Bij een "soft delete" wordt data NIET echt uit de database verwijderd.
 * In plaats daarvan wordt er een tijdstempel (deleted_at) gezet op het record.
 * Dit betekent dat de data nog steeds in de database staat, maar als
 * "verwijderd" wordt behandeld. Voordelen hiervan zijn:
 * - Data kan later hersteld worden als de gebruiker zich bedenkt
 * - Er is een audit trail (je kunt zien wanneer iets verwijderd is)
 * - Referentiele integriteit blijft behouden (geen kapotte verwijzingen)
 *
 * Dit bestand handelt het verwijderen af van items:
 * - Schema's (speelschema's)
 * - Evenementen
 * - Favoriete spellen
 * - Vrienden
 *
 * Beveiliging:
 * - Controleert of gebruiker ingelogd is
 * - Valideert eigenaarschap voor verwijdering
 * - Geeft juiste succes/fout meldingen
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
?>