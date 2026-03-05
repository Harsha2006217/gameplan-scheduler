<?php
/**
 * ==========================================================================
 * ADD_FRIEND.PHP - VRIEND TOEVOEGEN PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers gaming vrienden toevoegen op gebruikersnaam.
 * Vrienden kunnen een status hebben (Online/Offline) en persoonlijke notities.
 * Toont ook de huidige vriendenlijst met bewerk/verwijder opties.
 *
 * Gebruikersverhaal: "Voeg vrienden toe voor contact"
 * ==========================================================================
 */

/* ============================================================
 * STAP 1 - FUNCTIES INLADEN MET REQUIRE_ONCE
 * ============================================================
 * 'require_once' laadt het bestand 'functions.php' in.
 * Dit bestand bevat ALLE hulpfuncties die we nodig hebben:
 *   - isLoggedIn()      = controleert of de gebruiker is ingelogd
 *   - getUserId()       = haalt het unieke ID van de ingelogde gebruiker op
 *   - getFriends()      = haalt alle vrienden van een gebruiker op uit de database
 *   - addFriend()       = voegt een nieuwe vriend toe aan de database
 *   - setMessage()      = slaat een melding op in de sessie (bijv. "Vriend toegevoegd!")
 *   - getMessage()      = haalt de opgeslagen melding op om te tonen aan de gebruiker
 *   - safeEcho()        = toont tekst veilig op de pagina (beschermt tegen XSS-aanvallen)
 *   - checkSessionTimeout() = controleert of de sessie niet is verlopen
 * 'require_once' zorgt ervoor dat het bestand MAXIMAAL 1 KEER wordt ingeladen.
 * Als het bestand al eerder is ingeladen, wordt het NIET opnieuw geladen.
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
 * kan niemand anders zomaar verder werken in zijn account.
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
 * (ook niet-ingelogde bezoekers) vrienden kunnen toevoegen aan willekeurige accounts.
 * ============================================================ */
if (!isLoggedIn()) {
    header("Location: login.php"); /* Stuur de browser door naar login.php met een HTTP-header */
    exit; /* Stop onmiddellijk ALLE code hieronder - niets meer uitvoeren */
}

/* ============================================================
 * STAP 4 - GEBRUIKERSGEGEVENS OPHALEN
 * ============================================================
 * getUserId() haalt het unieke ID-nummer van de ingelogde gebruiker op uit de sessie.
 * Dit ID wordt gebruikt om te bepalen WELKE vrienden bij WELKE gebruiker horen.
 * Elke gebruiker heeft zijn eigen vriendenlijst, gescheiden van andere gebruikers.
 * ============================================================ */
$userId = getUserId();

/* ============================================================
 * STAP 5 - VRIENDENLIJST OPHALEN UIT DE DATABASE
 * ============================================================
 * getFriends($userId) haalt ALLE vrienden op die bij deze gebruiker horen.
 * De functie geeft een ARRAY (lijst) terug met voor elke vriend:
 *   - 'friend_id'  = uniek ID-nummer van de vriend (voor bewerken/verwijderen)
 *   - 'username'   = de gaming gebruikersnaam van de vriend
 *   - 'note'       = een persoonlijke notitie over de vriend (bijv. "Goed in Fortnite")
 *   - 'status'     = de huidige status (Online, Offline, Playing, Away)
 * Deze lijst wordt later gebruikt om de vriendenTABEL te vullen op de pagina.
 * ============================================================ */
$vrienden = getFriends($userId);

/* ============================================================
 * STAP 6 - FOUTVARIABELE INITIALISEREN
 * ============================================================
 * $fout wordt op een LEGE string ('') gezet.
 * Als er later een fout optreedt bij het toevoegen van een vriend,
 * wordt er een foutmelding in deze variabele opgeslagen.
 * Een lege string betekent: "er is (nog) geen fout opgetreden".
 * ============================================================ */
$fout = '';

/* ============================================================
 * STAP 7 - FORMULIER VERWERKING (POST-VERZOEK)
 * ============================================================
 * $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode waarmee de pagina is opgevraagd.
 * Er zijn twee mogelijkheden:
 *   - 'GET'  = de pagina wordt gewoon geladen (gebruiker bezoekt de pagina)
 *   - 'POST' = er is een formulier verzonden (gebruiker heeft op de knop gedrukt)
 * We controleren of de methode 'POST' is, want dat betekent dat het formulier
 * onderaan deze pagina is ingevuld en verzonden door de gebruiker.
 * ============================================================ */
// Verwerk formulier verzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* ----------------------------------------------------------
     * STAP 7a - FORMULIERGEGEVENS OPHALEN
     * ----------------------------------------------------------
     * $_POST is een speciale PHP-array die ALLE ingevulde formuliervelden bevat.
     * We halen drie waarden op:
     *   - 'friend_username' = de gaming naam die de gebruiker heeft ingetypt
     *   - 'note'            = de optionele notitie die de gebruiker heeft ingetypt
     *   - 'status'          = de gekozen status uit het dropdown-menu
     * De '??' operator is de "null coalescing operator":
     *   - Als het veld BESTAAT in $_POST, gebruik dan die waarde
     *   - Als het veld NIET BESTAAT (of null is), gebruik dan de standaardwaarde
     *     (lege string '' voor gebruikersnaam/notitie, 'Offline' voor status)
     * Dit voorkomt PHP-waarschuwingen als een veld ontbreekt.
     * ---------------------------------------------------------- */
    $vriendGebruikersnaam = $_POST['friend_username'] ?? ''; /* Haal de ingevulde gebruikersnaam op, of '' als die ontbreekt */
    $notitie = $_POST['note'] ?? ''; /* Haal de ingevulde notitie op, of '' als die ontbreekt */
    $status = $_POST['status'] ?? 'Offline'; /* Haal de gekozen status op, of 'Offline' als standaard */

    /* ----------------------------------------------------------
     * STAP 7b - VRIEND TOEVOEGEN AAN DE DATABASE
     * ----------------------------------------------------------
     * addFriend() probeert de nieuwe vriend op te slaan in de database.
     * De functie krijgt 4 parameters:
     *   1. $userId                = het ID van de INGELOGDE gebruiker (eigenaar van de vriendenlijst)
     *   2. $vriendGebruikersnaam  = de gaming naam van de vriend om toe te voegen
     *   3. $notitie               = een optionele notitie over de vriend
     *   4. $status                = de status van de vriend (Online/Offline/Playing/Away)
     * De functie RETOURNEERT:
     *   - Een LEGE string ('')     als het toevoegen GELUKT is (geen fout)
     *   - Een FOUTMELDING (string) als er iets mis ging (bijv. "Gebruikersnaam is verplicht")
     * Het resultaat wordt opgeslagen in $fout om later te tonen aan de gebruiker.
     * ---------------------------------------------------------- */
    $fout = addFriend($userId, $vriendGebruikersnaam, $notitie, $status);

    /* ----------------------------------------------------------
     * STAP 7c - CONTROLEER OF HET TOEVOEGEN GELUKT IS
     * ----------------------------------------------------------
     * Als $fout LEEG is (geen foutmelding), dan is de vriend succesvol toegevoegd.
     * '!$fout' betekent: "als $fout NIET waar is" (een lege string is 'niet waar' in PHP).
     * Bij succes:
     *   1. setMessage('success', 'Vriend toegevoegd!') slaat een SUCCESMELDING op in de sessie.
     *      De melding blijft bewaard tot de volgende paginalading (via een sessie-variabele).
     *   2. header("Location: add_friend.php") stuurt de browser opnieuw naar DEZE pagina (redirect).
     *      Dit is het "PRG-patroon" (Post-Redirect-Get): na een POST-verzoek sturen we door
     *      met GET, zodat de gebruiker niet per ongeluk het formulier opnieuw verstuurt
     *      als hij/zij op de "Vernieuwen"-knop drukt in de browser.
     *   3. exit; stopt alle code zodat de redirect schoon wordt uitgevoerd.
     * Als $fout NIET leeg is (er is een foutmelding), gaan we NIET doorsturen.
     * Dan wordt de pagina gewoon geladen en toont de foutmelding bovenaan het formulier.
     * ---------------------------------------------------------- */
    if (!$fout) {
        setMessage('success', 'Vriend toegevoegd!'); /* Sla een groene succesmelding op in de sessie */
        header("Location: add_friend.php"); /* Stuur de browser door naar dezelfde pagina (PRG-patroon) */
        exit; /* Stop alle verdere code om de redirect correct uit te voeren */
    }
}
?>
<!-- ============================================================
     STAP 8 - BEGIN VAN HET HTML-DOCUMENT
     ============================================================
     Alles hierboven was PHP (server-side logica).
     Alles hieronder is HTML (wat de browser daadwerkelijk toont aan de gebruiker).
     De PHP-code hierboven is al uitgevoerd VOORDAT de HTML naar de browser wordt gestuurd.
     ============================================================ -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5-document is.
     Zonder deze declaratie kan de browser de pagina verkeerd weergeven (quirks mode). -->
<!DOCTYPE html>

<!-- <html lang="nl"> opent het HTML-document en stelt de taal in op Nederlands ("nl").
     Dit helpt zoekmachines en schermlezers (voor slechtzienden) om de taal te herkennen.
     Schermlezers gebruiken dit om de juiste uitspraak te kiezen. -->
<html lang="nl">

<!-- ============================================================
     STAP 9 - HEAD SECTIE (METADATA EN EXTERNE BESTANDEN)
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
         De gebruiker ziet "Vrienden - GamePlan Scheduler" bovenaan in zijn browsertab.
         Dit is ook de tekst die zoekmachines tonen in zoekresultaten. -->
    <title>Vrienden - GamePlan Scheduler</title>

    <!-- Laad Bootstrap 5.3.3 CSS via een CDN (Content Delivery Network).
         Bootstrap is een CSS-framework dat kant-en-klare stijlen biedt voor:
         - Knoppen (btn, btn-primary, btn-danger, etc.)
         - Formulieren (form-control, form-select, form-label, etc.)
         - Tabellen (table, table-dark, table-bordered, etc.)
         - Kaarten (card, card-body)
         - Lay-out (container, mt-5, pt-5, mb-3, mb-5, etc.)
         - Meldingen (alert, alert-danger, alert-success)
         - Badges (badge, bg-success, bg-primary, bg-secondary)
         CDN betekent dat het bestand vanaf een EXTERNE server wordt geladen,
         niet vanaf onze eigen server. Dit is sneller omdat CDN-servers wereldwijd staan. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Laad ons EIGEN CSS-bestand 'style.css' voor aangepaste stijlen.
         Dit bestand bevat stijlen die BOVENOP Bootstrap komen,
         zoals het donkere gaming-thema, aangepaste kleuren, en speciale effecten.
         Omdat dit NA Bootstrap wordt geladen, kunnen onze stijlen Bootstrap-stijlen overschrijven. -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- ============================================================
     STAP 10 - BODY (ZICHTBARE INHOUD VAN DE PAGINA)
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
         STAP 11 - HEADER (NAVIGATIEBALK) INVOEGEN
         ============================================================
         'include' laadt het bestand 'header.php' in op deze exacte plek.
         header.php bevat de NAVIGATIEBALK bovenaan de pagina met links naar:
         - Home, Profiel, Vrienden, Speellijst, etc.
         Door de header in een apart bestand te zetten, hoeven we het maar 1x te schrijven.
         Alle pagina's die 'include header.php' gebruiken, krijgen dezelfde navigatiebalk.
         Dit heet het DRY-principe: "Don't Repeat Yourself" (Herhaal Jezelf Niet).
         ============================================================ -->
    <?php include 'header.php'; ?>

    <!-- ============================================================
         STAP 12 - HOOFDINHOUD (MAIN)
         ============================================================
         <main> is een semantisch HTML5-element dat de HOOFDINHOUD van de pagina markeert.
         Dit helpt zoekmachines en schermlezers om de belangrijkste content te vinden.

         Bootstrap-klassen:
         - 'container' = centreert de inhoud horizontaal op de pagina met automatische marges.
                         Op grote schermen wordt de breedte beperkt (bijv. 1140px).
                         Op kleine schermen (telefoons) neemt het de volledige breedte in.
         - 'mt-5'      = margin-top niveau 5 (3rem = 48px ruimte boven het element).
                         Dit duwt de inhoud naar beneden, weg van de navigatiebalk.
         - 'pt-5'      = padding-top niveau 5 (3rem = 48px binnenruimte bovenaan).
                         Dit voegt EXTRA ruimte toe binnenin het element, zodat de content
                         niet tegen de bovenkant van de container plakt.
         ============================================================ -->
    <main class="container mt-5 pt-5">

        <!-- ============================================================
             STAP 13 - SUCCESMELDING WEERGEVEN (INDIEN AANWEZIG)
             ============================================================
             getMessage() controleert of er een melding is opgeslagen in de sessie
             (bijv. door setMessage() na het succesvol toevoegen van een vriend).
             Als er een melding is, geeft het een HTML-string terug met een Bootstrap alert.
             Als er GEEN melding is, geeft het een lege string terug (er verschijnt niets).
             De melding wordt na het tonen automatisch gewist uit de sessie,
             zodat deze niet opnieuw verschijnt bij het verversen van de pagina.
             ============================================================ -->
        <?php echo getMessage(); ?>

        <!-- ============================================================
             STAP 14 - FOUTMELDING WEERGEVEN (INDIEN AANWEZIG)
             ============================================================
             Als $fout NIET leeg is (het formulier is verzonden maar er was een fout),
             tonen we een RODE foutmelding met Bootstrap-klassen:
             - 'alert'        = Bootstrap basisstijl voor meldingen (padding, rand, afronding)
             - 'alert-danger' = maakt de melding ROOD (voor foutmeldingen/waarschuwingen)
             safeEcho($fout) toont de foutmelding VEILIG: speciale HTML-tekens worden
             omgezet zodat kwaadaardige code niet kan worden uitgevoerd (XSS-bescherming).
             Bijvoorbeeld: '<script>' wordt omgezet naar '&lt;script&gt;'
             ============================================================ -->
        <?php if ($fout): ?>
            <div class="alert alert-danger"><?php echo safeEcho($fout); ?></div>
        <?php endif; ?>

        <!-- ============================================================
             STAP 15 - VRIEND TOEVOEGEN FORMULIER (SECTIE)
             ============================================================
             <section> is een semantisch HTML5-element dat een LOGISCH DEEL van de pagina markeert.
             Dit eerste deel bevat het formulier om een nieuwe vriend toe te voegen.

             Bootstrap-klasse:
             - 'mb-5' = margin-bottom niveau 5 (3rem = 48px ruimte ONDER dit element).
                         Dit creert visuele ruimte tussen het formulier en de vriendenlijst eronder.
             ============================================================ -->
        <!-- VRIEND TOEVOEGEN FORMULIER -->
        <section class="mb-5">

            <!-- De koptekst van de sectie met een emoji voor visuele herkenning.
                 <h2> is een tweede-niveau koptekst (kleiner dan <h1> maar nog steeds opvallend). -->
            <h2>👥 Vriend Toevoegen</h2>

            <!-- ============================================================
                 STAP 16 - BOOTSTRAP CARD (KAART-COMPONENT)
                 ============================================================
                 Een 'card' is een Bootstrap-component dat content groepeert in een
                 visueel aantrekkelijk kader met afgeronde hoeken en een rand.

                 Bootstrap-klassen:
                 - 'card'      = creert een kaart met een witte achtergrond, rand, en afgeronde hoeken.
                                  In ons donkere thema kan de kleur aangepast zijn via style.css.
                 - 'card-body' = voegt padding (binnenruimte) toe rondom de inhoud van de kaart.
                                  Standaard is dit 1rem (16px) aan alle kanten.
                 ============================================================ -->
            <div class="card">
                <div class="card-body">

                    <!-- ============================================================
                         STAP 17 - HET FORMULIER
                         ============================================================
                         <form method="POST"> maakt een HTML-formulier aan.
                         'method="POST"' betekent dat de gegevens worden verstuurd via een HTTP POST-verzoek.
                         POST is veiliger dan GET omdat de gegevens NIET zichtbaar zijn in de URL.
                         Zonder 'action' attribuut wordt het formulier verstuurd naar DEZELFDE pagina.
                         Als de gebruiker op de knop drukt, wordt deze pagina opnieuw geladen
                         met $_SERVER['REQUEST_METHOD'] gelijk aan 'POST', waardoor het PHP-blok
                         bovenaan (STAP 7) het formulier verwerkt.
                         ============================================================ -->
                    <form method="POST">

                        <!-- ============================================================
                             STAP 18 - INVOERVELD: GEBRUIKERSNAAM VAN DE VRIEND
                             ============================================================
                             Dit is het EERSTE formulierveld waar de gebruiker de gaming naam
                             van zijn/haar vriend invult.

                             Bootstrap-klasse op de wrapper-div:
                             - 'mb-3' = margin-bottom niveau 3 (1rem = 16px ruimte onder dit veld).
                                         Dit creert ruimte tussen de formuliervelden.

                             Het <label>-element:
                             - 'for="friend_username"' koppelt het label aan het invoerveld met id="friend_username".
                               Als de gebruiker op het label KLIKT, wordt het invoerveld automatisch geselecteerd.
                             - 'form-label' = Bootstrap-klasse die het label correct opmaakt (margin-bottom, lettertype).

                             Het <input>-element:
                             - 'type="text"'           = dit is een normaal tekstveld (geen wachtwoord, email, etc.)
                             - 'id="friend_username"'   = uniek ID voor het veld, gekoppeld aan het label (for="friend_username")
                             - 'name="friend_username"' = de NAAM waarmee dit veld wordt verstuurd in $_POST.
                                                          In PHP halen we het op met $_POST['friend_username'].
                             - 'class="form-control"'   = Bootstrap-klasse die het invoerveld opmaakt met:
                                                          afgeronde hoeken, volledige breedte, padding, en focus-effect.
                             - 'required'               = HTML5-validatie: het formulier kan NIET worden verstuurd
                                                          als dit veld leeg is. De browser toont een waarschuwing.
                             - 'maxlength="50"'         = maximaal 50 tekens mogen worden ingevoerd.
                                                          Dit beschermt tegen extreem lange invoer.
                             - 'placeholder="..."'      = grijze voorbeeldtekst die verdwijnt zodra de gebruiker typt.
                                                          Het geeft een hint over wat er ingevuld moet worden.
                             ============================================================ -->
                        <div class="mb-3">
                            <label for="friend_username" class="form-label">🎮 Gebruikersnaam Vriend *</label>
                            <input type="text" id="friend_username" name="friend_username" class="form-control" required
                                maxlength="50" placeholder="Gaming naam van je vriend">
                        </div>

                        <!-- ============================================================
                             STAP 19 - TEKSTVAK: NOTITIE OVER DE VRIEND
                             ============================================================
                             Dit is een OPTIONEEL tekstvak waar de gebruiker een persoonlijke
                             notitie kan schrijven over de vriend (bijv. "Goed in Fortnite").

                             Bootstrap-klasse op de wrapper-div:
                             - 'mb-3' = margin-bottom niveau 3 (1rem = 16px ruimte onder dit veld).

                             Het <label>-element:
                             - 'for="note"'      = koppelt het label aan het tekstvak met id="note"
                             - 'form-label'      = Bootstrap-stijl voor labels

                             Het <textarea>-element (in plaats van <input>):
                             - Een <textarea> is een MEHRREGEL tekstveld (de gebruiker kan meerdere regels typen).
                             - 'id="note"'            = uniek ID, gekoppeld aan het label
                             - 'name="note"'          = de naam waarmee dit veld wordt verstuurd ($_POST['note'])
                             - 'class="form-control"' = Bootstrap-stijl: volledige breedte, afgeronde hoeken, padding
                             - 'rows="2"'             = het tekstvak is standaard 2 regels hoog.
                                                        De gebruiker kan meer typen, maar het veld is 2 regels groot.
                             - 'placeholder="..."'    = grijze voorbeeldtekst als hint voor de gebruiker

                             OPMERKING: Dit veld heeft GEEN 'required' attribuut, dus het is OPTIONEEL.
                             De gebruiker mag het leeg laten.
                             ============================================================ -->
                        <div class="mb-3">
                            <label for="note" class="form-label">📝 Notitie (optioneel)</label>
                            <textarea id="note" name="note" class="form-control" rows="2"
                                placeholder="bijv. 'Goed in Fortnite'"></textarea>
                        </div>

                        <!-- ============================================================
                             STAP 20 - DROPDOWN-MENU: STATUS VAN DE VRIEND
                             ============================================================
                             Dit is een DROPDOWN-MENU (keuzelijst) waarmee de gebruiker de
                             huidige status van de vriend kan selecteren.

                             Bootstrap-klasse op de wrapper-div:
                             - 'mb-3' = margin-bottom niveau 3 (1rem = 16px ruimte onder dit veld).

                             Het <label>-element:
                             - 'for="status"'    = koppelt het label aan het dropdown-menu met id="status"
                             - 'form-label'      = Bootstrap-stijl voor labels

                             Het <select>-element:
                             - <select> creert een DROPDOWN-MENU waaruit de gebruiker kan kiezen.
                             - 'id="status"'           = uniek ID, gekoppeld aan het label
                             - 'name="status"'         = de naam waarmee de gekozen waarde wordt verstuurd ($_POST['status'])
                             - 'class="form-select"'   = Bootstrap-klasse specifiek voor dropdown-menu's.
                                                         form-select (niet form-control!) geeft het juiste pijltje-icoon,
                                                         de juiste padding, en het correcte uiterlijk.

                             Elke <option> is een KEUZEMOGELIJKHEID in de dropdown:
                             - 'value="Offline"'  = de waarde die naar de server wordt gestuurd als deze optie is gekozen
                             - De tekst tussen <option> en </option> is wat de GEBRUIKER ZIET
                             - De EERSTE optie ("Offline") is standaard geselecteerd omdat die bovenaan staat.

                             De vier status-opties zijn:
                             1. "Offline"  - De vriend is niet online (standaard)
                             2. "Online"   - De vriend is online en beschikbaar
                             3. "Playing"  - De vriend is aan het spelen (tekst: "Aan het spelen")
                             4. "Away"     - De vriend is afwezig (tekst: "Afwezig")
                             ============================================================ -->
                        <div class="mb-3">
                            <label for="status" class="form-label">🔘 Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="Offline">Offline</option>
                                <option value="Online">Online</option>
                                <option value="Playing">Aan het spelen</option>
                                <option value="Away">Afwezig</option>
                            </select>
                        </div>

                        <!-- ============================================================
                             STAP 21 - VERZENDKNOP
                             ============================================================
                             <button type="submit"> maakt een knop die het formulier VERSTUURT.
                             Als de gebruiker hierop klikt, worden ALLE ingevulde velden
                             via een POST-verzoek naar dezelfde pagina gestuurd.

                             Bootstrap-klassen:
                             - 'btn'         = Bootstrap basis-knopstijl (padding, afgeronde hoeken, cursor: pointer)
                             - 'btn-primary' = maakt de knop BLAUW (de primaire/hoofdkleur van Bootstrap).
                                               Blauw is de standaardkleur voor de belangrijkste actie op een pagina.

                             De emoji ➕ en tekst "Vriend Toevoegen" maken duidelijk wat de knop doet.
                             ============================================================ -->
                        <button type="submit" class="btn btn-primary">➕ Vriend Toevoegen</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- ============================================================
             STAP 22 - VRIENDENLIJST SECTIE
             ============================================================
             Dit tweede deel van de pagina toont een TABEL met alle
             bestaande vrienden van de ingelogde gebruiker.

             Bootstrap-klasse:
             - 'mb-5' = margin-bottom niveau 5 (3rem = 48px ruimte onder de sectie)
             ============================================================ -->
        <!-- VRIENDENLIJST -->
        <section class="mb-5">

            <!-- Koptekst voor de vriendenlijst met een emoji voor visuele herkenning. -->
            <h2>📋 Jouw Vrienden</h2>

            <!-- ============================================================
                 STAP 23 - RESPONSIVE TABEL-WRAPPER
                 ============================================================
                 'table-responsive' is een Bootstrap-klasse die de tabel SCROLLBAAR maakt
                 op kleine schermen (telefoons). Als de tabel te breed is voor het scherm,
                 verschijnt er een horizontale scrollbalk in plaats van dat de hele pagina
                 breder wordt. Dit voorkomt dat de gebruiker de hele pagina moet scrollen.
                 ============================================================ -->
            <div class="table-responsive">

                <!-- ============================================================
                     STAP 24 - DE VRIENDENLIJST TABEL
                     ============================================================
                     <table> maakt een HTML-tabel met rijen en kolommen.

                     Bootstrap-klassen op de tabel:
                     - 'table'          = Bootstrap basis-tabelstijl (padding in cellen, horizontale lijnen)
                     - 'table-dark'     = maakt de tabel DONKER (past bij het donkere thema van de site).
                                          Achtergrond wordt donkergrijs, tekst wordt wit.
                     - 'table-bordered' = voegt RANDEN toe rondom ALLE cellen van de tabel.
                                          Zonder deze klasse zijn er alleen horizontale lijnen.
                     - 'table-hover'    = als de gebruiker met de muis over een RIJ beweegt,
                                          wordt die rij LICHTER gemarkeerd (hover-effect).
                                          Dit maakt het makkelijker om gegevens in een rij te volgen.
                     ============================================================ -->
                <table class="table table-dark table-bordered table-hover">

                    <!-- ============================================================
                         STAP 25 - TABEL KOPTEKST (THEAD)
                         ============================================================
                         <thead> bevat de KOPTEKST-RIJ van de tabel.
                         <tr> is een tabelrij (table row).
                         <th> is een koptekst-cel (table header) - wordt VETGEDRUKT weergegeven.

                         De vier kolommen zijn:
                         1. "Gebruikersnaam" = de gaming naam van de vriend
                         2. "Status"         = de huidige status (Online/Offline/Playing/Away)
                         3. "Notitie"        = de persoonlijke notitie over de vriend
                         4. "Acties"         = knoppen om de vriend te bewerken of verwijderen
                         ============================================================ -->
                    <thead>
                        <tr>
                            <th>Gebruikersnaam</th>
                            <th>Status</th>
                            <th>Notitie</th>
                            <th>Acties</th>
                        </tr>
                    </thead>

                    <!-- ============================================================
                         STAP 26 - TABEL LICHAAM (TBODY)
                         ============================================================
                         <tbody> bevat de GEGEVENSRIJEN van de tabel.
                         Hier worden de daadwerkelijke vrienden getoond.
                         ============================================================ -->
                    <tbody>

                        <!-- ============================================================
                             STAP 27 - CONTROLEER OF ER VRIENDEN ZIJN
                             ============================================================
                             empty($vrienden) controleert of de vriendenlijst LEEG is.
                             Een array is leeg als er 0 elementen in zitten.
                             Als er GEEN vrienden zijn, tonen we een bericht: "Nog geen vrienden!"

                             'colspan="4"' laat de cel over ALLE 4 KOLOMMEN uitstrekken,
                             zodat het bericht gecentreerd over de hele breedte van de tabel staat.

                             Bootstrap-klassen op de lege-rij cel:
                             - 'text-center'    = centreert de tekst horizontaal
                             - 'text-secondary' = maakt de tekst GRIJS (minder opvallend),
                                                  dit geeft aan dat het een informatief bericht is, geen echte data.
                             ============================================================ -->
                        <?php if (empty($vrienden)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-secondary">Nog geen vrienden!</td>
                            </tr>

                            <!-- ============================================================
                             STAP 28 - FOREACH LOOP: ELKE VRIEND WEERGEVEN
                             ============================================================
                             Als er WEL vrienden zijn, gebruiken we een 'foreach' loop.
                             foreach ($vrienden as $vriend) doorloopt ELKE vriend in de lijst:
                             - $vrienden  = de hele array met alle vrienden (opgehaald in STAP 5)
                             - $vriend    = de HUIDIGE vriend in de loop (een associatieve array)

                             Bij elke doorloop van de loop wordt een nieuwe <tr> (tabelrij) gemaakt.
                             Als er 5 vrienden zijn, maakt de loop 5 rijen.
                             ============================================================ -->
                        <?php else: ?>
                            <?php foreach ($vrienden as $vriend): ?>
                                <tr>
                                    <!-- ============================================================
                                         STAP 29 - KOLOM 1: GEBRUIKERSNAAM
                                         ============================================================
                                         Toont de gaming gebruikersnaam van de vriend.
                                         safeEcho() zorgt ervoor dat de tekst VEILIG wordt weergegeven.
                                         Als iemand probeert HTML-code in te voeren als gebruikersnaam
                                         (bijv. "<script>hack</script>"), wordt dit ONSCHADELIJK gemaakt.
                                         Dit heet XSS-bescherming (Cross-Site Scripting preventie).
                                         ============================================================ -->
                                    <td><?php echo safeEcho($vriend['username']); ?></td>

                                    <!-- ============================================================
                                         STAP 30 - KOLOM 2: STATUS MET GEKLEURDE BADGE
                                         ============================================================
                                         De status wordt getoond als een BADGE (gekleurd label).
                                         <span> is een inline HTML-element dat we gebruiken als container
                                         voor de badge.

                                         Bootstrap-klasse 'badge' maakt een klein, afgerond label met padding.

                                         De KLEUR van de badge hangt af van de status-waarde:
                                         Dit wordt bepaald met een TERNARY OPERATOR (verkorte if/else):

                                         $vriend['status'] === 'Online' ? 'bg-success' : (...)

                                         De logica werkt als volgt:
                                         1. Is de status 'Online'?
                                            - JA: gebruik 'bg-success' = GROEN (groen = actief/beschikbaar)
                                            - NEE: ga naar de volgende controle...
                                         2. Is de status 'Playing'?
                                            - JA: gebruik 'bg-primary' = BLAUW (blauw = bezig met spelen)
                                            - NEE: gebruik 'bg-secondary' = GRIJS (voor Offline en Away)

                                         Samenvatting badge-kleuren:
                                         - 'bg-success'   = GROEN  achtergrond (voor "Online")
                                         - 'bg-primary'   = BLAUW  achtergrond (voor "Playing" / "Aan het spelen")
                                         - 'bg-secondary' = GRIJS  achtergrond (voor "Offline" en "Away" / "Afwezig")

                                         safeEcho() toont de statustekst veilig in de badge.
                                         ============================================================ -->
                                    <td>
                                        <span
                                            class="badge <?php echo $vriend['status'] === 'Online' ? 'bg-success' : ($vriend['status'] === 'Playing' ? 'bg-primary' : 'bg-secondary'); ?>">
                                            <?php echo safeEcho($vriend['status']); ?>
                                        </span>
                                    </td>

                                    <!-- ============================================================
                                         STAP 31 - KOLOM 3: NOTITIE
                                         ============================================================
                                         Toont de persoonlijke notitie over de vriend.
                                         Als er geen notitie is, wordt een lege cel getoond.
                                         safeEcho() beschermt opnieuw tegen XSS-aanvallen.
                                         ============================================================ -->
                                    <td><?php echo safeEcho($vriend['note']); ?></td>

                                    <!-- ============================================================
                                         STAP 32 - KOLOM 4: ACTIEKNOPPEN (BEWERKEN EN VERWIJDEREN)
                                         ============================================================
                                         Twee knoppen voor acties op deze vriend:

                                         --- BEWERKEN-KNOP ---
                                         <a href="edit_friend.php?id=..."> is een LINK naar de bewerkpagina.
                                         '?id=' voegt het vriend-ID toe als URL-parameter (query string).
                                         Voorbeeld: edit_friend.php?id=42 (bewerk de vriend met ID 42).
                                         De bewerkpagina leest dit ID uit $_GET['id'] om te weten
                                         WELKE vriend bewerkt moet worden.

                                         Bootstrap-klassen op de bewerken-link:
                                         - 'btn'         = stijlt de link als een KNOP (in plaats van blauwe tekst)
                                         - 'btn-sm'      = maakt de knop KLEINER (small) zodat hij in de tabelcel past
                                         - 'btn-warning' = maakt de knop GEEL/ORANJE (waarschuwingskleur).
                                                           Geel wordt vaak gebruikt voor "bewerken" acties.

                                         --- VERWIJDEREN-KNOP ---
                                         <a href="delete.php?type=friend&id=..."> stuurt naar de verwijderpagina.
                                         'type=friend' vertelt delete.php dat het om een VRIEND gaat (niet een spel, etc.)
                                         '&id=' voegt het vriend-ID toe om te weten WELKE vriend verwijderd moet worden.

                                         Bootstrap-klassen op de verwijderen-link:
                                         - 'btn'        = stijlt als knop
                                         - 'btn-sm'     = kleine knop
                                         - 'btn-danger' = maakt de knop ROOD (gevaar/verwijderen)

                                         'onclick="return confirm('Vriend verwijderen?');"' is een JavaScript-bevestiging.
                                         Als de gebruiker op "Verwijderen" klikt, verschijnt er een popup-venster
                                         met de vraag "Vriend verwijderen?" en twee knoppen: OK en Annuleren.
                                         - Als de gebruiker "OK" kiest:      confirm() geeft 'true' terug -> de link wordt gevolgd
                                         - Als de gebruiker "Annuleren" kiest: confirm() geeft 'false' terug -> niets gebeurt
                                         Dit voorkomt ONBEDOELD verwijderen door een misclick.
                                         ============================================================ -->
                                    <td>
                                        <a href="edit_friend.php?id=<?php echo $vriend['friend_id']; ?>"
                                            class="btn btn-sm btn-warning">✏️ Bewerken</a>
                                        <a href="delete.php?type=friend&id=<?php echo $vriend['friend_id']; ?>"
                                            class="btn btn-sm btn-danger" onclick="return confirm('Vriend verwijderen?');">🗑️
                                            Verwijderen</a>
                                    </td>
                                </tr>

                                <!-- endforeach sluit de foreach-loop af.
                                 Alle code tussen 'foreach' en 'endforeach' wordt herhaald voor ELKE vriend. -->
                            <?php endforeach; ?>

                            <!-- endif sluit de if/else controle af (of de vriendenlijst leeg is of niet). -->
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- ============================================================
         STAP 33 - FOOTER (VOETTEKST) INVOEGEN
         ============================================================
         'include' laadt het bestand 'footer.php' in.
         footer.php bevat de VOETTEKST onderaan de pagina, met bijv. copyright-informatie.
         Net als de header wordt de footer op ALLE pagina's hergebruikt (DRY-principe).
         ============================================================ -->
    <?php include 'footer.php'; ?>

    <!-- ============================================================
         STAP 34 - BOOTSTRAP JAVASCRIPT LADEN
         ============================================================
         Bootstrap heeft naast CSS ook JavaScript nodig voor INTERACTIEVE componenten:
         - Dropdown-menu's (openklikken en sluiten)
         - Modals (pop-upvensters)
         - Tooltips en popovers
         - Navigatiebalk hamburger-menu op mobiel
         'bootstrap.bundle.min.js' bevat zowel Bootstrap JS als Popper.js (voor positionering).
         '.min.js' betekent dat het bestand is GEMINIFICEERD (alle spaties en commentaar verwijderd)
         om het bestand kleiner en sneller te laden.
         Dit script staat ONDERAAN de pagina (voor </body>) zodat de HTML eerst wordt geladen
         en de pagina sneller zichtbaar is voor de gebruiker.
         ============================================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ============================================================
         STAP 35 - EIGEN JAVASCRIPT LADEN
         ============================================================
         'script.js' is ons EIGEN JavaScript-bestand met aangepaste functionaliteit.
         Dit kan bijvoorbeeld bevatten: formuliervalidatie, animaties, of interactieve functies.
         Het wordt NA Bootstrap geladen, zodat we Bootstrap-functies kunnen gebruiken.
         ============================================================ -->
    <script src="script.js"></script>
</body>

<!-- Sluit het HTML-document af. Alles tussen <html> en </html> is het volledige document. -->

</html>