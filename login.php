<?php
/**
 * ===========================================================================
 * LOGIN.PHP - INLOG PAGINA
 * ===========================================================================
 * Bestandsnaam : login.php
 * Auteur       : Harsha Kanaparthi
 * Studentnummer: 2195344
 * Opleiding    : MBO-4 Software Developer (Crebo 25998)
 * Datum        : 30-09-2025
 * Versie       : 1.0
 * PHP-versie   : 8.1+
 * Encoding     : UTF-8
 *
 * ===========================================================================
 * BESCHRIJVING
 * ===========================================================================
 * Dit bestand is de INLOGPAGINA van GamePlan Scheduler.
 * Hier logt een gebruiker in met e-mail en wachtwoord.
 * Bij succes wordt een sessie gestart en volgt een redirect naar het dashboard.
 *
 * ===========================================================================
 * STRUCTUUR EN FLOW
 * ===========================================================================
 * ┌─────────────────────────────────────────────────────────────────────┐
 * │ 1. Pagina laadt: require_once 'functions.php'                      │
 * │ 2. Sessiecontrole: isLoggedIn() → redirect indien ingelogd         │
 * │ 3. Formulier tonen (GET)                                          │
 * │ 4. Formulier verwerken (POST): loginUser()                        │
 * │ 5. Sessie aanmaken bij succes, foutmelding tonen bij mislukking    │
 * │ 6. Link naar registratiepagina                                    │
 * └─────────────────────────────────────────────────────────────────────┘
 *
 * ===========================================================================
 * BEVEILIGING (Security)
 * ===========================================================================
 * 1. INLOG-CONTROLE: isLoggedIn() + redirect naar index.php
 *    → OWASP A01: Broken Access Control voorkomen
 * 2. SESSIE-BEHEER: sessie wordt veilig aangemaakt en vernietigd
 * 3. WACHTWOORD HASHING: wachtwoorden worden gehasht met bcrypt
 *    → password_verify() in loginUser()
 * 4. SQL-INJECTIE: loginUser() gebruikt prepared statements (PDO)
 *    → OWASP A03: Injection voorkomen
 * 5. XSS-BESCHERMING: safeEcho() voor foutmeldingen
 *    → OWASP A07: Cross-Site Scripting voorkomen
 * 6. CLIENT-SIDE VALIDATIE: validateLoginForm() in script.js
 *    → Extra laag, maar server-side validatie blijft verplicht
 *
 * ===========================================================================
 * DATABASE TABELLEN
 * ===========================================================================
 * Tabel: Users
 * ┌─────────────┬─────────────┬─────────────┬─────────────┐
 * │ id (PK)     │ email       │ wachtwoord  │ naam        │
 * └─────────────┴─────────────┴─────────────┴─────────────┘
 * loginUser() zoekt gebruiker op via e-mail en vergelijkt wachtwoord.
 *
 * ===========================================================================
 * VERGELIJKING MET ANDERE PAGINA'S
 * ===========================================================================
 * ┌───────────────┬───────────────┬───────────────┬───────────────┐
 * │ Eigenschap    │ login.php     │ register.php  │ index.php     │
 * ├───────────────┼───────────────┼───────────────┼───────────────┤
 * │ Doel          │ inloggen      │ registreren   │ dashboard     │
 * │ Sessie check  │ ja            │ ja            │ ja            │
 * │ Data ophalen  │ gebruiker     │ gebruiker     │ alles         │
 * │ Security      │ hoog          │ hoog          │ hoog          │
 * │ Validatie     │ server+client │ server+client │ server+client │
 * │ Redirect      │ index.php     │ index.php     │ n.v.t.        │
 * └───────────────┴───────────────┴───────────────┴───────────────┘
 *
 * ===========================================================================
 * GEBRUIKTE CONCEPTEN
 * ===========================================================================
 * PHP:
 *   - Functies, parameters, return values
 *   - Prepared statements (PDO)
 *   - Sessie beheer (session_start, session_destroy)
 *   - Validatie, exception handling
 *   - array_filter, foreach, empty()
 * HTML:
 *   - Formulieren, labels, invoervelden, knoppen
 *   - Bootstrap: container, form-control, btn, alert, mb-5, mt-5, pt-5
 *   - Client-side validatie via required, type="email", type="password"
 *   - Toegankelijkheid: aria-label, role="alert", lang="nl"
 * ==========================================================================
 * EXAMENNIVEAU: VOLLEDIG GEDOCUMENTEERD, OWASP, DATABASE, FLOW, VERGELIJKING
 * ==========================================================================
 */

// ============================================================================
// require_once laadt het bestand 'functions.php' in en voert het uit.
// 'require_once' betekent:
//   - 'require': het bestand MOET bestaan, anders stopt PHP met een fout.
//   - '_once': het bestand wordt maar EEN keer geladen, zelfs als je het
//     meerdere keren aanroept. Dit voorkomt dubbele functie-definities.
// In functions.php staan alle hulpfuncties zoals:
//   - Database verbinding (connectie met MySQL)
//   - loginUser() voor het inloggen
//   - isLoggedIn() om te checken of iemand ingelogd is
//   - safeEcho() om tekst veilig weer te geven (tegen XSS aanvallen)
//   - Sessie beheer functies (starten, controleren, vernietigen)
// ============================================================================
require_once 'functions.php';

// ============================================================================
// isLoggedIn() is een functie die controleert of de huidige bezoeker al een
// actieve sessie heeft (dus al ingelogd is).
// Het kijkt in de PHP-sessie ($_SESSION) of er een gebruikers-ID is opgeslagen.
// Als de gebruiker AL ingelogd is:
//   - header("Location: index.php") stuurt de browser door naar de hoofdpagina.
//     Dit is een HTTP 302 redirect header die de browser vertelt: "ga naar
//     index.php in plaats van deze pagina te laden".
//   - exit; stopt het PHP-script ONMIDDELLIJK. Zonder exit zou de rest van
//     de code nog steeds worden uitgevoerd, wat ongewenst is.
// Waarom doen we dit? Een ingelogde gebruiker hoeft het loginformulier
// niet te zien. Ze worden meteen doorgestuurd naar het dashboard.
// ============================================================================
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// ============================================================================
// Initialiseer de variabele $fout als een lege tekst (string).
// Deze variabele slaat eventuele foutmeldingen op, zoals:
//   - "Ongeldig e-mailadres" als het e-mail format niet klopt
//   - "Verkeerd wachtwoord" als het wachtwoord niet overeenkomt
//   - "Gebruiker niet gevonden" als het e-mailadres niet bestaat
// We beginnen met een lege string zodat er geen fout wordt getoond
// wanneer de pagina voor het eerst geladen wordt (GET verzoek).
// ============================================================================
$fout = '';

// ============================================================================
// FORMULIER VERWERKING (POST verzoek afhandeling)
// ============================================================================
// $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode waarmee de pagina is
// opgevraagd. Dit kan 'GET' of 'POST' zijn:
//   - 'GET': de gebruiker heeft de pagina geopend via de URL (normaal bezoek).
//   - 'POST': de gebruiker heeft het formulier verzonden (op de knop geklikt).
// We controleren of de methode 'POST' is, want alleen dan zijn er
// formuliergegevens beschikbaar om te verwerken.
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ========================================================================
    // $_POST is een PHP superglobal array die alle formuliergegevens bevat
    // die via de POST-methode zijn verzonden.
    // $_POST['email'] haalt de waarde op van het invoerveld met name="email".
    // De ?? '' (null coalescing operator) betekent:
    //   - Als $_POST['email'] BESTAAT en NIET null is: gebruik die waarde.
    //   - Als $_POST['email'] NIET bestaat of null is: gebruik '' (lege tekst).
    // Dit voorkomt PHP waarschuwingen als iemand het formulier manipuleert
    // en een veld weglaat (bijvoorbeeld via ontwikkelaarstools in de browser).
    // ========================================================================
    $emailAdres = $_POST['email'] ?? '';

    // ========================================================================
    // Hetzelfde als hierboven maar dan voor het wachtwoord invoerveld.
    // $_POST['password'] haalt de waarde op van het veld met name="password".
    // De ?? '' zorgt ervoor dat we altijd een string hebben, nooit null.
    // ========================================================================
    $wachtwoord = $_POST['password'] ?? '';

    // ========================================================================
    // loginUser() is een functie uit functions.php die het inlogproces afhandelt:
    //   1. Controleert of de e-mail en het wachtwoord niet leeg zijn.
    //   2. Zoekt de gebruiker op in de database aan de hand van het e-mailadres.
    //   3. Vergelijkt het ingevoerde wachtwoord met de gehashte versie in de
    //      database (met password_verify() voor bcrypt vergelijking).
    //   4. Bij succes: maakt een sessie aan met de gebruikersgegevens en
    //      retourneert null (geen fout).
    //   5. Bij een fout: retourneert een foutmelding als tekst (string).
    // De retourwaarde wordt opgeslagen in $fout.
    // ========================================================================
    $fout = loginUser($emailAdres, $wachtwoord);

    // ========================================================================
    // Controleer of het inloggen gelukt is.
    // !$fout betekent: als $fout LEEG is (geen foutmelding, dus succes).
    // In PHP is een lege string '' "falsy", dus !'' is true.
    // Als er GEEN fout is (inloggen geslaagd):
    //   - Stuur de browser door naar index.php (het dashboard/hoofdpagina).
    //   - Stop het script met exit; zodat de rest niet meer wordt uitgevoerd.
    // Als er WEL een fout is: we doen niets hier, de foutmelding wordt
    // later in het HTML-gedeelte getoond aan de gebruiker.
    // ========================================================================
    if (!$fout) {
        header("Location: index.php");
        exit;
    }
}
?>
<!-- ======================================================================== -->
<!-- HTML GEDEELTE - Alles hieronder is de zichtbare pagina voor de gebruiker -->
<!-- ======================================================================== -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5 document is.               -->
<!-- Zonder deze declaratie kan de browser de pagina in "quirks mode" weergeven,   -->
<!-- wat kan leiden tot onverwachte lay-out problemen.                             -->
<!DOCTYPE html>

<!-- <html> is het hoofdelement van de hele HTML-pagina.                           -->
<!-- lang="nl" vertelt de browser en zoekmachines dat de inhoud in het             -->
<!-- Nederlands is. Dit helpt bij:                                                -->
<!--   - Schermlezers (accessibility) die de juiste taal uitspreken               -->
<!--   - Zoekmachines (SEO) die de taal van de pagina begrijpen                   -->
<!--   - De browser die de juiste spellingcontrole toepast                         -->
<html lang="nl">

<!-- <head> bevat metadata (informatie OVER de pagina, niet zichtbaar op scherm).  -->
<!-- Hierin staan: tekenset, viewport, beschrijving, titel, en stylesheets.       -->

<head>
    <!-- meta charset="UTF-8" stelt de tekencodering in op UTF-8.                 -->
    <!-- UTF-8 ondersteunt alle internationale tekens, inclusief:                  -->
    <!--   - Nederlandse tekens zoals e met trema (e)                              -->
    <!--   - Emoji's zoals de game controller                                      -->
    <!--   - Speciale tekens uit andere talen                                      -->
    <!-- Zonder dit kunnen speciale tekens als rare symbolen verschijnen.          -->
    <meta charset="UTF-8">

    <!-- meta viewport maakt de pagina geschikt voor mobiele apparaten.            -->
    <!-- width=device-width: de breedte van de pagina past zich aan aan het        -->
    <!--   scherm van het apparaat (telefoon, tablet, computer).                   -->
    <!-- initial-scale=1.0: de pagina wordt niet ingezoomd of uitgezoomd bij       -->
    <!--   het eerste laden. Schaal 1.0 betekent 100% zoom.                        -->
    <!-- Zonder deze meta tag zou de pagina op een telefoon heel klein lijken,     -->
    <!-- alsof je een desktop-website op een klein scherm bekijkt.                 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- meta description geeft een korte beschrijving van de pagina.              -->
    <!-- Deze tekst wordt gebruikt door:                                           -->
    <!--   - Zoekmachines (Google) als beschrijving in zoekresultaten              -->
    <!--   - Sociale media bij het delen van de link                               -->
    <!-- Het helpt bij SEO (zoekmachine optimalisatie).                            -->
    <meta name="description" content="Inloggen bij GamePlan Scheduler - Beheer je gaming schema's en evenementen">

    <!-- <title> is de titel die verschijnt in:                                    -->
    <!--   - Het browsertabblad bovenaan                                           -->
    <!--   - Bladwijzers/favorieten als je de pagina opslaat                       -->
    <!--   - Zoekresultaten van Google als paginatitel                             -->
    <title>Inloggen - GamePlan Scheduler</title>

    <!-- Laad Bootstrap 5.3.3 CSS via een CDN (Content Delivery Network).         -->
    <!-- Bootstrap is een CSS-framework dat kant-en-klare stijlen biedt voor:     -->
    <!--   - Knoppen, formulieren, kaarten, navigatie, enzovoort                  -->
    <!--   - Responsive ontwerp (past zich aan verschillende schermformaten aan)   -->
    <!-- CDN betekent dat het bestand van een externe snelle server wordt geladen  -->
    <!-- in plaats van onze eigen server, wat sneller is voor de gebruiker.        -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Laad ons eigen CSS-bestand met aangepaste stijlen.                       -->
    <!-- Dit bestand bevat stijlen die specifiek zijn voor GamePlan Scheduler,     -->
    <!-- zoals kleuren, lettertypen en lay-out die niet in Bootstrap zitten.       -->
    <!-- Dit overschrijft of vult Bootstrap-stijlen aan waar nodig.               -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- <body> bevat alle zichtbare inhoud van de pagina.                             -->
<!-- class="bg-dark text-light" zijn Bootstrap CSS-klassen:                        -->
<!--   - bg-dark: geeft de achtergrond een donkere kleur (bijna zwart, #212529).  -->
<!--     Dit creëert het donkere thema van de applicatie.                          -->
<!--   - text-light: maakt alle tekst licht/wit (#f8f9fa).                        -->
<!--     Dit zorgt voor contrast tegen de donkere achtergrond zodat tekst          -->
<!--     goed leesbaar is.                                                        -->

<body class="bg-dark text-light">

    <!-- ====================================================================== -->
    <!-- HOOFD CONTAINER - Omvat de hele pagina-inhoud                          -->
    <!-- ====================================================================== -->
    <!-- class="container" is een Bootstrap klasse die:                          -->
    <!--   - De inhoud centreert op de pagina (horizontaal midden).              -->
    <!--   - Een maximale breedte instelt (bijv. 1140px op grote schermen).     -->
    <!--   - Links en rechts automatische marges toevoegt (auto margin).        -->
    <!-- class="mt-5" is Bootstrap voor margin-top: 3rem (48px).               -->
    <!--   De 5 is het hoogste niveau van ruimte in Bootstrap (0 t/m 5).       -->
    <!--   Dit duwt de inhoud naar beneden, weg van de bovenkant van de pagina. -->
    <!-- class="pt-5" is Bootstrap voor padding-top: 3rem (48px).              -->
    <!--   Padding is BINNENRUIMTE, margin is BUITENRUIMTE.                    -->
    <!--   Samen met mt-5 zorgt dit voor voldoende ruimte boven het formulier. -->
    <div class="container mt-5 pt-5">

        <!-- auth-container is een eigen CSS klasse (gedefinieerd in style.css). -->
        <!-- Deze klasse geeft het loginformulier een mooie kaart-achtige stijl  -->
        <!-- met een maximale breedte, padding, achtergrondkleur en afgeronde    -->
        <!-- hoeken zodat het formulier er netjes en professioneel uitziet.      -->
        <div class="auth-container">

            <!-- <h1> is een niveau-1 koptekst (de belangrijkste kop op de pagina). -->
            <!-- class="text-center" is Bootstrap: centreert de tekst horizontaal.   -->
            <!-- class="mb-4" is Bootstrap voor margin-bottom: 1.5rem (24px).        -->
            <!--   Dit voegt ruimte toe ONDER de kop, tussen de kop en het formulier. -->
            <!--   De 4 is een gemiddeld niveau van ruimte (schaal 0 t/m 5).         -->
            <h1 class="text-center mb-4">🎮 Inloggen</h1>

            <!-- ============================================================== -->
            <!-- FOUTMELDING WEERGAVE                                           -->
            <!-- ============================================================== -->
            <!-- Dit PHP-blok controleert of de variabele $fout een waarde heeft. -->
            <!-- if ($fout): gebruikt de alternatieve syntaxis van PHP (met dubbele -->
            <!-- punt in plaats van accolades), wat beter leesbaar is in HTML.      -->
            <!-- Als $fout NIET leeg is (er is een foutmelding na een mislukte      -->
            <!-- inlogpoging), dan wordt de <div> met de foutmelding getoond.      -->
            <!-- Als $fout WEL leeg is (eerste bezoek of geen fout), wordt deze     -->
            <!-- hele sectie overgeslagen en niet getoond aan de gebruiker.         -->
            <!-- Toon foutmelding als inloggen mislukt -->
            <?php if ($fout): ?>

                <!-- class="alert" is een Bootstrap component voor meldingen/notificaties. -->
                <!-- class="alert-danger" maakt de melding ROOD, wat duidt op een fout.     -->
                <!--   Bootstrap heeft verschillende alert-types:                           -->
                <!--   - alert-danger: rood (fouten)                                        -->
                <!--   - alert-success: groen (succes)                                      -->
                <!--   - alert-warning: geel (waarschuwingen)                               -->
                <!--   - alert-info: blauw (informatie)                                     -->
                <!-- role="alert" is een ARIA-attribuut voor toegankelijkheid.               -->
                <!--   Het vertelt schermlezers dat dit een belangrijke melding is die       -->
                <!--   direct aan de gebruiker moet worden voorgelezen.                      -->
                <div class="alert alert-danger" role="alert">

                    <!-- safeEcho() is een functie uit functions.php die de foutmelding      -->
                    <!-- VEILIG weergeeft. Het gebruikt htmlspecialchars() om speciale        -->
                    <!-- tekens om te zetten naar HTML-entiteiten, zoals:                     -->
                    <!--   - < wordt &lt; en > wordt &gt;                                    -->
                    <!--   - " wordt &quot;                                                  -->
                    <!-- Dit voorkomt XSS-aanvallen (Cross-Site Scripting) waarbij een        -->
                    <!-- kwaadwillende gebruiker JavaScript-code zou kunnen injecteren        -->
                    <!-- via het formulier. Zonder safeEcho zou een aanvaller bijv.           -->
                    <!-- <script>alert('gehackt')</script> kunnen invoeren als e-mailadres.   -->
                    <?php echo safeEcho($fout); ?>

                </div>

                <!-- endif; sluit het PHP if-blok af (alternatieve syntaxis).                -->
            <?php endif; ?>

            <!-- ============================================================== -->
            <!-- LOGIN FORMULIER                                                -->
            <!-- ============================================================== -->
            <!-- <form> maakt een HTML-formulier aan waarmee de gebruiker        -->
            <!--   gegevens kan invoeren en versturen naar de server.            -->
            <!-- method="POST" bepaalt HOE de gegevens worden verstuurd:         -->
            <!--   - POST: gegevens worden in de body van het HTTP-verzoek       -->
            <!--     verstuurd (niet zichtbaar in de URL). Dit is VEILIGER       -->
            <!--     voor wachtwoorden want ze verschijnen niet in de            -->
            <!--     browsergeschiedenis of serverlogboeken.                     -->
            <!--   - GET (alternatief): zou de gegevens in de URL plaatsen       -->
            <!--     (?email=test@test.com&password=geheim) wat ONVEILIG is.     -->
            <!-- Omdat er geen action-attribuut is opgegeven, stuurt het         -->
            <!--   formulier de gegevens naar DEZELFDE pagina (login.php).       -->
            <!--   Dit heet een "self-submitting form".                          -->
            <!-- onsubmit="return validateLoginForm();" is JavaScript validatie: -->
            <!--   - Voordat het formulier wordt verstuurd, wordt de functie     -->
            <!--     validateLoginForm() uit script.js aangeroepen.              -->
            <!--   - Als deze functie TRUE retourneert: formulier wordt verstuurd. -->
            <!--   - Als deze functie FALSE retourneert: formulier wordt NIET     -->
            <!--     verstuurd en de gebruiker ziet een foutmelding in de browser. -->
            <!--   Dit is CLIENT-SIDE validatie (in de browser) als extra laag    -->
            <!--     bovenop de SERVER-SIDE validatie (in PHP).                   -->
            <!-- Login formulier -->
            <form method="POST" onsubmit="return validateLoginForm();">

                <!-- ========================================================== -->
                <!-- E-MAIL INVOERVELD                                          -->
                <!-- ========================================================== -->
                <!-- E-mail invoerveld -->

                <!-- class="mb-3" is Bootstrap voor margin-bottom: 1rem (16px).  -->
                <!--   Dit voegt ruimte toe onder dit veld, zodat de velden niet -->
                <!--   tegen elkaar aan geplakt staan. Het getal 3 is een        -->
                <!--   gemiddeld niveau op de Bootstrap schaal van 0 tot 5.      -->
                <div class="mb-3">

                    <!-- <label> is een tekstetiket dat beschrijft waar het invoerveld   -->
                    <!--   voor is. Het maakt het formulier toegankelijker.               -->
                    <!-- for="email" koppelt dit label aan het invoerveld met id="email". -->
                    <!--   Als de gebruiker op het label klikt, krijgt het invoerveld     -->
                    <!--   automatisch de focus (cursor springt erin). Dit is fijn        -->
                    <!--   voor de gebruikerservaring en essentieel voor schermlezers.    -->
                    <!-- class="form-label" is Bootstrap styling voor form labels:        -->
                    <!--   het voegt de juiste marges en lettertypegrootte toe.           -->
                    <label for="email" class="form-label">📧 E-mailadres</label>

                    <!-- <input> is een invoerveld waar de gebruiker tekst kan typen.     -->
                    <!-- type="email" vertelt de browser dat dit een e-mailveld is:      -->
                    <!--   - De browser controleert automatisch of het een geldig         -->
                    <!--     e-mailformaat is (bevat @ en een domein).                    -->
                    <!--   - Op mobiel verschijnt een toetsenbord met @ en .com knoppen.  -->
                    <!--   - Het is onderdeel van HTML5 formuliervalidatie.               -->
                    <!-- id="email" is een unieke identificatie voor dit element.         -->
                    <!--   Het wordt gebruikt door: het label (for="email"),              -->
                    <!--   CSS-styling, en JavaScript (getElementById).                   -->
                    <!-- name="email" is de SLEUTEL waarmee PHP de waarde ontvangt.       -->
                    <!--   Na verzending is de waarde beschikbaar als $_POST['email'].    -->
                    <!--   Zonder name-attribuut wordt het veld NIET meegestuurd.         -->
                    <!-- class="form-control" is Bootstrap styling voor invoervelden:     -->
                    <!--   het maakt het veld 100% breed, voegt padding toe, geeft        -->
                    <!--   afgeronde hoeken, en een blauwe rand bij focus.                -->
                    <!-- class="form-control-lg" maakt het veld GROTER dan standaard:    -->
                    <!--   grotere tekst, meer padding, voor een prettige gebruikerservaring. -->
                    <!-- required is een HTML5-attribuut dat het veld VERPLICHT maakt.    -->
                    <!--   De browser voorkomt dat het formulier wordt verstuurd als dit  -->
                    <!--   veld leeg is. Er verschijnt een melding "Vul dit veld in".    -->
                    <!-- placeholder="jouw@email.com" is grijze voorbeeldtekst die       -->
                    <!--   in het veld verschijnt als het LEEG is. Het laat de gebruiker  -->
                    <!--   zien welk formaat er verwacht wordt. De tekst verdwijnt zodra  -->
                    <!--   de gebruiker begint te typen.                                  -->
                    <!-- aria-label="E-mailadres" is een ARIA-attribuut voor              -->
                    <!--   toegankelijkheid. Het geeft schermlezers een beschrijving van  -->
                    <!--   het veld, zelfs als het label niet programmatisch gekoppeld is. -->
                    <input type="email" id="email" name="email" class="form-control form-control-lg" required
                        placeholder="jouw@email.com" aria-label="E-mailadres">
                </div>

                <!-- ========================================================== -->
                <!-- WACHTWOORD INVOERVELD                                      -->
                <!-- ========================================================== -->
                <!-- Wachtwoord invoerveld -->

                <!-- class="mb-4" is Bootstrap voor margin-bottom: 1.5rem (24px). -->
                <!--   Dit is iets meer ruimte dan mb-3 (1rem) omdat dit het      -->
                <!--   laatste veld voor de knop is. De extra ruimte geeft een     -->
                <!--   duidelijke visuele scheiding tussen het formulier en de knop. -->
                <div class="mb-4">

                    <!-- <label> voor het wachtwoord veld, gekoppeld via for="password". -->
                    <!-- class="form-label" voegt Bootstrap label-styling toe.           -->
                    <label for="password" class="form-label">🔒 Wachtwoord</label>

                    <!-- type="password" is speciaal voor wachtwoorden:                  -->
                    <!--   - De ingevoerde tekst wordt verborgen als puntjes/sterretjes.  -->
                    <!--   - Dit voorkomt dat iemand die meekijkt het wachtwoord kan      -->
                    <!--     lezen (schouder-surfen / shoulder surfing).                  -->
                    <!--   - De browser kan aanbieden het wachtwoord op te slaan.         -->
                    <!-- id="password" is de unieke identificatie voor JavaScript en CSS. -->
                    <!-- name="password" is de sleutel voor PHP: $_POST['password'].      -->
                    <!-- class="form-control form-control-lg" geeft Bootstrap styling:   -->
                    <!--   breed invoerveld met grote tekst en padding.                   -->
                    <!-- required zorgt dat het formulier niet kan worden verstuurd als    -->
                    <!--   dit veld leeg is (browser validatie).                          -->
                    <!-- placeholder="Voer je wachtwoord in" is voorbeeldtekst die        -->
                    <!--   verdwijnt zodra de gebruiker begint te typen.                  -->
                    <!-- aria-label="Wachtwoord" is voor schermlezers/toegankelijkheid.  -->
                    <input type="password" id="password" name="password" class="form-control form-control-lg" required
                        placeholder="Voer je wachtwoord in" aria-label="Wachtwoord">
                </div>

                <!-- ========================================================== -->
                <!-- VERZEND KNOP                                               -->
                <!-- ========================================================== -->
                <!-- Verzend knop -->

                <!-- <button> maakt een klikbare knop aan op de pagina.           -->
                <!-- type="submit" betekent dat deze knop het formulier VERSTUURT -->
                <!--   wanneer erop wordt geklikt. Dit activeert de POST-methode  -->
                <!--   en stuurt alle formuliergegevens naar de server.           -->
                <!-- class="btn" is de Bootstrap basisklasse voor knoppen:        -->
                <!--   het voegt padding, rand, cursor-pointer, en hover-effecten toe. -->
                <!-- class="btn-primary" geeft de knop de PRIMAIRE kleur (blauw): -->
                <!--   dit is de standaard actieknop in Bootstrap.                -->
                <!--   Andere opties: btn-secondary (grijs), btn-success (groen), -->
                <!--   btn-danger (rood), btn-warning (geel).                    -->
                <!-- class="btn-lg" maakt de knop GROOT (meer padding en tekst). -->
                <!-- class="w-100" is Bootstrap voor width: 100%. Dit zorgt dat   -->
                <!--   de knop de VOLLEDIGE breedte van het formulier inneemt.    -->
                <!--   Hierdoor ziet de knop er prominent en gemakkelijk klikbaar uit. -->
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    🚀 Inloggen
                </button>

                <!-- Sluiting van het <form>-element. Alles hierboven tussen         -->
                <!-- <form> en </form> hoort bij het formulier.                      -->
            </form>

            <!-- ============================================================== -->
            <!-- LINK NAAR REGISTRATIE PAGINA                                   -->
            <!-- ============================================================== -->
            <!-- Deze sectie biedt een link voor gebruikers die nog geen account -->
            <!-- hebben. Ze kunnen hiermee naar de registratiepagina navigeren.  -->
            <!-- Link naar registratie pagina -->

            <!-- <p> is een paragraaf (alinea) element voor tekst.               -->
            <!-- class="text-center" centreert de tekst horizontaal.             -->
            <!-- class="mt-4" is Bootstrap voor margin-top: 1.5rem (24px).       -->
            <!--   Dit voegt ruimte toe BOVEN de paragraaf, na het formulier.    -->
            <!-- class="mb-0" is Bootstrap voor margin-bottom: 0.               -->
            <!--   Dit verwijdert de standaard marge onder de paragraaf zodat    -->
            <!--   er geen onnodige ruimte aan de onderkant van de container is. -->
            <p class="text-center mt-4 mb-0">
                Nog geen account?

                <!-- <a> is een hyperlink (anker) element dat naar een andere pagina linkt. -->
                <!-- href="register.php" is de URL waar de link naartoe gaat.               -->
                <!--   Als de gebruiker hierop klikt, wordt register.php geopend.            -->
                <!-- class="text-info" is Bootstrap en geeft de link een cyaan/lichtblauwe   -->
                <!--   kleur (#0dcaf0). Dit maakt de link goed zichtbaar tegen de donkere    -->
                <!--   achtergrond en onderscheidt het van de gewone witte tekst.            -->
                <a href="register.php" class="text-info">Registreer hier</a>
            </p>

            <!-- Sluiting van de auth-container div. -->
        </div>

        <!-- Sluiting van de container div. -->
    </div>

    <!-- ====================================================================== -->
    <!-- JAVASCRIPT BESTANDEN (onderaan de body voor snellere paginalading)     -->
    <!-- ====================================================================== -->

    <!-- Laad Bootstrap JavaScript via CDN. Dit bestand is nodig voor           -->
    <!-- interactieve Bootstrap-componenten zoals:                               -->
    <!--   - Dropdown menu's                                                    -->
    <!--   - Modale vensters (popups)                                           -->
    <!--   - Tooltips en popovers                                               -->
    <!--   - Carousel (afbeelding slider)                                       -->
    <!-- "bundle" betekent dat Popper.js (voor positionering van dropdowns)      -->
    <!-- al is inbegrepen, zodat we dat niet apart hoeven te laden.             -->
    <!-- Het staat onderaan de <body> zodat de HTML-inhoud EERST wordt geladen   -->
    <!-- en de pagina sneller zichtbaar is voor de gebruiker.                    -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Laad ons eigen JavaScript-bestand met aangepaste functies.              -->
    <!-- Dit bestand bevat onder andere:                                         -->
    <!--   - validateLoginForm(): controleert of de velden correct zijn ingevuld -->
    <!--     VOORDAT het formulier naar de server wordt verstuurd.               -->
    <!--   - Client-side validatielogica (controles in de browser).             -->
    <!-- Client-side validatie geeft SNELLE feedback aan de gebruiker, maar      -->
    <!-- is NIET voldoende als enige beveiliging. Server-side validatie in PHP   -->
    <!-- is altijd nodig omdat JavaScript door de gebruiker omzeild kan worden.  -->
    <script src="script.js"></script>

    <!-- Sluiting van het <body>-element. Alle zichtbare inhoud staat hierboven. -->
</body>

<!-- Sluiting van het <html>-element. Dit is het einde van het hele document. -->

</html>