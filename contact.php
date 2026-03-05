<?php
/**
 * ==========================================================================
 * CONTACT.PHP - CONTACT PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand toont de contactpagina van GamePlan Scheduler.
 * Het is een combinatie van PHP (server-side logica) en HTML (pagina-structuur).
 *
 * DE PAGINA BEVAT DE VOLGENDE SECTIES:
 * 1. E-mail sectie - Met een klikbaar mailto: e-mailadres
 * 2. Ontwikkelaar sectie - Informatie over de maker van de applicatie
 * 3. GitHub Repository sectie - Link naar de broncode op GitHub
 * 4. Snelle Hulp sectie - Veelgestelde vragen en oplossingen
 * 5. Terug naar Dashboard knop - Navigatie terug naar de hoofdpagina
 *
 * WAAROM DEZE PAGINA?
 * Elke professionele webapplicatie heeft een contactpagina nodig zodat
 * gebruikers weten hoe ze hulp kunnen krijgen bij problemen of vragen.
 *
 * Contact informatie pagina voor ondersteuning en vragen.
 * Toont e-mail, ontwikkelaar info, GitHub link en snelle hulp.
 * ==========================================================================
 */

// ============================================================================
// PHP LOGICA - SERVER-SIDE CODE (wordt uitgevoerd VOORDAT de HTML wordt getoond)
// ============================================================================

// require_once laadt het bestand 'functions.php' precies 1 keer in.
// Dit bestand bevat alle hulpfuncties van de applicatie, zoals:
// - checkSessionTimeout() => controleert of de sessie niet verlopen is
// - isLoggedIn() => controleert of de gebruiker is ingelogd
// - en vele andere functies die door de hele applicatie gebruikt worden.
// "once" voorkomt dat het bestand dubbel geladen wordt als het al eerder is ingeladen.
require_once 'functions.php';

// checkSessionTimeout() controleert of de gebruiker niet te lang inactief is geweest.
// Als de sessie langer dan 30 minuten inactief is, wordt de gebruiker automatisch uitgelogd.
// Dit is een beveiligingsmaatregel om te voorkomen dat iemand anders toegang krijgt
// tot het account als de gebruiker vergeet uit te loggen.
// OPMERKING: deze pagina vereist NIET dat de gebruiker ingelogd is (geen isLoggedIn() check).
// De contactinformatie is dus ook zichtbaar voor uitgelogde gebruikers.
checkSessionTimeout();
?>
<!-- ==========================================================================
     HTML GEDEELTE - Dit wordt naar de browser gestuurd en door de browser weergegeven.
     Al het PHP-werk hierboven is al klaar voordat deze HTML naar de gebruiker gaat.
     ========================================================================== -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5 document is.
     Dit is VERPLICHT als eerste regel van elk HTML-document.
     Zonder DOCTYPE kan de browser in "quirks mode" gaan, wat layout-problemen veroorzaakt. -->
<!DOCTYPE html>

<!-- <html lang="nl"> is het root-element van de HTML-pagina.
     lang="nl" vertelt de browser en zoekmachines dat de pagina in het Nederlands is.
     Dit helpt met: toegankelijkheid (screenreaders), SEO, en automatische vertaling. -->
<html lang="nl">

<!-- <head> bevat metadata (informatie OVER de pagina) die NIET zichtbaar is op de pagina zelf.
     Hier staan dingen zoals de tekenset, viewport-instellingen, titel, en CSS-bestanden. -->

<head>
    <!-- meta charset="UTF-8" stelt de tekencodering in op UTF-8.
         UTF-8 ondersteunt ALLE talen en speciale tekens (zoals e-umlaut, Chinese karakters, emoji's).
         Zonder deze instelling kunnen Nederlandse tekens zoals e-umlaut, u-umlaut fout weergegeven worden. -->
    <meta charset="UTF-8">

    <!-- meta viewport zorgt ervoor dat de pagina er goed uitziet op mobiele apparaten.
         width=device-width: de breedte van de pagina past zich aan aan het scherm van het apparaat.
         initial-scale=1.0: het standaard zoomniveau is 100% (niet in- of uitgezoomd).
         Zonder deze tag zou de pagina op een telefoon heel klein weergegeven worden,
         alsof je naar een desktop-website kijkt op een klein scherm. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <title> is de tekst die in het tabblad van de browser verschijnt.
         Ook wordt deze titel gebruikt door zoekmachines (Google) als resultaattitel. -->
    <title>Contact - GamePlan Scheduler</title>

    <!-- Bootstrap 5.3.3 CSS wordt geladen via een CDN (Content Delivery Network).
         CDN = een netwerk van servers over de hele wereld die bestanden snel leveren.
         Bootstrap is een CSS-framework dat kant-en-klare stijlen biedt voor:
         - Grid-systeem (kolommen en rijen voor layout)
         - Knoppen, kaarten, formulieren, navigatie, etc.
         - Responsief design (past zich aan aan schermgrootte)
         Door Bootstrap te gebruiken hoef je niet alles zelf te stylen. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- style.css is het EIGEN stylesheet van GamePlan Scheduler.
         Dit bestand bevat aanvullende stijlen die specifiek zijn voor deze applicatie,
         bovenop de standaard Bootstrap-stijlen. Bijvoorbeeld: donker thema kleuren,
         aangepaste kaart-stijlen, en andere visuele aanpassingen. -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- <body> bevat ALLE zichtbare inhoud van de pagina.
     class="bg-dark text-light" zijn Bootstrap CSS-klassen:
     - bg-dark: geeft de achtergrond een DONKERE kleur (bijna zwart, #212529)
     - text-light: maakt ALLE tekst in de body LICHT (bijna wit)
     Deze combinatie creëert een donker thema voor de hele pagina,
     wat prettig is voor gamers die vaak in het donker achter hun scherm zitten. -->

<body class="bg-dark text-light">

    <!-- PHP include 'header.php' voegt de navigatiebalk (header) in op deze plek.
         include leest het bestand header.php en plaatst de inhoud hier.
         Dit is het DRY-principe (Don't Repeat Yourself): de header staat in 1 bestand
         en wordt op ELKE pagina hergebruikt. Als je de header wilt aanpassen,
         hoef je maar 1 bestand te wijzigen in plaats van elke pagina apart. -->
    <?php include 'header.php'; ?>

    <!-- <main> is een semantisch HTML5-element dat de HOOFDINHOUD van de pagina aangeeft.
         Het vertelt screenreaders en zoekmachines: "Dit is het belangrijkste deel van de pagina."
         class="container mt-5 pt-5" zijn Bootstrap klassen:
         - container: centreert de inhoud horizontaal en geeft een maximale breedte
           (bijv. 1140px op grote schermen). De inhoud wordt netjes in het midden van de pagina gezet.
         - mt-5: margin-top 5 = grote bovenmarge (3rem = 48px). Dit duwt de inhoud naar beneden,
           weg van de navigatiebalk bovenaan.
         - pt-5: padding-top 5 = grote bovenpadding (3rem = 48px). Extra ruimte binnenin het element
           aan de bovenkant, zodat de inhoud niet tegen de rand van het element zit.
         De combinatie mt-5 en pt-5 zorgt voor voldoende ruimte onder de vaste navigatiebalk. -->
    <main class="container mt-5 pt-5">

        <!-- <div class="card"> is een Bootstrap Card component.
             Een "card" is een visueel paneel met een rand, achtergrond en afgeronde hoeken.
             Het wordt vaak gebruikt om gerelateerde informatie te groeperen.
             In ons donkere thema heeft de card waarschijnlijk een iets lichtere achtergrond
             dan de pagina zelf, waardoor de inhoud visueel opvalt. -->
        <div class="card">

            <!-- <div class="card-body"> is het BINNENSTE deel van een Bootstrap card.
                 card-body voegt padding (binnenruimte) toe rondom de inhoud,
                 zodat tekst en elementen niet tegen de rand van de kaart aan zitten.
                 Standaard is dit 1rem (16px) padding aan alle kanten. -->
            <div class="card-body">

                <!-- <h1> is de hoofdtitel van de pagina - het GROOTSTE kopniveau.
                     Er zou maar 1 <h1> per pagina moeten zijn (belangrijk voor SEO en toegankelijkheid).
                     class="mb-4" is een Bootstrap klasse:
                     - mb-4: margin-bottom 4 = ondermarge van 1.5rem (24px).
                       Dit zorgt voor ruimte tussen de titel en de eerste sectie eronder. -->
                <h1 class="mb-4">📬 Neem Contact Op</h1>

                <!-- ============================================================
                     SECTIE 1: E-MAIL CONTACT
                     Deze sectie toont het e-mailadres waarnaar gebruikers kunnen schrijven.
                     ============================================================ -->

                <!-- <h3> is een koptekst van niveau 3 (kleiner dan h1 en h2).
                     Dit wordt gebruikt voor sectietitels binnen de kaart. -->
                <h3>📧 E-mail</h3>

                <!-- <p> is een paragraaf-element. Het toont een blok tekst met standaard marges.
                     Deze tekst legt uit waarvoor het e-mailadres gebruikt kan worden. -->
                <p>Voor ondersteuning of vragen, stuur een e-mail naar:</p>

                <!-- <p class="h5"> is een paragraaf die GESTYLED is als een h5-koptekst.
                     class="h5" geeft de tekst de grootte en het gewicht van een h5-kopniveau,
                     maar het is semantisch nog steeds een paragraaf (geen echte heading).
                     Dit maakt het e-mailadres groter en opvallender dan gewone tekst. -->
                <p class="h5">
                    <!-- <a href="mailto:..."> is een speciaal soort hyperlink.
                         href="mailto:" vertelt de browser: "Open het standaard e-mailprogramma
                         van de gebruiker (bijv. Outlook, Gmail app, Apple Mail) met dit e-mailadres
                         al ingevuld in het 'Aan' veld."
                         Als de gebruiker geen e-mailprogramma heeft ingesteld, kan het zijn
                         dat er niets gebeurt of dat de browser vraagt welk programma te gebruiken.
                         class="text-info" is een Bootstrap klasse die de tekst een lichtblauwe kleur geeft.
                         Dit maakt het duidelijk dat het een klikbaar element is. -->
                    <a href="mailto:harsha.kanaparthi20062@gmail.com" class="text-info">
                        harsha.kanaparthi20062@gmail.com
                    </a>
                </p>

                <!-- ============================================================
                     SECTIE 2: ONTWIKKELAAR INFORMATIE
                     Deze sectie toont wie de applicatie heeft gemaakt.
                     ============================================================ -->

                <!-- <h3 class="mt-4"> is een sectietitel met bovenmarge.
                     mt-4: margin-top 4 = bovenmarge van 1.5rem (24px).
                     Dit zorgt voor visuele scheiding tussen deze sectie en de vorige sectie. -->
                <h3 class="mt-4">👨‍💻 Ontwikkelaar</h3>

                <!-- <ul> is een ongeordende lijst (unordered list).
                     Standaard worden de items weergegeven met ronde opsommingstekens (bullets).
                     Elke <li> (list item) is een enkel punt in de lijst. -->
                <ul>
                    <!-- <li> is een lijstitem. <strong> maakt tekst vetgedrukt.
                         Hier wordt de naam van de ontwikkelaar getoond. -->
                    <li><strong>Naam:</strong> Harsha Kanaparthi</li>

                    <!-- Het studentnummer identificeert de ontwikkelaar als student. -->
                    <li><strong>Studentnummer:</strong> 2195344</li>

                    <!-- De opleiding geeft context over het niveau van het project. -->
                    <li><strong>Opleiding:</strong> MBO-4 Software Development</li>

                    <!-- De projectnaam bevestigt dat dit de GamePlan Scheduler applicatie is. -->
                    <li><strong>Project:</strong> GamePlan Scheduler</li>
                </ul>

                <!-- ============================================================
                     SECTIE 3: GITHUB REPOSITORY LINK
                     Een repository is een online opslagplaats voor broncode.
                     GitHub is een platform waar ontwikkelaars hun code delen en samenwerken.
                     ============================================================ -->

                <!-- Sectietitel voor de GitHub-link met bovenmarge voor visuele scheiding. -->
                <h3 class="mt-4">🔗 GitHub Repository</h3>

                <!-- Paragraaf die de GitHub-link bevat. -->
                <p>
                    <!-- <a href="..." target="_blank"> is een hyperlink die in een NIEUW TABBLAD opent.
                         href="https://github.com/..." is de URL naar de GitHub repository.
                         target="_blank" vertelt de browser: "Open deze link in een NIEUW tabblad."
                         Zonder target="_blank" zou de link in het HUIDIGE tabblad openen,
                         waardoor de gebruiker de GamePlan Scheduler pagina verlaat.
                         Met target="_blank" blijft de contactpagina open in het oorspronkelijke tabblad
                         en wordt GitHub geopend in een nieuw tabblad - de gebruiker kan dan
                         gemakkelijk terug wisselen.
                         class="text-info" geeft de link een lichtblauwe kleur (Bootstrap klasse),
                         waardoor het duidelijk zichtbaar is als klikbare link op de donkere achtergrond. -->
                    <a href="https://github.com/Harsha2006217/GamePlan-Scheduler" target="_blank" class="text-info">
                        github.com/Harsha2006217/GamePlan-Scheduler
                    </a>
                </p>

                <!-- ============================================================
                     SECTIE 4: SNELLE HULP
                     Deze sectie beantwoordt veelgestelde vragen (FAQ) zodat
                     gebruikers zelf snel een oplossing kunnen vinden zonder
                     een e-mail te hoeven sturen.
                     ============================================================ -->

                <!-- Sectietitel voor de snelle hulp met bovenmarge. -->
                <h3 class="mt-4">⚡ Snelle Hulp</h3>

                <!-- Ongeordende lijst met veelgestelde vragen en hun oplossingen. -->
                <ul>
                    <!-- Hulp bij inlogproblemen: de meest voorkomende oorzaak van inlogproblemen
                         is het gebruik van een verkeerd e-mailadres (bijv. een typfout bij registratie). -->
                    <li><strong>Inlogproblemen?</strong> Controleer of je het juiste e-mailadres hebt gebruikt bij
                        registratie.</li>

                    <!-- Hulp bij vergeten wachtwoord: omdat er geen automatische reset-functie is,
                         moet de gebruiker contact opnemen met de ontwikkelaar. -->
                    <li><strong>Wachtwoord vergeten?</strong> Neem contact met ons op voor een reset.</li>

                    <!-- Hulp bij het melden van bugs (softwarefouten): gebruikers worden aangemoedigd
                         om details te sturen zodat de ontwikkelaar het probleem kan reproduceren en oplossen. -->
                    <li><strong>Bug melden?</strong> Stuur de details naar bovenstaand e-mailadres.</li>
                </ul>

                <!-- ============================================================
                     NAVIGATIE: TERUG NAAR DASHBOARD KNOP
                     ============================================================ -->

                <!-- <a href="index.php" class="btn btn-primary mt-3"> is een link die eruitziet als een knop.
                     href="index.php": navigeert naar het dashboard (de hoofdpagina van de applicatie).
                     class="btn btn-primary mt-3" zijn Bootstrap klassen:
                     - btn: basisstijl voor een knop (padding, rand, afgeronde hoeken, tekstgrootte).
                       Dit maakt een gewone <a> link er uit als een echte knop.
                     - btn-primary: geeft de knop de primaire kleur (standaard BLAUW in Bootstrap).
                       "Primary" wordt gebruikt voor de belangrijkste actie op de pagina.
                     - mt-3: margin-top 3 = bovenmarge van 1rem (16px).
                       Dit zorgt voor ruimte tussen de knop en de lijst erboven. -->
                <a href="index.php" class="btn btn-primary mt-3">↩️ Terug naar Dashboard</a>

                <!-- Einde van card-body: sluit de binnenruimte van de kaart af. -->
            </div>

            <!-- Einde van card: sluit het visuele paneel af. -->
        </div>

        <!-- Einde van main: sluit het hoofdinhoud-gedeelte af. -->
    </main>

    <!-- PHP include 'footer.php' voegt de voettekst (footer) in op deze plek.
         De footer bevat meestal copyright-informatie en links naar privacybeleid/contact.
         Net als de header wordt de footer hergebruikt op elke pagina (DRY-principe). -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JavaScript bundle wordt geladen via CDN.
         "bundle" betekent dat het Popper.js INBEGREPEN heeft (nodig voor tooltips en dropdowns).
         Dit JavaScript is nodig voor interactieve Bootstrap-componenten zoals:
         - Dropdown menu's (uitklapmenu's)
         - Modal vensters (pop-up dialogen)
         - Navbar toggler (hamburger menu op mobiel)
         - Tooltips en popovers
         Het wordt aan het EINDE van de body geladen zodat de HTML-inhoud EERST wordt getoond.
         Als JavaScript bovenaan zou staan, moet de browser wachten tot het geladen is
         voordat de pagina zichtbaar wordt - dat is langzamer. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- script.js is het EIGEN JavaScript-bestand van GamePlan Scheduler.
         Dit bestand bevat aangepaste JavaScript-functionaliteit die specifiek is
         voor deze applicatie, zoals interactieve elementen en formuliervalidatie. -->
    <script src="script.js"></script>

    <!-- Einde van body: sluit alle zichtbare inhoud af. -->
</body>

<!-- Einde van html: sluit het volledige HTML-document af. -->

</html>