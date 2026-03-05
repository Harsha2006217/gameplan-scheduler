<?php
/**
 * ==========================================================================
 * ADD_SCHEDULE.PHP - SCHEMA TOEVOEGEN PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers gaming speelschema's toevoegen.
 * Gebruikers kunnen spel, datum, tijd en meespelende vrienden opgeven.
 * Bevat validatie voor BUG FIX #1001 (spaties) en #1004 (datums).
 *
 * Gebruikersverhaal: "Deel speelschema's met vrienden in een kalender"
 *
 * HOE DEZE PAGINA WERKT:
 * 1. Eerst wordt gecontroleerd of de gebruiker is ingelogd (sessiebeveiliging).
 * 2. Als het formulier is verzonden (POST-verzoek), worden de velden gelezen.
 * 3. De functie addSchedule() valideert alle invoer en slaat het schema op.
 * 4. Bij succes: doorsturen naar index.php met een succesmelding.
 * 5. Bij fout: het formulier opnieuw tonen met de foutmelding.
 *
 * BUG FIX #1001 - SPATIES IN INVOERVELDEN:
 *   Voorheen kon een gebruiker alleen spaties invoeren als speltitel.
 *   Nu wordt de invoer getrimd (trim()) en gecontroleerd of het niet leeg is.
 *   Dit voorkomt dat een schema wordt opgeslagen met een "lege" titel.
 *
 * BUG FIX #1004 - DATUMS IN HET VERLEDEN:
 *   Voorheen kon een gebruiker een datum in het verleden kiezen.
 *   Nu wordt er een min-attribuut op het datumveld gezet met de datum van vandaag.
 *   Daarnaast valideert de server ook of de datum niet in het verleden ligt.
 * ==========================================================================
 */

/* --------------------------------------------------------------------------
 * require_once 'functions.php' - Laad het bestand functions.php exact één keer.
 * Dit bestand bevat ALLE hulpfuncties die we nodig hebben:
 *   - isLoggedIn()          : controleert of de gebruiker is ingelogd via de sessie
 *   - getUserId()           : haalt het gebruikers-ID op uit de sessie
 *   - addSchedule()         : valideert invoer en voegt een nieuw schema toe aan de database
 *   - setMessage()          : slaat een flashbericht op in de sessie (bijv. 'success')
 *   - getMessage()          : haalt een flashbericht op en toont het als HTML-alert
 *   - safeEcho()            : beveiligt tekst tegen XSS-aanvallen met htmlspecialchars()
 *   - checkSessionTimeout() : controleert of de sessie is verlopen (time-out)
 * require_once zorgt ervoor dat het bestand maar één keer wordt geladen,
 * zelfs als het per ongeluk meerdere keren wordt aangeroepen.
 * -------------------------------------------------------------------------- */
require_once 'functions.php';

/* --------------------------------------------------------------------------
 * checkSessionTimeout() - Controleer of de sessie van de gebruiker is verlopen.
 * Als de gebruiker te lang inactief is geweest, wordt de sessie vernietigd
 * en wordt de gebruiker automatisch uitgelogd. Dit is een beveiligingsmaatregel
 * om te voorkomen dat iemand anders de computer kan gebruiken als de
 * oorspronkelijke gebruiker is weggelopen.
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
 *     toch het formulier kunnen zien of gegevens kunnen versturen).
 * -------------------------------------------------------------------------- */
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

/* --------------------------------------------------------------------------
 * $userId = getUserId() - Haal het unieke gebruikers-ID op uit de sessie.
 * Dit ID wordt gebruikt om het nieuwe schema te koppelen aan de juiste gebruiker.
 * Elk schema in de database heeft een 'user_id' kolom die aangeeft welke
 * gebruiker het schema heeft aangemaakt. Zo kan elke gebruiker alleen zijn
 * eigen schema's zien en bewerken.
 * -------------------------------------------------------------------------- */
$userId = getUserId();

/* --------------------------------------------------------------------------
 * $fout = '' - Initialiseer de foutvariabele als een lege string.
 * Deze variabele wordt gebruikt om foutmeldingen op te slaan.
 * Als $fout leeg blijft (''), betekent dit dat er geen fouten zijn.
 * Als $fout een tekst bevat, wordt deze als rode foutmelding getoond
 * aan de gebruiker in het formulier (via de alert-danger div hieronder).
 * -------------------------------------------------------------------------- */
$fout = '';

/* --------------------------------------------------------------------------
 * FORMULIERVERWERKING: Controleer of het formulier is verzonden.
 * $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode van het huidige verzoek.
 * Mogelijke waarden zijn 'GET' (pagina openen) en 'POST' (formulier verzenden).
 * We controleren of het 'POST' is, want dat betekent dat de gebruiker op de
 * "Schema Toevoegen" knop heeft geklikt en het formulier heeft verzonden.
 *
 * Bij het eerste bezoek aan deze pagina is de methode 'GET', dus wordt
 * dit hele if-blok overgeslagen en wordt alleen het lege formulier getoond.
 * -------------------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* ----------------------------------------------------------------------
     * FORMULIERVELDEN UITLEZEN met de null-coalescing operator (??).
     *
     * $_POST is een PHP superglobal array die alle formuliergegevens bevat
     * die via de POST-methode zijn verzonden. De sleutels komen overeen met
     * de 'name' attributen van de HTML-invoervelden.
     *
     * De ?? operator (null coalescing) werkt als volgt:
     *   $_POST['game_title'] ?? ''
     * Dit betekent: "Gebruik $_POST['game_title'] als die bestaat, anders
     * gebruik een lege string ''". Dit voorkomt PHP-waarschuwingen als een
     * veld niet is meegezonden.
     * ---------------------------------------------------------------------- */

    /* $spelTitel - De titel van het spel dat wordt gespeeld (bijv. "Fortnite").
     * Dit veld is verplicht (*). De addSchedule() functie controleert of het
     * niet leeg is en niet alleen uit spaties bestaat (BUG FIX #1001). */
    $spelTitel = $_POST['game_title'] ?? '';

    /* $datum - De datum waarop het spel wordt gespeeld (formaat: JJJJ-MM-DD).
     * Dit veld is verplicht (*). De addSchedule() functie controleert of de
     * datum vandaag of in de toekomst is (BUG FIX #1004). */
    $datum = $_POST['date'] ?? '';

    /* $tijd - Het tijdstip waarop het spel begint (formaat: UU:MM).
     * Dit veld is verplicht (*). */
    $tijd = $_POST['time'] ?? '';

    /* $vriendenStr - Komma-gescheiden lijst van meespelende vrienden.
     * Voorbeeld invoer: "speler1, speler2, speler3"
     * Dit is OPTIONEEL - de gebruiker hoeft geen vrienden op te geven.
     * De addSchedule() functie splitst deze string op de komma's en maakt
     * er een array van. Elke naam wordt getrimd (spaties verwijderd)
     * zodat "speler1 , speler2" correct wordt verwerkt als ["speler1", "speler2"]. */
    $vriendenStr = $_POST['friends_str'] ?? '';

    /* $gedeeldMetStr - Komma-gescheiden lijst van gebruikers die dit schema mogen zien.
     * Voorbeeld invoer: "gebruiker1, gebruiker2"
     * Dit is OPTIONEEL - als het leeg blijft, is het schema alleen zichtbaar
     * voor de eigenaar. Net als $vriendenStr wordt deze string gesplitst op
     * komma's en worden de namen getrimd. */
    $gedeeldMetStr = $_POST['shared_with_str'] ?? '';

    /* ------------------------------------------------------------------
     * addSchedule() AANROEPEN - Dit is de hoofdfunctie die alles doet:
     *
     * Parameters die worden meegegeven:
     *   1. $userId       - Het ID van de ingelogde gebruiker (eigenaar van het schema)
     *   2. $spelTitel     - De naam van het spel
     *   3. $datum         - De gekozen datum
     *   4. $tijd          - Het gekozen tijdstip
     *   5. $vriendenStr   - Komma-gescheiden lijst van meespelende vrienden
     *   6. $gedeeldMetStr - Komma-gescheiden lijst van gebruikers om mee te delen
     *
     * Wat addSchedule() intern doet:
     *   - Trimt alle invoervelden (spaties aan begin/einde verwijderen)
     *   - Controleert of verplichte velden niet leeg zijn (BUG FIX #1001)
     *   - Controleert of de datum niet in het verleden ligt (BUG FIX #1004)
     *   - Splitst de komma-gescheiden strings op in arrays
     *   - Slaat het schema op in de database/JSON-bestand
     *
     * Retourwaarde:
     *   - Lege string '' als alles goed is gegaan (geen fouten)
     *   - Een foutmelding als string als er iets mis is (bijv. "Speltitel is verplicht")
     * ------------------------------------------------------------------ */
    $fout = addSchedule($userId, $spelTitel, $datum, $tijd, $vriendenStr, $gedeeldMetStr);

    /* ------------------------------------------------------------------
     * SUCCES-CONTROLE: Als $fout GEEN foutmelding bevat (leeg = geen fout).
     * !$fout is true als $fout een lege string is (=== '').
     * In PHP wordt een lege string als "falsy" beschouwd, dus !'' geeft true.
     *
     * Bij succes:
     *   1. setMessage('success', 'Schema toegevoegd!') slaat een flashbericht
     *      op in de sessie. 'success' is het type (groen) en de tekst is de melding.
     *      Dit bericht wordt op de VOLGENDE pagina (index.php) getoond door
     *      getMessage() en daarna automatisch verwijderd uit de sessie.
     *   2. header("Location: index.php") stuurt de browser door naar het dashboard.
     *   3. exit; stopt de scripuitvoering zodat er niets meer wordt uitgevoerd.
     *
     * Als er WEL een fout is, wordt dit hele if-blok overgeslagen en gaat de
     * code verder naar het HTML-gedeelte waar het formulier met de foutmelding
     * wordt getoond.
     * ------------------------------------------------------------------ */
    if (!$fout) {
        setMessage('success', 'Schema toegevoegd!');
        header("Location: index.php");
        exit;
    }
}
?>
<!-- ==========================================================================
     HTML-GEDEELTE: Hier begint de visuele pagina die de gebruiker ziet.
     Het PHP-gedeelte hierboven heeft de logica afgehandeld; nu volgt de opmaak.
     ========================================================================== -->

<!-- DOCTYPE html - Vertelt de browser dat dit een HTML5-document is.
     Zonder deze declaratie kan de browser in "quirks mode" gaan, wat
     betekent dat de pagina er anders uit kan zien in verschillende browsers. -->
<!DOCTYPE html>

<!-- <html lang="nl"> - Het root-element van de HTML-pagina.
     lang="nl" geeft aan dat de taal van de pagina Nederlands is.
     Dit helpt schermlezers (voor blinden/slechtzienden) de juiste taal te kiezen
     en helpt zoekmachines de pagina correct te indexeren. -->
<html lang="nl">

<head>
    <!-- meta charset="UTF-8" - Stel de tekencodering in op UTF-8.
         UTF-8 ondersteunt alle internationale tekens, inclusief Nederlandse
         speciale tekens zoals e, i, u, o en tekens uit andere talen. -->
    <meta charset="UTF-8">

    <!-- meta viewport - Maakt de pagina responsief (past zich aan aan het scherm).
         width=device-width: de breedte van de pagina past zich aan het apparaat aan.
         initial-scale=1.0: de pagina wordt niet ingezoomd of uitgezoomd bij het laden.
         Zonder dit meta-tag zou de pagina er op een telefoon heel klein uitzien. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title - De titel die bovenaan in het browsertabblad wordt weergegeven.
         "Schema Toevoegen - GamePlan Scheduler" laat de gebruiker weten
         op welke pagina ze zich bevinden. -->
    <title>Schema Toevoegen - GamePlan Scheduler</title>

    <!-- Bootstrap 5.3.3 CSS - Een populair CSS-framework dat vooraf gemaakte
         stijlklassen biedt (bijv. container, btn, form-control, alert, card).
         We laden het via een CDN (Content Delivery Network) zodat het snel laadt
         vanuit een server dicht bij de gebruiker. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- style.css - Ons eigen aangepaste CSS-bestand met projectspecifieke stijlen.
         Dit overschrijft of vult Bootstrap-stijlen aan met onze eigen kleuren,
         lettertypen en lay-outaanpassingen voor de donkere gaming-look. -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- body class="bg-dark text-light" - Het zichtbare deel van de pagina.
     bg-dark  : Bootstrap klasse die de achtergrondkleur donker/zwart maakt.
     text-light: Bootstrap klasse die de standaard tekstkleur wit/licht maakt.
     Samen creeren deze klassen het donkere "gaming" thema van de applicatie. -->

<body class="bg-dark text-light">

    <!-- include 'header.php' - Voeg de navigatiebalk (header) in.
         Dit bestand bevat het <nav> element met het logo, menu-items en
         uitlog-knop. Door het in een apart bestand te bewaren (header.php)
         hoeven we het maar op een plek te onderhouden voor alle pagina's. -->
    <?php include 'header.php'; ?>

    <!-- main class="container mt-5 pt-5" - Het hoofdgedeelte van de pagina.
         container: Bootstrap klasse die de inhoud centreert met automatische marges
                    en een maximale breedte instelt (responsive breakpoints).
         mt-5     : margin-top: 3rem (48px) - ruimte boven het element.
         pt-5     : padding-top: 3rem (48px) - ruimte binnenin het element aan de bovenkant.
         Samen zorgen mt-5 en pt-5 ervoor dat de inhoud niet achter de
         navigatiebalk (die fixed/sticky kan zijn) verborgen wordt. -->
    <main class="container mt-5 pt-5">

        <!-- getMessage() - Toon eventuele flashberichten uit de sessie.
             Als er een bericht is opgeslagen met setMessage() (bijv. op een
             andere pagina), wordt het hier als een Bootstrap-alert getoond.
             Na het tonen wordt het bericht automatisch verwijderd uit de sessie
             zodat het niet opnieuw verschijnt bij het verversen van de pagina. -->
        <?php echo getMessage(); ?>

        <!-- FOUTMELDING WEERGEVEN: Als $fout niet leeg is, toon een rode foutmelding.
             De if ($fout): ... endif; syntax is de alternatieve PHP-syntax die
             makkelijker te lezen is wanneer PHP en HTML door elkaar worden gebruikt.
             alert         : Bootstrap klasse voor een opvallend meldingsblok.
             alert-danger  : Bootstrap klasse die het blok rood kleurt (voor fouten).
             safeEcho($fout): toont de foutmelding VEILIG door speciale HTML-tekens
                              te escapen met htmlspecialchars(). Dit voorkomt
                              XSS-aanvallen (Cross-Site Scripting) waarbij kwaadaardige
                              code zou kunnen worden ingevoegd via foutmeldingen. -->
        <?php if ($fout): ?>
            <div class="alert alert-danger"><?php echo safeEcho($fout); ?></div>
        <?php endif; ?>

        <!-- section class="mb-5" - Een semantische HTML5-sectie voor het formulier.
             mb-5: margin-bottom: 3rem (48px) - ruimte onder de sectie zodat er
                   voldoende afstand is tot de footer. -->
        <section class="mb-5">

            <!-- h2 - Koptekst niveau 2 met de paginatitel en kalender-emoji. -->
            <h2>📅 Schema Toevoegen</h2>

            <!-- div class="card" - Bootstrap-kaartcomponent.
                 Een card is een flexibel inhoudscontainer met een rand, afgeronde
                 hoeken en optionele header/body/footer secties.
                 Het geeft het formulier een nette, afgekaderde uitstraling. -->
            <div class="card">

                <!-- div class="card-body" - Het inhoudsgebied van de kaart.
                     card-body geeft padding (binnenruimte) aan de inhoud binnen de kaart.
                     Alle formulierelementen zitten hierbinnen. -->
                <div class="card-body">

                    <!-- form method="POST" - Het HTML-formulier.
                         method="POST": verstuurt gegevens via het HTTP POST-verzoek.
                             POST is veiliger dan GET omdat de gegevens niet in de URL
                             verschijnen en er geen limiet is op de hoeveelheid data.
                         onsubmit="return validateScheduleForm();":
                             Voordat het formulier wordt verzonden, wordt de JavaScript
                             functie validateScheduleForm() aangeroepen (vanuit script.js).
                             Als deze functie 'false' retourneert, wordt het formulier
                             NIET verzonden. Dit is client-side validatie (in de browser)
                             als extra controle naast de server-side validatie in PHP.
                         Er is geen action-attribuut opgegeven, wat betekent dat het
                         formulier naar DEZELFDE pagina (add_schedule.php) wordt verzonden. -->
                    <form method="POST" onsubmit="return validateScheduleForm();">

                        <!-- ============================================================
                             VELD 1: SPELTITEL (game_title)
                             Dit is een VERPLICHT tekstveld voor de naam van het spel.
                             BUG FIX #1001: Alleen spaties worden niet geaccepteerd.
                             ============================================================ -->
                        <!-- Speltitel -->
                        <div class="mb-3">
                            <!-- mb-3: margin-bottom: 1rem (16px) - ruimte tussen formuliervelden. -->

                            <!-- label for="game_title" - Het label dat bij het invoerveld hoort.
                                 for="game_title": koppelt het label aan het invoerveld met id="game_title".
                                     Als de gebruiker op het label klikt, wordt het invoerveld gefocust.
                                 form-label: Bootstrap klasse voor consistente label-opmaak.
                                 De * achter "Speltitel" geeft aan dat dit veld verplicht is. -->
                            <label for="game_title" class="form-label">🎮 Speltitel *</label>

                            <!-- input type="text" - Een tekst-invoerveld voor de speltitel.
                                 type="text"     : standaard tekstinvoer (één regel).
                                 id="game_title" : uniek ID voor het element (voor label-koppeling en JavaScript).
                                 name="game_title": de sleutel in $_POST waarmee PHP het veld uitleest.
                                     Dus $_POST['game_title'] geeft de ingevulde waarde.
                                 class="form-control": Bootstrap klasse die het veld opmaakt met:
                                     - volledige breedte (100%)
                                     - nette rand en afgeronde hoeken
                                     - focus-effect (blauwe rand bij klikken)
                                     - goede hoogte en padding voor leesbaarheid
                                 required        : HTML5-attribuut dat voorkomt dat het formulier
                                     wordt verzonden als dit veld leeg is (browser-validatie).
                                 maxlength="100" : maximaal 100 tekens toegestaan. Dit voorkomt
                                     extreem lange invoer die de database of layout zou breken.
                                 placeholder="Welk spel ga je spelen?": grijze hinttekst die
                                     verdwijnt zodra de gebruiker begint te typen. -->
                            <input type="text" id="game_title" name="game_title" class="form-control" required
                                maxlength="100" placeholder="Welk spel ga je spelen?">

                            <!-- small class="text-secondary" - Kleine hulptekst onder het veld.
                                 text-secondary: Bootstrap klasse voor grijze, minder opvallende tekst.
                                 Dit informeert de gebruiker over BUG FIX #1001: dat alleen spaties
                                 niet worden geaccepteerd als geldige speltitel. -->
                            <small class="text-secondary">Mag niet leeg of alleen spaties zijn (BUG FIX #1001)</small>
                        </div>

                        <!-- ============================================================
                             VELD 2: DATUM (date)
                             Dit is een VERPLICHT datumveld.
                             BUG FIX #1004: Datums in het verleden worden geblokkeerd.
                             Het min-attribuut wordt dynamisch gezet met PHP.
                             ============================================================ -->
                        <!-- Datum -->
                        <div class="mb-3">
                            <!-- mb-3: margin-bottom: 1rem (16px) - ruimte onder dit veld. -->

                            <!-- label voor het datumveld met form-label Bootstrap-klasse. -->
                            <label for="date" class="form-label">📆 Datum *</label>

                            <!-- input type="date" - Een datumkiezer (date picker) invoerveld.
                                 type="date"    : toont een kalenderwidget in de meeste browsers
                                     waarmee de gebruiker een datum kan selecteren.
                                 id="date"      : uniek ID voor label-koppeling en JavaScript.
                                 name="date"    : de sleutel in $_POST (dus $_POST['date']).
                                 class="form-control": Bootstrap-stijl voor het invoerveld.
                                 required       : het veld mag niet leeg zijn.
                                 min="<?php echo date('Y-m-d'); ?>": DIT IS BUG FIX #1004!
                                     - date('Y-m-d') genereert de datum van VANDAAG in het
                                       formaat JJJJ-MM-DD (bijv. "2025-09-30").
                                     - Het min-attribuut vertelt de browser dat de gebruiker
                                       GEEN datum voor vandaag mag kiezen.
                                     - In de datumkiezer worden alle eerdere datums grijs/uitgeschakeld.
                                     - Dit is CLIENT-SIDE validatie; de SERVER controleert dit OOK
                                       in addSchedule() voor extra beveiliging (want een slimme
                                       gebruiker kan de browser-validatie omzeilen). -->
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>">

                            <!-- Hulptekst die de gebruiker informeert over de datumbeperking. -->
                            <small class="text-secondary">Moet vandaag of in de toekomst zijn (BUG FIX #1004)</small>
                        </div>

                        <!-- ============================================================
                             VELD 3: TIJD (time)
                             Dit is een VERPLICHT tijdveld voor het starttijdstip.
                             ============================================================ -->
                        <!-- Tijd -->
                        <div class="mb-3">
                            <!-- mb-3: margin-bottom: 1rem (16px) - ruimte onder dit veld. -->

                            <!-- label voor het tijdveld. -->
                            <label for="time" class="form-label">⏰ Tijd *</label>

                            <!-- input type="time" - Een tijdkiezer invoerveld.
                                 type="time"    : toont een tijdselectiewidget (uren en minuten)
                                     in de meeste browsers. Het formaat is UU:MM (bijv. "14:30").
                                 id="time"      : uniek ID voor label-koppeling.
                                 name="time"    : de sleutel in $_POST (dus $_POST['time']).
                                 class="form-control": Bootstrap-stijl.
                                 required       : het veld mag niet leeg zijn. -->
                            <input type="time" id="time" name="time" class="form-control" required>
                        </div>

                        <!-- ============================================================
                             VELD 4: MEESPELENDE VRIENDEN (friends_str)
                             Dit is een OPTIONEEL tekstveld met komma-gescheiden invoer.
                             De gebruiker typt meerdere namen gescheiden door komma's.
                             Voorbeeld: "Jan, Piet, Klaas"
                             De server splitst dit op in een array: ["Jan", "Piet", "Klaas"]
                             ============================================================ -->
                        <!-- Meespelende vrienden -->
                        <div class="mb-3">
                            <!-- mb-3: margin-bottom: 1rem (16px). -->

                            <!-- label voor het vriendenveld. Geen * want het is optioneel. -->
                            <label for="friends_str" class="form-label">👥 Meespelende Vrienden</label>

                            <!-- input type="text" - Tekstveld voor komma-gescheiden vriendenlijst.
                                 id="friends_str"  : uniek ID.
                                 name="friends_str" : de sleutel in $_POST (dus $_POST['friends_str']).
                                 class="form-control": Bootstrap-stijl.
                                 GEEN required-attribuut: dit veld is OPTIONEEL.
                                 placeholder="speler1, speler2, speler3": laat de gebruiker zien
                                     HOE ze meerdere namen moeten invoeren (gescheiden door komma's).
                                 KOMMA-GESCHEIDEN INVOER UITLEG:
                                     De gebruiker voert namen in als één tekstregel met komma's ertussen.
                                     In de server-functie addSchedule() wordt deze string gesplitst:
                                       explode(',', $vriendenStr)  ->  maakt een array van de string
                                       array_map('trim', ...)      ->  verwijdert spaties rond elke naam
                                       array_filter(...)            ->  verwijdert lege waarden
                                     Zo wordt "speler1 , speler2,  speler3" omgezet naar:
                                       ["speler1", "speler2", "speler3"] -->
                            <input type="text" id="friends_str" name="friends_str" class="form-control"
                                placeholder="speler1, speler2, speler3">

                            <!-- Hulptekst die het invoerformaat uitlegt. -->
                            <small class="text-secondary">Komma-gescheiden gebruikersnamen</small>
                        </div>

                        <!-- ============================================================
                             VELD 5: GEDEELD MET (shared_with_str)
                             Dit is een OPTIONEEL tekstveld met komma-gescheiden invoer.
                             Hiermee bepaalt de gebruiker wie dit schema mogen BEKIJKEN.
                             Werkt op dezelfde manier als het vriendenveld hierboven.
                             ============================================================ -->
                        <!-- Gedeeld met -->
                        <div class="mb-3">
                            <!-- mb-3: margin-bottom: 1rem (16px). -->

                            <!-- label voor het gedeeld-met-veld. Geen * want het is optioneel. -->
                            <label for="shared_with_str" class="form-label">👀 Gedeeld Met</label>

                            <!-- input type="text" - Tekstveld voor komma-gescheiden deellijst.
                                 id="shared_with_str"  : uniek ID.
                                 name="shared_with_str" : de sleutel in $_POST (dus $_POST['shared_with_str']).
                                 class="form-control"   : Bootstrap-stijl.
                                 GEEN required-attribuut: dit veld is OPTIONEEL.
                                 placeholder="gebruiker1, gebruiker2": voorbeeldinvoer.
                                 KOMMA-GESCHEIDEN INVOER: werkt hetzelfde als het vriendenveld.
                                     De server splitst "gebruiker1, gebruiker2" op in een array. -->
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                placeholder="gebruiker1, gebruiker2">

                            <!-- Hulptekst die uitlegt waarvoor dit veld dient. -->
                            <small class="text-secondary">Wie kan dit schema zien</small>
                        </div>

                        <!-- VERZENDKNOP: button type="submit" - Verstuurt het formulier.
                             type="submit" : klikt op deze knop verzendt het formulier naar de server.
                             class="btn btn-primary btn-lg":
                                 btn         : Bootstrap basisklasse voor knoppen.
                                 btn-primary : blauwe/hoofdkleur knop (de belangrijkste actie).
                                 btn-lg      : grote knop (meer padding en groter lettertype).
                             De tekst "Schema Toevoegen" met +-emoji maakt duidelijk wat de knop doet. -->
                        <button type="submit" class="btn btn-primary btn-lg">➕ Schema Toevoegen</button>

                        <!-- ANNULEERLINK: a href="index.php" - Ga terug naar het dashboard.
                             Dit is een <a> (link) die eruitziet als een knop dankzij de Bootstrap klassen.
                             class="btn btn-secondary btn-lg":
                                 btn           : Bootstrap basisklasse voor knoppen.
                                 btn-secondary : grijze knop (minder belangrijke/secundaire actie).
                                 btn-lg        : grote knop.
                             href="index.php" : navigeert naar het dashboard zonder het formulier te verzenden.
                             Door een link te gebruiken in plaats van een button met type="button",
                             werkt de annuleerknop ook als JavaScript is uitgeschakeld. -->
                        <a href="index.php" class="btn btn-secondary btn-lg">↩️ Annuleren</a>

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

    <!-- include 'footer.php' - Voeg de voettekst (footer) van de pagina in.
         Dit bestand bevat copyright-informatie, links en andere footer-content.
         Net als header.php wordt het in een apart bestand bewaard zodat wijzigingen
         automatisch op alle pagina's worden doorgevoerd. -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap 5.3.3 JavaScript Bundle - Laad de Bootstrap JavaScript-bibliotheek.
         Deze is nodig voor interactieve Bootstrap-componenten zoals:
         - Dropdown-menu's in de navigatiebalk
         - Modal-vensters (pop-ups)
         - Tooltips en popovers
         - De hamburger-menu knop op mobiele apparaten
         "bundle" betekent dat Popper.js er al bij inbegrepen is (nodig voor dropdowns). -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- script.js - Ons eigen JavaScript-bestand met projectspecifieke functionaliteit.
         Bevat onder andere de validateScheduleForm() functie die wordt aangeroepen
         bij het onsubmit-event van het formulier. Deze functie controleert de invoer
         aan de client-side (in de browser) voordat het naar de server wordt verzonden. -->
    <script src="script.js"></script>

    <!-- Einde van het body-element. -->
</body>

<!-- Einde van het HTML-document. -->

</html>