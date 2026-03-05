<?php
/**
 * ==========================================================================
 * EDIT_FAVORITE.PHP - FAVORIET SPEL BEWERKEN
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bewerk de titel, beschrijving en persoonlijke notitie van een favoriet spel.
 *
 * Deze pagina ontvangt een spel-ID via de URL (bijv. edit_favorite.php?id=3).
 * Het zoekt het bijbehorende favoriete spel op in de database, toont een
 * vooringevuld formulier met de bestaande gegevens, en slaat wijzigingen op
 * na verzending. Na succesvol bijwerken wordt de gebruiker teruggestuurd
 * naar profile.php waar de lijst met favoriete spellen wordt getoond.
 * ==========================================================================
 */

/* ============================================================
 * STAP 1 - FUNCTIES INLADEN MET REQUIRE_ONCE
 * ============================================================
 * 'require_once' laadt het bestand 'functions.php' in.
 * Dit bestand bevat ALLE hulpfuncties die we nodig hebben op deze pagina:
 *   - isLoggedIn()           = controleert of de gebruiker is ingelogd
 *   - getUserId()            = haalt het unieke ID van de ingelogde gebruiker op
 *   - getFavoriteGames()     = haalt alle favoriete spellen van een gebruiker op uit de database
 *   - updateFavoriteGame()   = werkt de gegevens van een bestaand favoriet spel bij in de database
 *   - setMessage()           = slaat een melding op in de sessie (bijv. "Spel bijgewerkt!")
 *   - getMessage()           = haalt de opgeslagen melding op om te tonen aan de gebruiker
 *   - safeEcho()             = toont tekst veilig op de pagina (beschermt tegen XSS-aanvallen)
 *   - checkSessionTimeout()  = controleert of de sessie niet is verlopen
 * 'require_once' zorgt ervoor dat het bestand MAXIMAAL 1 KEER wordt ingeladen.
 * Als het al eerder is ingeladen, wordt het NIET opnieuw geladen.
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
 * kan niemand anders zomaar de gegevens van favoriete spellen aanpassen.
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
 * (ook niet-ingelogde bezoekers) favoriete spellen kunnen bewerken.
 * ============================================================ */
if (!isLoggedIn()) {
    header("Location: login.php"); /* Stuur de browser door naar login.php met een HTTP-header */
    exit; /* Stop onmiddellijk ALLE code hieronder - niets meer uitvoeren */
}

/* ============================================================
 * STAP 4 - GEBRUIKERS-ID EN SPEL-ID OPHALEN
 * ============================================================
 * getUserId() haalt het unieke ID-nummer van de ingelogde gebruiker op uit de sessie.
 * Dit wordt gebruikt om te verifiereren dat het spel bij DEZE gebruiker hoort.
 *
 * $_GET['id'] haalt het SPEL-ID op uit de URL.
 * Wanneer de gebruiker op "Bewerken" klikt bij een favoriet spel op profile.php,
 * wordt hij doorgestuurd naar bijv. edit_favorite.php?id=3
 * Het deel na het vraagteken (?id=3) heet een "query string" of "URL-parameter".
 * $_GET is een speciale PHP-array die ALLE URL-parameters bevat.
 * $_GET['id'] haalt de waarde '3' op uit de URL.
 *
 * De '??' operator (null coalescing operator):
 *   - Als $_GET['id'] BESTAAT en niet null is, gebruik die waarde
 *   - Als $_GET['id'] NIET BESTAAT (geen ?id= in de URL), gebruik 0 als standaard
 * De standaardwaarde 0 zorgt ervoor dat er geen PHP-fout optreedt als iemand
 * de pagina bezoekt zonder een ID in de URL.
 * ============================================================ */
$userId = getUserId(); /* Haal het ID van de ingelogde gebruiker op uit de sessie */
$id = $_GET['id'] ?? 0; /* Haal het spel-ID op uit de URL, of gebruik 0 als standaard */

/* ============================================================
 * STAP 5 - VALIDATIE: IS HET ID EEN GELDIG NUMMER?
 * ============================================================
 * is_numeric($id) controleert of de waarde een geldig NUMMER is.
 * Een spel-ID moet altijd een nummer zijn (bijv. 1, 2, 3, ...).
 * Als iemand probeert de URL te manipuleren met tekst of code
 * (bijv. edit_favorite.php?id=abc of edit_favorite.php?id=<script>),
 * is dat GEEN geldig nummer en sturen we de gebruiker terug naar profile.php.
 * Dit is een BEVEILIGINGSMAATREGEL tegen SQL-injectie en andere aanvallen.
 * De '!' (uitroepteken) keert de waarde om:
 *   - is_numeric("3")      geeft true   -> !true  = false -> NIET doorsturen
 *   - is_numeric("abc")    geeft false  -> !false = true  -> WEL doorsturen
 *
 * OPMERKING: bij een ongeldig ID sturen we hier naar profile.php (niet add_friend.php),
 * omdat favoriete spellen worden beheerd vanuit de profielpagina.
 * ============================================================ */
if (!is_numeric($id)) {
    header("Location: profile.php"); /* Stuur terug naar de profielpagina als het ID ongeldig is */
    exit; /* Stop alle verdere code op deze pagina */
}

/* ============================================================
 * STAP 6 - HET JUISTE FAVORIETE SPEL OPZOEKEN MET ARRAY_FILTER
 * ============================================================
 * Eerst halen we ALLE favoriete spellen van de gebruiker op met getFavoriteGames($userId).
 * Dit geeft een array (lijst) terug met alle favoriete spellen.
 *
 * Daarna gebruiken we array_filter() om het JUISTE spel te vinden.
 * array_filter() doorloopt elk element in de array en voert een functie uit.
 * De functie retourneert true (behouden) of false (weggooien).
 *
 * De anonieme functie (function ($g) use ($id)) werkt als volgt:
 *   - $g is het HUIDIGE element (een spel-array) in de loop
 *     (we gebruiken '$g' van 'game', net zoals '$f' van 'friend' bij edit_friend.php)
 *   - 'use ($id)' maakt de variabele $id beschikbaar BINNEN de anonieme functie.
 *     Normaal kan een anonieme functie geen variabelen van buitenaf gebruiken.
 *     Met 'use' maken we een uitzondering voor $id.
 *   - $g['game_id'] == $id vergelijkt het game_id van het huidige spel
 *     met het ID uit de URL
 *   - Als ze gelijk zijn, geeft de functie TRUE terug -> dit spel wordt BEHOUDEN
 *   - Als ze NIET gelijk zijn, geeft de functie FALSE terug -> dit spel wordt WEGGEFILTERD
 *
 * Het resultaat is een array met (hopelijk) PRECIES 1 element: het gezochte spel.
 *
 * reset($spel) haalt het EERSTE element uit de gefilterde array.
 * Dit is nodig omdat array_filter() de originele indexen behoudt.
 * Voorbeeld: als het spel met ID 3 op index 2 stond, is het resultaat [2 => {...}].
 * reset() geeft het eerste element terug ongeacht de index, dus het spel-object zelf.
 * Als de array leeg is (geen spel gevonden), geeft reset() FALSE terug.
 * ============================================================ */
// Haal het favoriete spel op
$favorieten = getFavoriteGames($userId); /* Haal ALLE favoriete spellen van de gebruiker op */
$spel = array_filter($favorieten, function ($g) use ($id) { /* Filter de lijst: zoek het spel met het juiste ID */
    return $g['game_id'] == $id; /* Vergelijk het game_id van elk spel met het ID uit de URL */
});
$spel = reset($spel); /* Haal het eerste (en enige) element uit de gefilterde array */

/* ============================================================
 * STAP 7 - CONTROLEER OF HET SPEL GEVONDEN IS
 * ============================================================
 * Als $spel FALSE is (reset() gaf false terug omdat de array leeg was),
 * dan bestaat er GEEN favoriet spel met dit ID voor deze gebruiker.
 * Dit kan gebeuren als:
 *   - Het spel al verwijderd is uit de favorieten
 *   - De gebruiker een ID probeert dat niet van hem/haar is
 *   - De URL handmatig is aangepast met een ongeldig ID
 *
 * In dat geval:
 *   1. setMessage('danger', 'Spel niet gevonden.') slaat een RODE foutmelding op
 *      in de sessie. 'danger' zorgt voor een rode alert op de volgende pagina.
 *   2. header("Location: profile.php") stuurt de browser terug naar de profielpagina
 *      waar de gebruiker zijn/haar favoriete spellen kan zien.
 *   3. exit; stopt alle verdere code
 * ============================================================ */
if (!$spel) {
    setMessage('danger', 'Spel niet gevonden.'); /* Sla een rode foutmelding op in de sessie */
    header("Location: profile.php"); /* Stuur de browser terug naar de profielpagina */
    exit; /* Stop alle verdere code op deze pagina */
}

/* ============================================================
 * STAP 8 - FOUTVARIABELE INITIALISEREN
 * ============================================================
 * $fout wordt op een LEGE string ('') gezet.
 * Als er later een fout optreedt bij het bijwerken van het spel,
 * wordt er een foutmelding in deze variabele opgeslagen.
 * Een lege string betekent: "er is (nog) geen fout opgetreden".
 * ============================================================ */
$fout = '';

/* ============================================================
 * STAP 9 - FORMULIER VERWERKING (POST-VERZOEK)
 * ============================================================
 * $_SERVER['REQUEST_METHOD'] bevat de HTTP-methode waarmee de pagina is opgevraagd.
 * Er zijn twee scenario's voor deze pagina:
 *   - 'GET'  = de gebruiker bezoekt de pagina (het formulier wordt getoond met bestaande gegevens)
 *   - 'POST' = het formulier is verzonden (de gebruiker heeft op "Bijwerken" gedrukt)
 * We controleren of de methode 'POST' is om het formulier te verwerken.
 * ============================================================ */
// Verwerk formulier verzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* ----------------------------------------------------------
     * STAP 9a - FORMULIERGEGEVENS OPHALEN
     * ----------------------------------------------------------
     * $_POST is een speciale PHP-array die ALLE ingevulde formuliervelden bevat.
     * We halen DRIE waarden op uit het formulier:
     *   - 'title'       = de (mogelijk gewijzigde) titel van het spel
     *   - 'description' = de (mogelijk gewijzigde) beschrijving van het spel
     *   - 'note'        = de (mogelijk gewijzigde) persoonlijke notitie
     * De '??' operator geeft een standaardwaarde (lege string '') als het veld ontbreekt.
     *
     * VERSCHIL MET EDIT_FRIEND.PHP: hier halen we titel/beschrijving/notitie op
     * (in plaats van gebruikersnaam/notitie/status bij vrienden).
     * ---------------------------------------------------------- */
    $titel = $_POST['title'] ?? ''; /* Haal de (gewijzigde) speltitel op uit het formulier */
    $beschrijving = $_POST['description'] ?? ''; /* Haal de (gewijzigde) beschrijving op */
    $notitie = $_POST['note'] ?? ''; /* Haal de (gewijzigde) persoonlijke notitie op */

    /* ----------------------------------------------------------
     * STAP 9b - FAVORIET SPEL BIJWERKEN IN DE DATABASE
     * ----------------------------------------------------------
     * updateFavoriteGame() werkt het bestaande favoriete spel bij in de database.
     * De functie krijgt 5 parameters:
     *   1. $userId       = het ID van de INGELOGDE gebruiker (beveiligingscheck).
     *                      Dit zorgt ervoor dat een gebruiker alleen ZIJN EIGEN spellen kan bewerken.
     *   2. $id           = het game_id van het spel dat bewerkt wordt (uit de URL).
     *                      Dit vertelt de database WELK spel bijgewerkt moet worden.
     *   3. $titel        = de nieuwe (of ongewijzigde) titel van het spel
     *   4. $beschrijving = de nieuwe (of ongewijzigde) beschrijving van het spel
     *   5. $notitie      = de nieuwe (of ongewijzigde) persoonlijke notitie
     * De functie RETOURNEERT:
     *   - Een LEGE string ('')     als het bijwerken GELUKT is (geen fout)
     *   - Een FOUTMELDING (string) als er iets mis ging (bijv. "Titel is verplicht")
     * Het resultaat wordt opgeslagen in $fout om later te tonen aan de gebruiker.
     * ---------------------------------------------------------- */
    $fout = updateFavoriteGame($userId, $id, $titel, $beschrijving, $notitie);

    /* ----------------------------------------------------------
     * STAP 9c - CONTROLEER OF HET BIJWERKEN GELUKT IS EN REDIRECT NAAR PROFILE.PHP
     * ----------------------------------------------------------
     * Als $fout LEEG is (geen foutmelding), dan is het spel succesvol bijgewerkt.
     * '!$fout' betekent: "als $fout NIET waar is" (een lege string is 'niet waar' in PHP).
     * Bij succes:
     *   1. setMessage('success', 'Spel bijgewerkt!') slaat een SUCCESMELDING op in de sessie.
     *      De melding wordt als een groene alert getoond op de volgende pagina.
     *   2. header("Location: profile.php") stuurt de browser door naar de PROFIELPAGINA.
     *      DIT IS DE REDIRECT NAAR PROFILE.PHP: na het bewerken gaat de gebruiker
     *      TERUG naar zijn/haar profiel waar de lijst met favoriete spellen staat.
     *      Dit is ook het PRG-patroon (Post-Redirect-Get) om dubbele verzending te voorkomen.
     *      BELANGRIJK VERSCHIL MET EDIT_FRIEND.PHP: daar redirecten we naar add_friend.php,
     *      hier redirecten we naar profile.php, omdat favoriete spellen op het profiel staan.
     *   3. exit; stopt alle code zodat de redirect correct wordt uitgevoerd.
     * Als $fout NIET leeg is, wordt de pagina gewoon geladen en toont de foutmelding.
     * ---------------------------------------------------------- */
    if (!$fout) {
        setMessage('success', 'Spel bijgewerkt!'); /* Sla een groene succesmelding op in de sessie */
        header("Location: profile.php"); /* REDIRECT: stuur de browser terug naar de profielpagina */
        exit; /* Stop alle verdere code om de redirect correct uit te voeren */
    }
}
?>
<!-- ============================================================
     STAP 10 - BEGIN VAN HET HTML-DOCUMENT
     ============================================================
     Alles hierboven was PHP (server-side logica).
     Alles hieronder is HTML (wat de browser daadwerkelijk toont aan de gebruiker).
     De PHP-code hierboven is al uitgevoerd VOORDAT de HTML naar de browser wordt gestuurd.
     ============================================================ -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5-document is.
     Zonder deze declaratie kan de browser de pagina verkeerd weergeven (quirks mode). -->
<!DOCTYPE html>

<!-- <html lang="nl"> opent het HTML-document en stelt de taal in op Nederlands ("nl").
     Dit helpt zoekmachines en schermlezers (voor slechtzienden) om de taal te herkennen. -->
<html lang="nl">

<!-- ============================================================
     STAP 11 - HEAD SECTIE (METADATA EN EXTERNE BESTANDEN)
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
         De gebruiker ziet "Favoriet Bewerken - GamePlan Scheduler" bovenaan in zijn browsertab. -->
    <title>Favoriet Bewerken - GamePlan Scheduler</title>

    <!-- Laad Bootstrap 5.3.3 CSS via een CDN (Content Delivery Network).
         Bootstrap biedt kant-en-klare stijlen voor knoppen, formulieren, kaarten, en lay-out.
         CDN betekent dat het bestand vanaf een EXTERNE server wordt geladen. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Laad ons EIGEN CSS-bestand 'style.css' voor het donkere gaming-thema en aangepaste stijlen.
         Omdat dit NA Bootstrap wordt geladen, kunnen onze stijlen Bootstrap-stijlen overschrijven. -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- ============================================================
     STAP 12 - BODY (ZICHTBARE INHOUD VAN DE PAGINA)
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
         STAP 13 - HEADER (NAVIGATIEBALK) INVOEGEN
         ============================================================
         'include' laadt het bestand 'header.php' in op deze exacte plek.
         header.php bevat de NAVIGATIEBALK bovenaan de pagina met links naar
         Home, Profiel, Vrienden, Speellijst, etc.
         Door de header in een apart bestand te zetten, wordt het hergebruikt op alle pagina's.
         Dit heet het DRY-principe: "Don't Repeat Yourself" (Herhaal Jezelf Niet).
         ============================================================ -->
    <?php include 'header.php'; ?>

    <!-- ============================================================
         STAP 14 - HOOFDINHOUD (MAIN)
         ============================================================
         <main> is een semantisch HTML5-element dat de HOOFDINHOUD van de pagina markeert.

         Bootstrap-klassen:
         - 'container' = centreert de inhoud horizontaal met automatische marges.
                         Op grote schermen beperkt het de breedte, op kleine schermen volledige breedte.
         - 'mt-5'      = margin-top niveau 5 (3rem = 48px ruimte boven het element).
                         Duwt de inhoud weg van de navigatiebalk.
         - 'pt-5'      = padding-top niveau 5 (3rem = 48px binnenruimte bovenaan).
                         Voegt extra ruimte toe zodat content niet tegen de bovenkant plakt.
         ============================================================ -->
    <main class="container mt-5 pt-5">

        <!-- ============================================================
             STAP 15 - SUCCESMELDING WEERGEVEN (INDIEN AANWEZIG)
             ============================================================
             getMessage() controleert of er een melding is opgeslagen in de sessie.
             Als er een melding is, toont het een Bootstrap alert (groen voor succes, rood voor fout).
             Als er GEEN melding is, verschijnt er niets.
             De melding wordt na het tonen automatisch gewist uit de sessie.
             ============================================================ -->
        <?php echo getMessage(); ?>

        <!-- ============================================================
             STAP 16 - FOUTMELDING WEERGEVEN (INDIEN AANWEZIG)
             ============================================================
             Als $fout NIET leeg is (formulier verzonden maar er was een fout),
             tonen we een RODE foutmelding.
             Bootstrap-klassen:
             - 'alert'        = basisstijl voor meldingen (padding, rand, afronding)
             - 'alert-danger' = maakt de melding ROOD (voor foutmeldingen)
             safeEcho($fout) toont de foutmelding veilig (XSS-bescherming).
             ============================================================ -->
        <?php if ($fout): ?>
            <div class="alert alert-danger"><?php echo safeEcho($fout); ?></div>
        <?php endif; ?>

        <!-- ============================================================
             STAP 17 - BEWERKFORMULIER SECTIE
             ============================================================
             <section> is een semantisch HTML5-element dat een logisch deel van de pagina markeert.

             Bootstrap-klasse:
             - 'mb-5' = margin-bottom niveau 5 (3rem = 48px ruimte onder de sectie).
             ============================================================ -->
        <section class="mb-5">

            <!-- De koptekst van de sectie met een emoji voor visuele herkenning.
                 <h2> is een tweede-niveau koptekst. -->
            <h2>✏️ Favoriet Spel Bewerken</h2>

            <!-- ============================================================
                 STAP 18 - BOOTSTRAP CARD (KAART-COMPONENT)
                 ============================================================
                 Bootstrap-klassen:
                 - 'card'      = creert een kaart met een achtergrond, rand, en afgeronde hoeken.
                 - 'card-body' = voegt padding (binnenruimte) toe rondom de inhoud van de kaart.
                 ============================================================ -->
            <div class="card">
                <div class="card-body">

                    <!-- ============================================================
                         STAP 19 - HET BEWERKFORMULIER
                         ============================================================
                         <form method="POST"> maakt een HTML-formulier aan.
                         'method="POST"' verstuurt de gegevens via een HTTP POST-verzoek (veilig, niet in de URL).
                         Zonder 'action' attribuut wordt het formulier verstuurd naar DEZELFDE pagina.
                         Het spel-ID zit al in de URL (?id=...) en wordt door PHP bovenaan opgepakt.
                         ============================================================ -->
                    <form method="POST">

                        <!-- ============================================================
                             STAP 20 - VOORINGEVULD INVOERVELD: SPELTITEL
                             ============================================================
                             Dit invoerveld is VOORINGEVULD met de huidige titel van het spel.
                             De gebruiker ziet de bestaande titel en kan deze aanpassen.

                             Bootstrap-klasse op de wrapper-div:
                             - 'mb-3' = margin-bottom niveau 3 (1rem = 16px ruimte onder dit veld).

                             Het <label>-element:
                             - 'for="title"'    = koppelt het label aan het invoerveld met id="title"
                             - 'form-label'     = Bootstrap-stijl voor labels

                             Het <input>-element:
                             - 'type="text"'         = normaal tekstveld
                             - 'id="title"'          = uniek ID, gekoppeld aan het label
                             - 'name="title"'        = naam waarmee het veld wordt verstuurd in $_POST.
                                                       In PHP halen we het op met $_POST['title'].
                             - 'class="form-control"' = Bootstrap-stijl: volledige breedte, afgeronde hoeken, padding
                             - 'required'            = HTML5-validatie: veld mag niet leeg zijn.
                                                       Een spel MOET een titel hebben.
                             - 'maxlength="100"'     = maximaal 100 tekens (meer dan de 50 bij gebruikersnaam,
                                                       omdat speltitels langer kunnen zijn).

                             CRUCIAAL - Het 'value' attribuut:
                             value="<?php echo safeEcho($spel['titel']); ?>"
                             Dit VOORVULT het invoerveld met de huidige titel uit de database.
                             $spel['titel'] bevat de bestaande titel van het favoriete spel
                             (opgehaald in STAP 6 met array_filter).
                             safeEcho() zorgt voor VEILIGE weergave (XSS-bescherming).
                             ============================================================ -->
                        <div class="mb-3">
                            <label for="title" class="form-label">🎮 Speltitel *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                value="<?php echo safeEcho($spel['titel']); ?>">
                        </div>

                        <!-- ============================================================
                             STAP 21 - VOORINGEVULD TEKSTVAK: BESCHRIJVING
                             ============================================================
                             Dit tekstvak is VOORINGEVULD met de bestaande beschrijving van het spel.
                             De beschrijving is een korte uitleg over het spel zelf (bijv. "Open wereld RPG").

                             Bootstrap-klasse op de wrapper-div:
                             - 'mb-3' = margin-bottom niveau 3 (1rem = 16px ruimte onder dit veld).

                             Het <label>-element:
                             - 'for="description"' = koppelt label aan textarea met id="description"
                             - 'form-label'        = Bootstrap-stijl voor labels

                             Het <textarea>-element:
                             - 'id="description"'        = uniek ID, gekoppeld aan het label
                             - 'name="description"'      = naam waarmee het veld wordt verstuurd ($_POST['description'])
                             - 'class="form-control"'    = Bootstrap-stijl: volledige breedte, afgeronde hoeken
                             - 'rows="2"'                = 2 regels hoog als standaard
                             - 'maxlength="500"'         = maximaal 500 tekens voor de beschrijving.
                                                           Dit limiteert de lengte om de database te beschermen.

                             BELANGRIJK - Vooringevulde waarde bij textarea:
                             Bij een <textarea> wordt de vooringevulde waarde TUSSEN de tags gezet:
                             <textarea ...>WAARDE HIER</textarea>
                             <?php echo safeEcho($spel['description']); ?> plaatst de huidige beschrijving
                             uit de database als de standaardwaarde van het tekstvak.
                             ============================================================ -->
                        <div class="mb-3">
                            <label for="description" class="form-label">📝 Beschrijving</label>
                            <textarea id="description" name="description" class="form-control" rows="2"
                                maxlength="500"><?php echo safeEcho($spel['description']); ?></textarea>
                        </div>

                        <!-- ============================================================
                             STAP 22 - VOORINGEVULD TEKSTVAK: PERSOONLIJKE NOTITIE
                             ============================================================
                             Dit tekstvak is VOORINGEVULD met de bestaande persoonlijke notitie.
                             De notitie is de PERSOONLIJKE mening van de gebruiker over het spel
                             (bijv. "Mijn favoriete spel, al 200 uur gespeeld!").

                             VERSCHIL MET BESCHRIJVING:
                             - Beschrijving = een objectieve uitleg over het spel
                             - Notitie      = een persoonlijke opmerking van de gebruiker

                             Bootstrap-klasse op de wrapper-div:
                             - 'mb-3' = margin-bottom niveau 3 (1rem = 16px ruimte onder dit veld).

                             Het <label>-element:
                             - 'for="note"'    = koppelt label aan textarea met id="note"
                             - 'form-label'    = Bootstrap-stijl voor labels

                             Het <textarea>-element:
                             - 'id="note"'            = uniek ID, gekoppeld aan het label
                             - 'name="note"'          = naam waarmee het veld wordt verstuurd ($_POST['note'])
                             - 'class="form-control"' = Bootstrap-stijl: volledige breedte, afgeronde hoeken
                             - 'rows="2"'             = 2 regels hoog

                             <?php echo safeEcho($spel['note']); ?> plaatst de huidige notitie
                             uit de database als de standaardwaarde van het tekstvak.
                             ============================================================ -->
                        <div class="mb-3">
                            <label for="note" class="form-label">📌 Notitie</label>
                            <textarea id="note" name="note" class="form-control"
                                rows="2"><?php echo safeEcho($spel['note']); ?></textarea>
                        </div>

                        <!-- ============================================================
                             STAP 23 - ACTIEKNOPPEN: BIJWERKEN EN ANNULEREN
                             ============================================================
                             Twee knoppen naast elkaar:

                             --- BIJWERKEN-KNOP ---
                             <button type="submit"> verstuurt het formulier via POST.
                             Wanneer de gebruiker hierop klikt, worden alle ingevulde waarden
                             verstuurd naar DEZE pagina, waar de PHP-code ze verwerkt (STAP 9).
                             Bootstrap-klassen:
                             - 'btn'         = Bootstrap basis-knopstijl (padding, afgeronde hoeken, cursor: pointer)
                             - 'btn-primary' = maakt de knop BLAUW (de hoofdkleur voor de belangrijkste actie)

                             --- ANNULEREN-LINK ---
                             <a href="profile.php"> is een link TERUG naar de profielpagina.
                             Dit is GEEN <button> maar een <a> (link), gestyled als een knop met Bootstrap.
                             Als de gebruiker op "Annuleren" klikt, wordt GEEN formulier verstuurd.
                             De gebruiker gaat gewoon terug naar profile.php zonder wijzigingen op te slaan.
                             BELANGRIJK: hier linken we naar profile.php (niet add_friend.php),
                             omdat favoriete spellen op het profiel worden beheerd.
                             Bootstrap-klassen:
                             - 'btn'           = stijlt de link als een knop
                             - 'btn-secondary' = maakt de knop GRIJS (secundaire actie, minder opvallend dan blauw).
                                                  Grijs geeft aan dat dit NIET de hoofdactie is.
                             ============================================================ -->
                        <button type="submit" class="btn btn-primary">💾 Bijwerken</button>
                        <a href="profile.php" class="btn btn-secondary">↩️ Annuleren</a>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- ============================================================
         STAP 24 - FOOTER (VOETTEKST) INVOEGEN
         ============================================================
         'include' laadt het bestand 'footer.php' in.
         footer.php bevat de voettekst onderaan de pagina met copyright-informatie.
         Wordt hergebruikt op alle pagina's via het DRY-principe.
         ============================================================ -->
    <?php include 'footer.php'; ?>

    <!-- ============================================================
         STAP 25 - BOOTSTRAP JAVASCRIPT LADEN
         ============================================================
         Bootstrap JavaScript is nodig voor interactieve componenten zoals
         dropdown-menu's, modals, en het hamburger-menu op mobiel.
         'bootstrap.bundle.min.js' bevat zowel Bootstrap JS als Popper.js.
         '.min.js' = geminificeerd (kleiner bestand, sneller laden).
         Staat ONDERAAN de pagina zodat de HTML eerst wordt geladen.
         ============================================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ============================================================
         STAP 26 - EIGEN JAVASCRIPT LADEN
         ============================================================
         'script.js' is ons eigen JavaScript-bestand met aangepaste functionaliteit.
         Wordt NA Bootstrap geladen zodat we Bootstrap-functies kunnen gebruiken.
         ============================================================ -->
    <script src="script.js"></script>
</body>

<!-- Sluit het HTML-document af. Alles tussen <html> en </html> is het volledige document. -->

</html>