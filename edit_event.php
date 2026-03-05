<?php
/**
 * ==========================================================================
 * EDIT_EVENT.PHP - EVENEMENT BEWERKEN PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bewerk een bestaand gaming evenement met alle velden en validatie.
 *
 * Gebruikersverhaal: "Voeg evenementen toe zoals toernooien"
 *
 * --------------------------------------------------------------------------
 * HOE DEZE PAGINA WERKT (samenvatting):
 * --------------------------------------------------------------------------
 * 1. Het evenement-ID wordt opgehaald uit de URL (?id=123).
 * 2. Er wordt gecontroleerd of het ID een geldig nummer is.
 * 3. Het bijbehorende evenement wordt opgezocht in de lijst van de gebruiker.
 * 4. Het formulier wordt vooraf ingevuld met de bestaande gegevens.
 * 5. Bij verzending worden de gewijzigde gegevens opgeslagen via editEvent().
 * 6. Bij succes wordt de gebruiker doorgestuurd naar de hoofdpagina.
 * ==========================================================================
 */

/* require_once laadt het bestand 'functions.php' in, maar slechts één keer.
   Dit bestand bevat alle helperfuncties zoals isLoggedIn(), getUserId(),
   editEvent(), getEvents(), setMessage(), getMessage(), safeEcho(), enz.
   'require_once' zorgt ervoor dat het bestand niet dubbel wordt geladen,
   zelfs als het meerdere keren wordt aangeroepen. Als het bestand niet
   gevonden wordt, stopt het script met een fatale fout. */
require_once 'functions.php';

/* checkSessionTimeout() controleert of de sessie van de gebruiker verlopen is.
   Als de gebruiker te lang inactief is geweest, wordt de sessie beëindigd
   en wordt de gebruiker uitgelogd. Dit is een beveiligingsmaatregel om te
   voorkomen dat iemand anders de computer gebruikt na een lange pauze. */
checkSessionTimeout();

/* isLoggedIn() controleert of de gebruiker momenteel is ingelogd.
   Het kijkt of er een geldige sessievariabele bestaat voor de gebruiker.
   Het uitroepteken (!) keert de waarde om: als de gebruiker NIET is ingelogd,
   dan is !isLoggedIn() waar (true), en wordt de code in het if-blok uitgevoerd.
   Alleen ingelogde gebruikers mogen evenementen bewerken. */
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
   uit de sessie. Dit ID wordt later gebruikt om te controleren welke
   evenementen bij deze gebruiker horen en om de bewerking op te slaan. */
$userId = getUserId();

/* $_GET is een superglobale array in PHP die alle parameters bevat die
   in de URL staan (na het vraagteken). Bijvoorbeeld: edit_event.php?id=5
   geeft $_GET['id'] de waarde "5".
   De ?? operator (null coalescing operator) geeft een standaardwaarde
   als de sleutel 'id' niet bestaat in de URL. In dat geval wordt $id
   ingesteld op 0 (nul), wat later als ongeldig wordt herkend.
   Dit is hoe de pagina weet WELK evenement er bewerkt moet worden. */
$id = $_GET['id'] ?? 0;

/* is_numeric() controleert of de waarde in $id een geldig nummer is.
   Dit is een belangrijke beveiligingscontrole die voorkomt dat kwaadwillende
   gebruikers niet-numerieke waarden (zoals SQL-injectiepogingen of willekeurige
   tekst) meegeven in de URL. Voorbeelden:
   - is_numeric("5") geeft true (geldig)
   - is_numeric("abc") geeft false (ongeldig)
   - is_numeric("5; DROP TABLE") geeft false (ongeldig, mogelijke aanval)
   Het uitroepteken (!) keert de waarde om: als het NIET numeriek is,
   wordt de gebruiker teruggestuurd naar de hoofdpagina. */
if (!is_numeric($id)) {
    /* Stuur de gebruiker terug naar de hoofdpagina als het ID ongeldig is.
       Er wordt geen foutmelding getoond, de gebruiker wordt gewoon weggestuurd. */
    header("Location: index.php");
    /* Stop het script onmiddellijk na de doorverwijzing. */
    exit;
}

/* Haal het evenement op:
   getEvents($userId) haalt ALLE evenementen op die bij de ingelogde
   gebruiker horen. Dit geeft een array (lijst) van evenementen terug.
   Elk evenement is zelf ook een array met sleutels zoals 'event_id',
   'title', 'date', 'time', 'description', 'reminder', 'external_link',
   en 'shared_with'. */
$evenementen = getEvents($userId);

/* array_filter() filtert de lijst van evenementen om alleen het evenement
   te vinden dat overeenkomt met het opgegeven ID.

   Hoe array_filter() werkt:
   - Het doorloopt elk element ($e) in de array $evenementen.
   - Voor elk element voert het de anonieme functie (callback) uit.
   - De functie vergelijkt $e['event_id'] met $id.
   - Als de vergelijking true oplevert, wordt het element BEHOUDEN.
   - Als de vergelijking false oplevert, wordt het element VERWIJDERD.

   'use ($id)' maakt de variabele $id beschikbaar binnen de anonieme functie.
   Zonder 'use' zou de functie geen toegang hebben tot $id, omdat anonieme
   functies in PHP standaard geen toegang hebben tot buitenliggende variabelen.

   De == operator vergelijkt de waarden, waarbij PHP automatisch typeconversie
   toepast (bijv. de string "5" is gelijk aan het getal 5).

   Het resultaat is een gefilterde array die alleen het evenement bevat
   met het juiste event_id (of een lege array als het niet is gevonden). */
$evenement = array_filter($evenementen, function ($e) use ($id) {
    return $e['event_id'] == $id;
});

/* reset() verplaatst de interne aanwijzer van de array naar het eerste element
   en geeft de waarde daarvan terug. Dit is nodig omdat array_filter() de
   originele array-sleutels behoudt. Als het evenement op index 3 stond,
   blijft het op index 3 in de gefilterde array. reset() haalt gewoon het
   eerste (en enige) element op, ongeacht de index.
   Als de array leeg is (evenement niet gevonden), geeft reset() false terug. */
$evenement = reset($evenement);

/* Controleer of $evenement een geldige waarde heeft.
   Als $evenement false is (het evenement is niet gevonden), wordt de gebruiker
   teruggestuurd naar de hoofdpagina met een foutmelding.
   Het uitroepteken (!) keert de waarde om: als er GEEN evenement is gevonden,
   wordt het if-blok uitgevoerd. */
if (!$evenement) {
    /* setMessage() slaat een foutbericht op in de sessie.
       'danger' is het type (rode Bootstrap-alert), en de tekst vertelt de
       gebruiker dat het evenement niet is gevonden. Dit bericht wordt getoond
       op de volgende pagina (index.php) nadat de doorverwijzing plaatsvindt. */
    setMessage('danger', 'Evenement niet gevonden.');
    /* Stuur de gebruiker door naar de hoofdpagina. */
    header("Location: index.php");
    /* Stop het script onmiddellijk. */
    exit;
}

/* $fout is een variabele die begint als een lege tekst (string).
   Als er later een fout optreedt bij het bewerken van het evenement,
   wordt deze variabele gevuld met de foutmelding. Als $fout leeg blijft,
   betekent dat dat alles goed is gegaan. */
$fout = '';

/* Verwerk formulier verzending:
   $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode waarmee de pagina
   is opgevraagd. 'GET' betekent dat de pagina gewoon is geladen (het
   formulier wordt getoond met de bestaande gegevens). 'POST' betekent
   dat het formulier is verzonden (de gebruiker heeft op "Bijwerken" geklikt).
   We controleren of de methode gelijk is aan 'POST'. */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* $_POST is een superglobale array in PHP die alle formuliergegevens bevat
       die via de POST-methode zijn verzonden.
       De ?? operator (null coalescing operator) geeft een standaardwaarde
       als de sleutel niet bestaat in $_POST. */

    /* $titel: haalt de (mogelijk gewijzigde) evenementtitel op uit het formulier.
       Het veld met name="title" stuurt de waarde naar $_POST['title']. */
    $titel = $_POST['title'] ?? '';

    /* $datum: haalt de (mogelijk gewijzigde) datum op uit het formulier.
       Het formaat is JJJJ-MM-DD (jaar-maand-dag). */
    $datum = $_POST['date'] ?? '';

    /* $tijd: haalt de (mogelijk gewijzigde) tijd op uit het formulier.
       Het formaat is UU:MM (uur:minuut). */
    $tijd = $_POST['time'] ?? '';

    /* $beschrijving: haalt de (mogelijk gewijzigde) beschrijving op uit het formulier.
       Dit veld is optioneel en mag leeg zijn. */
    $beschrijving = $_POST['description'] ?? '';

    /* $herinnering: haalt de (mogelijk gewijzigde) herinneringsinstelling op.
       Mogelijke waarden: 'none', '1_hour', '1_day'. Standaard is 'none'. */
    $herinnering = $_POST['reminder'] ?? 'none';

    /* $externeLink: haalt de (mogelijk gewijzigde) externe URL op uit het formulier.
       Als er niets is ingevuld, wordt het een lege tekst. */
    $externeLink = $_POST['external_link'] ?? '';

    /* $gedeeldMetStr: haalt de (mogelijk gewijzigde) lijst van gedeelde
       gebruikers op als komma-gescheiden tekst. */
    $gedeeldMetStr = $_POST['shared_with_str'] ?? '';

    /* editEvent() is de hoofdfunctie die het bestaande evenement bijwerkt.
       Het ontvangt 8 parameters (één meer dan addEvent, namelijk het event-ID):
       - $userId: het ID van de ingelogde gebruiker (eigenaar van het evenement)
       - $id: het unieke ID van het evenement dat wordt bewerkt (uit de URL)
       - $titel: de (gewijzigde) titel van het evenement
       - $datum: de (gewijzigde) datum waarop het evenement plaatsvindt
       - $tijd: de (gewijzigde) tijd waarop het evenement begint
       - $beschrijving: de (gewijzigde) beschrijving
       - $herinnering: de (gewijzigde) herinneringsinstelling
       - $externeLink: de (gewijzigde) externe URL
       - $gedeeldMetStr: de (gewijzigde) lijst van gebruikers om mee te delen
       De functie zoekt het evenement op basis van $id, past de wijzigingen toe,
       en slaat het op. Het geeft een foutmelding terug als er iets mis is,
       of een lege string als alles goed ging. */
    $fout = editEvent($userId, $id, $titel, $datum, $tijd, $beschrijving, $herinnering, $externeLink, $gedeeldMetStr);

    /* Controleer of $fout leeg is (geen fout).
       Het uitroepteken (!) keert de waarde om: als $fout een lege string is,
       is !$fout gelijk aan true, wat betekent dat er GEEN fout was.
       In dat geval is het evenement succesvol bijgewerkt. */
    if (!$fout) {
        /* setMessage() slaat een succesbericht op in de sessie.
           'success' is het type (groen Bootstrap-alert), en 'Evenement bijgewerkt!'
           is de tekst die de gebruiker ziet op de volgende pagina. */
        setMessage('success', 'Evenement bijgewerkt!');

        /* header("Location: index.php") stuurt de gebruiker door naar de
           hoofdpagina (index.php). Daar ziet de gebruiker het succesbericht
           en het zojuist bijgewerkte evenement met de nieuwe gegevens. */
        header("Location: index.php");

        /* exit stopt het script zodat er niets meer wordt uitgevoerd na
           de doorverwijzing. Dit voorkomt ongewenste uitvoer. */
        exit;
    }
    /* Als $fout WEL een waarde heeft (er is een foutmelding), gaat het script
       gewoon door en wordt de pagina opnieuw getoond met de foutmelding.
       Het formulier bevat nog steeds de bestaande gegevens, zodat de
       gebruiker de fout kan corrigeren en opnieuw kan indienen. */
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
         "Evenement Bewerken - GamePlan Scheduler" laat de gebruiker weten
         welke pagina er open staat. -->
    <title>Evenement Bewerken - GamePlan Scheduler</title>

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

            <!-- De koptekst van de pagina met een potlood-emoji en de tekst -->
            <h2>✏️ Evenement Bewerken</h2>

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
                         verzonden wanneer de gebruiker op "Bijwerken" klikt.
                         POST verbergt de gegevens in de body van het verzoek.
                         LET OP: er is geen action-attribuut, dus het formulier wordt
                         naar dezelfde URL verzonden (inclusief ?id=...), zodat de
                         PHP-code bovenaan het ID kan uitlezen en het evenement kan bijwerken.
                         onsubmit="return validateEventForm();" roept een JavaScript-
                         functie aan voordat het formulier wordt verzonden. Als de functie
                         false teruggeeft, wordt het verzenden gestopt (client-side validatie). -->
                    <form method="POST" onsubmit="return validateEventForm();">

                        <!-- ============================================================ -->
                        <!-- VELD 1: EVENEMENT TITEL (vooraf ingevuld)                    -->
                        <!-- Het value-attribuut vult het veld met de bestaande titel      -->
                        <!-- ============================================================ -->

                        <!-- Evenement titel -->
                        <!-- class="mb-3" is een Bootstrap-klasse:
                             - mb-3: margin-bottom 3 (1rem/16px ruimte onder dit blok)
                             Dit zorgt voor nette verticale ruimte tussen formuliervelden. -->
                        <div class="mb-3">

                            <!-- <label> is een tekst-label dat bij het invoerveld hoort.
                                 for="title" koppelt het label aan het veld met id="title".
                                 class="form-label" is een Bootstrap-klasse voor labels. -->
                            <label for="title" class="form-label">📌 Titel *</label>

                            <!-- <input type="text"> maakt een tekst-invoerveld.
                                 - id="title": uniek ID voor het veld
                                 - name="title": de sleutel voor $_POST['title'] in PHP
                                 - class="form-control": Bootstrap styling met volle breedte
                                 - required: het veld is verplicht
                                 - maxlength="100": maximaal 100 tekens
                                 - value="...": BELANGRIJK - dit is hoe het formulier wordt
                                   VOORAF INGEVULD met de bestaande gegevens!
                                   $evenement['title'] bevat de huidige titel van het evenement.
                                   safeEcho() escaped speciale HTML-tekens om XSS te voorkomen.
                                   Bijv. als de titel 'Game "Night"' is, wordt het veilig weergegeven
                                   als 'Game &quot;Night&quot;' in de HTML, maar de gebruiker ziet
                                   gewoon 'Game "Night"' in het invoerveld. -->
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                value="<?php echo safeEcho($evenement['title']); ?>">
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 2: DATUM (vooraf ingevuld met bestaande datum)           -->
                        <!-- Het min-attribuut voorkomt dat datums in het verleden gekozen -->
                        <!-- worden, het value-attribuut toont de bestaande datum           -->
                        <!-- ============================================================ -->

                        <!-- Datum -->
                        <div class="mb-3">

                            <!-- Label voor het datumveld met een kalender-emoji -->
                            <label for="date" class="form-label">📆 Datum *</label>

                            <!-- <input type="date"> maakt een datumkiezer in de browser.
                                 - id="date": uniek ID voor het veld
                                 - name="date": de sleutel voor $_POST['date'] in PHP
                                 - class="form-control": Bootstrap styling
                                 - required: het veld is verplicht
                                 - min="<?php echo date('Y-m-d'); ?>": de MINIMUM selecteerbare
                                   datum is vandaag. date('Y-m-d') genereert de huidige datum
                                   in JJJJ-MM-DD formaat. Datums in het verleden zijn geblokkeerd.
                                 - value="...": vult het veld vooraf in met de bestaande datum
                                   van het evenement. $evenement['date'] bevat bijv. "2025-12-25".
                                   Hierdoor ziet de gebruiker direct welke datum er nu staat
                                   en kan deze indien nodig wijzigen. -->
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>" value="<?php echo safeEcho($evenement['date']); ?>">
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 3: TIJD (vooraf ingevuld met bestaande tijd)             -->
                        <!-- ============================================================ -->

                        <!-- Tijd -->
                        <div class="mb-3">

                            <!-- Label voor het tijdveld met een klok-emoji -->
                            <label for="time" class="form-label">⏰ Tijd *</label>

                            <!-- <input type="time"> maakt een tijdkiezer in de browser.
                                 - id="time": uniek ID voor het veld
                                 - name="time": de sleutel voor $_POST['time'] in PHP
                                 - class="form-control": Bootstrap styling
                                 - required: het veld is verplicht
                                 - value="...": vult het veld vooraf in met de bestaande tijd
                                   van het evenement. $evenement['time'] bevat bijv. "14:30".
                                   De gebruiker ziet direct de huidige tijd en kan deze wijzigen. -->
                            <input type="time" id="time" name="time" class="form-control" required
                                value="<?php echo safeEcho($evenement['time']); ?>">
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 4: BESCHRIJVING (vooraf ingevuld met bestaande tekst)   -->
                        <!-- Bij textarea staat de vooraf ingevulde tekst TUSSEN de tags   -->
                        <!-- (niet in een value-attribuut zoals bij input)                 -->
                        <!-- ============================================================ -->

                        <!-- Beschrijving -->
                        <div class="mb-3">

                            <!-- Label voor het beschrijvingsveld met een potlood-emoji -->
                            <label for="description" class="form-label">📝 Beschrijving</label>

                            <!-- <textarea> maakt een groter tekstvak voor meerdere regels tekst.
                                 - id="description": uniek ID voor het veld
                                 - name="description": de sleutel voor $_POST['description'] in PHP
                                 - class="form-control": Bootstrap styling
                                 - rows="3": het tekstvak is 3 regels hoog
                                 - maxlength="500": maximaal 500 tekens
                                 BELANGRIJK: Bij een <textarea> wordt de vooraf ingevulde tekst
                                 NIET via een value-attribuut geplaatst (dat werkt niet bij textarea).
                                 In plaats daarvan staat de tekst TUSSEN de openings- en sluitingstag:
                                 <textarea>TEKST HIER</textarea>
                                 $evenement['description'] bevat de bestaande beschrijving.
                                 safeEcho() escaped speciale tekens voor veiligheid. -->
                            <textarea id="description" name="description" class="form-control" rows="3"
                                maxlength="500"><?php echo safeEcho($evenement['description']); ?></textarea>
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 5: HERINNERING (dropdown met voorgeselecteerde waarde)  -->
                        <!-- Het 'selected' attribuut bepaalt welke optie actief is        -->
                        <!-- ============================================================ -->

                        <!-- Herinnering -->
                        <div class="mb-3">

                            <!-- Label voor de herinneringsdropdown met een bel-emoji -->
                            <label for="reminder" class="form-label">🔔 Herinnering</label>

                            <!-- <select> maakt een keuzelijst (dropdown-menu).
                                 - id="reminder": uniek ID voor het veld
                                 - name="reminder": de sleutel voor $_POST['reminder'] in PHP
                                 - class="form-select": Bootstrap-klasse speciaal voor dropdown-
                                   menu's, met een pijltje, nette stijl en focus-effect.

                                 HOE DE VOORAF GESELECTEERDE OPTIE WERKT:
                                 Bij elke <option> staat een PHP if-statement dat controleert of
                                 de waarde van die optie overeenkomt met de opgeslagen herinnering.
                                 Als $evenement['reminder'] gelijk is aan de value van een optie,
                                 wordt het woord 'selected' toegevoegd aan die optie.
                                 Het HTML-attribuut 'selected' vertelt de browser: "dit is de
                                 optie die standaard geselecteerd moet zijn in de dropdown".
                                 Slechts één optie kan tegelijk 'selected' zijn. -->
                            <select id="reminder" name="reminder" class="form-select">

                                <!-- Optie "Geen" (value="none").
                                     De PHP-code controleert: als $evenement['reminder'] exact
                                     gelijk is aan 'none' (=== doet een stricte vergelijking op
                                     zowel waarde als type), dan wordt 'selected' geprint.
                                     Dit zorgt ervoor dat "Geen" voorgeselecteerd is als het
                                     evenement geen herinnering had. -->
                                <option value="none" <?php if ($evenement['reminder'] === 'none')
                                    echo 'selected'; ?>>
                                    Geen</option>

                                <!-- Optie "1 uur ervoor" (value="1_hour").
                                     De PHP-code controleert: als $evenement['reminder'] exact
                                     gelijk is aan '1_hour', dan wordt 'selected' geprint.
                                     Dit zorgt ervoor dat "1 uur ervoor" voorgeselecteerd is
                                     als het evenement een herinnering van 1 uur had. -->
                                <option value="1_hour" <?php if ($evenement['reminder'] === '1_hour')
                                    echo 'selected'; ?>>
                                    1 uur ervoor</option>

                                <!-- Optie "1 dag ervoor" (value="1_day").
                                     De PHP-code controleert: als $evenement['reminder'] exact
                                     gelijk is aan '1_day', dan wordt 'selected' geprint.
                                     Dit zorgt ervoor dat "1 dag ervoor" voorgeselecteerd is
                                     als het evenement een herinnering van 1 dag had. -->
                                <option value="1_day" <?php if ($evenement['reminder'] === '1_day')
                                    echo 'selected'; ?>>
                                    1 dag ervoor</option>
                            </select>
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 6: EXTERNE LINK (vooraf ingevuld met bestaande URL)     -->
                        <!-- ============================================================ -->

                        <!-- Externe link -->
                        <div class="mb-3">

                            <!-- Label voor het externe link veld met een ketting-emoji -->
                            <label for="external_link" class="form-label">🔗 Externe Link</label>

                            <!-- <input type="url"> maakt een invoerveld speciaal voor URL's.
                                 De browser valideert automatisch of het een geldig webadres is.
                                 - id="external_link": uniek ID voor het veld
                                 - name="external_link": de sleutel voor $_POST['external_link'] in PHP
                                 - class="form-control": Bootstrap styling
                                 - value="...": vult het veld vooraf in met de bestaande externe
                                   link. $evenement['external_link'] bevat bijv. "https://toernooi.nl".
                                   safeEcho() escaped speciale tekens voor veiligheid. -->
                            <input type="url" id="external_link" name="external_link" class="form-control"
                                value="<?php echo safeEcho($evenement['external_link']); ?>">
                        </div>

                        <!-- ============================================================ -->
                        <!-- VELD 7: GEDEELD MET (vooraf ingevuld met bestaande namen)    -->
                        <!-- ============================================================ -->

                        <!-- Gedeeld met -->
                        <div class="mb-3">

                            <!-- Label voor het deelveld met een ogen-emoji (bekijken) -->
                            <label for="shared_with_str" class="form-label">👀 Gedeeld Met</label>

                            <!-- <input type="text"> maakt een gewoon tekstveld.
                                 - id="shared_with_str": uniek ID voor het veld
                                 - name="shared_with_str": de sleutel voor $_POST['shared_with_str'] in PHP
                                 - class="form-control": Bootstrap styling
                                 - value="...": vult het veld vooraf in met de bestaande lijst
                                   van gedeelde gebruikers. $evenement['shared_with'] bevat bijv.
                                   "gebruiker1, gebruiker2". safeEcho() escaped speciale tekens.
                                   De gebruiker ziet direct met wie het evenement nu wordt gedeeld
                                   en kan namen toevoegen of verwijderen. -->
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                value="<?php echo safeEcho($evenement['shared_with']); ?>">

                            <!-- Hint-tekst die uitlegt waarvoor dit veld dient.
                                 class="text-secondary" maakt de tekst grijs (Bootstrap). -->
                            <small class="text-secondary">Wie kan dit evenement zien</small>
                        </div>

                        <!-- ============================================================ -->
                        <!-- KNOPPEN: BIJWERKEN EN ANNULEREN                              -->
                        <!-- ============================================================ -->

                        <!-- <button type="submit"> maakt een verzendknop voor het formulier.
                             Als de gebruiker hierop klikt, worden alle formuliergegevens
                             via POST naar dezelfde pagina verzonden.
                             - class="btn btn-primary" zijn Bootstrap-klassen:
                               - btn: basisstijl voor een knop (padding, rand, cursor)
                               - btn-primary: blauwe achtergrondkleur (primaire actie)
                             De diskette-emoji geeft visueel aan dat het een opslaan-actie is. -->
                        <button type="submit" class="btn btn-primary">💾 Bijwerken</button>

                        <!-- <a> is een hyperlink die er uitziet als een knop dankzij Bootstrap-klassen.
                             href="index.php" linkt terug naar de hoofdpagina zonder wijzigingen op te slaan.
                             - class="btn btn-secondary" zijn Bootstrap-klassen:
                               - btn: basisstijl voor een knop
                               - btn-secondary: grijze achtergrondkleur (secundaire/neutrale actie)
                             De pijl-emoji geeft visueel aan dat het een "terug" actie is. -->
                        <a href="index.php" class="btn btn-secondary">↩️ Annuleren</a>

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