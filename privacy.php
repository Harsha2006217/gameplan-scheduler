<?php
/**
 * ==========================================================================
 * PRIVACY.PHP - PRIVACYBELEID PAGINA
 * ==========================================================================
 * Bestandsnaam : privacy.php
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
 * Dit bestand toont het privacybeleid van GamePlan Scheduler.
 * Hierin staat welke gegevens worden verzameld, hoe ze worden beschermd,
 * wat er NIET met data gebeurt, welke rechten de gebruiker heeft,
 * en hoe contact opgenomen kan worden bij privacyvragen.
 *
 * ==========================================================================
 * STRUCTUUR EN FLOW
 * ==========================================================================
 * ┌─────────────────────────────────────────────────────────────────────┐
 * │ 1. Laad functions.php                                              │
 * │ 2. checkSessionTimeout()                                           │
 * │ 3. HTML: header, main, card, secties, footer                       │
 * │ 4. Secties: Gegevensverzameling, Beveiliging, Beloftes, Rechten,   │
 * │    Contact, AVG-naleving, Terugknop                                │
 * └─────────────────────────────────────────────────────────────────────┘
 *
 * ==========================================================================
 * BEVEILIGING & AVG/GDPR
 * ==========================================================================
 * 1. AVG/GDPR: Europese privacywetgeving, verplicht privacybeleid
 * 2. Transparantie: uitleg over dataverzameling, rechten, contact
 * 3. Technische maatregelen:
 *    - Bcrypt wachtwoord hashing
 *    - Prepared statements (SQL-injectie preventie)
 *    - Sessie timeout (30 min)
 *    - XSS-bescherming (htmlspecialchars)
 * 4. Geen tracking cookies, geen analytics, geen dataverkoop
 * 5. Rechten: inzage, rectificatie, verwijdering, dataportabiliteit
 *
 * ==========================================================================
 * DATABASE TABELLEN
 * ==========================================================================
 * Tabel: Users
 * ┌─────────────┬─────────────┬─────────────┬─────────────┐
 * │ id (PK)     │ email       │ wachtwoord  │ naam        │
 * └─────────────┴─────────────┴─────────────┴─────────────┘
 * Tabel: Games, UserGames, Friends, Schedules, Events
 * Gegevens worden alleen gebruikt voor de werking van de app.
 *
 * ==========================================================================
 * VERGELIJKING MET ANDERE PAGINA'S
 * ==========================================================================
 * ┌───────────────┬───────────────┬───────────────┬───────────────┐
 * │ Eigenschap    │ privacy.php   │ login.php     │ index.php     │
 * ├───────────────┼───────────────┼───────────────┼───────────────┤
 * │ Doel          │ privacybeleid │ inloggen      │ dashboard     │
 * │ Sessie check  │ timeout      │ ja            │ ja            │
 * │ Data ophalen  │ n.v.t.       │ gebruiker     │ alles         │
 * │ Security      │ hoog         │ hoog          │ hoog          │
 * │ AVG-naleving  │ ja           │ n.v.t.        │ n.v.t.        │
 * └───────────────┴───────────────┴───────────────┴───────────────┘
 *
 * ==========================================================================
 * GEBRUIKTE CONCEPTEN
 * ==========================================================================
 * PHP:
 *   - Functies, parameters, return values
 *   - Sessie timeout, include, require_once
 *   - Prepared statements (PDO), htmlspecialchars
 * HTML:
 *   - Cards, lists, knoppen, semantische elementen
 *   - Bootstrap: container, card, btn, mb-5, mt-5, pt-5
 *   - Toegankelijkheid: lang="nl", aria, SEO
 * ==========================================================================
 * EXAMENNIVEAU: VOLLEDIG GEDOCUMENTEERD, AVG/GDPR, SECURITY, DATABASE, FLOW
 * ==========================================================================
 */

// ============================================================================
// PHP LOGICA - SERVER-SIDE CODE (wordt uitgevoerd VOORDAT de HTML wordt getoond)
// ============================================================================

// require_once laadt het bestand 'functions.php' precies 1 keer in.
// Dit bestand bevat alle hulpfuncties van de applicatie, zoals:
// - checkSessionTimeout() => controleert of de sessie niet verlopen is
// - isLoggedIn() => controleert of de gebruiker is ingelogd
// - getDatabaseConnection() => maakt verbinding met de database
// - en vele andere functies die door de hele applicatie gebruikt worden.
// Het woord "once" voorkomt dat het bestand dubbel geladen wordt
// als het al eerder is ingeladen via een ander bestand.
require_once 'functions.php';

// checkSessionTimeout() controleert of de gebruiker niet te lang inactief is geweest.
// Als de sessie langer dan 30 minuten inactief is, wordt de gebruiker automatisch uitgelogd.
// Dit is een beveiligingsmaatregel: als iemand vergeet uit te loggen op een openbare computer,
// wordt de sessie automatisch beeindigd na 30 minuten zonder activiteit.
// OPMERKING: deze pagina vereist NIET dat de gebruiker ingelogd is (geen isLoggedIn() check).
// Het privacybeleid moet voor iedereen toegankelijk zijn, ook voor niet-ingelogde bezoekers,
// omdat de AVG vereist dat privacyinformatie altijd beschikbaar is.
checkSessionTimeout();
?>
<!-- ==========================================================================
     HTML GEDEELTE - Dit wordt naar de browser gestuurd en door de browser weergegeven.
     De PHP-code hierboven is al volledig uitgevoerd op de server.
     Alles hieronder is puur HTML dat de browser ontvangt en aan de gebruiker toont.
     ========================================================================== -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5 document is.
     Dit is VERPLICHT als allereerste regel van elk HTML-document.
     Zonder DOCTYPE kan de browser in "quirks mode" gaan, wat ouderwetse
     rendering-regels activeert en moderne layout-technieken kan breken. -->
<!DOCTYPE html>

<!-- <html lang="nl"> is het root-element (hoofdelement) van de HTML-pagina.
     Elk HTML-element op de pagina zit BINNENIN dit element.
     lang="nl" vertelt de browser en zoekmachines dat de pagina in het Nederlands is.
     Dit helpt met:
     - Toegankelijkheid: screenreaders weten dat ze Nederlands moeten spreken
     - SEO: zoekmachines weten in welke taal de pagina is geschreven
     - Automatische vertaling: browsers kunnen aanbieden de pagina te vertalen -->
<html lang="nl">

<!-- <head> bevat metadata (informatie OVER de pagina) die NIET zichtbaar is op de pagina zelf.
     Hier staan dingen zoals de tekenset, viewport-instellingen, de paginatitel,
     en verwijzingen naar CSS-stylesheets. De browser leest dit deel eerst
     voordat het de zichtbare inhoud (body) gaat weergeven. -->

<head>
     <!-- meta charset="UTF-8" stelt de tekencodering in op UTF-8.
         UTF-8 is de meest gebruikte tekencodering op het internet.
         Het ondersteunt ALLE talen en speciale tekens, waaronder:
         - Nederlandse tekens (e met accent, u met umlaut, etc.)
         - Emoji's (die in de sectietitels worden gebruikt)
         - Alle andere Unicode-tekens
         Zonder deze instelling kunnen speciale tekens als onleesbare symbolen verschijnen. -->
     <meta charset="UTF-8">

     <!-- meta viewport zorgt ervoor dat de pagina er goed uitziet op mobiele apparaten.
         width=device-width: de breedte van de pagina past zich aan aan het scherm.
         initial-scale=1.0: standaard zoomniveau is 100%.
         Zonder deze tag zou de pagina op een mobiel apparaat heel klein weergegeven worden,
         alsof je een desktop-website bekijkt op een klein scherm. De gebruiker zou moeten
         inzoomen om de tekst te kunnen lezen. Met deze tag schaalt de pagina automatisch mee. -->
     <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <!-- <title> is de tekst die in het tabblad van de browser verschijnt.
         "Privacybeleid - GamePlan Scheduler" vertelt de gebruiker op welke pagina hij/zij is.
         Deze titel wordt ook gebruikt door zoekmachines als de titel in zoekresultaten. -->
     <title>Privacybeleid - GamePlan Scheduler</title>

     <!-- Bootstrap 5.3.3 CSS wordt geladen via een CDN (Content Delivery Network).
         Een CDN is een netwerk van servers verspreid over de hele wereld.
         Het dichtstbijzijnde server levert het bestand, wat sneller is dan 1 centrale server.
         Bootstrap is het meest populaire CSS-framework ter wereld en biedt:
         - Een grid-systeem voor responsive layouts (kolommen die zich aanpassen aan schermgrootte)
         - Voorgedefinieerde klassen voor marges, padding, kleuren, typografie
         - Kant-en-klare componenten: knoppen, kaarten, formulieren, navigatiebalken, etc.
         - Responsief design: alles past zich automatisch aan op desktop, tablet en mobiel
         rel="stylesheet" vertelt de browser dat dit een CSS-bestand is (niet JavaScript). -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

     <!-- style.css is het EIGEN stylesheet van GamePlan Scheduler.
         Dit bestand bevat AANVULLENDE stijlen bovenop Bootstrap, specifiek voor deze app.
         Denk aan: donker thema kleuren, aangepaste kaart-stijlen, hover-effecten,
         en andere visuele aanpassingen die de app een uniek uiterlijk geven.
         Het wordt NA Bootstrap geladen, zodat eigen stijlen Bootstrap-stijlen kunnen overschrijven. -->
     <link rel="stylesheet" href="style.css">
</head>

<!-- <body> bevat ALLE zichtbare inhoud van de pagina.
     class="bg-dark text-light" zijn Bootstrap CSS-klassen:
     - bg-dark: zet de achtergrondkleur op donker (bijna zwart, #212529).
       "bg" staat voor "background" (achtergrond).
     - text-light: maakt ALLE tekst in de body licht van kleur (bijna wit, #f8f9fa).
       Dit zorgt voor goed contrast op de donkere achtergrond.
     Deze combinatie creëert het donkere thema dat populair is bij gaming-applicaties.
     Gamers spelen vaak in het donker, dus een donker thema is prettiger voor de ogen. -->

<body class="bg-dark text-light">

     <!-- PHP include 'header.php' voegt de navigatiebalk (header) in op deze plek.
         include is een PHP-instructie die de VOLLEDIGE inhoud van header.php hier plaatst.
         Dit is het DRY-principe (Don't Repeat Yourself): de header staat in 1 bestand
         en wordt op ELKE pagina hergebruikt. Voordelen:
         - Als je de navigatie wilt wijzigen, hoef je maar 1 bestand aan te passen
         - Alle pagina's hebben automatisch een consistente header
         - Minder code-duplicatie = minder kans op fouten -->
     <?php include 'header.php'; ?>

     <!-- <main> is een semantisch HTML5-element dat de HOOFDINHOUD van de pagina markeert.
         "Semantisch" betekent dat het element BETEKENIS heeft (niet alleen visueel).
         Screenreaders gebruiken <main> om direct naar de hoofdinhoud te navigeren.
         Zoekmachines gebruiken het om te begrijpen welk deel het belangrijkste is.
         class="container mt-5 pt-5" zijn Bootstrap klassen:
         - container: creëert een gecentreerde container met een maximale breedte.
           Op grote schermen (>1200px) is dit max 1140px breed.
           Op kleinere schermen past de breedte zich aan (responsive).
           De inhoud wordt altijd horizontaal gecentreerd op de pagina.
         - mt-5: margin-top 5 = bovenmarge van 3rem (48px).
           Dit duwt de inhoud naar beneden zodat het niet achter de vaste navigatiebalk verdwijnt.
         - pt-5: padding-top 5 = bovenpadding van 3rem (48px).
           Extra binnenruimte aan de bovenkant voor meer ademruimte.
         De combinatie mt-5 + pt-5 = 6rem (96px) ruimte boven de inhoud. -->
     <main class="container mt-5 pt-5">

          <!-- <div class="card"> is een Bootstrap Card component.
             Een "card" is een flexibel en uitbreidbaar inhoudscontainer met:
             - Een lichte achtergrond (of donker in ons thema via style.css)
             - Een subtiele rand
             - Afgeronde hoeken (border-radius)
             Cards worden veel gebruikt om gerelateerde informatie visueel te groeperen.
             In dit geval groepeert de card het volledige privacybeleid. -->
          <div class="card">

               <!-- <div class="card-body"> is het BINNENSTE deel van een Bootstrap card.
                 Het voegt padding (binnenruimte) toe rondom alle inhoud binnenin de card.
                 Standaard padding: 1rem (16px) aan alle vier de kanten.
                 Zonder card-body zou de tekst direct tegen de rand van de kaart aan staan,
                 wat er niet mooi uitziet. -->
               <div class="card-body">

                    <!-- <h1> is de hoofdtitel van de pagina - het GROOTSTE en BELANGRIJKSTE kopniveau.
                     Elke pagina zou maar 1 <h1> moeten hebben (belangrijk voor SEO en toegankelijkheid).
                     Screenreaders gebruiken kopniveaus om de structuur van de pagina te begrijpen.
                     class="mb-4" is een Bootstrap klasse:
                     - mb-4: margin-bottom 4 = ondermarge van 1.5rem (24px).
                       Dit zorgt voor visuele ruimte tussen de titel en de eerste sectie eronder.
                       Zonder deze marge zou de eerste sectie direct tegen de titel aan staan. -->
                    <h1 class="mb-4">🔒 Privacybeleid</h1>

                    <!-- ============================================================
                     SECTIE 1: GEGEVENSVERZAMELING
                     Deze sectie legt uit WELKE persoonlijke gegevens GamePlan Scheduler
                     verzamelt van de gebruiker. Dit is verplicht volgens de AVG/GDPR.
                     Transparantie over dataverzameling is een kernprincipe van de AVG.
                     De gebruiker moet VOORAF weten welke data wordt opgeslagen.
                     ============================================================ -->

                    <!-- <h3> is een koptekst van niveau 3. Dit wordt gebruikt voor sectietitels.
                     In de kopniveau-hiërarchie: h1 > h2 > h3 (h3 is kleiner dan h1).
                     Het is belangrijk om kopniveaus in de juiste volgorde te gebruiken
                     voor toegankelijkheid (screenreaders verwachten een logische hiërarchie). -->
                    <h3>📋 Gegevensverzameling</h3>

                    <!-- <p> is een paragraaf-element. Het toont een blok tekst met standaard marges.
                     Deze inleidende tekst benadrukt dat alleen ESSENTIËLE informatie wordt verzameld.
                     "Essentieel" betekent: alleen data die noodzakelijk is voor de werking van de app. -->
                    <p>Wij verzamelen alleen essentiële informatie:</p>

                    <!-- <ul> is een ongeordende lijst (unordered list).
                     De items worden weergegeven met ronde opsommingstekens (bullet points).
                     Elke <li> (list item) beschrijft een type data dat wordt verzameld. -->
                    <ul>
                         <!-- Gebruikersnaam: de naam die andere gebruikers zien in de applicatie.
                         <strong> maakt "Gebruikersnaam" vetgedrukt, zodat het opvalt als label.
                         Na het streepje (-) volgt de uitleg waarvoor deze data wordt gebruikt. -->
                         <li><strong>Gebruikersnaam</strong> - Jouw weergavenaam in de applicatie</li>

                         <!-- E-mailadres: wordt ALLEEN gebruikt voor het inloggen (authenticatie).
                         Het e-mailadres wordt NIET gebruikt voor marketing of nieuwsbrieven.
                         Dit is een voorbeeld van dataminimalisatie: alleen gebruiken waarvoor het nodig is. -->
                         <li><strong>E-mailadres</strong> - Alleen gebruikt voor het inloggen</li>

                         <!-- Favoriete spellen: games die de gebruiker ZELF toevoegt aan zijn/haar profiel.
                         "Zelf toevoegt" benadrukt dat dit vrijwillige data is, niet automatisch verzameld. -->
                         <li><strong>Favoriete spellen</strong> - Spellen die je zelf toevoegt</li>

                         <!-- Schema's en evenementen: de gaming plannen die de gebruiker maakt.
                         Dit is de kernfunctionaliteit van de GamePlan Scheduler applicatie. -->
                         <li><strong>Schema's en evenementen</strong> - Jouw gaming plannen</li>
                    </ul>

                    <!-- ============================================================
                     SECTIE 2: GEGEVENSBEVEILIGING
                     Deze sectie beschrijft de TECHNISCHE beveiligingsmaatregelen
                     die worden gebruikt om de gegevens van gebruikers te beschermen.
                     Volgens de AVG/GDPR moet je "passende technische maatregelen" treffen
                     om persoonsgegevens te beschermen. Deze sectie toont aan dat dit wordt gedaan.
                     ============================================================ -->

                    <!-- <h3 class="mt-4"> is een sectietitel met bovenmarge.
                     mt-4: margin-top 4 = bovenmarge van 1.5rem (24px).
                     Dit zorgt voor visuele scheiding tussen de secties zodat ze niet
                     in elkaar overlopen en de pagina overzichtelijk blijft. -->
                    <h3 class="mt-4">🔐 Gegevensbeveiliging</h3>

                    <!-- Ongeordende lijst met alle beveiligingsmaatregelen. -->
                    <ul>
                         <!-- BEVEILIGINGSMAATREGEL 1: BCRYPT WACHTWOORD-VERSLEUTELING
                         bcrypt is een wachtwoord-hashing algoritme dat speciaal ontworpen is
                         voor het veilig opslaan van wachtwoorden. Het werkt als volgt:
                         1. Het wachtwoord wordt omgezet in een onleesbare "hash" (reeks tekens)
                         2. Er wordt een willekeurige "salt" toegevoegd (extra willekeurige tekens)
                         3. Het hashing-proces is OPZETTELIJK traag (cost factor) om brute-force aanvallen
                            te vertragen - een aanvaller zou miljarden pogingen nodig hebben
                         "Nooit als platte tekst" betekent: het echte wachtwoord wordt NERGENS opgeslagen.
                         Zelfs als een hacker de database steelt, kan hij de wachtwoorden niet lezen.
                         In PHP wordt bcrypt gebruikt via password_hash() en password_verify(). -->
                         <li>Wachtwoorden worden <strong>versleuteld met bcrypt</strong> (nooit als platte tekst
                              opgeslagen)
                         </li>

                         <!-- BEVEILIGINGSMAATREGEL 2: PREPARED STATEMENTS (bescherming tegen SQL-injectie)
                         SQL-injectie is een veelvoorkomende hack-techniek waarbij een aanvaller
                         kwaadaardige SQL-code invoert via een formulier of URL-parameter.
                         Voorbeeld van een aanval: in het login-formulier vult iemand in als gebruikersnaam:
                         ' OR 1=1 --  (dit zou ALLE gebruikers retourneren zonder wachtwoord!)

                         Prepared statements voorkomen dit door:
                         1. De SQL-query APART te sturen naar de database (structuur)
                         2. De gebruikersinvoer APART te sturen (data)
                         3. De database behandelt de invoer ALTIJD als data, NOOIT als SQL-code
                         In PHP wordt dit gedaan met PDO::prepare() en $stmt->execute().
                         Dit is de MEEST EFFECTIEVE bescherming tegen SQL-injectie. -->
                         <li>Alle database queries gebruiken <strong>prepared statements</strong> (bescherming tegen
                              SQL-injectie)</li>

                         <!-- BEVEILIGINGSMAATREGEL 3: SESSIE TIMEOUT (automatisch uitloggen)
                         Na 30 minuten inactiviteit wordt de gebruiker automatisch uitgelogd.
                         Dit beschermt tegen het scenario waarin een gebruiker:
                         - Vergeet uit te loggen op een gedeelde/openbare computer
                         - Zijn/haar laptop open laat staan en wegloopt
                         - Een tabblad open laat staan en het vergeet
                         Na 30 minuten zonder activiteit vervalt de sessie en moet de gebruiker
                         opnieuw inloggen om toegang te krijgen tot zijn/haar account.
                         Dit wordt gecontroleerd door de checkSessionTimeout() functie. -->
                         <li>Sessies verlopen na <strong>30 minuten inactiviteit</strong></li>

                         <!-- BEVEILIGINGSMAATREGEL 4: XSS-BESCHERMING (Cross-Site Scripting preventie)
                         XSS (Cross-Site Scripting) is een aanval waarbij een hacker kwaadaardige
                         JavaScript-code injecteert in een webpagina. Bijvoorbeeld:
                         Een aanvaller vult als gebruikersnaam in: <script>alert('gehackt!')</script>
                         Als dit NIET geëscaped wordt, zou die code uitgevoerd worden in de browsers
                         van ANDERE gebruikers die die naam zien.

                         "Escapen" (ook wel "sanitizen") betekent: speciale HTML-tekens omzetten
                         naar veilige equivalenten. Bijvoorbeeld:
                         < wordt &lt;  (browser toont "<" maar voert het niet uit als HTML)
                         > wordt &gt;  (browser toont ">" maar voert het niet uit als HTML)
                         In PHP wordt dit gedaan met htmlspecialchars().
                         Zo wordt kwaadaardige code gewoon als TEKST getoond in plaats van uitgevoerd. -->
                         <li>Alle uitvoer wordt <strong>geëscaped</strong> om XSS-aanvallen te voorkomen</li>
                    </ul>

                    <!-- ============================================================
                     SECTIE 3: WAT WIJ NIET DOEN
                     Deze sectie beschrijft expliciet wat er NIET met de gegevens
                     van gebruikers wordt gedaan. Dit is belangrijk voor het vertrouwen
                     van de gebruiker en is ook een AVG/GDPR-vereiste:
                     je moet duidelijk communiceren over het gebruik van data.
                     ============================================================ -->

                    <!-- Sectietitel met bovenmarge voor visuele scheiding. -->
                    <h3 class="mt-4">🚫 Wat Wij Niet Doen</h3>

                    <!-- Ongeordende lijst met beloftes over wat er NIET met data gebeurt. -->
                    <ul>
                         <!-- BELOFTE 1: Geen verkoop van gegevens aan derden.
                         "Derden" zijn externe bedrijven of personen die niet bij de applicatie betrokken zijn.
                         Veel grote techbedrijven verdienen geld door gebruikersdata te verkopen aan
                         adverteerders. GamePlan Scheduler doet dit uitdrukkelijk NIET. -->
                         <li>Wij <strong>verkopen nooit</strong> jouw gegevens aan derden</li>

                         <!-- BELOFTE 2: Geen delen van informatie zonder toestemming.
                         Volgens de AVG mag je persoonsgegevens alleen delen met derden
                         als de gebruiker daar EXPLICIETE toestemming voor heeft gegeven. -->
                         <li>Wij delen jouw informatie niet zonder toestemming</li>

                         <!-- BELOFTE 3: Geen tracking cookies of analytics.
                         Tracking cookies volgen het surfgedrag van gebruikers over meerdere websites.
                         Analytics-tools (zoals Google Analytics) verzamelen gedetailleerde data
                         over hoe gebruikers de website gebruiken (welke pagina's, hoelang, etc.).
                         GamePlan Scheduler gebruikt GEEN van beide - de privacy van de gebruiker
                         is belangrijker dan het verzamelen van gebruiksstatistieken. -->
                         <li>Wij gebruiken geen tracking cookies of analytics</li>

                         <!-- BELOFTE 4: Geen advertenties die overmatig gamen aanmoedigen.
                         Dit is een maatschappelijk verantwoorde keuze: de applicatie helpt gebruikers
                         hun gaming-tijd te PLANNEN, niet om MEER te gamen. Er worden geen advertenties
                         getoond die onverantwoord gamegedrag stimuleren. -->
                         <li>Wij tonen geen advertenties die overmatig gamen aanmoedigen</li>
                    </ul>

                    <!-- ============================================================
                     SECTIE 4: JOUW RECHTEN
                     Volgens de AVG/GDPR heeft elke gebruiker recht op:
                     - Inzage: je mag je eigen data bekijken
                     - Rectificatie: je mag je data laten corrigeren
                     - Verwijdering: je mag je data laten verwijderen ("recht op vergetelheid")
                     - Dataportabiliteit: je mag je data opvragen in een leesbaar formaat
                     Deze sectie informeert gebruikers over hun rechten.
                     ============================================================ -->

                    <!-- Sectietitel met bovenmarge voor visuele scheiding. -->
                    <h3 class="mt-4">🗑️ Jouw Rechten</h3>

                    <!-- Ongeordende lijst met rechten van de gebruiker. -->
                    <ul>
                         <!-- RECHT 1: Inzage - De gebruiker kan al zijn/haar data bekijken.
                         Dit is het "recht op inzage" uit de AVG (artikel 15).
                         In de applicatie is dit geimplementeerd via het profiel en dashboard. -->
                         <li>Je kunt <strong>al jouw gegevens bekijken</strong> op je profiel en dashboard</li>

                         <!-- RECHT 2: Rectificatie en verwijdering - De gebruiker kan data bewerken of verwijderen.
                         Dit combineert het "recht op rectificatie" (artikel 16) en
                         het "recht op verwijdering" / "recht op vergetelheid" (artikel 17).
                         De gebruiker hoeft geen verzoek in te dienen - hij/zij kan het zelf doen. -->
                         <li>Je kunt al jouw informatie <strong>bewerken of verwijderen</strong></li>

                         <!-- RECHT 3: Accountverwijdering - De gebruiker kan zijn hele account laten verwijderen.
                         Dit gaat verder dan individuele items verwijderen: het hele account
                         en ALLE bijbehorende data wordt verwijderd. Omdat dit een ingrijpende actie is,
                         moet de gebruiker hiervoor contact opnemen met de ontwikkelaar. -->
                         <li>Je kunt <strong>accountverwijdering aanvragen</strong> door contact met ons op te nemen
                         </li>
                    </ul>

                    <!-- ============================================================
                     SECTIE 5: CONTACT VOOR PRIVACYVRAGEN
                     Een contactmogelijkheid voor privacyvragen is VERPLICHT volgens de AVG.
                     Gebruikers moeten weten bij wie ze terecht kunnen met vragen
                     over hun privacy en persoonsgegevens.
                     ============================================================ -->

                    <!-- Sectietitel met bovenmarge voor visuele scheiding. -->
                    <h3 class="mt-4">📬 Contact</h3>

                    <!-- Paragraaf met het e-mailadres voor privacyvragen.
                     <a href="mailto:..."> opent het standaard e-mailprogramma van de gebruiker
                     met het e-mailadres al ingevuld in het 'Aan' veld.
                     class="text-info" is een Bootstrap klasse die de link een lichtblauwe kleur geeft,
                     waardoor het duidelijk zichtbaar is als klikbaar element op de donkere achtergrond. -->
                    <p>Voor privacyvragen: <a href="mailto:harsha.kanaparthi20062@gmail.com"
                              class="text-info">harsha.kanaparthi20062@gmail.com</a></p>

                    <!-- AVG-NALEVINGSVERKLARING
                     <p class="mt-4 text-secondary"> is een paragraaf met Bootstrap klassen:
                     - mt-4: margin-top 4 = bovenmarge van 1.5rem (24px) voor visuele scheiding
                     - text-secondary: maakt de tekst grijs (minder opvallend dan gewone tekst)
                       Dit wordt vaak gebruikt voor minder belangrijke aanvullende informatie.
                     <small> maakt de tekst kleiner dan de standaard tekstgrootte.
                     Deze combinatie (grijs + klein) geeft aan dat dit een juridische opmerking is,
                     niet de hoofdinhoud van de pagina. Het bevestigt dat het privacybeleid
                     voldoet aan de AVG (Algemene Verordening Gegevensbescherming). -->
                    <p class="mt-4 text-secondary">
                         <small>Dit privacybeleid voldoet aan de AVG (Algemene Verordening Gegevensbescherming)
                              regelgeving.</small>
                    </p>

                    <!-- ============================================================
                     NAVIGATIE: TERUG NAAR DASHBOARD KNOP
                     ============================================================ -->

                    <!-- <a href="index.php" class="btn btn-primary mt-3"> is een hyperlink gestyled als knop.
                     href="index.php": navigeert naar het dashboard (de hoofdpagina van de applicatie).
                     class="btn btn-primary mt-3" zijn Bootstrap klassen:
                     - btn: basisstijl voor een knop (padding, rand, afgeronde hoeken, cursor: pointer).
                       Dit transformeert een gewone <a> link visueel in een klikbare knop.
                     - btn-primary: geeft de knop de primaire kleur (standaard BLAUW in Bootstrap, #0d6efd).
                       "Primary" is de kleur voor de belangrijkste actie op de pagina.
                       Andere opties zijn: btn-secondary (grijs), btn-success (groen),
                       btn-danger (rood), btn-warning (geel), btn-info (lichtblauw).
                     - mt-3: margin-top 3 = bovenmarge van 1rem (16px).
                       Dit zorgt voor ruimte tussen de knop en de tekst erboven.
                     De knop stuurt de gebruiker terug naar het dashboard waar schema's en
                     evenementen worden getoond. -->
                    <a href="index.php" class="btn btn-primary mt-3">↩️ Terug naar Dashboard</a>

                    <!-- Einde van card-body: sluit de binnenruimte van de kaart af. -->
               </div>

               <!-- Einde van card: sluit het visuele paneel af. -->
          </div>

          <!-- Einde van main: sluit het hoofdinhoud-gedeelte van de pagina af. -->
     </main>

     <!-- PHP include 'footer.php' voegt de voettekst (footer) in op deze plek.
         De footer bevat meestal copyright-informatie, het jaartal, en links naar
         het privacybeleid en de contactpagina. Net als de header wordt de footer
         hergebruikt op elke pagina via include (DRY-principe: Don't Repeat Yourself).
         Als je de footer wilt aanpassen, hoef je maar 1 bestand te wijzigen. -->
     <?php include 'footer.php'; ?>

     <!-- Bootstrap JavaScript bundle wordt geladen via CDN.
         "bundle" betekent dat Popper.js is INBEGREPEN in dit bestand.
         Popper.js is nodig voor het positioneren van tooltips, popovers en dropdowns.
         Dit JavaScript is nodig voor INTERACTIEVE Bootstrap-componenten:
         - Dropdown menu's (uitklapmenu's in de navigatiebalk)
         - Modal vensters (pop-up dialogen)
         - Navbar toggler (hamburger menu op mobiele apparaten)
         - Tooltips (kleine informatieballon bij hover)
         - Popovers (grotere informatieballonnen)
         - Collapse/Accordion (inklapbare secties)
         Het script wordt aan het EINDE van de body geladen (niet in de head),
         zodat de HTML-inhoud EERST wordt weergegeven aan de gebruiker.
         Dit verbetert de "perceived performance" (hoe snel de pagina LIJKT te laden). -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

     <!-- script.js is het EIGEN JavaScript-bestand van GamePlan Scheduler.
         Dit bestand bevat aangepaste JavaScript-functionaliteit die specifiek is
         voor deze applicatie, zoals interactieve elementen, formuliervalidatie,
         en andere client-side logica die niet door Bootstrap wordt geleverd. -->
     <script src="script.js"></script>

     <!-- Einde van body: sluit alle zichtbare inhoud van de pagina af. -->
</body>

<!-- Einde van html: sluit het volledige HTML-document af.
     Na deze tag mag geen enkele HTML-code meer staan. -->

</html>