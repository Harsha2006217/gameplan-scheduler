<?php
/**
 * ==========================================================================
 * EDIT_FRIEND.PHP - VRIEND BEWERKEN PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bewerk de gebruikersnaam, notitie en status van een vriend.
 *
 * Deze pagina ontvangt een vriend-ID via de URL (bijv. edit_friend.php?id=5).
 * Het zoekt de bijbehorende vriend op in de database, toont een vooringevuld
 * formulier met de bestaande gegevens, en slaat wijzigingen op na verzending.
 * Na succesvol bijwerken wordt de gebruiker teruggestuurd naar add_friend.php.
 * ==========================================================================
 */

/* ============================================================
 * STAP 1 - FUNCTIES INLADEN MET REQUIRE_ONCE
 * ============================================================
 * 'require_once' laadt het bestand 'functions.php' in.
 * Dit bestand bevat ALLE hulpfuncties die we nodig hebben op deze pagina:
 *   - isLoggedIn()         = controleert of de gebruiker is ingelogd
 *   - getUserId()          = haalt het unieke ID van de ingelogde gebruiker op
 *   - getFriends()         = haalt alle vrienden van een gebruiker op uit de database
 *   - updateFriend()       = werkt de gegevens van een bestaande vriend bij in de database
 *   - setMessage()         = slaat een melding op in de sessie (bijv. "Vriend bijgewerkt!")
 *   - getMessage()         = haalt de opgeslagen melding op om te tonen aan de gebruiker
 *   - safeEcho()           = toont tekst veilig op de pagina (beschermt tegen XSS-aanvallen)
 *   - checkSessionTimeout() = controleert of de sessie niet is verlopen
 * 'require_once' zorgt ervoor dat het bestand MAXIMAAL 1 KEER wordt ingeladen.
 * Als het al eerder is ingeladen, wordt het NIET opnieuw geladen.
 * Dit voorkomt dubbele functie-definities en fouten.
 * ============================================================ */
require_once 'functions.php';

/* ============================================================
 * STAP 2 - SESSIE TIMEOUT CONTROLEREN
 * ============================================================
 * checkSessionTimeout() kijkt of de gebruiker te lang inactief is geweest.
 * Als de sessie is verlopen (bijv. na 30 minuten niets doen),
 * wordt de gebruiker automatisch uitgelogd en doorgestuurd naar de loginpagina.
 * Dit is een BEVEILIGINGSMAATREGEL: als iemand zijn computer onbeheerd achterlaat,
 * kan niemand anders zomaar de gegevens van vrienden aanpassen.
 * ============================================================ */
checkSessionTimeout();

/* ============================================================
 * STAP 3 - CONTROLEER OF DE GEBRUIKER IS INGELOGD
 * ============================================================
 * isLoggedIn() controleert of er een geldige sessie bestaat.
 * Het kijkt of $_SESSION['user_id'] bestaat en een geldige waarde heeft.
 * Als de gebruiker NIET is ingelogd (isLoggedIn() geeft 'false' terug):
 *   - header("Location: login.php") stuurt de browser door naar de loginpagina
 *   - exit; stopt ALLE verdere PHP-code op deze pagina onmiddellijk
 * Dit is CRUCIAAL voor beveiliging: zonder deze check zou iedereen
 * (ook niet-ingelogde bezoekers) vrienden kunnen bewerken in andere accounts.
 * ============================================================ */
if (!isLoggedIn()) {
    header("Location: login.php"); /* Stuur de browser door naar login.php met een HTTP-header */
    exit; /* Stop onmiddellijk ALLE code hieronder - niets meer uitvoeren */
}

/* ============================================================
 * STAP 4 - GEBRUIKERS-ID EN VRIEND-ID OPHALEN
 * ============================================================
 * getUserId() haalt het unieke ID-nummer van de ingelogde gebruiker op uit de sessie.
 * Dit wordt gebruikt om te verifiereren dat de vriend bij DEZE gebruiker hoort.
 *
 * $_GET['id'] haalt het vriend-ID op uit de URL.
 * Wanneer de gebruiker op "Bewerken" klikt in add_friend.php, wordt hij
 * doorgestuurd naar bijv. edit_friend.php?id=5
 * Het deel na het vraagteken (?id=5) heet een "query string" of "URL-parameter".
 * $_GET is een speciale PHP-array die ALLE URL-parameters bevat.
 * $_GET['id'] haalt de waarde '5' op uit de URL.
 *
 * De '??' operator (null coalescing operator):
 *   - Als $_GET['id'] BESTAAT en niet null is, gebruik die waarde
 *   - Als $_GET['id'] NIET BESTAAT (geen ?id= in de URL), gebruik 0 als standaard
 * De standaardwaarde 0 zorgt ervoor dat er geen PHP-fout optreedt als iemand
 * de pagina bezoekt zonder een ID in de URL.
 * ============================================================ */
$userId = getUserId(); /* Haal het ID van de ingelogde gebruiker op uit de sessie */
$id = $_GET['id'] ?? 0; /* Haal het vriend-ID op uit de URL, of gebruik 0 als standaard */

/* ============================================================
 * STAP 5 - VALIDATIE: IS HET ID EEN GELDIG NUMMER?
 * ============================================================
 * is_numeric($id) controleert of de waarde een geldig NUMMER is.
 * Een vriend-ID moet altijd een nummer zijn (bijv. 1, 2, 3, ...).
 * Als iemand probeert de URL te manipuleren met tekst of code
 * (bijv. edit_friend.php?id=abc of edit_friend.php?id=<script>),
 * is dat GEEN geldig nummer en sturen we de gebruiker terug naar add_friend.php.
 * Dit is een BEVEILIGINGSMAATREGEL tegen SQL-injectie en andere aanvallen.
 * De '!' (uitroepteken) keert de waarde om:
 *   - is_numeric("5")      geeft true   -> !true  = false -> NIET doorsturen
 *   - is_numeric("abc")    geeft false  -> !false = true  -> WEL doorsturen
 * ============================================================ */
if (!is_numeric($id)) {
    header("Location: add_friend.php"); /* Stuur terug naar de vriendenlijst als het ID ongeldig is */
    exit; /* Stop alle verdere code op deze pagina */
}

/* ============================================================
 * STAP 6 - DE JUISTE VRIEND OPZOEKEN MET ARRAY_FILTER
 * ============================================================
 * Eerst halen we ALLE vrienden van de gebruiker op met getFriends($userId).
 * Dit geeft een array (lijst) terug met alle vrienden.
 *
 * Daarna gebruiken we array_filter() om de JUISTE vriend te vinden.
 * array_filter() doorloopt elk element in de array en voert een functie uit.
 * De functie retourneert true (behouden) of false (weggooien).
 *
 * De anonieme functie (function ($f) use ($id)) werkt als volgt:
 *   - $f is het HUIDIGE element (een vriend-array) in de loop
 *   - 'use ($id)' maakt de variabele $id beschikbaar BINNEN de anonieme functie.
 *     Normaal kan een anonieme functie geen variabelen van buitenaf gebruiken.
 *     Met 'use' maken we een uitzondering voor $id.
 *   - $f['friend_id'] == $id vergelijkt het friend_id van de huidige vriend
 *     met het ID uit de URL
 *   - Als ze gelijk zijn, geeft de functie TRUE terug -> deze vriend wordt BEHOUDEN
 *   - Als ze NIET gelijk zijn, geeft de functie FALSE terug -> deze vriend wordt WEGGEFILTERD
 *
 * Het resultaat is een array met (hopelijk) PRECIES 1 element: de gezochte vriend.
 *
 * reset($vriend) haalt het EERSTE element uit de gefilterde array.
 * Dit is nodig omdat array_filter() de originele indexen behoudt.
 * Voorbeeld: als vriend met ID 5 op index 3 stond, is het resultaat [3 => {...}].
 * reset() geeft het eerste element terug ongeacht de index, dus het vriend-object zelf.
 * Als de array leeg is (geen vriend gevonden), geeft reset() FALSE terug.
 * ============================================================ */
// Haal vriend gegevens op
$vrienden = getFriends($userId); /* Haal ALLE vrienden van de gebruiker op uit de database */
$vriend = array_filter($vrienden, function ($f) use ($id) { /* Filter de lijst: zoek de vriend met het juiste ID */
    return $f['friend_id'] == $id; /* Vergelijk het friend_id van elke vriend met het ID uit de URL */
});
$vriend = reset($vriend); /* Haal het eerste (en enige) element uit de gefilterde array */

/* ============================================================
 * STAP 7 - CONTROLEER OF DE VRIEND GEVONDEN IS
 * ============================================================
 * Als $vriend FALSE is (reset() gaf false terug omdat de array leeg was),
 * dan bestaat er GEEN vriend met dit ID voor deze gebruiker.
 * Dit kan gebeuren als:
 *   - De vriend al verwijderd is
 *   - De gebruiker een ID probeert dat niet van hem/haar is
 *   - De URL handmatig is aangepast met een ongeldig ID
 *
 * In dat geval:
 *   1. setMessage('danger', 'Vriend niet gevonden.') slaat een RODE foutmelding op
 *      in de sessie. 'danger' zorgt voor een rode alert op de volgende pagina.
 *   2. header("Location: add_friend.php") stuurt de browser terug naar de vriendenlijst
 *   3. exit; stopt alle verdere code
 * ============================================================ */
if (!$vriend) {
    setMessage('danger', 'Vriend niet gevonden.'); /* Sla een rode foutmelding op in de sessie */
    header("Location: add_friend.php"); /* Stuur de browser terug naar de vriendenlijst */
    exit; /* Stop alle verdere code op deze pagina */
}

/* ============================================================
 * STAP 8 - FOUTVARIABELE INITIALISEREN
 * ============================================================
 * $fout wordt op een LEGE string ('') gezet.
 * Als er later een fout optreedt bij het bijwerken van de vriend,
 * wordt er een foutmelding in deze variabele opgeslagen.
 * Een lege string betekent: "er is (nog) geen fout opgetreden".
 * ============================================================ */
$fout = '';

/* ============================================================
 * STAP 9 - FORMULIER VERWERKING (POST-VERZOEK)
 * ============================================================
 * $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode waarmee de pagina is opgevraagd.
 * Er zijn twee scenario's voor deze pagina:
 *   - 'GET'  = de gebruiker bezoekt de pagina (het formulier wordt getoond met bestaande gegevens)
 *   - 'POST' = het formulier is verzonden (de gebruiker heeft op "Bijwerken" gedrukt)
 * We controleren of de methode 'POST' is om het formulier te verwerken.
 * ============================================================ */
// Verwerk formulier verzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* ----------------------------------------------------------
     * STAP 9a - FORMULIERGEGEVENS OPHALEN
     * ----------------------------------------------------------
     * $_POST is een speciale PHP-array die ALLE ingevulde formuliervelden bevat.
     * We halen drie waarden op uit het formulier:
     *   - 'friend_username' = de (mogelijk gewijzigde) gaming naam van de vriend
     *   - 'note'            = de (mogelijk gewijzigde) notitie over de vriend
     *   - 'status'          = de (mogelijk gewijzigde) status van de vriend
     * De '??' operator geeft een standaardwaarde als het veld ontbreekt:
     *   - '' (lege string) voor gebruikersnaam en notitie
     *   - 'Offline' als standaard voor de status
     * ---------------------------------------------------------- */
    $vriendGebruikersnaam = $_POST['friend_username'] ?? ''; /* Haal de (gewijzigde) gebruikersnaam op */
    $notitie = $_POST['note'] ?? ''; /* Haal de (gewijzigde) notitie op */
    $status = $_POST['status'] ?? 'Offline'; /* Haal de (gewijzigde) status op */

    /* ----------------------------------------------------------
     * STAP 9b - VRIEND BIJWERKEN IN DE DATABASE
     * ----------------------------------------------------------
     * updateFriend() werkt de bestaande vriend bij in de database.
     * De functie krijgt 5 parameters:
     *   1. $userId                = het ID van de INGELOGDE gebruiker (beveiligingscheck)
     *   2. $id                    = het friend_id van de vriend die bewerkt wordt (uit de URL)
     *   3. $vriendGebruikersnaam  = de nieuwe (of ongewijzigde) gebruikersnaam
     *   4. $notitie               = de nieuwe (of ongewijzigde) notitie
     *   5. $status                = de nieuwe (of ongewijzigde) status
     * De functie RETOURNEERT:
     *   - Een LEGE string ('')     als het bijwerken GELUKT is (geen fout)
     *   - Een FOUTMELDING (string) als er iets mis ging (bijv. "Gebruikersnaam is verplicht")
     * Het resultaat wordt opgeslagen in $fout om later te tonen aan de gebruiker.
     *
     * VERSCHIL MET addFriend(): addFriend() maakt een NIEUW record aan,
     * updateFriend() WIJZIGT een BESTAAND record op basis van het friend_id.
     * ---------------------------------------------------------- */
    $fout = updateFriend($userId, $id, $vriendGebruikersnaam, $notitie, $status);

    /* ----------------------------------------------------------
     * STAP 9c - CONTROLEER OF HET BIJWERKEN GELUKT IS
     * ----------------------------------------------------------
     * Als $fout LEEG is (geen foutmelding), dan is de vriend succesvol bijgewerkt.
     * '!$fout' betekent: "als $fout NIET waar is" (een lege string is 'niet waar' in PHP).
     * Bij succes:
     *   1. setMessage('success', 'Vriend bijgewerkt!') slaat een SUCCESMELDING op in de sessie.
     *      De melding wordt als een groene alert getoond op de volgende pagina.
     *   2. header("Location: add_friend.php") stuurt de browser door naar de vriendenlijst.
     *      DIT IS DE REDIRECT NAAR add_friend.php: na het bewerken gaat de gebruiker
     *      TERUG naar de vriendenlijst waar hij/zij de bijgewerkte gegevens kan zien.
     *      Dit is ook het PRG-patroon (Post-Redirect-Get) om dubbele verzending te voorkomen.
     *   3. exit; stopt alle code zodat de redirect correct wordt uitgevoerd.
     * Als $fout NIET leeg is, wordt de pagina gewoon geladen en toont de foutmelding.
     * ---------------------------------------------------------- */
    if (!$fout) {
        setMessage('success', 'Vriend bijgewerkt!'); /* Sla een groene succesmelding op in de sessie */
        header("Location: add_friend.php"); /* REDIRECT: stuur de browser terug naar de vriendenlijst */
        exit; /* Stop alle verdere code om de redirect correct uit te voeren */
    }
}
?>
<!-- ============================================================
     STAP 10 - BEGIN VAN HET HTML-DOCUMENT
     ============================================================
     Alles hierboven was PHP (server-side logica).
     Alles hieronder is HTML (wat de browser daadwerkelijk toont aan de gebruiker).
     De PHP-code hierboven is al uitgevoerd VOORDAT de HTML naar de browser wordt gestuurd.
     ============================================================ -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5-document is.
     Zonder deze declaratie kan de browser de pagina verkeerd weergeven (quirks mode). -->
<!DOCTYPE html>

<!-- <html lang="nl"> opent het HTML-document en stelt de taal in op Nederlands ("nl").
     Dit helpt zoekmachines en schermlezers (voor slechtzienden) om de taal te herkennen. -->
<html lang="nl">

<!-- ============================================================
     STAP 11 - HEAD SECTIE (METADATA EN EXTERNE BESTANDEN)
     ============================================================
     De <head> bevat informatie OVER de pagina, maar wordt NIET zichtbaar getoond.
     Het bevat metadata, de paginatitel, en links naar CSS-stylesheets.
     ============================================================ -->

<head>
    <!-- charset="UTF-8" zorgt ervoor dat ALLE tekens correct worden weergegeven.
         UTF-8 ondersteunt Nederlandse tekens zoals e, i, o, u, en ook emoji's.
         Zonder dit zouden speciale tekens als vreemde symbolen verschijnen. -->
    <meta charset="UTF-8">

    <!-- viewport meta tag maakt de pagina RESPONSIVE (past zich aan op alle schermformaten).
         'width=device-width' = de breedte van de pagina volgt de breedte van het apparaat.
         'initial-scale=1.0' = de pagina wordt niet ingezoomd of uitgezoomd bij het laden.
         Zonder deze tag zou de pagina op een telefoon piepklein worden weergegeven. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <title> bepaalt de tekst die verschijnt in het tabblad van de browser.
         De gebruiker ziet "Vriend Bewerken - GamePlan Scheduler" bovenaan in zijn browsertab. -->
    <title>Vriend Bewerken - GamePlan Scheduler</title>

    <!-- Laad Bootstrap 5.3.3 CSS via een CDN (Content Delivery Network).
         Bootstrap biedt kant-en-klare stijlen voor knoppen, formulieren, kaarten, en lay-out.
         CDN betekent dat het bestand vanaf een EXTERNE server wordt geladen. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Laad ons EIGEN CSS-bestand 'style.css' voor het donkere gaming-thema en aangepaste stijlen.
         Omdat dit NA Bootstrap wordt geladen, kunnen onze stijlen Bootstrap-stijlen overschrijven. -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- ============================================================
     STAP 12 - BODY (ZICHTBARE INHOUD VAN DE PAGINA)
     ============================================================
     De <body> bevat ALLES wat de gebruiker daadwerkelijk ziet op het scherm.

     Bootstrap-klassen op de body:
     - 'bg-dark'    = achtergrondkleur wordt DONKERGRIJS/ZWART (#212529).
                      Dit past bij het gaming-thema van de applicatie.
     - 'text-light' = alle tekst wordt standaard WIT/LICHTGRIJS (#f8f9fa).
                      Dit zorgt voor goed contrast tegen de donkere achtergrond.
     ============================================================ -->

<body class="bg-dark text-light">

    <!-- ============================================================
         STAP 13 - HEADER (NAVIGATIEBALK) INVOEGEN
         ============================================================
         'include' laadt het bestand 'header.php' in op deze exacte plek.
         header.php bevat de NAVIGATIEBALK bovenaan de pagina met links naar
         Home, Profiel, Vrienden, Speellijst, etc.
         Door de header in een apart bestand te zetten, wordt het hergebruikt op alle pagina's.
         Dit heet het DRY-principe: "Don't Repeat Yourself" (Herhaal Jezelf Niet).
         ============================================================ -->
    <?php include 'header.php'; ?>

    <!-- ============================================================
         STAP 14 - HOOFDINHOUD (MAIN)
         ============================================================
         <main> is een semantisch HTML5-element dat de HOOFDINHOUD van de pagina markeert.

         Bootstrap-klassen:
         - 'container' = centreert de inhoud horizontaal met automatische marges.
                         Op grote schermen beperkt het de breedte, op kleine schermen volledige breedte.
         - 'mt-5'      = margin-top niveau 5 (3rem = 48px ruimte boven het element).
                         Duwt de inhoud weg van de navigatiebalk.
         - 'pt-5'      = padding-top niveau 5 (3rem = 48px binnenruimte bovenaan).
                         Voegt extra ruimte toe zodat content niet tegen de bovenkant plakt.
         ============================================================ -->
    <main class="container mt-5 pt-5">

        <!-- ============================================================
             STAP 15 - SUCCESMELDING WEERGEVEN (INDIEN AANWEZIG)
             ============================================================
             getMessage() controleert of er een melding is opgeslagen in de sessie.
             Als er een melding is, toont het een Bootstrap alert (groen voor succes, rood voor fout).
             Als er GEEN melding is, verschijnt er niets.
             De melding wordt na het tonen automatisch gewist uit de sessie.
             ============================================================ -->
        <?php echo getMessage(); ?>

        <!-- ============================================================
             STAP 16 - FOUTMELDING WEERGEVEN (INDIEN AANWEZIG)
             ============================================================
             Als $fout NIET leeg is (formulier verzonden maar er was een fout),
             tonen we een RODE foutmelding.
             Bootstrap-klassen:
             - 'alert'        = basisstijl voor meldingen (padding, rand, afronding)
             - 'alert-danger' = maakt de melding ROOD (voor foutmeldingen)
             safeEcho($fout) toont de foutmelding veilig (XSS-bescherming).
             ============================================================ -->
        <?php if ($fout): ?>
            <div class="alert alert-danger"><?php echo safeEcho($fout); ?></div>
        <?php endif; ?>

        <!-- ============================================================
             STAP 17 - BEWERKFORMULIER SECTIE
             ============================================================
             <section> is een semantisch HTML5-element dat een logisch deel van de pagina markeert.

             Bootstrap-klasse:
             - 'mb-5' = margin-bottom niveau 5 (3rem = 48px ruimte onder de sectie).
             ============================================================ -->
        <section class="mb-5">

            <!-- De koptekst van de sectie met een emoji voor visuele herkenning.
                 <h2> is een tweede-niveau koptekst. -->
            <h2>✏️ Vriend Bewerken</h2>

            <!-- ============================================================
                 STAP 18 - BOOTSTRAP CARD (KAART-COMPONENT)
                 ============================================================
                 Bootstrap-klassen:
                 - 'card'      = creert een kaart met een achtergrond, rand, en afgeronde hoeken.
                 - 'card-body' = voegt padding (binnenruimte) toe rondom de inhoud van de kaart.
                 ============================================================ -->
            <div class="card">
                <div class="card-body">

                    <!-- ============================================================
                         STAP 19 - HET BEWERKFORMULIER
                         ============================================================
                         <form method="POST"> maakt een HTML-formulier aan.
                         'method="POST"' verstuurt de gegevens via een HTTP POST-verzoek (veilig, niet in de URL).
                         Zonder 'action' attribuut wordt het formulier verstuurd naar DEZELFDE pagina.
                         Het vriend-ID zit al in de URL (?id=...) en wordt door PHP bovenaan opgepakt.
                         ============================================================ -->
                    <form method="POST">

                        <!-- ============================================================
                             STAP 20 - VOORINGEVULD INVOERVELD: GEBRUIKERSNAAM
                             ============================================================
                             Dit invoerveld is VOORINGEVULD met de huidige gebruikersnaam van de vriend.
                             Dit is het BELANGRIJKSTE verschil met het toevoeg-formulier in add_friend.php:
                             hier is het veld al gevuld met de bestaande waarde zodat de gebruiker
                             alleen hoeft te wijzigen wat hij/zij wil veranderen.

                             Bootstrap-klasse op de wrapper-div:
                             - 'mb-3' = margin-bottom niveau 3 (1rem = 16px ruimte onder dit veld).

                             Het <label>-element:
                             - 'for="friend_username"' koppelt label aan input met id="friend_username"
                             - 'form-label' = Bootstrap-stijl voor labels

                             Het <input>-element:
                             - 'type="text"'           = normaal tekstveld
                             - 'id="friend_username"'  = uniek ID, gekoppeld aan het label
                             - 'name="friend_username"' = naam waarmee het veld wordt verstuurd in $_POST
                             - 'class="form-control"'  = Bootstrap-stijl: volledige breedte, afgeronde hoeken, padding
                             - 'required'              = HTML5-validatie: veld mag niet leeg zijn
                             - 'maxlength="50"'        = maximaal 50 tekens

                             CRUCIAAL - Het 'value' attribuut:
                             value="<?php echo safeEcho($vriend['username']); ?>"
                             Dit VOORVULT het invoerveld met de huidige waarde uit de database.
                             $vriend['username'] bevat de bestaande gebruikersnaam van de vriend
                             (opgehaald in STAP 6 met array_filter).
                             safeEcho() zorgt ervoor dat de waarde VEILIG wordt weergegeven
                             en beschermt tegen XSS-aanvallen.
                             De gebruiker ziet de huidige naam en kan deze aanpassen.
                             ============================================================ -->
                        <div class="mb-3">
                            <label for="friend_username" class="form-label">🎮 Gebruikersnaam *</label>
                            <input type="text" id="friend_username" name="friend_username" class="form-control" required
                                maxlength="50" value="<?php echo safeEcho($vriend['username']); ?>">
                        </div>

                        <!-- ============================================================
                             STAP 21 - VOORINGEVULD TEKSTVAK: NOTITIE
                             ============================================================
                             Dit tekstvak is VOORINGEVULD met de bestaande notitie over de vriend.

                             Bootstrap-klasse op de wrapper-div:
                             - 'mb-3' = margin-bottom niveau 3 (1rem = 16px ruimte onder dit veld).

                             Het <label>-element:
                             - 'for="note"'    = koppelt label aan textarea met id="note"
                             - 'form-label'    = Bootstrap-stijl voor labels

                             Het <textarea>-element:
                             - 'id="note"'            = uniek ID, gekoppeld aan het label
                             - 'name="note"'          = naam waarmee het veld wordt verstuurd ($_POST['note'])
                             - 'class="form-control"' = Bootstrap-stijl: volledige breedte, afgeronde hoeken
                             - 'rows="2"'             = 2 regels hoog

                             BELANGRIJK - Vooringevulde waarde bij textarea:
                             Bij een <textarea> wordt de vooringevulde waarde NIET via een 'value' attribuut gezet
                             (zoals bij <input>), maar TUSSEN de opening-tag en sluit-tag:
                             <textarea ...>WAARDE HIER</textarea>
                             <?php echo safeEcho($vriend['note']); ?> plaatst de huidige notitie
                             uit de database als de standaardwaarde van het tekstvak.
                             De gebruiker ziet de bestaande notitie en kan deze aanpassen.
                             ============================================================ -->
                        <div class="mb-3">
                            <label for="note" class="form-label">📝 Notitie</label>
                            <textarea id="note" name="note" class="form-control"
                                rows="2"><?php echo safeEcho($vriend['note']); ?></textarea>
                        </div>

                        <!-- ============================================================
                             STAP 22 - VOORINGEVULD DROPDOWN-MENU: STATUS
                             ============================================================
                             Dit dropdown-menu toont de HUIDIGE status van de vriend als geselecteerd.

                             Bootstrap-klasse op de wrapper-div:
                             - 'mb-3' = margin-bottom niveau 3 (1rem = 16px ruimte onder dit veld).

                             Het <select>-element:
                             - 'id="status"'         = uniek ID, gekoppeld aan het label
                             - 'name="status"'       = naam waarmee de waarde wordt verstuurd ($_POST['status'])
                             - 'class="form-select"' = Bootstrap-klasse specifiek voor dropdown-menu's

                             CRUCIAAL - Het 'selected' attribuut:
                             Bij een dropdown-menu wordt de vooringevulde waarde gezet met het 'selected' attribuut.
                             Het 'selected' attribuut wordt toegevoegd aan de <option> die overeenkomt
                             met de huidige status van de vriend in de database.

                             De PHP-code bij elke <option> werkt als volgt:
                             <?php if ($vriend['status'] === 'Offline')
                                 echo 'selected'; ?>
                             - $vriend['status'] bevat de huidige status uit de database (bijv. 'Online')
                             - '===' is een STRIKTE vergelijking (zowel waarde ALS type moeten gelijk zijn)
                             - Als de status overeenkomt, wordt het woord 'selected' toegevoegd aan de HTML
                             - Het 'selected' attribuut vertelt de browser: "Toon DEZE optie als standaard geselecteerd"

                             Voorbeeld: als $vriend['status'] is 'Online', dan wordt de HTML:
                             <option value="Offline">Offline</option>           (NIET selected)
                             <option value="Online" selected>Online</option>    (WEL selected -> deze wordt getoond)
                             <option value="Playing">Aan het spelen</option>    (NIET selected)
                             <option value="Away">Afwezig</option>              (NIET selected)

                             Zo ziet de gebruiker de HUIDIGE status van de vriend in de dropdown,
                             en kan hij/zij deze eventueel wijzigen door een andere optie te kiezen.
                             ============================================================ -->
                        <div class="mb-3">
                            <label for="status" class="form-label">🔘 Status</label>
                            <select id="status" name="status" class="form-select">
                                <!-- Elke optie controleert of de huidige status overeenkomt.
                                     Als dat zo is, wordt 'selected' toegevoegd zodat DEZE optie
                                     zichtbaar is in de dropdown wanneer de pagina laadt. -->
                                <option value="Offline" <?php if ($vriend['status'] === 'Offline')
                                    echo 'selected'; ?>>
                                    Offline</option>
                                <option value="Online" <?php if ($vriend['status'] === 'Online')
                                    echo 'selected'; ?>>
                                    Online</option>
                                <option value="Playing" <?php if ($vriend['status'] === 'Playing')
                                    echo 'selected'; ?>>Aan
                                    het spelen</option>
                                <option value="Away" <?php if ($vriend['status'] === 'Away')
                                    echo 'selected'; ?>>Afwezig
                                </option>
                            </select>
                        </div>

                        <!-- ============================================================
                             STAP 23 - ACTIEKNOPPEN: BIJWERKEN EN ANNULEREN
                             ============================================================
                             Twee knoppen naast elkaar:

                             --- BIJWERKEN-KNOP ---
                             <button type="submit"> verstuurt het formulier via POST.
                             Bootstrap-klassen:
                             - 'btn'         = Bootstrap basis-knopstijl (padding, afgeronde hoeken, cursor: pointer)
                             - 'btn-primary' = maakt de knop BLAUW (de hoofdkleur voor de belangrijkste actie)

                             --- ANNULEREN-LINK ---
                             <a href="add_friend.php"> is een link TERUG naar de vriendenlijst.
                             Dit is GEEN <button> maar een <a> (link), gestyled als een knop met Bootstrap.
                             Als de gebruiker op "Annuleren" klikt, wordt GEEN formulier verstuurd.
                             De gebruiker gaat gewoon terug naar add_friend.php zonder wijzigingen op te slaan.
                             Bootstrap-klassen:
                             - 'btn'           = stijlt de link als een knop
                             - 'btn-secondary' = maakt de knop GRIJS (secundaire actie, minder opvallend dan blauw)
                                                  Grijs geeft aan dat dit NIET de hoofdactie is.
                             ============================================================ -->
                        <button type="submit" class="btn btn-primary">💾 Bijwerken</button>
                        <a href="add_friend.php" class="btn btn-secondary">↩️ Annuleren</a>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- ============================================================
         STAP 24 - FOOTER (VOETTEKST) INVOEGEN
         ============================================================
         'include' laadt het bestand 'footer.php' in.
         footer.php bevat de voettekst onderaan de pagina met copyright-informatie.
         Wordt hergebruikt op alle pagina's via het DRY-principe.
         ============================================================ -->
    <?php include 'footer.php'; ?>

    <!-- ============================================================
         STAP 25 - BOOTSTRAP JAVASCRIPT LADEN
         ============================================================
         Bootstrap JavaScript is nodig voor interactieve componenten zoals
         dropdown-menu's, modals, en het hamburger-menu op mobiel.
         'bootstrap.bundle.min.js' bevat zowel Bootstrap JS als Popper.js.
         '.min.js' = geminificeerd (kleiner bestand, sneller laden).
         Staat ONDERAAN de pagina zodat de HTML eerst wordt geladen.
         ============================================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ============================================================
         STAP 26 - EIGEN JAVASCRIPT LADEN
         ============================================================
         'script.js' is ons eigen JavaScript-bestand met aangepaste functionaliteit.
         Wordt NA Bootstrap geladen zodat we Bootstrap-functies kunnen gebruiken.
         ============================================================ -->
    <script src="script.js"></script>
</body>

<!-- Sluit het HTML-document af. Alles tussen <html> en </html> is het volledige document. -->

</html>