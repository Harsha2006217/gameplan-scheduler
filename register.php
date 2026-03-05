<?php
/**
 * ==========================================================================
 * REGISTER.PHP - REGISTRATIE PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat nieuwe gebruikers een GamePlan Scheduler account aanmaken.
 * Het valideert gebruikersnaam, e-mail en wachtwoord, en maakt het account aan.
 *
 * Beveiligingsmaatregelen:
 * - Wachtwoord wordt versleuteld met bcrypt (nooit als platte tekst opgeslagen)
 * - E-mail uniekheid controle (één account per e-mail)
 * - Invoer validatie (BUG FIX #1001: spaties controle)
 * - Minimale wachtwoord lengte: 8 tekens
 *
 * Hoe het registratieproces werkt:
 * 1. De gebruiker opent deze pagina in de browser (GET verzoek).
 * 2. Het formulier wordt getoond met velden voor gebruikersnaam, e-mail
 *    en wachtwoord.
 * 3. De gebruiker vult alles in en klikt op "Account Aanmaken" (POST verzoek).
 * 4. PHP ontvangt de POST-gegevens en roept registerUser() aan.
 * 5. registerUser() controleert:
 *    a. Of alle velden zijn ingevuld en geen onnodige spaties bevatten.
 *    b. Of het e-mailadres een geldig formaat heeft.
 *    c. Of het wachtwoord minstens 8 tekens lang is.
 *    d. Of het e-mailadres niet al in gebruik is door een andere gebruiker.
 * 6. Het wachtwoord wordt gehasht met bcrypt (password_hash) voor veilige
 *    opslag. Dit betekent dat het originele wachtwoord NOOIT in de database
 *    wordt bewaard - alleen een versleutelde versie die niet terug te
 *    rekenen is naar het origineel.
 * 7. Bij succes: de gebruiker wordt doorgestuurd naar login.php met een
 *    succesbericht.
 * 8. Bij een fout: de foutmelding wordt getoond op de pagina.
 * ==========================================================================
 */

// ============================================================================
// require_once laadt het bestand 'functions.php' in en voert het uit.
// 'require_once' betekent:
//   - 'require': het bestand MOET bestaan, anders stopt PHP met een fatale fout.
//     (In tegenstelling tot 'include' dat alleen een waarschuwing geeft.)
//   - '_once': het bestand wordt maar EEN keer geladen, ook als deze regel
//     meerdere keren wordt uitgevoerd. Dit voorkomt problemen met dubbele
//     functie-definities die een PHP fatale fout zouden veroorzaken.
// In functions.php staan alle hulpfuncties die we nodig hebben:
//   - connectDB(): maakt verbinding met de MySQL database.
//   - registerUser(): verwerkt de registratie (validatie, hashing, opslaan).
//   - isLoggedIn(): controleert of de gebruiker al een actieve sessie heeft.
//   - setMessage(): slaat een bericht op in de sessie voor de volgende pagina.
//   - safeEcho(): geeft tekst veilig weer (bescherming tegen XSS-aanvallen).
// ============================================================================
require_once 'functions.php';

// ============================================================================
// CONTROLE: Is de gebruiker al ingelogd?
// ============================================================================
// isLoggedIn() controleert of er een actieve sessie is met een gebruikers-ID.
// Als iemand AL ingelogd is, hoeft ze geen nieuw account aan te maken.
// Daarom sturen we ze door naar de hoofdpagina (index.php).
//
// header("Location: index.php") stuurt een HTTP 302 redirect header naar
// de browser. De browser ontvangt dit en navigeert automatisch naar index.php.
// Dit is een server-side redirect - de gebruiker ziet de huidige pagina niet.
//
// exit; stopt het PHP-script ONMIDDELLIJK na de redirect. Dit is ESSENTIEEL
// omdat header() alleen een HTTP-header stuurt, maar de rest van het script
// zou normaal gewoon doorgaan met uitvoeren. Zonder exit zou de pagina
// alsnog worden opgebouwd en eventueel gevoelige informatie kunnen lekken.
// ============================================================================
// Redirect als al ingelogd
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// ============================================================================
// Initialiseer de variabele $fout als een lege string (tekst).
// Deze variabele wordt gebruikt om foutmeldingen op te slaan, zoals:
//   - "Gebruikersnaam mag niet leeg zijn"
//   - "Ongeldig e-mailadres"
//   - "Wachtwoord moet minimaal 8 tekens lang zijn"
//   - "Dit e-mailadres is al in gebruik"
// We beginnen met een lege string zodat er GEEN foutmelding wordt getoond
// wanneer de pagina voor het eerst wordt geladen (GET verzoek, nog niets
// ingevuld door de gebruiker).
// ============================================================================
$fout = '';

// ============================================================================
// FORMULIER VERWERKING (POST verzoek afhandeling)
// ============================================================================
// $_SERVER is een PHP superglobal array met informatie over de server en
// het huidige HTTP-verzoek.
// $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode: 'GET' of 'POST'.
//   - 'GET': de pagina is normaal geopend via de URL (eerste bezoek).
//   - 'POST': het formulier is verzonden (gebruiker klikte op de knop).
// We controleren op 'POST' zodat we ALLEEN formuliergegevens verwerken
// wanneer de gebruiker daadwerkelijk het formulier heeft ingevuld en verstuurd.
// ============================================================================
// Verwerk formulier verzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ========================================================================
    // FORMULIERGEGEVENS OPHALEN
    // ========================================================================
    // $_POST is een PHP superglobal array die alle gegevens bevat die via
    // de POST-methode zijn verstuurd vanuit het HTML-formulier.
    //
    // $_POST['username'] haalt de waarde op van het invoerveld met
    // name="username" uit het formulier. Dit is de gekozen gebruikersnaam.
    //
    // De ?? '' operator is de "null coalescing operator" (ingevoerd in PHP 7):
    //   - Als $_POST['username'] BESTAAT en NIET null is: gebruik die waarde.
    //   - Als $_POST['username'] NIET bestaat of null is: gebruik '' (leeg).
    // Dit beschermt tegen fouten als iemand het formulier manipuleert
    // (bijv. via browser ontwikkelaarstools) en een veld weglaat.
    // ========================================================================
    $gebruikersnaam = $_POST['username'] ?? '';

    // ========================================================================
    // $_POST['email'] haalt het ingevulde e-mailadres op uit het formulier.
    // Dit is het veld met name="email" in de HTML.
    // De ?? '' geeft een lege string als het veld niet bestaat of null is.
    // ========================================================================
    $emailAdres = $_POST['email'] ?? '';

    // ========================================================================
    // $_POST['password'] haalt het ingevulde wachtwoord op uit het formulier.
    // Dit is het veld met name="password" in de HTML.
    // BELANGRIJK: op dit moment is het wachtwoord PLATTE TEKST. Het wordt
    // pas gehasht (versleuteld) in de registerUser() functie met bcrypt.
    // De ?? '' geeft een lege string als het veld niet bestaat of null is.
    // ========================================================================
    $wachtwoord = $_POST['password'] ?? '';

    // ========================================================================
    // REGISTRATIE UITVOEREN
    // ========================================================================
    // registerUser() is een functie uit functions.php die het volledige
    // registratieproces afhandelt:
    //   1. Controleert of geen van de velden leeg is.
    //   2. Verwijdert voor- en achterliggende spaties (trim).
    //   3. Controleert of het e-mailadres een geldig formaat heeft.
    //   4. Controleert of het wachtwoord minstens 8 tekens lang is.
    //   5. Controleert of het e-mailadres niet al in gebruik is (uniekheid).
    //   6. Hasht het wachtwoord met bcrypt via password_hash().
    //      Bcrypt is een veilig hashing-algoritme dat:
    //        - Een "salt" (willekeurige tekst) toevoegt aan het wachtwoord.
    //        - Het resultaat meerdere keren hasht (key stretching).
    //        - Het ONMOGELIJK maakt om het originele wachtwoord terug te
    //          berekenen uit de hash (eenrichting-encryptie).
    //   7. Slaat de gebruikersnaam, het e-mailadres en de wachtwoord-hash
    //      op in de database.
    //
    // Retourwaarde:
    //   - null (geen waarde): registratie is GELUKT. Geen foutmelding.
    //   - Een string (tekst): er is een fout opgetreden. De string bevat
    //     de foutmelding die aan de gebruiker wordt getoond.
    // ========================================================================
    // Probeer te registreren - retourneert foutmelding of null bij succes
    $fout = registerUser($gebruikersnaam, $emailAdres, $wachtwoord);

    // ========================================================================
    // SUCCESVOLLE REGISTRATIE AFHANDELING
    // ========================================================================
    // !$fout controleert of $fout LEEG of null is (geen foutmelding).
    // In PHP zijn lege strings '' en null beide "falsy" (onwaar).
    // !$fout (NOT $fout) wordt TRUE als $fout leeg/null is.
    //
    // Als de registratie SUCCESVOL was (geen fout):
    //   1. setMessage('success', 'Registratie succesvol! Log nu in.') slaat
    //      een succesbericht op in de PHP-sessie ($_SESSION). Dit bericht
    //      wordt NIET op deze pagina getoond, maar op de VOLGENDE pagina
    //      (login.php) nadat de redirect heeft plaatsgevonden. Dit patroon
    //      heet "Post/Redirect/Get" (PRG) en voorkomt dat het formulier
    //      per ongeluk opnieuw wordt verstuurd als de gebruiker de pagina
    //      ververst. Het 'success' type zorgt voor een groene melding.
    //   2. header("Location: login.php") stuurt de browser door naar de
    //      inlogpagina waar de gebruiker kan inloggen met het nieuwe account.
    //   3. exit; stopt het PHP-script onmiddellijk na de redirect.
    //
    // Als er WEL een fout is: we doen niets hier, de foutmelding wordt
    // later in het HTML-gedeelte aan de gebruiker getoond.
    // ========================================================================
    if (!$fout) {
        setMessage('success', 'Registratie succesvol! Log nu in.');
        header("Location: login.php");
        exit;
    }
}
?>
<!-- ======================================================================== -->
<!-- HTML GEDEELTE - Alles hieronder is de zichtbare pagina voor de gebruiker -->
<!-- ======================================================================== -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5 document is.               -->
<!-- Dit is VERPLICHT als eerste regel van elk HTML-document. Zonder deze          -->
<!-- declaratie kan de browser terugvallen op "quirks mode", een oudere            -->
<!-- weergavemodus die voor onverwachte lay-out problemen kan zorgen.              -->
<!DOCTYPE html>

<!-- <html> is het HOOFD-element (root element) van de hele HTML-pagina.           -->
<!-- lang="nl" geeft aan dat de inhoud van de pagina in het Nederlands is.         -->
<!-- Dit attribuut is belangrijk voor:                                             -->
<!--   - Schermlezers (bijv. NVDA, JAWS) die de juiste taal uitspreken            -->
<!--   - Zoekmachines (Google) die de taal van de pagina begrijpen                -->
<!--   - De browser die de juiste spellingcontrole kan toepassen                   -->
<!--   - Vertaaltools die weten of de pagina vertaald moet worden                 -->
<html lang="nl">

<!-- <head> bevat METADATA: informatie OVER de pagina die niet direct zichtbaar    -->
<!-- is op het scherm. Hierin staan: tekenset, viewport instellingen,              -->
<!-- beschrijving voor zoekmachines, de paginatitel, en links naar stylesheets.    -->

<head>
    <!-- meta charset="UTF-8" stelt de tekencodering in op UTF-8.                 -->
    <!-- UTF-8 (Unicode Transformation Format - 8 bit) kan ALLE tekens van ALLE   -->
    <!-- talen ter wereld weergeven, plus emoji's en speciale symbolen.            -->
    <!-- Voor Nederlands is dit belangrijk voor tekens zoals e met trema.          -->
    <!-- Zonder deze instelling kunnen speciale tekens als vraagtekens of rare     -->
    <!-- symbolen verschijnen (mojibake/garbled text).                             -->
    <meta charset="UTF-8">

    <!-- meta viewport maakt de pagina RESPONSIVE (past zich aan schermgrootte aan). -->
    <!-- width=device-width: de breedte van de pagina wordt gelijk aan de           -->
    <!--   breedte van het apparaat. Op een telefoon is dat bijv. 375px,            -->
    <!--   op een tablet 768px, op een desktop 1920px.                             -->
    <!-- initial-scale=1.0: de pagina wordt op 100% zoom geladen (niet ingezoomd    -->
    <!--   of uitgezoomd). Schaal 1.0 = normaal formaat.                           -->
    <!-- Zonder deze meta tag zou de pagina op mobiele apparaten worden weergegeven  -->
    <!-- alsof het een desktop-pagina is (heel klein en onleesbaar), en moet de     -->
    <!-- gebruiker handmatig inzoomen.                                              -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- meta description geeft een korte beschrijving van deze pagina.             -->
    <!-- Deze tekst wordt NIET op de pagina zelf getoond, maar wordt gebruikt door: -->
    <!--   - Google en andere zoekmachines: de tekst verschijnt onder de link in     -->
    <!--     de zoekresultaten als beschrijving.                                     -->
    <!--   - Sociale media (Facebook, Twitter): bij het delen van de link.           -->
    <!-- Een goede description helpt bij SEO (zoekmachine optimalisatie) en          -->
    <!-- trekt meer bezoekers aan vanuit zoekresultaten.                             -->
    <meta name="description" content="Registreer voor GamePlan Scheduler - Maak je gaming profiel aan">

    <!-- <title> is de paginatitel die verschijnt in:                                -->
    <!--   - Het browsertabblad bovenaan de browser                                  -->
    <!--   - De taakbalk van het besturingssysteem                                   -->
    <!--   - Bladwijzers/favorieten als je de pagina opslaat                          -->
    <!--   - Zoekresultaten van Google als klikbare link                              -->
    <title>Registreren - GamePlan Scheduler</title>

    <!-- Laad Bootstrap 5.3.3 CSS via een CDN (Content Delivery Network).           -->
    <!-- Bootstrap is een populair CSS-framework ontwikkeld door Twitter dat         -->
    <!-- kant-en-klare stijlen biedt voor formulieren, knoppen, lay-out, etc.       -->
    <!-- CDN (Content Delivery Network) is een netwerk van servers wereldwijd        -->
    <!-- die het bestand snel kunnen leveren. Voordelen:                             -->
    <!--   - Snellere laadtijd (server dichtbij de gebruiker).                      -->
    <!--   - Caching: als de gebruiker Bootstrap al eerder heeft geladen op een      -->
    <!--     andere website, hoeft het niet opnieuw te worden gedownload.            -->
    <!--   - Minder belasting op onze eigen server.                                 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Laad ons eigen CSS-bestand met aangepaste stijlen voor GamePlan Scheduler. -->
    <!-- Dit bestand bevat stijlen die SPECIFIEK zijn voor onze applicatie,          -->
    <!-- zoals het donkere kleurenschema, de auth-container stijl, en andere         -->
    <!-- visuele aanpassingen die Bootstrap niet standaard biedt.                    -->
    <!-- Als er overlappende stijlen zijn, wint style.css omdat het NA Bootstrap     -->
    <!-- wordt geladen (CSS cascade: later geladen = hogere prioriteit).             -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- <body> bevat ALLE zichtbare inhoud van de webpagina.                           -->
<!-- class="bg-dark text-light" zijn twee Bootstrap utility-klassen:                 -->
<!--   - bg-dark: zet de achtergrondkleur op donker (#212529, bijna zwart).          -->
<!--     Dit creëert het donkere thema dat populair is bij gaming-applicaties.       -->
<!--   - text-light: zet de standaard tekstkleur op licht (#f8f9fa, bijna wit).     -->
<!--     Dit zorgt voor goed contrast tegen de donkere achtergrond,                 -->
<!--     waardoor de tekst goed leesbaar is (WCAG toegankelijkheidsrichtlijnen).     -->

<body class="bg-dark text-light">

    <!-- ====================================================================== -->
    <!-- HOOFD CONTAINER - Omvat de hele pagina-inhoud                          -->
    <!-- ====================================================================== -->
    <!-- class="container" is een Bootstrap lay-outklasse die:                  -->
    <!--   - De inhoud horizontaal centreert op de pagina.                      -->
    <!--   - Een responsive maximale breedte instelt:                           -->
    <!--       * Extra klein (<576px): 100% breedte                             -->
    <!--       * Klein (>=576px): 540px                                         -->
    <!--       * Middel (>=768px): 720px                                        -->
    <!--       * Groot (>=992px): 960px                                         -->
    <!--       * Extra groot (>=1200px): 1140px                                 -->
    <!--   - Links en rechts automatische marges toevoegt.                      -->
    <!-- class="mt-5" is Bootstrap spacing utility: margin-top met waarde 5.   -->
    <!--   Waarde 5 = 3rem = 48px. Dit duwt de inhoud naar beneden.            -->
    <!-- class="pt-5" is Bootstrap spacing utility: padding-top met waarde 5.  -->
    <!--   Padding is BINNENRUIMTE (binnen het element), margin is BUITENRUIMTE. -->
    <!--   Samen met mt-5 zorgt dit voor voldoende ruimte boven het formulier.  -->
    <div class="container mt-5 pt-5">

        <!-- auth-container is een EIGEN CSS-klasse gedefinieerd in style.css.   -->
        <!-- Deze klasse geeft het registratieformulier een kaart-achtige stijl   -->
        <!-- met een beperkte breedte, padding rondom, een donkere achtergrond,   -->
        <!-- en afgeronde hoeken. Dit maakt het formulier visueel aantrekkelijk   -->
        <!-- en houdt het compact in het midden van het scherm.                   -->
        <div class="auth-container">

            <!-- <h1> is een niveau-1 koptekst: de belangrijkste en grootste kop.  -->
            <!-- Er hoort maar EEN <h1> per pagina te zijn (voor SEO en structuur). -->
            <!-- class="text-center" is Bootstrap: centreert de tekst horizontaal.  -->
            <!-- class="mb-4" is Bootstrap: margin-bottom: 1.5rem (24px).           -->
            <!--   Dit voegt ruimte toe ONDER de kop, zodat er een nette scheiding  -->
            <!--   is tussen de titel en het formulier eronder.                     -->
            <h1 class="text-center mb-4">🎮 Registreren</h1>

            <!-- ============================================================== -->
            <!-- FOUTMELDING WEERGAVE                                           -->
            <!-- ============================================================== -->
            <!-- Dit PHP-blok controleert of de variabele $fout een waarde heeft. -->
            <!-- De alternatieve if-syntaxis (if(): ... endif;) wordt hier        -->
            <!-- gebruikt in plaats van accolades { } omdat dit BETER LEESBAAR    -->
            <!-- is wanneer PHP en HTML door elkaar worden gebruikt.               -->
            <!-- Als $fout NIET leeg is (er is een fout opgetreden bij de          -->
            <!-- registratie), dan wordt de rode foutmelding-div getoond.          -->
            <!-- Als $fout WEL leeg is (eerste bezoek of succesvolle registratie), -->
            <!-- dan wordt de hele foutmelding-sectie OVERGESLAGEN.                -->
            <!-- Toon foutmelding -->
            <?php if ($fout): ?>

                <!-- class="alert" is een Bootstrap component voor meldingen.       -->
                <!-- class="alert-danger" maakt de melding ROOD, wat aangeeft       -->
                <!--   dat er een FOUT is opgetreden. De rode kleur is een           -->
                <!--   universele visuele aanduiding voor problemen/fouten.          -->
                <!-- safeEcho() geeft de foutmelding VEILIG weer door speciale       -->
                <!--   tekens te escapen met htmlspecialchars(). Dit voorkomt        -->
                <!--   XSS-aanvallen (Cross-Site Scripting) waarbij iemand           -->
                <!--   kwaadaardige JavaScript-code zou kunnen injecteren via        -->
                <!--   het formulier, bijv. als gebruikersnaam.                     -->
                <div class="alert alert-danger"><?php echo safeEcho($fout); ?></div>

                <!-- endif; sluit het PHP if-blok (alternatieve syntaxis).              -->
            <?php endif; ?>

            <!-- ============================================================== -->
            <!-- REGISTRATIE FORMULIER                                          -->
            <!-- ============================================================== -->
            <!-- <form> maakt een HTML-formulier waarmee de gebruiker gegevens   -->
            <!--   kan invullen en naar de server versturen.                     -->
            <!-- method="POST" bepaalt de verzendmethode:                        -->
            <!--   - POST: gegevens worden verborgen in de HTTP request body.    -->
            <!--     NIET zichtbaar in de URL, browsergeschiedenis of logs.      -->
            <!--     Noodzakelijk voor wachtwoorden en gevoelige gegevens.       -->
            <!--   - GET (alternatief): gegevens staan in de URL (?key=value).   -->
            <!--     Dit zou ONVEILIG zijn voor wachtwoorden!                    -->
            <!-- Geen action-attribuut: het formulier stuurt naar DEZELFDE       -->
            <!--   pagina (register.php). Dit is een "self-submitting form".    -->
            <!-- onsubmit="return validateRegisterForm();" roept een JavaScript  -->
            <!--   functie aan uit script.js VOORDAT het formulier wordt         -->
            <!--   verstuurd:                                                    -->
            <!--   - TRUE: de validatie is geslaagd, formulier wordt verstuurd.  -->
            <!--   - FALSE: er is een fout, het formulier wordt NIET verstuurd   -->
            <!--     en de gebruiker ziet een melding in de browser.             -->
            <!--   Dit is CLIENT-SIDE validatie (browser) als EXTRA laag         -->
            <!--   bovenop de SERVER-SIDE validatie (PHP). Client-side validatie -->
            <!--   alleen is NIET voldoende omdat JavaScript uitgeschakeld       -->
            <!--   of omzeild kan worden.                                        -->
            <!-- Registratie formulier -->
            <form method="POST" onsubmit="return validateRegisterForm();">

                <!-- ========================================================== -->
                <!-- GEBRUIKERSNAAM INVOERVELD                                  -->
                <!-- ========================================================== -->
                <!-- Gebruikersnaam veld -->

                <!-- class="mb-3" is Bootstrap: margin-bottom: 1rem (16px).      -->
                <!--   Dit voegt verticale ruimte toe ONDER deze div, zodat de    -->
                <!--   formuliervelden niet tegen elkaar aan staan.               -->
                <div class="mb-3">

                    <!-- <label> beschrijft het invoerveld voor de gebruiker.          -->
                    <!-- for="username" koppelt dit label aan het veld met             -->
                    <!--   id="username". Klikken op het label zet de cursor in het    -->
                    <!--   invoerveld (verbetert bruikbaarheid en toegankelijkheid).   -->
                    <!-- class="form-label" is Bootstrap styling: juiste marge en      -->
                    <!--   lettertypegrootte voor formulierlabels.                     -->
                    <label for="username" class="form-label">👤 Gebruikersnaam</label>

                    <!-- <input> is het invoerveld waar de gebruiker typt.              -->
                    <!-- type="text" is een standaard tekstveld zonder speciale          -->
                    <!--   validatie. De gebruiker kan letters, cijfers en speciale      -->
                    <!--   tekens invoeren.                                              -->
                    <!-- id="username" is de unieke identificatie van dit element,       -->
                    <!--   gebruikt door het label (for="username"), CSS en JavaScript.  -->
                    <!-- name="username" is de SLEUTEL voor PHP. Na verzending is de     -->
                    <!--   waarde beschikbaar als $_POST['username']. Zonder het         -->
                    <!--   name-attribuut wordt het veld NIET meegestuurd naar de server. -->
                    <!-- class="form-control" is Bootstrap styling voor invoervelden:    -->
                    <!--   100% breedte, padding, afgeronde hoeken, en een blauwe        -->
                    <!--   gloed bij focus (als de cursor erin staat).                   -->
                    <!-- class="form-control-lg" vergroot het invoerveld: grotere tekst  -->
                    <!--   en meer interne ruimte (padding). Dit maakt het veld           -->
                    <!--   makkelijker te gebruiken, vooral op aanraakschermen.           -->
                    <!-- required is een HTML5-attribuut dat het veld VERPLICHT maakt.   -->
                    <!--   De browser BLOKKEERT het verzenden van het formulier als dit   -->
                    <!--   veld leeg is. Er verschijnt een melding "Vul dit veld in".    -->
                    <!--   Dit is browser-validatie (client-side) als eerste controlelaag. -->
                    <!-- maxlength="50" beperkt de invoer tot MAXIMAAL 50 tekens.        -->
                    <!--   Als de gebruiker meer dan 50 tekens probeert te typen,         -->
                    <!--   worden de extra tekens simpelweg NIET geaccepteerd door de     -->
                    <!--   browser. De cursor stopt en er kan niet meer getypt worden.    -->
                    <!--   Dit beschermt tegen:                                          -->
                    <!--   - Te lange gebruikersnamen die de lay-out breken.              -->
                    <!--   - Database overflow (als het database-veld ook max 50 is).     -->
                    <!--   - Spam of misbruik met extreem lange teksten.                  -->
                    <!-- placeholder="Jouw gamer naam" is grijze voorbeeldtekst die      -->
                    <!--   in het veld verschijnt als het LEEG is. Het verdwijnt zodra    -->
                    <!--   de gebruiker begint te typen. Het geeft een hint over wat      -->
                    <!--   er ingevuld moet worden.                                      -->
                    <!-- aria-label="Gebruikersnaam" is een ARIA-attribuut voor           -->
                    <!--   toegankelijkheid. Schermlezers lezen dit voor om blinde        -->
                    <!--   gebruikers te informeren over het doel van het invoerveld.     -->
                    <input type="text" id="username" name="username" class="form-control form-control-lg" required
                        maxlength="50" placeholder="Jouw gamer naam" aria-label="Gebruikersnaam">

                    <!-- <small> maakt de tekst kleiner dan de standaardgrootte.          -->
                    <!-- class="text-secondary" is Bootstrap: geeft de tekst een grijze  -->
                    <!--   kleur (#6c757d). Dit is minder opvallend dan de witte tekst,  -->
                    <!--   wat aangeeft dat het een ondersteunende/hulptekst is.          -->
                    <!-- Deze tekst informeert de gebruiker over de maximale lengte,      -->
                    <!--   zodat ze weten VOORDAT ze te veel tekens proberen te typen.    -->
                    <small class="text-secondary">Maximaal 50 tekens</small>
                </div>

                <!-- ========================================================== -->
                <!-- E-MAIL INVOERVELD                                          -->
                <!-- ========================================================== -->
                <!-- E-mail veld -->

                <!-- class="mb-3" voegt 1rem (16px) marge toe aan de onderkant.  -->
                <div class="mb-3">

                    <!-- <label> voor het e-mailveld, gekoppeld via for="email".  -->
                    <!-- class="form-label" voegt Bootstrap label-styling toe.    -->
                    <label for="email" class="form-label">📧 E-mailadres</label>

                    <!-- type="email" vertelt de browser dat dit een e-mailveld is.     -->
                    <!--   De browser valideert automatisch het formaat:                 -->
                    <!--   - Moet een @ teken bevatten.                                 -->
                    <!--   - Moet een domeinnaam hebben na de @.                        -->
                    <!--   - Op mobiel verschijnt een speciaal toetsenbord met @ en .com. -->
                    <!-- id="email" is de unieke identificatie voor label-koppeling,     -->
                    <!--   CSS-styling, en JavaScript DOM-manipulatie.                    -->
                    <!-- name="email" is de sleutel voor PHP: $_POST['email'].            -->
                    <!-- class="form-control form-control-lg" is Bootstrap styling:      -->
                    <!--   breed invoerveld met grote tekst en ruime padding.             -->
                    <!-- required maakt het veld verplicht (browser blokkeert lege        -->
                    <!--   formulierverzending).                                          -->
                    <!-- placeholder="jouw@email.com" toont een voorbeeld van het         -->
                    <!--   verwachte e-mailformaat als grijze tekst.                      -->
                    <!-- aria-label="E-mailadres" is voor schermlezers.                  -->
                    <input type="email" id="email" name="email" class="form-control form-control-lg" required
                        placeholder="jouw@email.com" aria-label="E-mailadres">

                    <!-- Hulptekst die de gebruiker informeert dat dit e-mailadres        -->
                    <!-- later gebruikt wordt om in te loggen. Belangrijke informatie      -->
                    <!-- zodat de gebruiker een e-mailadres kiest dat ze onthouden.        -->
                    <!-- class="text-secondary" maakt het grijs en minder opvallend.       -->
                    <small class="text-secondary">Wordt gebruikt voor inloggen</small>
                </div>

                <!-- ========================================================== -->
                <!-- WACHTWOORD INVOERVELD                                      -->
                <!-- ========================================================== -->
                <!-- Wachtwoord veld -->

                <!-- class="mb-4" is Bootstrap: margin-bottom: 1.5rem (24px).    -->
                <!--   Dit is iets meer ruimte dan mb-3 omdat dit het LAATSTE     -->
                <!--   veld voor de verzendknop is. De grotere marge geeft een    -->
                <!--   duidelijke visuele scheiding.                              -->
                <div class="mb-4">

                    <!-- <label> voor het wachtwoord veld, gekoppeld via for="password". -->
                    <!-- class="form-label" is Bootstrap label-styling.                  -->
                    <label for="password" class="form-label">🔒 Wachtwoord</label>

                    <!-- type="password" verbergt de ingevoerde tekst als puntjes         -->
                    <!--   of sterretjes. Dit is ESSENTIEEL voor beveiliging zodat        -->
                    <!--   niemand die meekijkt het wachtwoord kan lezen (schouder-surfen). -->
                    <!-- id="password" is de unieke identificatie voor dit element.       -->
                    <!-- name="password" is de sleutel voor PHP: $_POST['password'].      -->
                    <!--   De waarde wordt in PHP verwerkt door registerUser() die het    -->
                    <!--   wachtwoord hasht met bcrypt voordat het in de database wordt   -->
                    <!--   opgeslagen. Het platte wachtwoord wordt NOOIT bewaard.         -->
                    <!-- class="form-control form-control-lg" is Bootstrap styling.      -->
                    <!-- required maakt het veld verplicht (browser validatie).           -->
                    <!-- minlength="8" is een HTML5-attribuut dat een MINIMALE lengte    -->
                    <!--   vereist van 8 tekens. Als de gebruiker minder dan 8 tekens    -->
                    <!--   invult en het formulier probeert te verzenden:                 -->
                    <!--   - De browser BLOKKEERT de verzending.                          -->
                    <!--   - Er verschijnt een foutmelding: "Gebruik ten minste 8 tekens". -->
                    <!--   De keuze voor 8 tekens is een veelgebruikte beveiligingsnorm:  -->
                    <!--   - Kortere wachtwoorden zijn te makkelijk te raden of kraken.   -->
                    <!--   - 8 tekens biedt een goede balans tussen veiligheid en gemak.  -->
                    <!--   Net als bij maxlength is dit CLIENT-SIDE validatie. De server  -->
                    <!--   (PHP) controleert het ook nog eens als extra beveiliging.      -->
                    <!-- placeholder="Minimaal 8 tekens" informeert de gebruiker over de -->
                    <!--   minimale vereiste lengte.                                      -->
                    <!-- aria-label="Wachtwoord" is voor toegankelijkheid/schermlezers.  -->
                    <input type="password" id="password" name="password" class="form-control form-control-lg" required
                        minlength="8" placeholder="Minimaal 8 tekens" aria-label="Wachtwoord">

                    <!-- Hulptekst die de minimale wachtwoordlengte benadrukt.            -->
                    <!-- "voor veiligheid" legt uit WAAROM 8 tekens vereist zijn.          -->
                    <!-- class="text-secondary" maakt de tekst grijs en ondersteunend.    -->
                    <small class="text-secondary">Minimaal 8 tekens voor veiligheid</small>
                </div>

                <!-- ========================================================== -->
                <!-- VERZEND KNOP                                               -->
                <!-- ========================================================== -->
                <!-- Verzend knop -->

                <!-- <button> maakt een klikbare knop aan.                        -->
                <!-- type="submit" betekent dat klikken op deze knop het          -->
                <!--   formulier VERSTUURT naar de server (POST verzoek).        -->
                <!--   Dit triggert eerst de onsubmit JavaScript-validatie,       -->
                <!--   dan de browser validatie (required, minlength, etc.),     -->
                <!--   en als alles slaagt, worden de gegevens naar PHP gestuurd. -->
                <!-- class="btn" is de Bootstrap basisklasse voor alle knoppen:  -->
                <!--   cursor-pointer, padding, tekstcentrering, hover-effect.   -->
                <!-- class="btn-primary" geeft de knop de PRIMAIRE kleur (blauw, -->
                <!--   #0d6efd). Dit is de standaard actieknop in Bootstrap.     -->
                <!-- class="btn-lg" vergroot de knop (meer padding en tekst).    -->
                <!-- class="w-100" is Bootstrap shorthand voor width: 100%.      -->
                <!--   De knop neemt de VOLLEDIGE breedte van het formulier in.  -->
                <!--   Dit maakt de knop prominent en gemakkelijk klikbaar.       -->
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    ✨ Account Aanmaken
                </button>

                <!-- Sluiting van het <form>-element.                                -->
            </form>

            <!-- ============================================================== -->
            <!-- LINK NAAR LOGIN PAGINA VOOR BESTAANDE GEBRUIKERS               -->
            <!-- ============================================================== -->
            <!-- Deze sectie biedt een link voor gebruikers die AL een account   -->
            <!-- hebben. Ze hoeven zich dan niet opnieuw te registreren, maar    -->
            <!-- kunnen direct naar de inlogpagina gaan.                        -->
            <!-- Link naar login voor bestaande gebruikers -->

            <!-- <p> is een paragraaf (alinea) element voor tekst.               -->
            <!-- class="text-center" centreert de tekst horizontaal.             -->
            <!-- class="mt-4" is Bootstrap: margin-top: 1.5rem (24px).           -->
            <!--   Ruimte BOVEN de paragraaf, na het formulier.                  -->
            <!-- class="mb-0" is Bootstrap: margin-bottom: 0px.                  -->
            <!--   Verwijdert de standaard paragraaf-marge onderaan zodat er     -->
            <!--   geen overtollige ruimte aan de onderkant van de container is. -->
            <p class="text-center mt-4 mb-0">
                Al een account?

                <!-- <a> is een hyperlink die naar een andere pagina verwijst.    -->
                <!-- href="login.php" is het adres waarnaar de link navigeert.   -->
                <!--   Na het klikken wordt login.php geopend.                   -->
                <!-- class="text-info" is Bootstrap: cyaan/lichtblauwe kleur     -->
                <!--   (#0dcaf0). Dit maakt de link goed zichtbaar tegen de      -->
                <!--   donkere achtergrond en onderscheidt het van gewone tekst. -->
                <a href="login.php" class="text-info">Log hier in</a>
            </p>

            <!-- Sluiting van de auth-container div. -->
        </div>

        <!-- Sluiting van de container div. -->
    </div>

    <!-- ====================================================================== -->
    <!-- JAVASCRIPT BESTANDEN                                                   -->
    <!-- ====================================================================== -->
    <!-- Scripts worden onderaan de <body> geplaatst (in plaats van in <head>)   -->
    <!-- omdat de browser HTML van BOVEN naar BENEDEN laadt. Door scripts        -->
    <!-- onderaan te zetten, wordt de zichtbare inhoud (HTML + CSS) EERST        -->
    <!-- geladen en weergegeven. Dan pas worden de scripts geladen. Dit maakt    -->
    <!-- de pagina SNELLER zichtbaar voor de gebruiker.                          -->

    <!-- Laad Bootstrap JavaScript via CDN.                                     -->
    <!-- Dit bestand bevat de JavaScript-logica voor interactieve Bootstrap-     -->
    <!-- componenten zoals dropdown menu's, modale vensters, tooltips, en       -->
    <!-- accordion elementen.                                                   -->
    <!-- "bundle" in de bestandsnaam betekent dat Popper.js (een bibliotheek     -->
    <!-- voor het positioneren van dropdown menu's en tooltips) al is            -->
    <!-- INBEGREPEN, zodat we het niet apart hoeven te laden.                    -->
    <!-- ".min" in de bestandsnaam betekent dat het bestand GEMINIFICEERD is:    -->
    <!-- alle overbodige spaties, enters en commentaren zijn verwijderd om het   -->
    <!-- bestandsgrootte kleiner te maken en de laadtijd te verkorten.           -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Laad ons eigen JavaScript-bestand met aangepaste functies.              -->
    <!-- Dit bestand bevat onder andere:                                         -->
    <!--   - validateRegisterForm(): controleert of de gebruikersnaam, e-mail    -->
    <!--     en wachtwoord correct zijn ingevuld VOORDAT het formulier wordt     -->
    <!--     verstuurd naar de server.                                           -->
    <!--   - Andere client-side validatiefuncties en eventuele UI-interacties.   -->
    <!-- Client-side validatie (in de browser) geeft SNELLE feedback, maar is    -->
    <!-- NIET voldoende als enige beveiliging. Server-side validatie (PHP) is    -->
    <!-- altijd NOODZAKELIJK omdat JavaScript door de gebruiker kan worden       -->
    <!-- uitgeschakeld of omzeild via browser ontwikkelaarstools.               -->
    <script src="script.js"></script>

    <!-- Sluiting van het <body>-element. Alle zichtbare inhoud staat hierboven.  -->
</body>

<!-- Sluiting van het <html>-element. Dit is het einde van het hele document. -->

</html>