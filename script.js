/**
 * ==========================================================================
 * SCRIPT.JS - CLIENT-SIDE JAVASCRIPT (BROWSER-KANT CODE)
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * WAT IS DIT BESTAND?
 * -------------------
 * Dit bestand bevat alle JavaScript-code die in de BROWSER van de gebruiker
 * draait (client-side). In tegenstelling tot PHP (dat op de SERVER draait),
 * wordt JavaScript uitgevoerd op het apparaat van de gebruiker zelf.
 *
 * CLIENT-SIDE vs SERVER-SIDE:
 * - SERVER-SIDE (PHP): code draait op de webserver, de gebruiker ziet de code NIET.
 *   Geschikt voor: database queries, wachtwoord hashing, sessie beheer.
 * - CLIENT-SIDE (JavaScript): code draait in de browser, de gebruiker KAN de code zien
 *   (via broncode bekijken / F12 Developer Tools).
 *   Geschikt voor: formulier validatie, animaties, interactieve elementen.
 *
 * WAAROM CLIENT-SIDE VALIDATIE?
 * Formulier validatie in JavaScript geeft DIRECTE feedback aan de gebruiker
 * ZONDER dat de pagina opnieuw geladen hoeft te worden. Dit is sneller dan
 * server-side validatie (PHP) omdat de data niet naar de server gestuurd wordt.
 * MAAR: client-side validatie is NIET VEILIG genoeg als enige bescherming,
 * want een kwaadwillende gebruiker kan JavaScript uitschakelen of omzeilen.
 * Daarom hebben we OOK server-side validatie in functions.php (dubbele beveiliging).
 *
 * HOE WORDT DIT BESTAND GELADEN?
 * In de HTML-pagina's staat onderaan:
 *   <script src="script.js"></script>
 * De browser downloadt dit bestand en voert de code direct uit.
 * Functies worden pas uitgevoerd wanneer ze worden AANGEROEPEN (bijv. bij
 * het indienen van een formulier via onsubmit="return validateLoginForm()").
 *
 * JAVASCRIPT BASISCONCEPTEN IN DIT BESTAND:
 * - const/let    : variabelen declareren (const = niet herwijsbaar, let = wel)
 * - function     : een herbruikbaar blok code met een naam
 * - if/else      : voorwaardelijke uitvoering (als/anders)
 * - return       : geeft een waarde terug aan de aanroeper
 * - alert()      : toont een pop-up venster met een bericht
 * - document     : het HTML-document object (de hele webpagina)
 * - getElementById() : zoekt een HTML-element op basis van zijn id-attribuut
 * - querySelector()  : zoekt een HTML-element met een CSS-selector
 * - addEventListener() : koppelt een functie aan een gebeurtenis (klik, laden, etc.)
 * - RegExp (regex)    : een patroon om tekst te controleren (validatie)
 *
 * STRUCTUUR: 7 secties
 * 1. Login formulier validatie      (validateLoginForm)
 * 2. Registratie formulier validatie (validateRegisterForm)
 * 3. Schema formulier validatie     (validateScheduleForm)
 * 4. Evenement formulier validatie  (validateEventForm)
 * 5. Pagina initialisatie           (DOMContentLoaded)
 * 6. Functie initialisatie          (initialiseerFuncties)
 * 7. Hulp functies                  (toonMelding)
 *
 * BUGFIXES:
 * - #1001: Alleen-spaties controle met regex /^\s*$/
 *   Probleem: gebruiker kon een veld invullen met alleen spaties ("    ")
 *   Oplossing: regex die controleert of de invoer ALLEEN uit spaties bestaat
 * - #1004: Strenge datumvalidatie met new Date() + isNaN()
 *   Probleem: ongeldige datums zoals "2025-13-45" werden geaccepteerd
 *   Oplossing: new Date() + isNaN() controle om ongeldige datums te detecteren
 * ==========================================================================
 */


// ==========================================================================
// SECTIE 1: LOGIN FORMULIER VALIDATIE
// ==========================================================================
// Deze sectie bevat de validatiefunctie voor het inlogformulier (login.php).
// Het formulier heeft twee velden: e-mailadres en wachtwoord.
// De functie controleert of beide velden ingevuld zijn en of het e-mailadres
// een geldig formaat heeft VOORDAT het formulier naar de server wordt gestuurd.
//
// KOPPELING MET HTML:
// In login.php staat: <form onsubmit="return validateLoginForm()">
// Het 'return' keyword zorgt ervoor dat als de functie 'false' retourneert,
// het formulier NIET wordt verzonden (de standaard actie wordt geblokkeerd).
// ==========================================================================

/**
 * validateLoginForm - Valideert het login formulier voor verzending
 *
 * Deze functie draait wanneer de gebruiker op de "Inloggen" knop klikt.
 * Het controleert of e-mail en wachtwoord correct zijn ingevuld.
 * Retourneert true om verzending toe te staan, false om te blokkeren.
 *
 * VALIDATIE STAPPEN:
 * 1. Controleer of beide velden ingevuld zijn (niet leeg)
 * 2. Controleer of het e-mailadres een geldig formaat heeft (met regex)
 * 3. Als alles geldig is: return true → formulier wordt verstuurd naar PHP
 * 4. Als iets ongeldig is: return false → formulier wordt NIET verstuurd
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateLoginForm() {
    // document.getElementById('email') zoekt het HTML-element met id="email"
    // .value haalt de TEKST op die de gebruiker heeft ingetypt
    // .trim() verwijdert WITRUIMTE aan het begin en einde van de tekst
    // Voorbeeld: "  test@mail.nl  " wordt "test@mail.nl"
    // Dit voorkomt dat spaties als geldige invoer worden geaccepteerd
    const emailAdres = document.getElementById('email').value.trim();

    // const = constante variabele declaratie (de waarde kan NIET opnieuw worden toegewezen)
    // In tegenstelling tot 'let' (herwijsbaar) en 'var' (oud, functie-scope)
    // We gebruiken const omdat de waarde na toewijzing niet meer verandert
    const wachtwoord = document.getElementById('password').value.trim();

    // De ! operator (logische NIET/NOT) keert een boolean om:
    // !true = false, !false = true
    // Een lege string ("") is "falsy" in JavaScript → !("") = true
    // Een gevulde string ("test") is "truthy" → !("test") = false
    // || is de logische OF (OR) operator: als EEN van beide true is, is het geheel true
    if (!emailAdres || !wachtwoord) {
        // alert() toont een MODAL pop-up venster in de browser
        // De gebruiker MOET op "OK" klikken voordat hij verder kan
        // Dit is de eenvoudigste manier om een foutmelding te tonen
        alert('E-mail en wachtwoord zijn verplicht.');
        // return false blokkeert het verzenden van het formulier
        // De browser stopt en stuurt NIETS naar de server
        return false;
    }

    // REGULIERE EXPRESSIE (regex) voor e-mail formaat validatie
    // Een regex is een PATROON dat tekst controleert op een bepaalde structuur
    // De regex staat tussen twee schuine strepen: /patroon/
    //
    // UITLEG VAN HET PATROON: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    // ^           = begin van de tekst (er mag NIETS voor staan)
    // [^\s@]+     = een of meer (+) tekens die GEEN spatie (\s) of @ zijn
    //              [^...] = ALLES BEHALVE de tekens in de haakjes
    //              Dit is het lokale deel (voor de @), bijv. "harsha"
    // @           = het apenstaartje (letterlijk, verplicht)
    // [^\s@]+     = het domein deel (na de @), bijv. "gmail"
    // \.          = een letterlijke punt (\ is een escape, want . heeft
    //              in regex een speciale betekenis: "elk willekeurig teken")
    // [^\s@]+     = de extensie, bijv. "com" of "nl"
    // $           = einde van de tekst (er mag NIETS na staan)
    //
    // VOORBEELDEN:
    // "harsha@gmail.com"    → GELDIG   (voldoet aan het patroon)
    // "test@"               → ONGELDIG (mist domein na @)
    // "@gmail.com"          → ONGELDIG (mist lokaal deel voor @)
    // "test test@mail.com"  → ONGELDIG (bevat spatie)
    //
    // .test() is een methode van RegExp die true/false retourneert
    // true als de tekst aan het patroon voldoet, false als niet
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailAdres)) {
        alert('Ongeldig e-mail formaat.');
        return false;
    }

    // Alle validaties geslaagd - sta verzending toe
    // return true laat het formulier door naar de server (PHP verwerkt het)
    return true;
}


// ==========================================================================
// SECTIE 2: REGISTRATIE FORMULIER VALIDATIE
// ==========================================================================
// Deze sectie bevat de validatiefunctie voor het registratie formulier
// (register.php). Het registratie formulier heeft DRIE velden:
// gebruikersnaam, e-mailadres en wachtwoord.
//
// De validatie is STRENGER dan bij login omdat:
// 1. De gebruikersnaam wordt OPGESLAGEN in de database (max 50 tekens)
// 2. We willen voorkomen dat gebruikers alleen-spaties namen aanmaken
// 3. Het wachtwoord moet sterk genoeg zijn (minimaal 8 tekens)
//
// KOPPELING MET HTML:
// In register.php staat: <form onsubmit="return validateRegisterForm()">
// ==========================================================================

/**
 * validateRegisterForm - Valideert het registratie formulier
 *
 * Controleert of gebruikersnaam, e-mail en wachtwoord aan eisen voldoen:
 * - Gebruikersnaam: max 50 tekens, niet leeg, geen alleen spaties
 * - E-mail: geldig formaat (bevat @, domein en extensie)
 * - Wachtwoord: minimaal 8 tekens (voor veiligheid)
 *
 * VALIDATIE VOLGORDE (van boven naar beneden):
 * 1. Zijn alle velden ingevuld?         → Zo nee: foutmelding
 * 2. Bevat de naam alleen spaties?       → Zo ja: foutmelding (bug #1001)
 * 3. Is de naam niet te lang?            → Meer dan 50: foutmelding
 * 4. Is het e-mail formaat geldig?       → Ongeldige regex: foutmelding
 * 5. Is het wachtwoord lang genoeg?      → Minder dan 8: foutmelding
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateRegisterForm() {
    // Haal alle drie de formulier waarden op met .trim()
    // .trim() verwijdert spaties aan het BEGIN en EINDE, maar NIET in het midden
    // Voorbeeld: "  Harsha  " → "Harsha", maar "Game Plan" blijft "Game Plan"
    const gebruikersnaam = document.getElementById('username').value.trim();
    const emailAdres = document.getElementById('email').value.trim();
    const wachtwoord = document.getElementById('password').value.trim();

    // Stap 1: Controleer of ALLE verplichte velden ingevuld zijn
    // De || (OF) operator controleert elk veld: als EEN veld leeg is → foutmelding
    // Een lege string na trim() ("") is "falsy" in JavaScript
    if (!gebruikersnaam || !emailAdres || !wachtwoord) {
        alert('Alle velden zijn verplicht.');
        return false;
    }

    // Stap 2: BUG FIX #1001 - Controleer op alleen-spaties invoer
    // PROBLEEM: trim() verwijdert spaties aan de randen, maar als de gebruiker
    // ALLEEN spaties intypt (bijv. "     "), is de getrimde waarde "" (leeg).
    // Dit wordt al gevangen door stap 1. MAAR als de gebruiker spaties intypt
    // EN de browser trim() niet correct afhandelt, is een extra controle veilig.
    //
    // REGEX UITLEG: /^\s*$/
    // ^     = begin van de tekst
    // \s*   = nul of meer (*) whitespace-tekens (\s = spatie, tab, newline)
    // $     = einde van de tekst
    // Dus: de HELE tekst bestaat uit NUL of meer spaties = LEEG/ALLEEN SPATIES
    //
    // .test() retourneert true als het patroon overeenkomt
    if (/^\s*$/.test(gebruikersnaam)) {
        alert('Gebruikersnaam kan niet alleen spaties bevatten.');
        return false;
    }

    // Stap 3: Controleer gebruikersnaam lengte
    // De database kolom 'username' is VARCHAR(50), wat maximaal 50 tekens toestaat
    // .length geeft het AANTAL TEKENS in een string
    // Als we meer dan 50 tekens naar de database sturen, wordt de tekst afgekapt
    // of geeft een fout. Daarom controleren we dit vooraf.
    if (gebruikersnaam.length > 50) {
        alert('Gebruikersnaam is te lang (maximaal 50 tekens).');
        return false;
    }

    // Stap 4: Valideer e-mail formaat (zelfde regex als in validateLoginForm)
    // Zie Sectie 1 voor uitgebreide uitleg van het regex patroon
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailAdres)) {
        alert('Ongeldig e-mail formaat.');
        return false;
    }

    // Stap 5: Controleer wachtwoord minimum lengte
    // 8 tekens is de MINIMALE aanbeveling voor wachtwoordveiligheid
    // (NIST SP 800-63B richtlijnen raden minimaal 8 tekens aan)
    // Het ECHTE hashen van het wachtwoord gebeurt in PHP (bcrypt in functions.php)
    // JavaScript controleert alleen of het LANG GENOEG is
    if (wachtwoord.length < 8) {
        alert('Wachtwoord moet minimaal 8 tekens zijn.');
        return false;
    }

    // Alle 5 validatiestappen geslaagd → formulier mag verstuurd worden
    return true;
}


// ==========================================================================
// SECTIE 3: SCHEMA FORMULIER VALIDATIE
// ==========================================================================
// Deze sectie valideert het formulier voor het toevoegen/bewerken van
// gaming speelschema's (add_schedule.php en edit_schedule.php).
//
// Een speelschema heeft VIJF velden:
// 1. Speltitel    (verplicht) - de naam van het spel dat gespeeld wordt
// 2. Datum        (verplicht) - wanneer er gespeeld wordt
// 3. Tijd         (verplicht) - hoe laat er gespeeld wordt
// 4. Vrienden     (optioneel) - komma-gescheiden lijst van meespelers
// 5. Gedeeld met  (optioneel) - met wie het schema gedeeld wordt
//
// DATUM VALIDATIE Is EXTRA STRENG (Bug fix #1004):
// We gebruiken het JavaScript Date-object om te controleren of de datum
// echt bestaat EN in de toekomst ligt. Dit voorkomt ongeldige datums
// zoals "30 februari" of datums in het verleden.
//
// KOPPELING MET HTML:
// In add_schedule.php staat: <form onsubmit="return validateScheduleForm()">
// ==========================================================================

/**
 * validateScheduleForm - Valideert het schema formulier
 *
 * Valideert een gaming schema met 7 validatiestappen:
 * 1. Speltitel niet leeg of alleen spaties (bug #1001)
 * 2. Datum is ingevuld
 * 3. Datum is een GELDIGE datum (niet "2025-13-45")
 * 4. Datum ligt in de TOEKOMST (niet in het verleden)
 * 5. Tijd heeft geldig UU:MM formaat
 * 6. Vrienden veld bevat alleen toegestane tekens
 * 7. Gedeeld-met veld bevat alleen toegestane tekens
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateScheduleForm() {
    // Haal alle formulier waarden op via hun HTML id-attribuut
    // Elk document.getElementById() zoekt het element in de DOM
    // (Document Object Model = de boomstructuur van alle HTML-elementen)
    const spelTitel = document.getElementById('game_title').value.trim();
    const datum = document.getElementById('date').value;
    const tijd = document.getElementById('time').value;
    const vriendenStr = document.getElementById('friends_str').value.trim();
    const gedeeldMetStr = document.getElementById('shared_with_str').value.trim();

    // Stap 1: BUG FIX #1001 - Controleer of speltitel niet leeg of alleen spaties is
    // Dubbele controle: !spelTitel vangt lege string, regex vangt alleen-spaties
    // De || (OF) operator: als EEN van beide waar is → foutmelding
    if (!spelTitel || /^\s*$/.test(spelTitel)) {
        alert('Speltitel is verplicht en kan niet alleen spaties bevatten.');
        return false;
    }

    // Stap 2: BUG FIX #1004 - Controleer of datum is ingevuld
    // Een leeg datumveld geeft een lege string ("") terug
    if (!datum) {
        alert('Datum is verplicht.');
        return false;
    }

    // Stap 3 & 4: Geavanceerde datum validatie met het JavaScript Date object
    // new Date(datum) maakt een Date-object aan van de ingevoerde tekst
    // Het Date object is een INGEBOUWD JavaScript object dat datums en tijden
    // kan parsen, opslaan en vergelijken.
    //
    // VOORBEELD: new Date("2025-12-25") → een object dat 25 december 2025 voorstelt
    const gekozenDatum = new Date(datum);

    // new Date() ZONDER parameters geeft de HUIDIGE datum en tijd terug
    // Dit gebruiken we als referentiepunt: is de gekozen datum in de toekomst?
    const vandaag = new Date();

    // setHours(0, 0, 0, 0) zet de tijd op MIDDERNACHT (00:00:00.000)
    // Parameters: uren, minuten, seconden, milliseconden
    // WAAROM? Als vandaag 15:30 is en de gebruiker vandaag kiest,
    // zou de vergelijking FALEN omdat 00:00 < 15:30. Door beide op
    // middernacht te zetten, vergelijken we ALLEEN de datum (niet de tijd).
    vandaag.setHours(0, 0, 0, 0);

    // .getTime() converteert het Date object naar MILLISECONDEN sinds
    // 1 januari 1970 (de "Unix epoch"). Dit is een getal dat makkelijk
    // te vergelijken is. isNaN() controleert of het resultaat "Not a Number" is.
    // Als de datum ongeldig is (bijv. "2025-13-45"), retourneert getTime() NaN.
    // NaN = Not a Number = geen geldig getal.
    if (isNaN(gekozenDatum.getTime())) {
        alert('Ongeldig datum formaat.');
        return false;
    }

    // Stap 4: Controleer of de datum VANDAAG of in de TOEKOMST is
    // De < operator vergelijkt twee Date objecten (intern als milliseconden)
    // Als gekozenDatum < vandaag → de datum ligt in het VERLEDEN
    if (gekozenDatum < vandaag) {
        alert('Datum moet vandaag of in de toekomst zijn.');
        return false;
    }

    // Stap 5: Valideer tijd formaat (UU:MM = uren:minuten)
    // REGEX UITLEG: /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
    // ^               = begin van de tekst
    // (               = begin van een groep (voor de uren)
    //   [01]?[0-9]    = optioneel 0 of 1, gevolgd door 0-9 → matches 0-19
    //                   ? = het vorige element is OPTIONEEL (0 of 1 keer)
    //   |             = OF (alternatief)
    //   2[0-3]        = 20, 21, 22 of 23
    // )               = einde van de groep
    // :               = letterlijke dubbele punt (verplicht)
    // [0-5][0-9]      = 00 tot 59 (minuten)
    // $               = einde van de tekst
    //
    // GELDIGE VOORBEELDEN: "9:30", "09:30", "23:59", "0:00"
    // ONGELDIGE VOORBEELDEN: "24:00", "12:60", "abc", "9:5"
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(tijd)) {
        alert('Ongeldig tijd formaat. Gebruik UU:MM.');
        return false;
    }

    // Stap 6: Valideer vrienden veld (optioneel, maar als ingevuld: geldig formaat)
    // De && (EN) operator: de regex wordt ALLEEN gecontroleerd als vriendenStr
    // niet leeg is. Als het veld leeg is (""), wordt de hele expressie overgeslagen.
    //
    // REGEX UITLEG: /^[a-zA-Z0-9,\s]*$/
    // ^               = begin van de tekst
    // [a-zA-Z0-9,\s]  = toegestane tekens: letters (a-z, A-Z), cijfers (0-9),
    //                   komma's (,) en spaties (\s)
    // *               = nul of meer van de bovenstaande tekens
    // $               = einde van de tekst
    //
    // GELDIG: "Jan, Piet, Klaas" of "Player1,Player2"
    // ONGELDIG: "Jan; Piet" (puntkomma niet toegestaan) of "Jan<script>"
    // Dit voorkomt XSS (Cross-Site Scripting) injecties met speciale tekens
    if (vriendenStr && !/^[a-zA-Z0-9,\s]*$/.test(vriendenStr)) {
        alert('Vrienden veld bevat ongeldige tekens.');
        return false;
    }

    // Stap 7: Valideer gedeeld-met veld (zelfde regels als vrienden)
    if (gedeeldMetStr && !/^[a-zA-Z0-9,\s]*$/.test(gedeeldMetStr)) {
        alert('Gedeeld met veld bevat ongeldige tekens.');
        return false;
    }

    // Alle 7 validatiestappen geslaagd → formulier mag verstuurd worden
    return true;
}


// ==========================================================================
// SECTIE 4: EVENEMENT FORMULIER VALIDATIE
// ==========================================================================
// Deze sectie valideert het formulier voor gaming evenementen (toernooien,
// livestreams, game-releases, etc.) in add_event.php en edit_event.php.
//
// Een evenement heeft MEER velden dan een schema:
// 1. Titel          (verplicht) - naam van het evenement (max 100 tekens)
// 2. Datum          (verplicht) - wanneer het evenement plaatsvindt
// 3. Tijd           (verplicht) - hoe laat het evenement begint
// 4. Beschrijving   (optioneel) - korte beschrijving (max 500 tekens)
// 5. Externe link   (optioneel) - URL naar bijv. Twitch stream of toernooi site
// 6. Gedeeld met    (optioneel) - komma-gescheiden lijst van gebruikers
//
// EXTRA VALIDATIE: URL-formaat controle voor de externe link
// We willen alleen geldige webadressentoelaten (http:// of https://)
//
// KOPPELING MET HTML:
// In add_event.php staat: <form onsubmit="return validateEventForm()">
// ==========================================================================

/**
 * validateEventForm - Valideert het evenement formulier
 *
 * Valideert een gaming evenement met 7 validatiestappen:
 * 1. Titel niet leeg of alleen spaties (bug #1001)
 * 2. Titel niet langer dan 100 tekens
 * 3. Datum is ingevuld en geldig (bug #1004)
 * 4. Datum ligt in de toekomst
 * 5. Tijd heeft geldig UU:MM formaat
 * 6. Beschrijving niet langer dan 500 tekens
 * 7. Externe link is een geldige URL (indien ingevuld)
 * 8. Gedeeld-met bevat alleen toegestane tekens
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateEventForm() {
    // Haal alle 6 formulier waarden op via document.getElementById()
    // Elk veld heeft een uniek id-attribuut in de HTML
    const titel = document.getElementById('title').value.trim();
    const datum = document.getElementById('date').value;
    const tijd = document.getElementById('time').value;

    // beschrijving en externeLink worden NIET getrimd (.trim())
    // omdat deze velden spaties en opmaak mogen bevatten
    const beschrijving = document.getElementById('description').value;
    const externeLink = document.getElementById('external_link').value;
    const gedeeldMetStr = document.getElementById('shared_with_str').value.trim();

    // Stap 1: BUG FIX #1001 - Valideer dat titel niet leeg of alleen spaties is
    // Zelfde patroon als in validateScheduleForm() en validateRegisterForm()
    if (!titel || /^\s*$/.test(titel)) {
        alert('Titel is verplicht en kan niet alleen spaties bevatten.');
        return false;
    }

    // Stap 2: Controleer titel maximum lengte
    // De database kolom 'title' in de Events tabel is VARCHAR(100)
    // Als we meer dan 100 tekens versturen, wordt de tekst afgekapt door MySQL
    if (titel.length > 100) {
        alert('Titel is te lang (maximaal 100 tekens).');
        return false;
    }

    // Stap 3: BUG FIX #1004 - Valideer dat datum is ingevuld
    if (!datum) {
        alert('Datum is verplicht.');
        return false;
    }

    // Stap 3b: Maak Date objecten aan voor vergelijking
    // Zelfde techniek als in validateScheduleForm() - zie Sectie 3 voor
    // gedetailleerde uitleg van new Date(), setHours() en isNaN()
    const gekozenDatum = new Date(datum);
    const vandaag = new Date();
    vandaag.setHours(0, 0, 0, 0);

    // Controleer of de datum een GELDIG Date object oplevert
    // isNaN() = "is Not a Number" → controleert of getTime() een geldig getal geeft
    if (isNaN(gekozenDatum.getTime())) {
        // JJJJ-MM-DD = Jaar-Maand-Dag, het standaard HTML5 datum formaat
        alert('Ongeldig datum formaat. Gebruik JJJJ-MM-DD.');
        return false;
    }

    // Stap 4: Controleer of de datum vandaag of in de toekomst is
    if (gekozenDatum < vandaag) {
        alert('Datum moet vandaag of in de toekomst zijn.');
        return false;
    }

    // Stap 5: Valideer tijd formaat (UU:MM)
    // Zelfde regex als in validateScheduleForm() - zie Sectie 3 voor
    // gedetailleerde uitleg van het regex patroon
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(tijd)) {
        alert('Ongeldig tijd formaat. Gebruik UU:MM.');
        return false;
    }

    // Stap 6: Controleer beschrijving lengte (max 500 tekens)
    // De database kolom 'description' is TEXT, maar we beperken de lengte
    // tot 500 tekens voor een beknopte beschrijving
    // .length geeft het aantal tekens in de string (inclusief spaties)
    if (beschrijving.length > 500) {
        alert('Beschrijving is te lang (maximaal 500 tekens).');
        return false;
    }

    // Stap 7: Valideer externe URL indien opgegeven
    // De && operator zorgt dat de regex ALLEEN draait als externeLink niet leeg is
    // Een leeg veld ("") is "falsy" → de hele expressie wordt overgeslagen
    //
    // REGEX UITLEG: /^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/
    // ^                = begin van de tekst
    // (https?:\/\/)?   = optioneel "http://" of "https://"
    //                   s? = de 's' is optioneel (http OF https)
    //                   \/ = letterlijke schuine streep (ge-escaped met \)
    //                   ? aan het einde = de hele groep is optioneel
    // [\w\-]+          = een of meer (+) woordtekens (\w = a-z, A-Z, 0-9, _)
    //                   of koppeltekens (\-), bijv. "twitch" of "my-site"
    // (\.[\w\-]+)+     = een of meer keer: een punt gevolgd door woordtekens
    //                   bijv. ".tv" of ".co.uk" (kan meerdere punten bevatten)
    // [/#?]?           = optioneel een /, # of ? (begin van pad/fragment/query)
    // .*               = nul of meer willekeurige tekens (de rest van de URL)
    // $                = einde van de tekst
    //
    // GELDIGE VOORBEELDEN:
    // "https://twitch.tv/harsha"     → GELDIG
    // "http://toernooi.nl/signup"    → GELDIG
    // "twitch.tv"                    → GELDIG (protocol is optioneel)
    // ONGELDIGE VOORBEELDEN:
    // "geen website"                 → ONGELDIG (geen punt/domein)
    // "ftp://server.com"             → ONGELDIG (alleen http/https)
    if (externeLink && !/^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/.test(externeLink)) {
        alert('Ongeldig externe link formaat.');
        return false;
    }

    // Stap 8: Valideer gedeeld-met veld (zelfde regex als in Sectie 3)
    // Alleen letters, cijfers, komma's en spaties zijn toegestaan
    if (gedeeldMetStr && !/^[a-zA-Z0-9,\s]*$/.test(gedeeldMetStr)) {
        alert('Gedeeld met veld bevat ongeldige tekens.');
        return false;
    }

    // Alle 8 validatiestappen geslaagd → formulier mag verstuurd worden
    return true;
}


// ==========================================================================
// SECTIE 5: PAGINA INITIALISATIE (DOMContentLoaded)
// ==========================================================================
// Deze sectie bevat code die wordt uitgevoerd zodra de HTML-pagina
// VOLLEDIG GELADEN is in de browser. Dit is het startpunt van alle
// interactieve functies die niet aan een specifiek formulier zijn gekoppeld.
//
// WAT IS DE DOM?
// DOM = Document Object Model = een boomstructuur die de browser maakt
// van de HTML-code. Elk HTML-element (<div>, <p>, <a>, etc.) wordt een
// "node" (knooppunt) in deze boom. JavaScript kan de DOM lezen en veranderen:
// elementen toevoegen, verwijderen, stijlen aanpassen, tekst wijzigen, etc.
//
// WAAROM DOMContentLoaded?
// Als de browser HTML van boven naar beneden leest, kan het zijn dat een
// JavaScript-commando een element probeert te vinden dat NOG NIET geladen is.
// DOMContentLoaded wacht totdat ALLE HTML-elementen beschikbaar zijn, maar
// hoeft NIET te wachten op afbeeldingen, CSS of andere externe bestanden.
// Dit verschilt van 'load' dat wacht op ALLES (inclusief afbeeldingen).
// ==========================================================================

/**
 * DOMContentLoaded Event Listener
 *
 * Een EVENT LISTENER is een functie die "luistert" naar een bepaalde
 * gebeurtenis (event) en code uitvoert wanneer die gebeurtenis plaatsvindt.
 *
 * SYNTAX: element.addEventListener(gebeurtenis, functieDieUitvoert)
 * - document = het hele HTML-document
 * - 'DOMContentLoaded' = de gebeurtenis: "alle HTML is geladen"
 * - function() { ... } = de code die wordt uitgevoerd (callback functie)
 *
 * Een CALLBACK functie is een functie die als PARAMETER aan een andere
 * functie wordt meegegeven, en later wordt uitgevoerd (niet direct).
 */
document.addEventListener('DOMContentLoaded', function () {
    // console.log() schrijft een bericht naar de DEVELOPER CONSOLE
    // (F12 → Console tab in Chrome/Firefox/Edge)
    // Dit is ALLEEN zichtbaar voor ontwikkelaars, niet voor normale gebruikers
    // Het is nuttig voor DEBUGGEN: controleren of code wordt bereikt
    console.log('GamePlan Scheduler succesvol geladen!');

    // Roep de initialisatiefunctie aan die alle interactieve functies instelt
    // De functie is gedefinieerd in Sectie 6 hieronder
    initialiseerFuncties();
});


// ==========================================================================
// SECTIE 6: FUNCTIE INITIALISATIE
// ==========================================================================
// Deze sectie stelt alle interactieve pagina-functies in die niet direct
// aan een specifiek formulier zijn gekoppeld. Het omvat drie functionaliteiten:
//
// 1. VLOEIEND SCROLLEN (smooth scrolling):
//    Wanneer een gebruiker op een interne link klikt (bijv. href="#sectie2"),
//    springt de pagina standaard DIRECT naar dat punt. Met smooth scrolling
//    GLIJDT de pagina er geleidelijk naartoe, wat een prettigere ervaring geeft.
//
// 2. VERWIJDER BEVESTIGING (delete confirmation):
//    Rode knoppen (.btn-danger) die iets verwijderen, krijgen automatisch een
//    bevestigingspop-up ("Weet je zeker dat...?") zodat gebruikers niet per
//    ongeluk data verwijderen.
//
// 3. AUTOMATISCH MELDINGEN SLUITEN (auto-dismiss alerts):
//    Succes- en foutmeldingen (.alert-dismissible) verdwijnen automatisch
//    na 5 seconden, zodat de gebruiker ze niet handmatig hoeft weg te klikken.
// ==========================================================================

/**
 * initialiseerFuncties - Stelt interactieve pagina functies in
 *
 * Deze functie wordt aangeroepen door het DOMContentLoaded event (Sectie 5).
 * Het installeert drie soorten interactieve functies op de pagina.
 *
 * DESIGN PATTERN: Event Delegation
 * In plaats van elke knop individueel in de HTML te configureren, zoeken we
 * ALLE relevante elementen op en voegen programmatisch event listeners toe.
 * Dit is schoner en makkelijker te onderhouden.
 */
function initialiseerFuncties() {
    // ======================================================================
    // FUNCTIONALITEIT 1: VLOEIEND SCROLLEN
    // ======================================================================
    // document.querySelectorAll() zoekt ALLE elementen die aan een CSS-selector voldoen
    // 'a[href^="#"]' = alle <a> links waarvan het href-attribuut BEGINT MET (#)
    //   a        = HTML link elementen
    //   [href^="#"] = attribuut-selector: href begint met (^=) een hekje (#)
    //   Dit zijn INTERNE links die naar een element op dezelfde pagina wijzen
    //
    // .forEach() voert een functie uit voor ELK gevonden element
    // forEach is een ARRAY-METHODE die een callback uitvoert per item
    //
    // anchor => { ... } is een ARROW FUNCTION (pijlfunctie, ES6 syntax)
    // Dit is een kortere schrijfwijze voor: function(anchor) { ... }
    // Arrow functions zijn geïntroduceerd in ES6 (ECMAScript 2015)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        // addEventListener('click', ...) luistert naar KLIK-gebeurtenissen
        // Telkens als de gebruiker op dit element klikt, wordt de functie uitgevoerd
        anchor.addEventListener('click', function (e) {
            // e.preventDefault() VOORKOMT het standaard browsergedrag
            // Normaal springt de browser direct naar het doel-element als je op
            // een ankerlink klikt. We willen dit VERVANGEN door vloeiend scrollen.
            // 'e' is het Event object dat informatie bevat over de gebeurtenis
            e.preventDefault();

            // this.getAttribute('href') haalt de waarde van het href-attribuut op
            // 'this' verwijst naar het element dat geklikt is (de <a> link)
            // Voorbeeld: als href="#sectie2", dan is de waarde "#sectie2"
            // document.querySelector() zoekt het EERSTE element dat aan de selector voldoet
            const doel = document.querySelector(this.getAttribute('href'));

            // Controleer of het doel-element daadwerkelijk BESTAAT op de pagina
            // Als het element niet gevonden wordt, geeft querySelector() null terug
            // null is "falsy" in JavaScript, dus de if-controle voorkomt een fout
            if (doel) {
                // scrollIntoView() scrollt de pagina zodat het element ZICHTBAAR wordt
                // { behavior: 'smooth' } maakt de scrollbeweging GELEIDELIJK
                // In plaats van direct springen, glijdt het scherm soepel naar het doel
                // Dit werkt samen met CSS: html { scroll-behavior: smooth; } (style.css)
                doel.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // ======================================================================
    // FUNCTIONALITEIT 2: VERWIJDER BEVESTIGING
    // ======================================================================
    // Zoek ALLE knoppen met de klasse 'btn-danger' (rode verwijderknoppen)
    // .btn-danger is een Bootstrap klasse voor rode "gevaar" knoppen
    document.querySelectorAll('.btn-danger').forEach(btn => {
        // Controleer of de knop al een onclick-attribuut heeft in de HTML
        // hasAttribute() retourneert true als het attribuut BESTAAT
        // Sommige knoppen in index.php hebben al onclick="return confirm(...)"
        // Voor die knoppen hoeven we GEEN extra event listener toe te voegen
        // Dit voorkomt DUBBELE bevestigingspop-ups
        if (!btn.hasAttribute('onclick')) {
            btn.addEventListener('click', function (e) {
                // confirm() toont een pop-up met "OK" en "Annuleren" knoppen
                // Het retourneert true bij "OK" en false bij "Annuleren"
                // De ! (NOT) operator keert het resultaat om:
                // Als de gebruiker op "Annuleren" klikt → !false = true → voer if-blok uit
                if (!confirm('Weet je zeker dat je dit wilt verwijderen?')) {
                    // e.preventDefault() annuleert de klik-actie
                    // De browser volgt de link NIET → het item wordt NIET verwijderd
                    // De gebruiker heeft de verwijdering geannuleerd
                    e.preventDefault();
                }
            });
        }
    });

    // ======================================================================
    // FUNCTIONALITEIT 3: AUTOMATISCH MELDINGEN SLUITEN
    // ======================================================================
    // Zoek ALLE meldingen met de klasse 'alert-dismissible'
    // .alert-dismissible is een Bootstrap klasse voor sluitbare meldingen
    // (meldingen met een kruisje/sluitknop)
    document.querySelectorAll('.alert-dismissible').forEach(melding => {
        // setTimeout() voert een functie uit NA een bepaalde vertraging
        // SYNTAX: setTimeout(functie, vertraging_in_milliseconden)
        // 5000 milliseconden = 5 seconden
        //
        // () => { ... } is een ARROW FUNCTION (pijlfunctie)
        // De code binnen { ... } wordt pas na 5 seconden uitgevoerd
        // Dit is ASYNCHROON: de rest van de code gaat gewoon door
        // terwijl de timer op de achtergrond aftelt
        setTimeout(() => {
            // querySelector() zoekt het EERSTE element BINNEN de melding
            // dat de klasse 'btn-close' heeft (Bootstrap's sluitknop/kruisje)
            const sluitKnop = melding.querySelector('.btn-close');

            // Controleer of de sluitknop bestaat voordat we erop "klikken"
            // .click() simuleert een muisklik op het element
            // Dit activeert Bootstrap's dismiss functionaliteit:
            // de melding verdwijnt met een fade-out animatie
            if (sluitKnop) sluitKnop.click();
        }, 5000);
    });
}


// ==========================================================================
// SECTIE 7: HULP FUNCTIES (Utility Functions)
// ==========================================================================
// Deze sectie bevat herbruikbare hulpfuncties die door andere delen
// van de applicatie kunnen worden aangeroepen. Momenteel bevat het
// één functie: toonMelding() - voor het dynamisch tonen van meldingen.
//
// DYNAMISCHE ELEMENTEN:
// In tegenstelling tot de meldingen die PHP genereert (via getMessage()
// in functions.php), maakt toonMelding() elementen aan met JavaScript.
// Dit is handig voor meldingen die moeten verschijnen ZONDER pagina-herlaad
// (bijv. na een AJAX-verzoek of een client-side actie).
//
// DOM MANIPULATIE:
// JavaScript kan HTML-elementen dynamisch AANMAKEN, stijlen en TOEVOEGEN
// aan de pagina. Dit heet DOM-manipulatie. De stappen zijn:
// 1. createElement() - maak een nieuw element aan (in geheugen)
// 2. Stel eigenschappen in (className, style, innerHTML)
// 3. appendChild() - voeg het element toe aan de pagina (wordt zichtbaar)
// ==========================================================================

/**
 * toonMelding - Toont een tijdelijke melding op het scherm
 *
 * Maakt dynamisch een Bootstrap alert-element aan, plaatst het
 * rechtsboven in het scherm, en verwijdert het automatisch na 5 seconden.
 *
 * PARAMETERS:
 * @param {string} bericht  Het bericht om te tonen (bijv. "Opgeslagen!")
 * @param {string} type     Het type melding, bepaalt de kleur:
 *                          'success' = groen (gelukt)
 *                          'danger'  = rood (fout)
 *                          'warning' = geel (waarschuwing)
 *                          'info'    = blauw (informatie) - STANDAARD
 *                          'error'   = wordt omgezet naar 'danger' (rood)
 *
 * STANDAARD PARAMETER (type = 'info'):
 * Als de aanroeper geen type meegeeft, wordt automatisch 'info' gebruikt.
 * Dit heet een "default parameter" (standaard parameter, ES6 JavaScript).
 * Voorbeeld: toonMelding("Hallo") → type is automatisch 'info'
 */
function toonMelding(bericht, type = 'info') {
    // Stap 1: MAAK een nieuw HTML-element aan
    // document.createElement('div') maakt een <div> element in het GEHEUGEN
    // Het element is nog NIET zichtbaar op de pagina (nog niet toegevoegd aan de DOM)
    const melding = document.createElement('div');

    // Stap 2: STEL de CSS-klassen in
    // .className stelt het class-attribuut in op het element
    // De TEMPLATE LITERAL (backticks ` `) maakt het mogelijk om variabelen
    // in te voegen met ${...} (dollar + accolades)
    //
    // De TERNAIRE OPERATOR (? :) is een korte if/else:
    // conditie ? waarde_als_true : waarde_als_false
    // type === 'error' ? 'danger' : type
    // → Als type 'error' is → gebruik 'danger' (Bootstrap kent geen 'error')
    // → Anders → gebruik het originele type ('success', 'warning', 'info')
    //
    // BOOTSTRAP KLASSEN die worden toegepast:
    // alert         = Bootstrap basistyle voor meldingen (padding, rand, afronding)
    // alert-${type} = kleur: alert-success (groen), alert-danger (rood), etc.
    // alert-dismissible = voegt ruimte toe voor de sluitknop
    // fade          = Bootstrap animatieklasse: start als onzichtbaar
    // show          = maakt het element zichtbaar (gecombineerd met fade = fade-in)
    melding.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;

    // Stap 3: STEL de inline CSS in
    // .style.cssText stelt meerdere CSS-eigenschappen tegelijk in als tekst
    // position: fixed = het element blijft op dezelfde plek bij scrollen
    // top: 100px      = 100px vanaf de bovenkant (onder de header)
    // right: 20px     = 20px vanaf de rechterrand van het scherm
    // z-index: 9999   = BOVEN alle andere elementen (zelfs boven de header met z-index 1000)
    // min-width: 300px = minimaal 300px breed (zodat korte berichten niet te smal worden)
    melding.style.cssText = 'position: fixed; top: 100px; right: 20px; z-index: 9999; min-width: 300px;';

    // Stap 4: VUL het element met HTML-inhoud
    // .innerHTML stelt de HTML-inhoud BINNENIN het element in
    // WAARSCHUWING: innerHTML kan XSS-kwetsbaar zijn als 'bericht' onveilige
    // gebruikersinvoer bevat. In dit geval is 'bericht' altijd een door de
    // ontwikkelaar bepaalde tekst, dus dit is hier veilig.
    //
    // De template literal bevat:
    // 1. ${bericht} = de meldingstekst (bijv. "Opgeslagen!")
    // 2. Een Bootstrap sluitknop (<button> met .btn-close klasse)
    //    data-bs-dismiss="alert" = Bootstrap attribuut dat de sluiting afhandelt
    //    De sluitknop verschijnt als een kruisje (×) rechts in de melding
    melding.innerHTML = `
        ${bericht}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Stap 5: VOEG het element toe aan de pagina (wordt nu ZICHTBAAR)
    // document.body.appendChild() voegt het element toe als LAATSTE kind
    // van het <body> element. Het element verschijnt op het scherm op de
    // positie die we met CSS hebben ingesteld (rechtsboven).
    document.body.appendChild(melding);

    // Stap 6: VERWIJDER het element automatisch na 5 seconden
    // setTimeout() wacht 5000 milliseconden (5 seconden) en voert dan de functie uit
    // melding.remove() verwijdert het element VOLLEDIG uit de DOM
    // Na remove() is het element niet meer zichtbaar en wordt het geheugen vrijgegeven
    //
    // Dit is een ARROW FUNCTION: () => melding.remove()
    // Korte schrijfwijze voor: function() { melding.remove(); }
    // Als een arrow function slechts ÉÉN expressie bevat, mag je de
    // accolades { } en het return keyword weglaten
    setTimeout(() => melding.remove(), 5000);
}


// ==========================================================================
// EINDE VAN BESTAND
// ==========================================================================
// Dit bestand bevat 7 secties met 5 functies:
//
// OVERZICHT VAN ALLE FUNCTIES:
// ┌────────────────────────────┬──────────────────────────────────────────┐
// │ Functie                    │ Beschrijving                             │
// ├────────────────────────────┼──────────────────────────────────────────┤
// │ validateLoginForm()        │ Login formulier validatie (2 velden)     │
// │ validateRegisterForm()     │ Registratie validatie (3 velden, 5 stap) │
// │ validateScheduleForm()     │ Schema validatie (5 velden, 7 stappen)   │
// │ validateEventForm()        │ Evenement validatie (6 velden, 8 stap)   │
// │ initialiseerFuncties()     │ Scroll, bevestiging, auto-sluit          │
// │ toonMelding()              │ Dynamische melding tonen (5s auto-sluit) │
// └────────────────────────────┴──────────────────────────────────────────┘
//
// JAVASCRIPT CONCEPTEN GEBRUIKT IN DIT BESTAND:
// - const/let variabelen, functies, if/else, return, for/forEach
// - DOM manipulatie: getElementById, querySelector, querySelectorAll
// - Event listeners: addEventListener, DOMContentLoaded, click
// - Regular Expressions (regex): e-mail, tijd, URL, spaties validatie
// - Date object: new Date(), getTime(), isNaN(), setHours()
// - Template literals: backticks met ${variabele}
// - Arrow functions: (param) => { ... } of (param) => expressie
// - Ternaire operator: conditie ? waarde_true : waarde_false
// - setTimeout: vertraagde uitvoering (asynchroon)
// - DOM creatie: createElement, className, innerHTML, appendChild, remove
// ==========================================================================
