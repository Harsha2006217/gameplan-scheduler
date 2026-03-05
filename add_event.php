<?php
/**
 * ==========================================================================
 * ADD_EVENT.PHP - EVENEMENT TOEVOEGEN PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers gaming evenementen toevoegen
 * (toernooien, streams, etc.). Bevat herinnering instellingen,
 * externe links en deel opties.
 * Bevat validatie voor BUG FIX #1001 (spaties) en #1004 (datums).
 *
 * Gebruikersverhaal: "Voeg evenementen toe zoals toernooien"
 *
 * --------------------------------------------------------------------------
 * HOE DEZE PAGINA WERKT (samenvatting):
 * --------------------------------------------------------------------------
 * 1. Eerst wordt gecontroleerd of de gebruiker is ingelogd via de sessie.
 * 2. Als het formulier is verzonden (POST), worden alle velden opgehaald.
 * 3. De functie addEvent() slaat het evenement op en geeft een foutmelding
 *    terug als er iets mis is, of niets als het gelukt is.
 * 4. Bij succes wordt de gebruiker doorgestuurd naar de hoofdpagina.
 * 5. Bij een fout blijft de gebruiker op deze pagina en ziet de foutmelding.
 * ==========================================================================
 */

/* require_once laadt het bestand 'functions.php' in, maar slechts één keer.
   Dit bestand bevat alle helperfuncties zoals isLoggedIn(), getUserId(),
   addEvent(), getEvents(), setMessage(), getMessage(), safeEcho(), enz.
   'require_once' zorgt ervoor dat het bestand niet dubbel wordt geladen,
   zelfs als het meerdere keren wordt aangeroepen. Als het bestand niet
   gevonden wordt, stopt het script met een fatale fout. */
require_once 'functions.php';

/* checkSessionTimeout() controleert of de sessie van de gebruiker verlopen is.
   Als de gebruiker te lang inactief is geweest, wordt de sessie beëindigd
   en wordt de gebruiker uitgelogd. Dit is een beveiligingsmaatregel. */
checkSessionTimeout();

/* isLoggedIn() controleert of de gebruiker momenteel is ingelogd.
   Het kijkt of er een geldige sessievariabele bestaat voor de gebruiker.
   Het uitroepteken (!) keert de waarde om: als de gebruiker NIET is ingelogd,
   dan is !isLoggedIn() waar (true), en wordt de code in het if-blok uitgevoerd. */
if (!isLoggedIn()) {
    /* header("Location: login.php") stuurt een HTTP-header naar de browser
       die zegt: "ga naar login.php". Dit is een doorverwijzing (redirect).
       De browser laadt dan automatisch de inlogpagina. */
    header("Location: login.php");
    /* exit stopt het PHP-script onmiddellijk. Dit is belangrijk omdat de
       code anders gewoon doorgaat na de header() aanroep. Zonder exit
       zou de rest van de pagina nog steeds worden uitgevoerd. */
    exit;
}

/* getUserId() haalt het unieke ID-nummer van de ingelogde gebruiker op
   uit de sessie. Dit ID wordt later gebruikt om het evenement te koppelen
   aan de juiste gebruiker in de database/opslag. */
$userId = getUserId();

/* $fout is een variabele die begint als een lege tekst (string).
   Als er later een fout optreedt bij het toevoegen van het evenement,
   wordt deze variabele gevuld met de foutmelding. Als $fout leeg blijft,
   betekent dat dat alles goed is gegaan. */
$fout = '';

/* Verwerk formulier verzending:
   $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode waarmee de pagina
   is opgevraagd. 'GET' betekent dat de pagina gewoon is geladen.
   'POST' betekent dat er een formulier is verzonden.
   We controleren of de methode gelijk is aan 'POST', want dat betekent
   dat de gebruiker op de "Evenement Toevoegen" knop heeft geklikt. */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* $_POST is een superglobale array in PHP die alle formuliergegevens bevat
       die via de POST-methode zijn verzonden.
       De ?? operator (null coalescing operator) geeft een standaardwaarde
       als de sleutel niet bestaat in $_POST. Bijvoorbeeld: als 'title' niet
       is meegegeven, wordt $titel een lege string ''. */

    /* $titel: haalt de evenementtitel op uit het formulierveld met name="title".
       Als het veld niet is ingevuld, wordt het een lege tekst.
       BUG FIX #1001 zorgt ervoor dat alleen-spaties titels worden afgewezen. */
    $titel = $_POST['title'] ?? '';

    /* $datum: haalt de geselecteerde datum op uit het formulierveld met name="date".
       Het formaat is JJJJ-MM-DD (jaar-maand-dag), bijv. "2025-12-25".
       BUG FIX #1004 controleert dat deze datum niet in het verleden ligt. */
    $datum = $_POST['date'] ?? '';

    /* $tijd: haalt de geselecteerde tijd op uit het formulierveld met name="time".
       Het formaat is UU:MM (uur:minuut), bijv. "14:30". */
    $tijd = $_POST['time'] ?? '';

    /* $beschrijving: haalt de beschrijvingstekst op uit het textarea-veld
       met name="description". Dit veld is optioneel en mag leeg zijn. */
    $beschrijving = $_POST['description'] ?? '';

    /* $herinnering: haalt de herinneringsinstelling op uit de dropdown
       met name="reminder". Mogelijke waarden zijn:
       - 'none' = geen herinnering (standaardwaarde als niets is geselecteerd)
       - '1_hour' = herinnering 1 uur voor het evenement
       - '1_day' = herinnering 1 dag voor het evenement */
    $herinnering = $_POST['reminder'] ?? 'none';

    /* $externeLink: haalt de optionele URL op uit het invoerveld
       met name="external_link". Bijvoorbeeld een link naar een toernooi-pagina.
       Als er niets is ingevuld, wordt het een lege tekst. */
    $externeLink = $_POST['external_link'] ?? '';

    /* $gedeeldMetStr: haalt de tekst op van het deelveld met name="shared_with_str".
       De gebruiker kan hier namen invoeren gescheiden door komma's,
       bijv. "gebruiker1, gebruiker2". Dit bepaalt wie het evenement kan zien. */
    $gedeeldMetStr = $_POST['shared_with_str'] ?? '';

    /* addEvent() is de hoofdfunctie die het evenement daadwerkelijk opslaat.
       Het ontvangt alle 7 parameters:
       - $userId: het ID van de ingelogde gebruiker (eigenaar van het evenement)
       - $titel: de naam/titel van het evenement
       - $datum: de datum waarop het evenement plaatsvindt
       - $tijd: de tijd waarop het evenement begint
       - $beschrijving: een optionele beschrijving met details
       - $herinnering: de herinneringsinstelling (none/1_hour/1_day)
       - $externeLink: een optionele URL naar een externe pagina
       - $gedeeldMetStr: een komma-gescheiden lijst van gebruikers om mee te delen
       De functie geeft een foutmelding terug als er iets mis is (bijv.
       lege titel, datum in het verleden), of een lege string als alles goed ging. */
    $fout = addEvent($userId, $titel, $datum, $tijd, $beschrijving, $herinnering, $externeLink, $gedeeldMetStr);

    /* Controleer of $fout leeg is (geen fout).
       Het uitroepteken (!) keert de waarde om: als $fout een lege string is,
       is !$fout gelijk aan true, wat betekent dat er GEEN fout was.
       In dat geval is het evenement succesvol toegevoegd. */
    if (!$fout) {
        /* setMessage() slaat een bericht op in de sessie dat op de volgende
           pagina wordt getoond. 'success' is het type (groen Bootstrap-alert),
           en 'Evenement toegevoegd!' is de tekst die de gebruiker ziet. */
        setMessage('success', 'Evenement toegevoegd!');

        /* header("Location: index.php") stuurt de gebruiker door naar de
           hoofdpagina (index.php). Daar ziet de gebruiker het succesbericht
           en het zojuist toegevoegde evenement in de lijst. */
        header("Location: index.php");

        /* exit stopt het script zodat er niets meer wordt uitgevoerd na
           de doorverwijzing. Dit voorkomt ongewenste uitvoer. */
        exit;
    }
    /* Als $fout WEL een waarde heeft (er is een foutmelding), gaat het script
       gewoon door en wordt de pagina opnieuw getoond met de foutmelding.
       De gebruiker kan dan het formulier corrigeren en opnieuw indienen. */
}
?>
<!-- Einde van het PHP-gedeelte. Hieronder begint de HTML-pagina.
     PHP wisselt hier naar de HTML-modus. Alles buiten de PHP-tags
     wordt direct naar de browser gestuurd als HTML. -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5-document is.
     Dit is verplicht en moet altijd de allereerste regel van het HTML-document zijn. -->
<!DOCTYPE html>

<!-- <html lang="nl"> opent het HTML-document.
     lang="nl" geeft aan dat de taal van de pagina Nederlands is.
     Dit helpt zoekmachines en schermlezers om de taal correct te herkennen. -->
<html lang="nl">

<!-- <head> bevat meta-informatie over de pagina die niet zichtbaar is
     voor de gebruiker, zoals de tekenset, viewport, titel en stylesheets. -->

<head>
    <!-- meta charset="UTF-8" stelt de tekencodering in op UTF-8.
         UTF-8 ondersteunt bijna alle tekens en symbolen ter wereld,
         inclusief Nederlandse tekens zoals é, ë, ï, ü. -->
    <meta charset="UTF-8">

    <!-- meta viewport zorgt ervoor dat de pagina goed wordt weergegeven
         op mobiele apparaten. width=device-width past de breedte aan op
         het scherm van het apparaat. initial-scale=1.0 zet het zoomniveau
         op 100% bij het eerste laden. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <title> is de tekst die in het browsertabblad verschijnt.
         "Evenement Toevoegen - GamePlan Scheduler" laat de gebruiker weten
         welke pagina er open staat. -->
    <title>Evenement Toevoegen - GamePlan Scheduler</title>

    <!-- Dit laadt het Bootstrap 5.3.3 CSS-framework via een CDN (Content Delivery Network).
         Bootstrap biedt kant-en-klare CSS-klassen voor knoppen, formulieren,
         kaarten, rasters, kleuren en meer. Het CDN zorgt ervoor dat het snel
         wordt geladen zonder dat het lokaal hoeft te staan. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dit laadt het eigen stylesheet (style.css) met aangepaste stijlen
         die specifiek zijn voor de GamePlan Scheduler applicatie.
         Dit bestand overschrijft of vult de Bootstrap-stijlen aan. -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- <body> bevat alle zichtbare inhoud van de pagina.
     class="bg-dark text-light" zijn Bootstrap-klassen:
     - bg-dark: geeft de achtergrond een donkere kleur (bijna zwart)
     - text-light: maakt alle tekst licht (wit/lichtgrijs)
     Dit creëert het donkere thema dat past bij een gaming applicatie. -->

<body class="bg-dark text-light">

    <!-- include 'header.php' laadt het header-bestand in.
         Dit bevat de navigatiebalk (navbar) bovenaan de pagina met links
         naar andere pagina's. Het wordt op elke pagina hergebruikt. -->
    <?php include 'header.php'; ?>

    <!-- <main> is het hoofdinhoudsgebied van de pagina.
         class="container mt-5 pt-5" zijn Bootstrap-klassen:
         - container: centreert de inhoud en geeft het een maximale breedte
         - mt-5: margin-top 5 (3rem/48px ruimte boven het element)
         - pt-5: padding-top 5 (3rem/48px opvulling boven in het element)
         Samen zorgen mt-5 en pt-5 ervoor dat de inhoud niet achter de
         vaste navigatiebalk (fixed navbar) verdwijnt. -->
    <main class="container mt-5 pt-5">

        <!-- getMessage() haalt een eerder opgeslagen sessiebericht op en toont het.
             Bijvoorbeeld een succesbericht of foutmelding van een vorige actie.
             echo print het resultaat direct in de HTML. -->
        <?php echo getMessage(); ?>

        <!-- Controleer of de variabele $fout een waarde bevat (niet leeg is).
             Als $fout gevuld is met een foutmelding, wordt het alert-blok getoond. -->
        <?php if ($fout): ?>
            <!-- class="alert alert-danger" zijn Bootstrap-klassen:
                 - alert: maakt een opvallend meldingsblok met padding en rand
                 - alert-danger: kleurt het blok rood om een fout aan te geven
                 safeEcho() toont de foutmelding op een veilige manier door
                 speciale HTML-tekens te escapen (bijv. < wordt &lt;).
                 Dit voorkomt XSS-aanvallen (Cross-Site Scripting). -->
            <div class="alert alert-danger"><?php echo safeEcho($fout); ?></div>
        <?php endif; ?>
        <!-- endif sluit het if-blok af. Dit is de alternatieve PHP-syntaxis
             die beter leesbaar is wanneer PHP en HTML door elkaar staan. -->

        <!-- <section> groepeert gerelateerde inhoud.
             class="mb-5" is een Bootstrap-klasse:
             - mb-5: margin-bottom 5 (3rem/48px ruimte onder het element) -->
        <section class="mb-5">

            <!-- De koptekst van de pagina met een doelwit-emoji en de tekst -->
            <h2>🎯 Evenement Toevoegen</h2>

            <!-- class="card" is een Bootstrap-klasse die een kaartcomponent maakt.
                 Een kaart is een container met een rand, schaduw en afgeronde hoeken.
                 Het wordt veel gebruikt om formulieren en content netjes te groeperen. -->
            <div class="card">

                <!-- class="card-body" is de binnenste container van de kaart.
                     Het voegt padding (opvulling) toe aan de inhoud van de kaart
                     zodat de tekst en velden niet tegen de rand aanzitten. -->
                <div class="card-body">

                    <!-- <form> maakt een HTML-formulier aan.
                         method="POST" betekent dat de gegevens via HTTP POST worden
                         verzonden. POST verbergt de gegevens in de body van het verzoek,
                         in tegenstelling tot GET dat gegevens in de URL plaatst.
                         onsubmit="return validateEventForm();" roept een JavaScript-
                         functie aan voordat het formulier wordt verzonden. Als de functie
                         false teruggeeft, wordt het verzenden gestopt (client-side validatie). -->
                    <form method="POST" onsubmit="return validateEventForm();">

                        <!-- ============================================================ -->
                        <!-- VELD 1: EVENEMENT TITEL                                      -->
                        <!-- Dit is een verplicht tekstveld voor de naam van het evenement -->
                        <!-- ============================================================ -->

                        <!-- Evenement titel -->
                        <!-- class="mb-3" is een Bootstrap-klasse:
                             - mb-3: margin-bottom 3 (1rem/16px ruimte onder dit blok)
                             Dit zorgt voor nette verticale ruimte tussen formuliervelden. -->
                        <div class="mb-3">

                            <!-- <label> is een tekst-label dat bij het invoerveld hoort.
                                 for="title" koppelt het label aan het veld met id="title".
                                 Als je op het label klikt, krijgt het invoerveld de focus.
                                 class="form-label" is een Bootstrap-klasse die het label
                                 de juiste opmaak geeft (lettergrootte, kleur, marge). -->
                            <label for="title" class="form-label">📌 Evenement Titel *</label>

                            <!-- <input type="text"> maakt een tekst-invoerveld.
                                 - id="title": uniek identificatienummer voor CSS/JS en het label
                                 - name="title": de sleutel waarmee PHP het veld kan opvragen via $_POST['title']
                                 - class="form-control": Bootstrap-klasse die het veld de volledige
                                   breedte geeft met nette randen, padding en focus-effect
                                 - required: HTML5-attribuut dat voorkomt dat het formulier wordt
                                   verzonden als dit veld leeg is (browser-validatie)
                                 - maxlength="100": beperkt de invoer tot maximaal 100 tekens
                                 - placeholder: voorbeeldtekst die zichtbaar is als het veld leeg is -->
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                placeholder="Fortnite Toernooi, etc.">

                            <!-- <small> maakt de tekst klein.
                                 class="text-secondary" is een Bootstrap-klasse die de tekst
                                 grijs maakt (secundaire kleur).
                                 BUG FIX #1001: verwijst naar de bugfix waarbij titels die
                                 alleen uit spaties bestonden werden geaccepteerd. Nu wordt
                                 gecontroleerd dat de titel echte tekens bevat, niet alleen spaties. -->
                            <small class="text-secondary">Max 100 tekens, mag niet leeg zijn (BUG FIX #1001)</small>
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 2: DATUM                                                -->
                        <!-- Dit is een verplicht datumveld met minimale datum beperking   -->
                        <!-- ============================================================ -->

                        <!-- Datum -->
                        <div class="mb-3">

                            <!-- Label voor het datumveld met een kalender-emoji -->
                            <label for="date" class="form-label">📆 Datum *</label>

                            <!-- <input type="date"> maakt een datumkiezer (date picker) in de browser.
                                 De browser toont een kalender-widget waarmee de gebruiker een
                                 datum kan selecteren. De waarde wordt opgeslagen als JJJJ-MM-DD.
                                 - id="date": uniek ID voor het veld
                                 - name="date": de sleutel voor $_POST['date'] in PHP
                                 - class="form-control": Bootstrap styling voor het invoerveld
                                 - required: het veld mag niet leeg zijn
                                 - min="<?php echo date('Y-m-d'); ?>": dit is de MINIMUM datum
                                   die geselecteerd mag worden. date('Y-m-d') genereert de datum
                                   van vandaag in het formaat JJJJ-MM-DD (bijv. "2025-09-30").
                                   Hierdoor kan de gebruiker geen datum in het verleden kiezen.
                                   BUG FIX #1004: deze beperking is toegevoegd om te voorkomen
                                   dat gebruikers evenementen aanmaken met een datum die al
                                   voorbij is. Zonder deze fix konden gebruikers evenementen
                                   in het verleden aanmaken, wat geen zin heeft. -->
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>">

                            <!-- Hint-tekst die uitlegt dat de datum vandaag of later moet zijn.
                                 BUG FIX #1004 verwijst naar de bugfix voor het accepteren
                                 van datums in het verleden. -->
                            <small class="text-secondary">Moet vandaag of in de toekomst zijn (BUG FIX #1004)</small>
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 3: TIJD                                                 -->
                        <!-- Dit is een verplicht tijdveld voor de starttijd               -->
                        <!-- ============================================================ -->

                        <!-- Tijd -->
                        <div class="mb-3">

                            <!-- Label voor het tijdveld met een klok-emoji -->
                            <label for="time" class="form-label">⏰ Tijd *</label>

                            <!-- <input type="time"> maakt een tijdkiezer (time picker) in de browser.
                                 De browser toont een invoerveld met uren en minuten.
                                 De waarde wordt opgeslagen als UU:MM (bijv. "14:30").
                                 - id="time": uniek ID voor het veld
                                 - name="time": de sleutel voor $_POST['time'] in PHP
                                 - class="form-control": Bootstrap styling
                                 - required: het veld is verplicht en mag niet leeg zijn -->
                            <input type="time" id="time" name="time" class="form-control" required>
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 4: BESCHRIJVING                                         -->
                        <!-- Dit is een optioneel tekstvak voor extra details               -->
                        <!-- ============================================================ -->

                        <!-- Beschrijving -->
                        <div class="mb-3">

                            <!-- Label voor het beschrijvingsveld met een potlood-emoji -->
                            <label for="description" class="form-label">📝 Beschrijving</label>

                            <!-- <textarea> maakt een groter tekstvak waar meerdere regels
                                 tekst kunnen worden ingevoerd (in tegenstelling tot <input type="text">
                                 dat maar één regel toestaat).
                                 - id="description": uniek ID voor het veld
                                 - name="description": de sleutel voor $_POST['description'] in PHP
                                 - class="form-control": Bootstrap styling voor het tekstvak
                                 - rows="3": het tekstvak is 3 regels hoog zichtbaar
                                 - maxlength="500": maximaal 500 tekens toegestaan
                                 - placeholder: voorbeeldtekst die zichtbaar is als het veld leeg is
                                 Let op: dit veld heeft GEEN required attribuut, dus het is optioneel. -->
                            <textarea id="description" name="description" class="form-control" rows="3" maxlength="500"
                                placeholder="Details over het evenement..."></textarea>

                            <!-- Hint-tekst die aangeeft dat het veld maximaal 500 tekens mag bevatten -->
                            <small class="text-secondary">Max 500 tekens</small>
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 5: HERINNERING                                          -->
                        <!-- Een keuzelijst (dropdown) waarmee de gebruiker kan instellen  -->
                        <!-- wanneer ze een herinnering willen ontvangen                   -->
                        <!-- ============================================================ -->

                        <!-- Herinnering -->
                        <div class="mb-3">

                            <!-- Label voor de herinneringsdropdown met een bel-emoji -->
                            <label for="reminder" class="form-label">🔔 Herinnering</label>

                            <!-- <select> maakt een keuzelijst (dropdown-menu).
                                 De gebruiker kan één optie selecteren uit de lijst.
                                 - id="reminder": uniek ID voor het veld
                                 - name="reminder": de sleutel voor $_POST['reminder'] in PHP
                                 - class="form-select": Bootstrap-klasse speciaal voor dropdown-
                                   menu's. Het geeft de dropdown een nette stijl met een pijltje,
                                   goede padding en focus-effect. (Dit is anders dan "form-control"
                                   dat voor tekstvelden wordt gebruikt.) -->
                            <select id="reminder" name="reminder" class="form-select">
                                <!-- Elke <option> is een keuzemogelijkheid in de dropdown.
                                     - value="none": de waarde die naar PHP wordt gestuurd als
                                       deze optie is geselecteerd. "Geen" betekent geen herinnering. -->
                                <option value="none">Geen</option>

                                <!-- value="1_hour": stuurt de tekst "1_hour" naar PHP.
                                     De gebruiker ziet "1 uur ervoor" maar PHP ontvangt "1_hour". -->
                                <option value="1_hour">1 uur ervoor</option>

                                <!-- value="1_day": stuurt de tekst "1_day" naar PHP.
                                     De gebruiker ziet "1 dag ervoor" maar PHP ontvangt "1_day". -->
                                <option value="1_day">1 dag ervoor</option>
                            </select>
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 6: EXTERNE LINK                                         -->
                        <!-- Een optioneel URL-veld voor een link naar een externe pagina  -->
                        <!-- ============================================================ -->

                        <!-- Externe link -->
                        <div class="mb-3">

                            <!-- Label voor het externe link veld met een ketting-emoji -->
                            <label for="external_link" class="form-label">🔗 Externe Link (optioneel)</label>

                            <!-- <input type="url"> maakt een speciaal invoerveld voor URL's.
                                 De browser valideert automatisch of de ingevoerde tekst een
                                 geldig webadres is (moet beginnen met http:// of https://).
                                 - id="external_link": uniek ID voor het veld
                                 - name="external_link": de sleutel voor $_POST['external_link'] in PHP
                                 - class="form-control": Bootstrap styling
                                 - placeholder: toont een voorbeeld-URL als het veld leeg is
                                 Dit veld is NIET verplicht (geen required attribuut). -->
                            <input type="url" id="external_link" name="external_link" class="form-control"
                                placeholder="https://toernooi-pagina.nl">
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 7: GEDEELD MET                                          -->
                        <!-- Een optioneel tekstveld waar gebruikersnamen in kunnen worden -->
                        <!-- ingevoerd, gescheiden door komma's                            -->
                        <!-- ============================================================ -->

                        <!-- Gedeeld met -->
                        <div class="mb-3">

                            <!-- Label voor het deelveld met een ogen-emoji (bekijken) -->
                            <label for="shared_with_str" class="form-label">👀 Gedeeld Met</label>

                            <!-- <input type="text"> maakt een gewoon tekstveld.
                                 - id="shared_with_str": uniek ID voor het veld
                                 - name="shared_with_str": de sleutel voor $_POST['shared_with_str'] in PHP
                                 - class="form-control": Bootstrap styling
                                 - placeholder: toont een voorbeeld met komma-gescheiden gebruikersnamen
                                 De gebruiker voert hier namen in van mensen waarmee ze het
                                 evenement willen delen, gescheiden door komma's. -->
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                placeholder="gebruiker1, gebruiker2">

                            <!-- Hint-tekst die uitlegt waarvoor dit veld dient -->
                            <small class="text-secondary">Wie kan dit evenement zien</small>
                        </div>

                        <!-- ============================================================ -->
                        <!-- KNOPPEN: TOEVOEGEN EN ANNULEREN                              -->
                        <!-- ============================================================ -->

                        <!-- <button type="submit"> maakt een verzendknop voor het formulier.
                             Als de gebruiker hierop klikt, wordt het formulier verzonden via POST.
                             - class="btn btn-success btn-lg" zijn Bootstrap-klassen:
                               - btn: basisstijl voor een knop (padding, rand, cursor)
                               - btn-success: groene achtergrondkleur (betekent "succes/positief")
                               - btn-lg: maakt de knop groter dan normaal (large size) -->
                        <button type="submit" class="btn btn-success btn-lg">🎯 Evenement Toevoegen</button>

                        <!-- <a> is een hyperlink die er uitziet als een knop dankzij Bootstrap-klassen.
                             href="index.php" linkt terug naar de hoofdpagina.
                             - class="btn btn-secondary btn-lg" zijn Bootstrap-klassen:
                               - btn: basisstijl voor een knop
                               - btn-secondary: grijze achtergrondkleur (secundaire/neutrale actie)
                               - btn-lg: maakt de knop groter dan normaal
                             De pijl-emoji geeft visueel aan dat het een "terug" actie is. -->
                        <a href="index.php" class="btn btn-secondary btn-lg">↩️ Annuleren</a>

                        <!-- </form> sluit het formulier af. Alle invoervelden hierboven
                         worden verzameld en samen verstuurd wanneer de submit-knop
                         wordt ingedrukt. -->
                    </form>
                </div>
                <!-- Einde van card-body: de binnenste container van de kaart -->
            </div>
            <!-- Einde van card: de kaartcontainer met rand en schaduw -->
        </section>
        <!-- Einde van de sectie: het hoofdformuliergebied -->
    </main>
    <!-- Einde van main: het hoofdinhoudsgebied van de pagina -->

    <!-- include 'footer.php' laadt het footer-bestand in.
         Dit bevat de voettekst onderaan de pagina, zoals copyright-informatie.
         Het wordt op elke pagina hergebruikt voor consistentie. -->
    <?php include 'footer.php'; ?>

    <!-- Dit laadt het Bootstrap JavaScript-bestand via een CDN.
         bootstrap.bundle.min.js bevat zowel Bootstrap JS als Popper.js.
         Dit is nodig voor interactieve Bootstrap-componenten zoals
         dropdowns, modals, tooltips en de responsieve navigatiebalk.
         Het .min in de bestandsnaam betekent dat het bestand is verkleind
         (minified) voor snellere laadtijden. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dit laadt het eigen JavaScript-bestand (script.js) van de applicatie.
         Dit bevat aangepaste functies zoals validateEventForm() die wordt
         aangeroepen bij het verzenden van het formulier (onsubmit).
         Het staat onder het Bootstrap-script zodat Bootstrap eerst wordt geladen. -->
    <script src="script.js"></script>
</body>
<!-- Einde van body: alle zichtbare inhoud van de pagina -->

</html>
<!-- Einde van het HTML-document -->