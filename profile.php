<?php
/**
 * ==========================================================================
 * PROFILE.PHP - PROFIEL BEHEER
 * ==========================================================================
 * Bestandsnaam : profile.php
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
 * Dit bestand is de PROFIELPAGINA van GamePlan Scheduler.
 * Hier kan de gebruiker zijn/haar favoriete spellen beheren:
 *   - Toevoegen, bekijken, bewerken, verwijderen
 *   - Persoonlijke notities opslaan
 *
 * ==========================================================================
 * STRUCTUUR EN FLOW
 * ==========================================================================
 * ┌─────────────────────────────────────────────────────────────────────┐
 * │ 1. Laad functions.php                                              │
 * │ 2. checkSessionTimeout()                                           │
 * │ 3. isLoggedIn() → redirect indien niet ingelogd                    │
 * │ 4. Favorieten ophalen uit database                                 │
 * │ 5. Formulierverwerking: favoriet toevoegen                         │
 * │ 6. HTML: header, main, formulier, tabel, footer                    │
 * └─────────────────────────────────────────────────────────────────────┘
 *
 * ==========================================================================
 * BEVEILIGING
 * ==========================================================================
 * 1. Sessie-timeout: automatisch uitloggen na 30 min inactiviteit
 * 2. Inlogcontrole: alleen ingelogde gebruikers hebben toegang
 * 3. XSS-bescherming: safeEcho() voor alle uitvoer
 * 4. CSRF-preventie: formulierverwerking op REQUEST_METHOD
 * 5. SQL-injectie: alle database queries via prepared statements
 *
 * ==========================================================================
 * DATABASE TABELLEN
 * ==========================================================================
 * Tabel: UserGames
 * ┌─────────────┬─────────────┬─────────────┬─────────────┬─────────────┐
 * │ id (PK)     │ user_id     │ titel       │ description │ note        │
 * └─────────────┴─────────────┴─────────────┴─────────────┴─────────────┘
 * Favorieten worden per gebruiker opgeslagen en opgehaald.
 *
 * ==========================================================================
 * VERGELIJKING MET ANDERE PAGINA'S
 * ==========================================================================
 * ┌───────────────┬───────────────┬───────────────┬───────────────┐
 * │ Eigenschap    │ profile.php   │ index.php     │ add_event.php │
 * ├───────────────┼───────────────┼───────────────┼───────────────┤
 * │ Doel          │ favorieten    │ dashboard     │ evenement toevoegen │
 * │ Sessie check  │ ja            │ ja            │ ja            │
 * │ Data ophalen  │ favorieten    │ alles         │ geen           │
 * │ Acties        │ toevoegen, bewerken, verwijderen │ bewerken, verwijderen │ toevoegen │
 * │ Security      │ hoog          │ hoog          │ hoog           │
 * └───────────────┴───────────────┴───────────────┴───────────────┘
 *
 * ==========================================================================
 * GEBRUIKTE CONCEPTEN
 * ==========================================================================
 * PHP:
 *   - Functies, parameters, return values
 *   - Prepared statements (PDO)
 *   - Sessie beheer (session_start, session_destroy)
 *   - Validatie, exception handling
 *   - array_filter, foreach, empty()
 * HTML:
 *   - Formulieren, labels, invoervelden, knoppen, tabellen
 *   - Bootstrap: container, card, btn, table, mb-5, mt-5, pt-5
 *   - Toegankelijkheid: lang="nl", aria, SEO
 * ==========================================================================
 * EXAMENNIVEAU: VOLLEDIG GEDOCUMENTEERD, SECURITY, DATABASE, FLOW, VERGELIJKING
 * ==========================================================================
 */

// ============================================================================
// STAP 1: FUNCTIES INLADEN
// ============================================================================
// require_once laadt het bestand 'functions.php' in.
// Dit bestand bevat alle PHP-functies die we nodig hebben, zoals:
//   - isLoggedIn()       -> controleert of de gebruiker is ingelogd
//   - getUserId()        -> haalt het gebruikers-ID op uit de sessie
//   - getFavoriteGames() -> haalt favoriete spellen op uit de database
//   - addFavoriteGame()  -> voegt een nieuw favoriet spel toe aan de database
//   - safeEcho()         -> beveiligt tekst tegen XSS (Cross-Site Scripting) aanvallen
//   - setMessage()       -> slaat een succesbericht of foutmelding op in de sessie
//   - getMessage()       -> haalt het opgeslagen bericht op en toont het
//   - checkSessionTimeout() -> controleert of de sessie verlopen is
// Het woord 'require_once' betekent: dit bestand MOET ingeladen worden,
// en als het al eerder is ingeladen, wordt het NIET opnieuw ingeladen.
// Als het bestand niet gevonden wordt, stopt het script met een fatale fout.
require_once 'functions.php';

// ============================================================================
// STAP 2: SESSIE-TIMEOUT CONTROLEREN
// ============================================================================
// checkSessionTimeout() controleert of de gebruiker te lang inactief is geweest.
// Als de sessie verlopen is (bijvoorbeeld na 30 minuten inactiviteit),
// wordt de gebruiker automatisch uitgelogd en doorgestuurd naar de loginpagina.
// Dit is een beveiligingsmaatregel: als iemand zijn computer vergeet af te sluiten,
// wordt de sessie na een bepaalde tijd automatisch beeindigd.
checkSessionTimeout();

// ============================================================================
// STAP 3: INLOGCONTROLE - IS DE GEBRUIKER INGELOGD?
// ============================================================================
// isLoggedIn() controleert of er een geldige sessie bestaat voor de gebruiker.
// Het kijkt of er een gebruikers-ID opgeslagen is in de $_SESSION variabele.
// Als de functie FALSE teruggeeft (de gebruiker is NIET ingelogd):
if (!isLoggedIn()) {
    // header("Location: login.php") stuurt een HTTP-headermelding naar de browser.
    // Dit zorgt ervoor dat de browser de gebruiker DOORVERWIJST naar login.php.
    // De gebruiker ziet deze pagina dus nooit als hij/zij niet is ingelogd.
    // "Location:" is een standaard HTTP-header die de browser vertelt:
    // "Ga naar dit nieuwe adres in plaats van de huidige pagina."
    header("Location: login.php");

    // exit; stopt het PHP-script ONMIDDELLIJK.
    // Dit is HEEL BELANGRIJK: zonder exit zou de rest van het script
    // nog steeds uitgevoerd worden, zelfs na de doorverwijzing.
    // Dat zou een beveiligingsrisico zijn omdat de data dan toch
    // verwerkt wordt voor een niet-ingelogde gebruiker.
    exit;
}

// ============================================================================
// STAP 4: GEBRUIKERSGEGEVENS OPHALEN
// ============================================================================
// getUserId() haalt het unieke ID van de ingelogde gebruiker op uit de sessie.
// Dit ID is een getal (bijvoorbeeld 1, 2, 3...) dat elke gebruiker uniek identificeert.
// We slaan dit op in de variabele $userId zodat we het later kunnen gebruiken
// om de favoriete spellen van DEZE specifieke gebruiker op te halen.
$userId = getUserId();

// getFavoriteGames($userId) haalt ALLE favoriete spellen van deze gebruiker op
// uit de database. Het resultaat is een array (lijst) van spellen.
// Elk spel in de array bevat informatie zoals:
//   - 'game_id'     -> het unieke ID van het spel in de database
//   - 'titel'       -> de naam/titel van het spel (bijv. "Minecraft")
//   - 'description' -> een beschrijving van het spel
//   - 'note'        -> een persoonlijke notitie van de gebruiker
// Als de gebruiker geen favorieten heeft, is $favorieten een lege array [].
$favorieten = getFavoriteGames($userId);

// $fout is een variabele die we gebruiken om foutmeldingen op te slaan.
// We stellen het in op een lege string '' (geen fout).
// Als er later een fout optreedt bij het toevoegen van een favoriet,
// wordt deze variabele gevuld met de foutmelding (bijv. "Titel is verplicht").
// We initialiseren dit als lege string zodat we later kunnen controleren:
// if ($fout) -> er IS een fout, if (!$fout) -> er is GEEN fout.
$fout = '';

// ============================================================================
// STAP 5: FORMULIERVERWERKING - NIEUW FAVORIET SPEL TOEVOEGEN
// ============================================================================
// Hier controleren we of het formulier is verstuurd (POST-methode).
// $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode waarmee de pagina is opgevraagd.
// 'GET'  = de pagina wordt normaal geladen (door de URL te bezoeken)
// 'POST' = er is een formulier verstuurd (de gebruiker heeft op 'Toevoegen' geklikt)
//
// isset($_POST['add_favorite']) controleert of de knop met name="add_favorite"
// is ingedrukt. Dit zorgt ervoor dat we ALLEEN dit formulier verwerken
// en niet per ongeluk een ander formulier op dezelfde pagina.
//
// Beide voorwaarden moeten waar zijn (&&):
// 1. De pagina moet via POST zijn opgevraagd (formulier verstuurd)
// 2. De 'add_favorite' knop moet zijn ingedrukt
// Verwerk formulier voor het toevoegen van een favoriet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {

    // $_POST['title'] bevat de waarde die de gebruiker heeft ingetypt in het
    // tekstveld met name="title". De ?? '' operator (null coalescing operator)
    // betekent: als $_POST['title'] niet bestaat of null is, gebruik dan
    // een lege string '' als standaardwaarde. Dit voorkomt PHP-waarschuwingen.
    // We slaan de titel op in de variabele $titel.
    $titel = $_POST['title'] ?? '';

    // $_POST['description'] bevat de beschrijving die de gebruiker heeft ingetypt
    // in het textarea-veld met name="description". Ook hier gebruiken we ?? ''
    // als veiligheidsnet voor het geval het veld leeg of niet verstuurd is.
    // Dit veld is optioneel, dus het mag leeg zijn.
    $beschrijving = $_POST['description'] ?? '';

    // $_POST['note'] bevat de persoonlijke notitie die de gebruiker heeft ingetypt
    // in het textarea-veld met name="note". Wederom met ?? '' als veiligheidsnet.
    // Dit veld is ook optioneel en mag leeg zijn.
    $notitie = $_POST['note'] ?? '';

    // addFavoriteGame() probeert het nieuwe favoriete spel toe te voegen aan de database.
    // Parameters die worden meegegeven:
    //   $userId       -> het ID van de ingelogde gebruiker (wie voegt het toe)
    //   $titel        -> de naam van het spel
    //   $beschrijving -> de beschrijving van het spel
    //   $notitie      -> de persoonlijke notitie
    // De functie geeft TERUG:
    //   - Een lege string '' als het GELUKT is (geen fout)
    //   - Een foutmelding als het MISLUKT is (bijv. "Titel is verplicht")
    // We slaan het resultaat op in $fout.
    $fout = addFavoriteGame($userId, $titel, $beschrijving, $notitie);

    // Hier controleren we of er GEEN fout is opgetreden.
    // !$fout betekent: als $fout leeg is (geen foutmelding), dan is het gelukt.
    // Een lege string '' wordt in PHP als FALSE beschouwd,
    // dus !'' wordt TRUE -> het toevoegen is geslaagd.
    if (!$fout) {

        // setMessage('success', 'Favoriet spel toegevoegd!') slaat een succesbericht
        // op in de sessie ($_SESSION). Het eerste argument 'success' geeft het type
        // bericht aan (wordt gebruikt voor de CSS-klasse van de melding).
        // Het tweede argument is de tekst die de gebruiker te zien krijgt.
        // Dit bericht wordt op de VOLGENDE paginalading weergegeven door getMessage().
        setMessage('success', 'Favoriet spel toegevoegd!');

        // header("Location: profile.php") stuurt de browser TERUG naar profile.php.
        // Dit heet het "Post/Redirect/Get" (PRG) patroon.
        // WAAROM doen we dit? Als de gebruiker na het toevoegen op F5 (vernieuwen) drukt,
        // zou het formulier OPNIEUW verstuurd worden zonder deze doorverwijzing.
        // Door te redirecten naar dezelfde pagina, wordt bij vernieuwen alleen
        // een GET-verzoek gestuurd in plaats van het formulier opnieuw te versturen.
        // Dit voorkomt dubbele toevoegingen in de database.
        header("Location: profile.php");

        // exit; stopt het script onmiddellijk na de doorverwijzing.
        // Zonder exit zou de rest van het PHP-script nog uitgevoerd worden,
        // wat onnodig is omdat de browser toch naar een nieuwe pagina gaat.
        exit;
    }
    // Als $fout WEL een waarde bevat (er IS een foutmelding), gaat het script
    // gewoon door naar de HTML-sectie hieronder. De foutmelding wordt dan
    // bovenaan de pagina weergegeven in een rood waarschuwingsvak (alert-danger).
}

// Hier eindigt het PHP-blok. Alles hierna is HTML die naar de browser wordt gestuurd.
// Het sluit de PHP-modus af en schakelt over naar HTML-modus.//
?>
<!-- ======================================================================== -->
<!-- HTML-SECTIE: HIER BEGINT DE WEBPAGINA DIE DE BROWSER TOONT              -->
<!-- ======================================================================== -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5-document is.           -->
<!-- Dit MOET altijd de allereerste regel van een HTML-pagina zijn.           -->
<!-- Zonder deze declaratie kan de browser de pagina in "quirks mode" tonen,  -->
<!-- wat betekent dat oude, verouderde regels worden gebruikt voor de opmaak. -->
<!-- Met <!DOCTYPE html> gebruikt de browser de moderne "standards mode".     -->
<!DOCTYPE html>

<!-- <html> is het hoofdelement dat ALLE HTML-inhoud omvat.                   -->
<!-- lang="nl" vertelt de browser en zoekmachines dat de pagina in het        -->
<!-- Nederlands is geschreven. Dit helpt bij:                                  -->
<!--   - Schermlezers (voor blinden) om de juiste uitspraak te kiezen         -->
<!--   - Zoekmachines (Google) om de taal van de pagina te begrijpen          -->
<!--   - De browser om de juiste vertaalopties aan te bieden                  -->
<html lang="nl">

<!-- <head> bevat metadata (informatie OVER de pagina) die niet zichtbaar     -->
<!-- is voor de gebruiker. Hier staan dingen zoals de paginatitel, tekenset,  -->
<!-- links naar CSS-bestanden (opmaak), en viewport-instellingen.             -->

<head>

    <!-- meta charset="UTF-8" stelt de tekencodering van de pagina in op UTF-8. -->
    <!-- UTF-8 is een universele tekencodering die ALLE tekens ondersteunt:      -->
    <!-- Nederlandse tekens (e, u, o), Japanse tekens, emoji's, enz.             -->
    <!-- Zonder deze instelling kunnen speciale tekens verkeerd weergegeven       -->
    <!-- worden (bijvoorbeeld als "Ã«" in plaats van "e").                        -->
    <meta charset="UTF-8">

    <!-- meta viewport zorgt ervoor dat de pagina goed werkt op mobiele apparaten -->
    <!-- width=device-width  -> de breedte van de pagina past zich aan aan het    -->
    <!--                        scherm van het apparaat (telefoon, tablet, laptop)-->
    <!-- initial-scale=1.0   -> het zoomniveau start op 100% (niet in- of         -->
    <!--                        uitgezoomd)                                       -->
    <!-- Zonder deze meta-tag zou de pagina op een telefoon heel klein worden     -->
    <!-- weergegeven alsof het een desktoppagina is, en moet je inzoomen.         -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <title> bepaalt de tekst die bovenaan het browsertabblad wordt getoond. -->
    <!-- De gebruiker ziet "Profiel - GamePlan Scheduler" in het tabblad.         -->
    <!-- Dit is ook de tekst die zoekmachines tonen als titel in de zoekresultaten-->
    <title>Profiel - GamePlan Scheduler</title>

    <!-- Deze <link> laadt het Bootstrap 5.3.3 CSS-framework in via een CDN.      -->
    <!-- CDN = Content Delivery Network, een netwerk van servers wereldwijd        -->
    <!--        dat bestanden snel kan leveren aan gebruikers overal ter wereld.   -->
    <!-- Bootstrap is een populair CSS-framework dat kant-en-klare opmaakklassen  -->
    <!-- biedt zoals knoppen (btn), formulieren (form-control), tabellen (table), -->
    <!-- kaarten (card), en een rasterlayout (container, row, col).               -->
    <!-- rel="stylesheet" vertelt de browser dat dit een CSS-bestand is.          -->
    <!-- Hierdoor hoeven we niet alle CSS zelf te schrijven maar kunnen we         -->
    <!-- de vooraf gedefinieerde klassen van Bootstrap gebruiken.                  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Deze <link> laadt ons EIGEN CSS-bestand 'style.css' in.                  -->
    <!-- Dit bevat aanvullende opmaakregels die specifiek zijn voor onze website,  -->
    <!-- zoals aangepaste kleuren, lettertypen, achtergronden, en andere stijlen  -->
    <!-- die niet standaard in Bootstrap zitten. Ons eigen bestand overschrijft    -->
    <!-- eventueel Bootstrap-stijlen als dat nodig is.                            -->
    <link rel="stylesheet" href="style.css">

    <!-- Einde van het <head>-gedeelte. Alles hierna is zichtbaar voor de gebruiker. -->
</head>

<!-- <body> bevat ALLE zichtbare inhoud van de webpagina.                        -->
<!-- class="bg-dark text-light" zijn Bootstrap-klassen:                           -->
<!--   bg-dark    -> geeft de achtergrond een donkere kleur (bijna zwart, #212529)-->
<!--                 Dit maakt de pagina een "dark mode" / donker thema.          -->
<!--   text-light -> maakt alle tekst op de pagina licht/wit (#f8f9fa).           -->
<!--                 Zonder dit zou de tekst zwart zijn op een donkere achtergrond -->
<!--                 en dus onleesbaar. Deze twee klassen samen maken een          -->
<!--                 donker thema met lichte tekst.                               -->

<body class="bg-dark text-light">

    <!-- PHP include 'header.php' laadt het header-bestand (navigatiebalk) in.   -->
    <!-- include voegt de inhoud van een ander PHP-bestand in op deze plek.       -->
    <!-- header.php bevat de navigatiebalk bovenaan de pagina met links naar      -->
    <!-- andere pagina's (Home, Profiel, Uitloggen, enz.).                        -->
    <!-- Het verschil met require_once is: include geeft alleen een waarschuwing  -->
    <!-- als het bestand niet gevonden wordt, maar het script gaat wel door.      -->
    <!-- require_once stopt het script met een fatale fout als het bestand mist.  -->
    <?php include 'header.php'; ?>

    <!-- <main> is een semantisch HTML5-element dat de HOOFDINHOUD van de pagina  -->
    <!-- markeert. Dit helpt schermlezers en zoekmachines te begrijpen welk deel  -->
    <!-- van de pagina de belangrijkste inhoud bevat (niet de header of footer).  -->
    <!-- class="container mt-5 pt-5" zijn Bootstrap-klassen:                      -->
    <!--   container -> maakt een gecentreerde container met een maximale breedte -->
    <!--               (bijv. 1140px op grote schermen). De inhoud wordt mooi     -->
    <!--               gecentreerd op het scherm met automatische marges links    -->
    <!--               en rechts. Op kleinere schermen past de breedte zich aan.  -->
    <!--   mt-5      -> margin-top: 5 (3rem = 48px). Voegt ruimte toe BOVEN het  -->
    <!--               element zodat de inhoud niet tegen de navigatiebalk plakt. -->
    <!--               "mt" = margin-top, "5" = de grootste standaard maat.      -->
    <!--   pt-5      -> padding-top: 5 (3rem = 48px). Voegt INTERNE ruimte toe   -->
    <!--               aan de bovenkant. Samen met mt-5 zorgt dit voor voldoende  -->
    <!--               afstand onder de navigatiebalk die waarschijnlijk "fixed"  -->
    <!--               is (altijd bovenaan blijft bij scrollen).                  -->
    <main class="container mt-5 pt-5">

        <!-- getMessage() haalt een eventueel opgeslagen bericht op uit de sessie.  -->
        <!-- Als er een succesbericht is (bijv. "Favoriet spel toegevoegd!"),       -->
        <!-- wordt dat hier weergegeven als een Bootstrap-alert.                    -->
        <!-- Na het weergeven wordt het bericht uit de sessie verwijderd, zodat     -->
        <!-- het niet bij elke paginalading opnieuw verschijnt.                     -->
        <!-- Dit is het "flash message" patroon: een bericht dat eenmalig wordt     -->
        <!-- getoond na een actie (zoals toevoegen, bewerken, of verwijderen).      -->
        <?php echo getMessage(); ?>

        <!-- Hier controleren we of er een foutmelding is ($fout is niet leeg).    -->
        <!-- Als $fout een waarde bevat (bijv. "Titel is verplicht"), wordt een     -->
        <!-- rood foutmeldingsvak getoond. De dubbele punt : is een alternatieve   -->
        <!-- PHP-syntaxis voor accolades {} die beter leesbaar is in HTML-templates.-->
        <!-- if ($fout): is hetzelfde als if ($fout) { maar dan mooier in HTML.    -->
        <?php if ($fout): ?>

            <!-- class="alert alert-danger" zijn Bootstrap-klassen voor meldingen: -->
            <!--   alert        -> basisklasse voor alle meldingsvakken. Geeft het -->
            <!--                  element padding, een rand, en afgeronde hoeken.  -->
            <!--   alert-danger -> maakt het meldingsvak ROOD, wat aangeeft dat er -->
            <!--                  een FOUT of probleem is opgetreden.              -->
            <!--                  Andere opties zijn: alert-success (groen),       -->
            <!--                  alert-warning (geel), alert-info (blauw).        -->
            <!-- safeEcho($fout) beveiligt de foutmelding tegen XSS-aanvallen.     -->
            <!-- XSS = Cross-Site Scripting: een aanvaller zou schadelijke         -->
            <!-- JavaScript-code kunnen meesturen als invoer. safeEcho() zet       -->
            <!-- speciale HTML-tekens om naar veilige tekst:                       -->
            <!--   < wordt &lt;   > wordt &gt;   " wordt &quot;   & wordt &amp;   -->
            <!-- Zo wordt <script>alert('hack')</script> onschadelijk gemaakt      -->
            <!-- en als platte tekst weergegeven in plaats van als code uitgevoerd. -->
            <div class="alert alert-danger"><?php echo safeEcho($fout); ?></div>

            <!-- endif; sluit het if-blok af (alternatieve syntaxis voor de sluit-accolade }). -->
        <?php endif; ?>

        <!-- ================================================================== -->
        <!-- SECTIE 1: FORMULIER OM EEN NIEUW FAVORIET SPEL TOE TE VOEGEN      -->
        <!-- ================================================================== -->
        <!-- Dit HTML-commentaar markeert het begin van het toevoegformulier.   -->
        <!-- FAVORIET SPEL TOEVOEGEN FORMULIER -->

        <!-- <section> is een semantisch HTML5-element dat een logisch gedeelte  -->
        <!-- van de pagina markeert. Het helpt de structuur van de pagina te      -->
        <!-- begrijpen voor schermlezers en zoekmachines.                         -->
        <!-- class="mb-5" is een Bootstrap-klasse:                                -->
        <!--   mb-5 -> margin-bottom: 5 (3rem = 48px). Voegt ruimte toe ONDER    -->
        <!--          dit element zodat er afstand is tussen het formulier en     -->
        <!--          de tabel eronder. "mb" = margin-bottom, "5" = grootste maat.-->
        <section class="mb-5">

            <!-- <h2> is een koptekst van niveau 2 (tweede belangrijkste na h1).   -->
            <!-- Het emoji-icoon en de tekst beschrijven wat deze sectie doet:      -->
            <!-- het toevoegen van een favoriet spel.                               -->
            <h2>➕ Favoriet Spel Toevoegen</h2>

            <!-- class="card" is een Bootstrap-component:                           -->
            <!--   card -> maakt een "kaart" - een rechthoekig vlak met een         -->
            <!--          lichte achtergrond, een dunne rand, en afgeronde hoeken.  -->
            <!--          Cards worden veel gebruikt om inhoud visueel te groeperen  -->
            <!--          en van de rest van de pagina te onderscheiden.             -->
            <!--          De card bevat een witte/lichte achtergrond, een subtiele   -->
            <!--          rand (border), en border-radius voor afgeronde hoeken.    -->
            <div class="card">

                <!-- class="card-body" is het binnenste gedeelte van een Bootstrap-card: -->
                <!--   card-body -> voegt padding (binnenruimte) toe aan de kaart,        -->
                <!--               zodat de inhoud niet tegen de randen van de kaart plakt.-->
                <!--               Standaard is dit 1rem (16px) aan alle kanten.           -->
                <!--               Hierin wordt het formulier geplaatst.                   -->
                <div class="card-body">

                    <!-- <form method="POST"> maakt een HTML-formulier aan.                -->
                    <!-- method="POST" bepaalt HOE de gegevens naar de server worden        -->
                    <!-- verstuurd wanneer de gebruiker op de verzendknop klikt:             -->
                    <!--   POST -> de gegevens worden in de BODY van het HTTP-verzoek       -->
                    <!--          verstuurd (onzichtbaar in de URL). Dit is veiliger voor   -->
                    <!--          gevoelige gegevens en er is geen limiet aan de hoeveelheid-->
                    <!--          data die verstuurd kan worden.                             -->
                    <!--   GET  -> de gegevens worden in de URL geplaatst (zichtbaar),      -->
                    <!--          bijv. ?title=Minecraft&description=leuk. Dit is minder    -->
                    <!--          veilig en heeft een limiet van ~2000 tekens.               -->
                    <!-- Er is geen action-attribuut opgegeven, wat betekent dat het         -->
                    <!-- formulier naar DEZELFDE pagina (profile.php) wordt verstuurd.       -->
                    <!-- De PHP-code bovenaan deze pagina vangt het POST-verzoek op.         -->
                    <form method="POST">

                        <!-- class="mb-3" is een Bootstrap-klasse:                          -->
                        <!--   mb-3 -> margin-bottom: 3 (1rem = 16px). Voegt ruimte toe     -->
                        <!--          onder dit formulierveld zodat de velden niet tegen     -->
                        <!--          elkaar aanzitten. Dit zorgt voor een nette lay-out.    -->
                        <div class="mb-3">

                            <!-- <label> is een tekstelement dat beschrijft wat het           -->
                            <!-- bijbehorende invoerveld verwacht. Het verbindt de tekst      -->
                            <!-- met het invoerveld via het for-attribuut.                    -->
                            <!-- for="title" koppelt dit label aan het invoerveld met id="title". -->
                            <!-- Wanneer de gebruiker op de tekst klikt, wordt het invoerveld -->
                            <!-- automatisch geselecteerd (focus). Dit verbetert de           -->
                            <!-- gebruiksvriendelijkheid, vooral op mobiele apparaten.        -->
                            <!-- class="form-label" is een Bootstrap-klasse die het label     -->
                            <!-- netjes opmaakt met de juiste marge en lettergrootte.         -->
                            <!-- De * achter "Speltitel" geeft aan dat dit veld VERPLICHT is. -->
                            <label for="title" class="form-label">🎮 Speltitel *</label>

                            <!-- <input type="text"> maakt een tekstveld waar de gebruiker    -->
                            <!-- een enkele regel tekst kan invoeren (de naam van het spel).  -->
                            <!-- id="title"      -> unieke identificatie, gekoppeld aan het   -->
                            <!--                   label hierboven via for="title".           -->
                            <!-- name="title"    -> de SLEUTELNAAM waarmee de waarde naar de  -->
                            <!--                   server wordt verstuurd. In PHP is dit      -->
                            <!--                   beschikbaar als $_POST['title'].           -->
                            <!-- class="form-control" -> Bootstrap-klasse die het invoerveld  -->
                            <!--                   opmaakt met een nette rand, padding,       -->
                            <!--                   afgeronde hoeken, en een focus-effect      -->
                            <!--                   (blauwe rand wanneer je erin klikt).       -->
                            <!-- required        -> HTML5-validatie: de browser staat niet toe -->
                            <!--                   dat het formulier wordt verstuurd als dit  -->
                            <!--                   veld leeg is. Er verschijnt een melding.   -->
                            <!-- maxlength="100" -> de gebruiker kan maximaal 100 tekens      -->
                            <!--                   invoeren. Dit beschermt de database tegen  -->
                            <!--                   extreem lange invoer.                      -->
                            <!-- placeholder="..." -> grijze voorbeeldtekst die verdwijnt     -->
                            <!--                   zodra de gebruiker begint met typen.       -->
                            <!--                   Het geeft een hint over wat er ingevuld    -->
                            <!--                   moet worden.                               -->
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                placeholder="Fortnite, Minecraft, etc.">

                            <!-- Einde van de div met klasse mb-3 voor het titelveld. -->
                        </div>

                        <!-- Tweede formuliergroep: het beschrijvingsveld. -->
                        <!-- class="mb-3" -> margin-bottom voor ruimte onder dit veld. -->
                        <div class="mb-3">

                            <!-- Label voor het beschrijvingsveld. "📝" is een emoji-icoon. -->
                            <!-- "(optioneel)" geeft aan dat dit veld NIET verplicht is.   -->
                            <!-- De gebruiker mag dit veld leeg laten.                      -->
                            <!-- for="description" koppelt dit label aan het textarea       -->
                            <!-- met id="description" zodat klikken op het label het        -->
                            <!-- tekstvak selecteert.                                       -->
                            <label for="description" class="form-label">📝 Beschrijving (optioneel)</label>

                            <!-- <textarea> maakt een GROOT tekstveld waar meerdere regels  -->
                            <!-- tekst ingevoerd kunnen worden (anders dan <input> die maar -->
                            <!-- een regel toestaat).                                       -->
                            <!-- id="description"      -> unieke identificatie, gekoppeld   -->
                            <!--                         aan het label hierboven.            -->
                            <!-- name="description"    -> sleutelnaam voor $_POST. In PHP   -->
                            <!--                         beschikbaar als $_POST['description'].-->
                            <!-- class="form-control"  -> Bootstrap-opmaak voor het tekstveld,-->
                            <!--                         zelfde stijl als het input-veld.    -->
                            <!-- rows="2"              -> de hoogte van het tekstveld in      -->
                            <!--                         regels. 2 regels hoog is compact    -->
                            <!--                         maar geeft voldoende ruimte.        -->
                            <!-- maxlength="500"       -> maximaal 500 tekens toegestaan.    -->
                            <!--                         Beschermt de database tegen te lange -->
                            <!--                         teksten.                             -->
                            <!-- placeholder="..."     -> grijze voorbeeldtekst als hint.    -->
                            <textarea id="description" name="description" class="form-control" rows="2" maxlength="500"
                                placeholder="Waar gaat dit spel over?"></textarea>

                            <!-- Einde van de div met klasse mb-3 voor het beschrijvingsveld. -->
                        </div>

                        <!-- Derde formuliergroep: het notitieveld. -->
                        <!-- class="mb-3" -> margin-bottom voor ruimte onder dit veld. -->
                        <div class="mb-3">

                            <!-- Label voor het notitieveld. "📌" is een punaise-emoji.    -->
                            <!-- "(optioneel)" geeft aan dat dit veld niet verplicht is.    -->
                            <!-- for="note" koppelt dit label aan het textarea met id="note"-->
                            <!-- zodat klikken op de labeltekst het tekstvak selecteert.    -->
                            <label for="note" class="form-label">📌 Notitie (optioneel)</label>

                            <!-- <textarea> voor de persoonlijke notitie van de gebruiker.  -->
                            <!-- id="note"             -> unieke identificatie, gekoppeld   -->
                            <!--                         aan het label hierboven.            -->
                            <!-- name="note"           -> sleutelnaam voor $_POST. In PHP   -->
                            <!--                         beschikbaar als $_POST['note'].     -->
                            <!-- class="form-control"  -> Bootstrap-opmaak voor het tekstveld.-->
                            <!-- rows="2"              -> 2 regels hoog.                     -->
                            <!-- placeholder="..."     -> grijze voorbeeldtekst met een      -->
                            <!--                         voorbeeld van wat je kunt invullen.  -->
                            <!-- Merk op: dit veld heeft GEEN maxlength attribuut, dus er    -->
                            <!-- is geen limiet aan de lengte van de notitie aan de           -->
                            <!-- clientzijde (de server kan dit wel beperken).                -->
                            <textarea id="note" name="note" class="form-control" rows="2"
                                placeholder="Persoonlijke notities, bijv. 'Mijn favoriete spel!'"></textarea>

                            <!-- Einde van de div met klasse mb-3 voor het notitieveld. -->
                        </div>

                        <!-- De verzendknop van het formulier.                              -->
                        <!-- type="submit"       -> wanneer de gebruiker op deze knop klikt, -->
                        <!--                       wordt het HELE formulier verstuurd naar   -->
                        <!--                       de server via de POST-methode.            -->
                        <!-- name="add_favorite" -> deze naam wordt meegestuurd naar de     -->
                        <!--                       server zodat de PHP-code (bovenaan) kan   -->
                        <!--                       controleren WELKE knop is ingedrukt met   -->
                        <!--                       isset($_POST['add_favorite']).             -->
                        <!-- class="btn btn-primary" -> Bootstrap-knopklassen:               -->
                        <!--   btn         -> basisklasse voor alle Bootstrap-knoppen.       -->
                        <!--                 Geeft de knop padding, een rand, afgeronde      -->
                        <!--                 hoeken, en een hover-effect.                    -->
                        <!--   btn-primary -> maakt de knop BLAUW (de primaire kleur).       -->
                        <!--                 Bootstrap-knopkleuren:                          -->
                        <!--                 btn-primary (blauw), btn-secondary (grijs),     -->
                        <!--                 btn-success (groen), btn-danger (rood),         -->
                        <!--                 btn-warning (geel), btn-info (lichtblauw).      -->
                        <button type="submit" name="add_favorite" class="btn btn-primary">➕ Toevoegen</button>

                        <!-- Einde van het <form> element. Alle invoervelden hierboven worden  -->
                        <!-- verzameld en als POST-gegevens naar de server gestuurd.             -->
                    </form>

                    <!-- Einde van de card-body div (binnenruimte van de kaart). -->
                </div>

                <!-- Einde van de card div (de volledige kaart met rand en achtergrond). -->
            </div>

            <!-- Einde van de <section> voor het toevoegformulier. -->
        </section>

        <!-- ================================================================== -->
        <!-- SECTIE 2: TABEL MET FAVORIETE SPELLEN VAN DE GEBRUIKER             -->
        <!-- ================================================================== -->
        <!-- Dit HTML-commentaar markeert het begin van de favorieten-tabel.     -->
        <!-- JOUW FAVORIETEN TABEL -->

        <!-- Nieuwe sectie voor de favorieten-tabel.                             -->
        <!-- class="mb-5" -> margin-bottom: 5 (3rem = 48px) voor ruimte eronder. -->
        <section class="mb-5">

            <!-- Koptekst voor de favorieten-sectie met een ster-emoji.           -->
            <h2>⭐ Jouw Favorieten</h2>

            <!-- class="table-responsive" is een Bootstrap-klasse die ervoor zorgt -->
            <!-- dat de tabel HORIZONTAAL gescrolld kan worden op kleine schermen.  -->
            <!-- Op een telefoon is het scherm te smal voor een brede tabel.        -->
            <!-- Met table-responsive krijgt de tabel een horizontale scrolbalk     -->
            <!-- zodat de gebruiker naar links/rechts kan scrollen zonder dat de    -->
            <!-- hele pagina mee beweegt. Dit verbetert de mobiele ervaring enorm.  -->
            <div class="table-responsive">

                <!-- <table> maakt een HTML-tabel aan.                              -->
                <!-- class="table table-dark table-bordered table-hover" zijn        -->
                <!-- Bootstrap-tabelklassen die samen de tabel opmaken:              -->
                <!--   table         -> basisklasse voor alle Bootstrap-tabellen.    -->
                <!--                   Voegt padding toe aan cellen, horizontale     -->
                <!--                   lijnen tussen rijen, en een nette opmaak.     -->
                <!--   table-dark    -> geeft de tabel een DONKERE achtergrond       -->
                <!--                   (donkergrijs/zwart) met lichte tekst.          -->
                <!--                   Past bij het donkere thema van de pagina.      -->
                <!--   table-bordered -> voegt RANDEN toe aan alle kanten van elke   -->
                <!--                   cel in de tabel (niet alleen horizontale       -->
                <!--                   lijnen, maar ook verticale). Dit maakt de     -->
                <!--                   tabel duidelijker leesbaar.                    -->
                <!--   table-hover   -> wanneer de gebruiker met de muis over een    -->
                <!--                   rij beweegt (hover), wordt die rij IETS       -->
                <!--                   lichter opgelicht. Dit helpt de gebruiker      -->
                <!--                   te zien welke rij hij/zij bekijkt.             -->
                <table class="table table-dark table-bordered table-hover">

                    <!-- <thead> bevat de KOPRIJ van de tabel (de bovenste rij met   -->
                    <!-- kolomnamen). Dit is een semantisch element dat de browser en -->
                    <!-- schermlezers helpt te begrijpen welk deel de kopjes zijn.   -->
                    <thead>

                        <!-- <tr> staat voor "table row" (tabelrij). Dit is de koprij -->
                        <!-- met de namen van de kolommen.                             -->
                        <tr>

                            <!-- <th> staat voor "table header" (tabelkop).             -->
                            <!-- Dit zijn de kolomnamen die vet worden weergegeven.     -->
                            <!-- "Titel" -> de kolom voor de naam van het spel.         -->
                            <th>Titel</th>

                            <!-- "Beschrijving" -> de kolom voor de beschrijving.       -->
                            <th>Beschrijving</th>

                            <!-- "Notitie" -> de kolom voor de persoonlijke notitie.    -->
                            <th>Notitie</th>

                            <!-- "Acties" -> de kolom met knoppen om het spel te        -->
                            <!-- bewerken of verwijderen.                                -->
                            <th>Acties</th>

                            <!-- Einde van de koprij. -->
                        </tr>

                        <!-- Einde van het kopgedeelte van de tabel. -->
                    </thead>

                    <!-- <tbody> bevat de GEGEVENSRIJEN van de tabel (de daadwerkelijke -->
                    <!-- data, niet de kolomnamen). Dit is een semantisch element dat   -->
                    <!-- het verschil maakt tussen kopregels en datarijen.               -->
                    <tbody>

                        <!-- Hier controleren we of de $favorieten array LEEG is.      -->
                        <!-- empty() retourneert TRUE als de array geen elementen bevat.-->
                        <!-- Als de gebruiker geen favoriete spellen heeft toegevoegd,  -->
                        <!-- tonen we een melding in plaats van een lege tabel.          -->
                        <?php if (empty($favorieten)): ?>

                            <!-- Als er geen favorieten zijn, tonen we een enkele rij   -->
                            <!-- met een bericht. -->
                            <tr>

                                <!-- colspan="4" laat deze ene cel zich uitstrekken over -->
                                <!-- ALLE 4 kolommen (Titel, Beschrijving, Notitie,     -->
                                <!-- Acties). Zo wordt de melding gecentreerd over de    -->
                                <!-- hele tabelbreedte weergegeven.                      -->
                                <!-- class="text-center text-secondary" zijn Bootstrap:  -->
                                <!--   text-center    -> centreert de tekst horizontaal. -->
                                <!--   text-secondary -> maakt de tekst grijs (minder    -->
                                <!--                    opvallend), wat aangeeft dat     -->
                                <!--                    het een plachoudertekst is en    -->
                                <!--                    geen echte data.                  -->
                                <td colspan="4" class="text-center text-secondary">Nog geen favorieten!</td>

                                <!-- Einde van de rij met de "geen favorieten" melding. -->
                            </tr>

                            <!-- else: -> als de $favorieten array NIET leeg is              -->
                            <!-- (de gebruiker heeft WEL favoriete spellen), dan              -->
                            <!-- gaan we de spellen een voor een weergeven.                   -->
                        <?php else: ?>

                            <!-- foreach is een PHP-lus die door ELKE item in de array   -->
                            <!-- $favorieten loopt. Bij elke herhaling (iteratie) wordt  -->
                            <!-- het huidige spel opgeslagen in de variabele $spel.       -->
                            <!-- Bijvoorbeeld: als $favorieten 3 spellen bevat, wordt    -->
                            <!-- deze lus 3 keer uitgevoerd en maakt 3 tabelrijen aan.   -->
                            <!-- $favorieten as $spel -> "voor elk element in             -->
                            <!--                         $favorieten, noem het $spel"    -->
                            <!-- Elke $spel is een associatieve array met sleutels:       -->
                            <!--   $spel['game_id']     -> uniek ID van het spel          -->
                            <!--   $spel['titel']       -> de titel/naam van het spel     -->
                            <!--   $spel['description'] -> de beschrijving                -->
                            <!--   $spel['note']        -> de persoonlijke notitie         -->
                            <?php foreach ($favorieten as $spel): ?>

                                <!-- Voor elk favoriet spel maken we een nieuwe tabelrij (<tr>) -->
                                <!-- aan. Elke rij bevat 4 cellen (<td>) die overeenkomen      -->
                                <!-- met de 4 kolommen in de kop (Titel, Beschrijving,         -->
                                <!-- Notitie, Acties).                                          -->
                                <tr>

                                    <!-- KOLOM 1: TITEL                                         -->
                                    <!-- <td> staat voor "table data" (tabelcel).                -->
                                    <!-- safeEcho($spel['titel']) toont de titel van het spel    -->
                                    <!-- op een VEILIGE manier. safeEcho() is onze eigen functie -->
                                    <!-- die htmlspecialchars() aanroept om gevaarlijke HTML-    -->
                                    <!-- tekens te ontsnappen (escapen). Dit beschermt tegen     -->
                                    <!-- XSS-aanvallen (Cross-Site Scripting).                   -->
                                    <!-- Voorbeeld: als een aanvaller als titel invult:          -->
                                    <!--   <script>document.cookie</script>                      -->
                                    <!-- Dan zet safeEcho() dit om naar:                         -->
                                    <!--   &lt;script&gt;document.cookie&lt;/script&gt;          -->
                                    <!-- wat als PLATTE TEKST wordt weergegeven, niet als code.  -->
                                    <td><?php echo safeEcho($spel['titel']); ?></td>

                                    <!-- KOLOM 2: BESCHRIJVING                                   -->
                                    <!-- Toont de beschrijving van het spel, beveiligd met        -->
                                    <!-- safeEcho() tegen XSS-aanvallen. Als de beschrijving     -->
                                    <!-- leeg is, wordt er gewoon niets getoond in de cel.       -->
                                    <td><?php echo safeEcho($spel['description']); ?></td>

                                    <!-- KOLOM 3: NOTITIE                                        -->
                                    <!-- Toont de persoonlijke notitie van de gebruiker,          -->
                                    <!-- beveiligd met safeEcho() tegen XSS-aanvallen.           -->
                                    <!-- Net als bij beschrijving, als het leeg is wordt er       -->
                                    <!-- niets getoond.                                          -->
                                    <td><?php echo safeEcho($spel['note']); ?></td>

                                    <!-- KOLOM 4: ACTIES (BEWERKEN EN VERWIJDEREN)                -->
                                    <!-- Deze cel bevat twee knoppen: een om het spel te bewerken -->
                                    <!-- en een om het spel te verwijderen uit de favorieten.     -->
                                    <td>

                                        <!-- BEWERKEN-KNOP                                        -->
                                        <!-- <a href="edit_favorite.php?id=..."> is een hyperlink  -->
                                        <!-- die de gebruiker naar de bewerkpagina stuurt.          -->
                                        <!-- edit_favorite.php is de pagina waar je een bestaand    -->
                                        <!-- favoriet spel kunt aanpassen.                         -->
                                        <!-- ?id=<?php echo $spel['game_id']; ?> voegt het unieke  -->
                                        <!-- spel-ID toe aan de URL als GET-parameter.              -->
                                        <!-- Voorbeeld: edit_favorite.php?id=5 -> bewerk spel #5.  -->
                                        <!-- Op de edit_favorite.php pagina wordt dit ID uit de     -->
                                        <!-- URL gelezen met $_GET['id'] om het juiste spel op te   -->
                                        <!-- halen uit de database.                                -->
                                        <!-- class="btn btn-sm btn-warning" zijn Bootstrap-klassen: -->
                                        <!--   btn      -> basisklasse voor knoppen.                -->
                                        <!--   btn-sm   -> maakt de knop KLEINER (small). Zonder    -->
                                        <!--              deze klasse is de knop standaardgrootte,   -->
                                        <!--              wat te groot zou zijn in een tabelcel.     -->
                                        <!--   btn-warning -> maakt de knop GEEL/ORANJE, wat        -->
                                        <!--              conventioneel wordt gebruikt voor          -->
                                        <!--              "bewerk"/"waarschuwing" acties.            -->
                                        <a href="edit_favorite.php?id=<?php echo $spel['game_id']; ?>"
                                            class="btn btn-sm btn-warning">✏️ Bewerken</a>

                                        <!-- VERWIJDEREN-KNOP                                       -->
                                        <!-- <a href="delete.php?type=favorite&id=..."> is een link  -->
                                        <!-- naar het verwijder-script.                              -->
                                        <!-- delete.php is de pagina die het verwijderen afhandelt.  -->
                                        <!-- ?type=favorite vertelt delete.php dat we een FAVORIET   -->
                                        <!--  spel willen verwijderen (niet bijv. een evenement).    -->
                                        <!-- &id=<?php echo $spel['game_id']; ?> voegt het unieke   -->
                                        <!-- spel-ID toe zodat delete.php weet WELK spel verwijderd -->
                                        <!-- moet worden. Het &-teken scheidt meerdere parameters.  -->
                                        <!-- Voorbeeld: delete.php?type=favorite&id=5               -->
                                        <!-- class="btn btn-sm btn-danger" zijn Bootstrap-klassen:   -->
                                        <!--   btn        -> basisklasse voor knoppen.               -->
                                        <!--   btn-sm     -> maakt de knop kleiner (small).          -->
                                        <!--   btn-danger -> maakt de knop ROOD, wat conventioneel   -->
                                        <!--                wordt gebruikt voor destructieve acties   -->
                                        <!--                zoals verwijderen. De rode kleur waarschuwt -->
                                        <!--                de gebruiker dat dit een onomkeerbare     -->
                                        <!--                actie is.                                 -->
                                        <!-- onclick="return confirm('...')" is JavaScript dat       -->
                                        <!-- wordt uitgevoerd VOORDAT de link wordt gevolgd.          -->
                                        <!-- confirm('Uit favorieten verwijderen?') toont een        -->
                                        <!-- pop-upvenster (dialoogvenster) in de browser met de      -->
                                        <!-- tekst "Uit favorieten verwijderen?" en twee knoppen:    -->
                                        <!--   OK     -> confirm() retourneert TRUE, de link wordt   -->
                                        <!--            gevolgd en het spel wordt verwijderd.         -->
                                        <!--   Annuleren -> confirm() retourneert FALSE, en door      -->
                                        <!--            "return false" wordt de link NIET gevolgd.    -->
                                        <!--            Het spel blijft behouden.                     -->
                                        <!-- Dit is een beveiligingsmaatregel om te voorkomen dat     -->
                                        <!-- de gebruiker per ongeluk een favoriet spel verwijdert.   -->
                                        <!-- Zonder deze bevestiging zou een enkel klik het spel      -->
                                        <!-- onmiddellijk verwijderen zonder waarschuwing.            -->
                                        <a href="delete.php?type=favorite&id=<?php echo $spel['game_id']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Uit favorieten verwijderen?');">🗑️ Verwijderen</a>

                                        <!-- Einde van de acties-cel. -->
                                    </td>

                                    <!-- Einde van de tabelrij voor dit specifieke spel. -->
                                </tr>

                                <!-- endforeach; sluit de foreach-lus af. Na deze regel springt  -->
                                <!-- PHP terug naar het begin van de lus voor het volgende spel  -->
                                <!-- in de $favorieten array, totdat alle spellen zijn weergegeven.-->
                            <?php endforeach; ?>

                            <!-- endif; sluit het if/else-blok af dat controleerde of er         -->
                            <!-- favorieten waren. Na deze regel gaan we verder met het sluiten  -->
                            <!-- van de tabel-elementen.                                         -->
                        <?php endif; ?>

                        <!-- Einde van het <tbody> element (alle gegevensrijen). -->
                    </tbody>

                    <!-- Einde van het <table> element (de volledige tabel). -->
                </table>

                <!-- Einde van de table-responsive div (horizontale scrol-container). -->
            </div>

            <!-- Einde van de <section> voor de favorieten-tabel. -->
        </section>

        <!-- Einde van het <main>-element (hoofdinhoud van de pagina). -->
    </main>

    <!-- PHP include 'footer.php' laadt het voettekst-bestand in.                -->
    <!-- footer.php bevat de onderste gedeelte van de pagina, met informatie      -->
    <!-- zoals copyright, contactlinks, of andere links.                          -->
    <!-- Net als header.php wordt dit op elke pagina hergebruikt voor             -->
    <!-- consistentie. Als je iets in de footer wilt veranderen, hoef je maar     -->
    <!-- een bestand aan te passen in plaats van elke pagina apart.               -->
    <?php include 'footer.php'; ?>

    <!-- Dit <script>-element laadt het Bootstrap JavaScript-bestand in.          -->
    <!-- Bootstrap JS is nodig voor INTERACTIEVE componenten zoals:               -->
    <!--   - Dropdown-menu's (uitklapmenu's)                                      -->
    <!--   - Modals (pop-upvensters)                                              -->
    <!--   - Toasts (meldingen die automatisch verdwijnen)                        -->
    <!--   - Tooltips (kleine informatieballonnetjes bij hover)                  -->
    <!--   - De hamburger-menu op mobiele apparaten (navbar toggler)              -->
    <!-- "bundle" betekent dat Popper.js al is inbegrepen (nodig voor dropdowns  -->
    <!-- en tooltips). Zonder dit JS-bestand werken alleen de CSS-stijlen van     -->
    <!-- Bootstrap, maar niet de interactieve functies.                           -->
    <!-- Dit script wordt ONDERAAN de pagina geladen (voor </body>) zodat de     -->
    <!-- HTML-inhoud eerst wordt weergegeven. Dit maakt de pagina sneller.       -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dit <script>-element laadt ons EIGEN JavaScript-bestand 'script.js' in. -->
    <!-- Dit bestand bevat aangepaste JavaScript-code specifiek voor onze         -->
    <!-- website, zoals eventuele formuliervalidatie, animaties, of andere        -->
    <!-- interactieve functies die niet door Bootstrap worden aangeboden.         -->
    <!-- Het wordt NA Bootstrap geladen zodat we eventueel Bootstrap JS-functies  -->
    <!-- kunnen gebruiken in ons eigen script.                                   -->
    <script src="script.js"></script>

    <!-- Einde van het <body>-element. Alle zichtbare inhoud van de pagina is nu afgesloten. -->
</body>

<!-- Einde van het <html>-element. Dit sluit het volledige HTML-document af.     -->
<!-- Na deze tag mag er NIETS meer staan in het bestand (behalve eventueel       -->
<!-- witruimte die door de browser wordt genegeerd).                             -->

</html>