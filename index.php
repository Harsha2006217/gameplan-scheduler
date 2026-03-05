<?php
/**
 * ==========================================================================
 * INDEX.PHP - DASHBOARD / KALENDER OVERZICHT (HOMEPAGINA)
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * WAT IS DIT BESTAND?
 * -------------------
 * Dit is de HOOFDPAGINA (dashboard) van de GamePlan Scheduler.
 * Na het inloggen komt de gebruiker op deze pagina terecht.
 * Het is als een "startscherm" waarop je ALLES kunt zien:
 *
 * SECTIES OP DEZE PAGINA:
 * 1. Vriendenlijst    = al je gaming vrienden met hun online status
 * 2. Favoriete spellen = spellen die je als favoriet hebt gemarkeerd
 * 3. Speelschema's    = wanneer je gaat gamen (met sorteer opties)
 * 4. Evenementen      = toernooien en streams (met sorteer opties)
 * 5. Kalender         = overzicht van alles in kaartjes
 * 6. Herinneringen    = pop-ups als er iets binnenkort begint
 *
 * HOE WERKT DEZE PAGINA?
 * 1. PHP controleert of de gebruiker ingelogd is
 * 2. Als niet ingelogd: doorsturen naar login.php
 * 3. Als wel ingelogd: alle data ophalen uit de database
 * 4. De data wordt getoond in tabellen en kaartjes (HTML)
 * 5. JavaScript toont herinnering pop-ups als dat nodig is
 * ==========================================================================
 */

// Laad alle functies uit functions.php (sessie, database, validatie, enz.)
require_once 'functions.php';

// Controleer of de sessie niet is verlopen (30 minuten inactiviteit)
// Als de sessie verlopen is, wordt de gebruiker automatisch uitgelogd
checkSessionTimeout();

// Controleer of de gebruiker is ingelogd
// isLoggedIn() kijkt of er een user_id in de sessie staat
// Als de gebruiker NIET is ingelogd, stuur door naar de login pagina
if (!isLoggedIn()) {
    // header("Location: ...") stuurt de browser naar een andere pagina
    header("Location: login.php");
    // exit stopt het script zodat de rest van de code niet wordt uitgevoerd
    exit;
}

// Haal het unieke ID van de ingelogde gebruiker op uit de sessie
// Dit ID hebben we nodig om data op te halen die bij DEZE gebruiker hoort
$userId = getUserId();

// Werk de "laatste activiteit" bij in de database
// Dit wordt gebruikt door checkSessionTimeout() om te weten
// wanneer de gebruiker voor het laatst actief was
updateLastActivity(getDBConnection(), $userId);

// Haal sorteer parameters uit de URL (als ze er zijn)
// De ?? operator (null coalescing) geeft een standaardwaarde als de parameter niet bestaat
// Standaard sorteren we op datum oplopend (oud naar nieuw = ASC)
// De gebruiker kan dit veranderen door op de sorteer knoppen te klikken
$sorteerSchemas = $_GET['sort_schedules'] ?? 'date ASC';
$sorteerEvenementen = $_GET['sort_events'] ?? 'date ASC';

// ALLE DATA OPHALEN UIT DE DATABASE
// Elke functie doet een database query en geeft de resultaten terug als een array
$vrienden = getFriends($userId);                          // Alle vrienden van deze gebruiker
$favorieten = getFavoriteGames($userId);                  // Alle favoriete spellen
$schemas = getSchedules($userId, $sorteerSchemas);        // Alle speelschema's (gesorteerd)
$evenementen = getEvents($userId, $sorteerEvenementen);   // Alle evenementen (gesorteerd)
$kalenderItems = getCalendarItems($userId);               // Schema's + evenementen gecombineerd
$herinneringen = getReminders($userId);                   // Evenementen die binnenkort beginnen

// Controleer of er een logout verzoek is via de URL
// Als de gebruiker ?logout in de URL typt, wordt hij uitgelogd
if (isset($_GET['logout'])) {
    logout();
}
?>
<!-- ========================================================================
     HIER BEGINT DE HTML (wat de gebruiker ziet in de browser)
     ======================================================================== -->

<!-- DOCTYPE html vertelt de browser dat dit een HTML5 document is -->
<!DOCTYPE html>

<!-- html tag met lang="nl" = de taal van deze pagina is Nederlands -->
<!-- Dit helpt zoekmachines en schermlezers om de taal te herkennen -->
<html lang="nl">

<!-- HEAD SECTIE: onzichtbare informatie over de pagina -->
<head>
    <!-- charset="UTF-8" = tekencodering zodat alle tekens correct worden weergegeven -->
    <meta charset="UTF-8">

    <!-- viewport = zorgt ervoor dat de pagina goed wordt weergegeven op mobiele telefoons -->
    <!-- width=device-width = de breedte van de pagina past zich aan het scherm aan -->
    <!-- initial-scale=1.0 = de pagina begint op 100% zoom (niet ingezoomd of uitgezoomd) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- description = een beschrijving van de pagina voor zoekmachines (Google, Bing, etc.) -->
    <meta name="description" content="GamePlan Scheduler Dashboard - Jouw gaming kalender en planning beheerder">

    <!-- title = de tekst die in het tabblad van de browser verschijnt -->
    <title>Dashboard - GamePlan Scheduler</title>

    <!-- Bootstrap 5 CSS laden via CDN (Content Delivery Network) -->
    <!-- Bootstrap is een gratis CSS-bibliotheek die kant-en-klare stijlen biedt -->
    <!-- CDN = het bestand wordt geladen van een externe server, niet van onze eigen server -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Onze eigen CSS-stijlen laden voor het donkere gaming-thema -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- BODY: het zichtbare deel van de pagina -->
<!-- bg-dark = donkere achtergrondkleur (Bootstrap klasse) -->
<!-- text-light = lichte tekstkleur (Bootstrap klasse) -->
<body class="bg-dark text-light">

    <!-- HEADER invoegen: de navigatiebalk bovenaan de pagina -->
    <!-- include 'header.php' plakt de code uit header.php hier in -->
    <?php include 'header.php'; ?>

    <!-- MAIN: de hoofdinhoud van de pagina -->
    <!-- container = centreert de inhoud en beperkt de maximale breedte -->
    <!-- mt-5 = margin-top 5 (bovenruimte zodat de inhoud niet achter de header zit) -->
    <!-- pt-5 = padding-top 5 (nog meer bovenruimte als extra veiligheid) -->
    <main class="container mt-5 pt-5">

        <!-- SESSIE BERICHTEN: toon succes- of foutmeldingen als die er zijn -->
        <!-- getMessage() haalt een eventueel bericht op dat eerder is opgeslagen -->
        <!-- Bijvoorbeeld: "Vriend succesvol verwijderd!" na het verwijderen van een vriend -->
        <?php echo getMessage(); ?>

        <!-- ================================================================
             SECTIE 1: VRIENDENLIJST
             ================================================================
             Hier worden alle gaming vrienden van de gebruiker getoond.
             Elke vriend heeft een gebruikersnaam, online status, notitie,
             en knoppen om te bewerken of verwijderen.
             ================================================================ -->
        <section class="mb-5">
            <!-- mb-5 = margin-bottom 5 (ruimte onder deze sectie) -->

            <!-- Sectie titel met emoji -->
            <h2>👥 Vriendenlijst</h2>

            <!-- table-responsive = als de tabel te breed is voor het scherm, -->
            <!-- kun je horizontaal scrollen (belangrijk voor mobiele telefoons) -->
            <div class="table-responsive">

                <!-- TABEL: een raster van rijen en kolommen om data te tonen -->
                <!-- table = Bootstrap basistabel klasse -->
                <!-- table-dark = donkere achtergrond voor de tabel -->
                <!-- table-bordered = randen rondom elke cel -->
                <!-- table-hover = rijen lichten op als je er met de muis overheen gaat -->
                <table class="table table-dark table-bordered table-hover">

                    <!-- THEAD: de koptekst rij van de tabel (de titels van de kolommen) -->
                    <thead>
                        <tr>
                            <!-- th = table header = vetgedrukte kolomtitel -->
                            <th>Gebruikersnaam</th>
                            <th>Status</th>
                            <th>Notitie</th>
                            <th>Acties</th>
                        </tr>
                    </thead>

                    <!-- TBODY: de inhoud van de tabel (de daadwerkelijke data) -->
                    <tbody>
                        <!-- CONTROLE: als er GEEN vrienden zijn, toon een lege melding -->
                        <!-- empty() controleert of de array leeg is (geen data) -->
                        <?php if (empty($vrienden)): ?>
                            <!-- colspan="4" = deze cel neemt 4 kolommen in beslag -->
                            <!-- text-center = tekst gecentreerd -->
                            <!-- text-secondary = grijze tekstkleur -->
                            <tr><td colspan="4" class="text-center text-secondary">Nog geen vrienden. Voeg er een toe!</td></tr>

                        <?php else: ?>
                            <!-- foreach LOOP: herhaal de code voor ELKE vriend in de lijst -->
                            <!-- $vrienden is een array met alle vrienden -->
                            <!-- $vriend is EEN vriend in elke herhaling -->
                            <?php foreach ($vrienden as $vriend): ?>
                                <tr>
                                    <!-- Toon de gebruikersnaam van de vriend -->
                                    <!-- safeEcho() maakt de tekst veilig tegen XSS-aanvallen -->
                                    <!-- $vriend['username'] haalt het 'username' veld op -->
                                    <td><?php echo safeEcho($vriend['username']); ?></td>

                                    <!-- Toon de online status als een gekleurde badge -->
                                    <td>
                                        <!-- badge = een klein gekleurd label -->
                                        <!-- Als status 'Online' is: groene badge (bg-success) -->
                                        <!-- Anders: grijze badge (bg-secondary) -->
                                        <!-- De ? : is een ternaire operator (kort als/anders) -->
                                        <span class="badge <?php echo $vriend['status'] === 'Online' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo safeEcho($vriend['status']); ?>
                                        </span>
                                    </td>

                                    <!-- Toon de persoonlijke notitie over deze vriend -->
                                    <td><?php echo safeEcho($vriend['note']); ?></td>

                                    <!-- ACTIE KNOPPEN: bewerken en verwijderen -->
                                    <td>
                                        <!-- BEWERK KNOP: gaat naar edit_friend.php met het vriend ID -->
                                        <!-- ?id= stuurt het friend_id mee in de URL -->
                                        <!-- btn-sm = kleine knop, btn-warning = gele kleur -->
                                        <a href="edit_friend.php?id=<?php echo $vriend['friend_id']; ?>" class="btn btn-sm btn-warning">✏️ Bewerken</a>

                                        <!-- VERWIJDER KNOP: gaat naar delete.php met type en ID -->
                                        <!-- type=friend vertelt delete.php dat het een vriend is -->
                                        <!-- onclick="return confirm(...)" toont een bevestigingsvenster -->
                                        <!-- De gebruiker moet "OK" klikken om echt te verwijderen -->
                                        <!-- btn-danger = rode kleur (gevaar/verwijderen) -->
                                        <a href="delete.php?type=friend&id=<?php echo $vriend['friend_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je deze vriend wilt verwijderen?');">🗑️ Verwijderen</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <!-- Einde van de foreach loop -->

                        <?php endif; ?>
                        <!-- Einde van de if/else controle -->
                    </tbody>
                </table>
            </div>

            <!-- KNOP: link om een nieuwe vriend toe te voegen -->
            <!-- btn-primary = blauwe knop (primaire actie) -->
            <a href="add_friend.php" class="btn btn-primary">➕ Vriend Toevoegen</a>
        </section>

        <!-- ================================================================
             SECTIE 2: FAVORIETE SPELLEN
             ================================================================
             Hier worden alle favoriete spellen van de gebruiker getoond.
             Elk spel heeft een titel, beschrijving, notitie en actieknoppen.
             ================================================================ -->
        <section class="mb-5">
            <h2>🎮 Favoriete Spellen</h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Beschrijving</th>
                            <th>Notitie</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Als er geen favoriete spellen zijn, toon een lege melding -->
                        <?php if (empty($favorieten)): ?>
                            <tr><td colspan="4" class="text-center text-secondary">Nog geen favorieten. Voeg er een toe!</td></tr>
                        <?php else: ?>
                            <!-- Loop door elk favoriet spel -->
                            <?php foreach ($favorieten as $spel): ?>
                                <tr>
                                    <!-- Toon de speltitel veilig -->
                                    <td><?php echo safeEcho($spel['titel']); ?></td>
                                    <!-- Toon de spelbeschrijving veilig -->
                                    <td><?php echo safeEcho($spel['description']); ?></td>
                                    <!-- Toon de persoonlijke notitie veilig -->
                                    <td><?php echo safeEcho($spel['note']); ?></td>
                                    <td>
                                        <!-- Bewerk knop: ga naar edit_favorite.php met het spel ID -->
                                        <a href="edit_favorite.php?id=<?php echo $spel['game_id']; ?>" class="btn btn-sm btn-warning">✏️ Bewerken</a>
                                        <!-- Verwijder knop: ga naar delete.php met type=favorite -->
                                        <a href="delete.php?type=favorite&id=<?php echo $spel['game_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Uit favorieten verwijderen?');">🗑️ Verwijderen</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Knop om naar de profiel pagina te gaan waar je favorieten kunt toevoegen -->
            <a href="profile.php" class="btn btn-primary">➕ Favoriet Toevoegen</a>
        </section>

        <!-- ================================================================
             SECTIE 3: SPEELSCHEMA'S (met sorteer opties)
             ================================================================
             Hier worden alle gaming speelschema's getoond.
             De gebruiker kan sorteren op datum (oplopend of aflopend).
             De sorteer knoppen sturen een URL parameter mee die PHP gebruikt
             om de juiste volgorde uit de database op te halen.
             ================================================================ -->
        <section class="mb-5">
            <h2>
                📅 Speelschema's
                <!-- SORTEER KNOPPEN: veranderen de volgorde van de schema's -->
                <!-- ?sort_schedules=date ASC = sorteer op datum oplopend (oud naar nieuw) -->
                <!-- ASC = ascending = oplopend, DESC = descending = aflopend -->
                <!-- btn-light = lichtgrijze knop, ms-2 = linkerruimte -->
                <a href="?sort_schedules=date ASC" class="btn btn-sm btn-light ms-2">📆 Datum ↑</a>
                <a href="?sort_schedules=date DESC" class="btn btn-sm btn-light">📆 Datum ↓</a>
            </h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Spel</th>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Vrienden</th>
                            <th>Gedeeld met</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Als er geen schema's zijn, toon een lege melding -->
                        <?php if (empty($schemas)): ?>
                            <!-- colspan="6" omdat deze tabel 6 kolommen heeft -->
                            <tr><td colspan="6" class="text-center text-secondary">Nog geen schema's.</td></tr>
                        <?php else: ?>
                            <!-- Loop door elk speelschema -->
                            <?php foreach ($schemas as $schema): ?>
                                <tr>
                                    <!-- game_titel = de naam van het spel dat gespeeld wordt -->
                                    <td><?php echo safeEcho($schema['game_titel']); ?></td>
                                    <!-- date = de datum waarop gespeeld wordt -->
                                    <td><?php echo safeEcho($schema['date']); ?></td>
                                    <!-- time = het tijdstip waarop gespeeld wordt -->
                                    <td><?php echo safeEcho($schema['time']); ?></td>
                                    <!-- friends = komma-gescheiden lijst van meespelende vrienden -->
                                    <td><?php echo safeEcho($schema['friends']); ?></td>
                                    <!-- shared_with = met wie dit schema gedeeld is -->
                                    <td><?php echo safeEcho($schema['shared_with']); ?></td>
                                    <td>
                                        <!-- Bewerk knop: ga naar edit_schedule.php met het schema ID -->
                                        <a href="edit_schedule.php?id=<?php echo $schema['schedule_id']; ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <!-- Verwijder knop met bevestiging -->
                                        <a href="delete.php?type=schedule&id=<?php echo $schema['schedule_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je dit wilt verwijderen?');">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Knop om een nieuw speelschema toe te voegen -->
            <a href="add_schedule.php" class="btn btn-primary">➕ Schema Toevoegen</a>
        </section>

        <!-- ================================================================
             SECTIE 4: EVENEMENTEN (met sorteer opties)
             ================================================================
             Hier worden alle gaming evenementen getoond (toernooien, streams).
             Evenementen hebben extra velden: beschrijving, herinnering, link.
             ================================================================ -->
        <section class="mb-5">
            <h2>
                🎯 Evenementen
                <!-- Sorteer knoppen voor evenementen (zelfde principe als bij schema's) -->
                <a href="?sort_events=date ASC" class="btn btn-sm btn-light ms-2">📆 Datum ↑</a>
                <a href="?sort_events=date DESC" class="btn btn-sm btn-light">📆 Datum ↓</a>
            </h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Beschrijving</th>
                            <th>Herinnering</th>
                            <th>Link</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Als er geen evenementen zijn, toon een lege melding -->
                        <?php if (empty($evenementen)): ?>
                            <!-- colspan="7" omdat deze tabel 7 kolommen heeft -->
                            <tr><td colspan="7" class="text-center text-secondary">Nog geen evenementen.</td></tr>
                        <?php else: ?>
                            <!-- Loop door elk evenement -->
                            <?php foreach ($evenementen as $evenement): ?>
                                <tr>
                                    <!-- title = de naam van het evenement -->
                                    <td><?php echo safeEcho($evenement['title']); ?></td>
                                    <!-- date = de datum van het evenement -->
                                    <td><?php echo safeEcho($evenement['date']); ?></td>
                                    <!-- time = het starttijdstip -->
                                    <td><?php echo safeEcho($evenement['time']); ?></td>
                                    <!-- description = korte beschrijving, afgekort tot 50 tekens -->
                                    <!-- substr() knipt de tekst af na 50 tekens -->
                                    <!-- De ?? '' zorgt ervoor dat als description NULL is, er een lege tekst wordt gebruikt -->
                                    <td><?php echo safeEcho(substr($evenement['description'] ?? '', 0, 50)); ?>...</td>
                                    <!-- reminder = herinnering instelling als een gekleurde badge -->
                                    <!-- bg-info = lichtblauwe badge kleur -->
                                    <td><span class="badge bg-info"><?php echo safeEcho($evenement['reminder']); ?></span></td>
                                    <td>
                                        <!-- Toon een link knop ALLEEN als er een externe link is ingevuld -->
                                        <!-- !empty() controleert of de waarde niet leeg is -->
                                        <?php if (!empty($evenement['external_link'])): ?>
                                            <!-- target="_blank" = opent de link in een NIEUW tabblad -->
                                            <!-- btn-outline-info = knop met lichtblauwe rand (geen vulling) -->
                                            <a href="<?php echo safeEcho($evenement['external_link']); ?>" target="_blank" class="btn btn-sm btn-outline-info">🔗 Openen</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Bewerk knop: ga naar edit_event.php met het evenement ID -->
                                        <a href="edit_event.php?id=<?php echo $evenement['event_id']; ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <!-- Verwijder knop met bevestiging -->
                                        <a href="delete.php?type=event&id=<?php echo $evenement['event_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je dit wilt verwijderen?');">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Knop om een nieuw evenement toe te voegen (groene kleur = opvallend) -->
            <a href="add_event.php" class="btn btn-success">➕ Evenement Toevoegen</a>
        </section>

        <!-- ================================================================
             SECTIE 5: KALENDER OVERZICHT
             ================================================================
             Dit is een visueel overzicht van ALLE komende items
             (schema's EN evenementen samen) als kaartjes.
             De kaartjes worden in een raster (grid) getoond:
             - Op grote schermen: 3 kaartjes naast elkaar (col-md-4)
             - Op kleine schermen: 2 kaartjes naast elkaar (col-sm-6)
             ================================================================ -->
        <section class="mb-5">
            <h2>📆 Kalender Overzicht</h2>

            <!-- Bootstrap rij: een horizontale rij voor het kaartjes raster -->
            <div class="row">
                <!-- Als er geen kalender items zijn, toon een lege melding -->
                <?php if (empty($kalenderItems)): ?>
                    <div class="col-12">
                        <p class="text-secondary text-center">Geen komende items. Voeg schema's of evenementen toe!</p>
                    </div>
                <?php else: ?>
                    <!-- Loop door elk kalender item (kan een schema OF een evenement zijn) -->
                    <?php foreach ($kalenderItems as $onderdeel): ?>
                        <!-- col-md-4 = op medium schermen en groter: neem 4 van 12 kolommen (= 1/3 breedte) -->
                        <!-- col-sm-6 = op kleine schermen: neem 6 van 12 kolommen (= halve breedte) -->
                        <!-- mb-3 = onderruimte tussen de kaartjes -->
                        <div class="col-md-4 col-sm-6 mb-3">

                            <!-- KAARTJE: een Bootstrap card component -->
                            <!-- h-100 = hoogte 100% zodat alle kaartjes even hoog zijn in een rij -->
                            <div class="card h-100">
                                <div class="card-body">

                                    <!-- Kaartje titel: de naam van het spel of evenement -->
                                    <!-- text-cyan = cyaan kleur (gedefinieerd in style.css) -->
                                    <!-- De ?? operator kiest 'title' (evenement) of 'game_titel' (schema) -->
                                    <h5 class="card-title text-cyan">
                                        <?php echo safeEcho($onderdeel['title'] ?? $onderdeel['game_titel']); ?>
                                    </h5>

                                    <!-- Datum en tijd van het item -->
                                    <p class="mb-1">
                                        <strong>📅</strong> <?php echo safeEcho($onderdeel['date']); ?>
                                        <strong>⏰</strong> <?php echo safeEcho($onderdeel['time']); ?>
                                    </p>

                                    <!-- Toon beschrijving ALLEEN als die er is (niet leeg) -->
                                    <!-- Afgekort tot 100 tekens met substr() -->
                                    <?php if (!empty($onderdeel['description'])): ?>
                                        <p class="text-secondary small"><?php echo safeEcho(substr($onderdeel['description'], 0, 100)); ?></p>
                                    <?php endif; ?>

                                    <!-- Toon herinnering badge ALLEEN als er een herinnering is ingesteld -->
                                    <!-- en als de herinnering niet 'none' (geen) is -->
                                    <?php if (!empty($onderdeel['reminder']) && $onderdeel['reminder'] !== 'none'): ?>
                                        <!-- bg-warning = gele badge, text-dark = donkere tekst op gele achtergrond -->
                                        <span class="badge bg-warning text-dark">🔔 <?php echo safeEcho($onderdeel['reminder']); ?></span>
                                    <?php endif; ?>

                                    <!-- Toon externe link knop ALLEEN als er een link is ingevuld -->
                                    <?php if (!empty($onderdeel['external_link'])): ?>
                                        <!-- mt-2 = kleine bovenruimte zodat de knop niet tegen de tekst aan zit -->
                                        <a href="<?php echo safeEcho($onderdeel['external_link']); ?>" target="_blank" class="btn btn-sm btn-outline-info mt-2">🔗 Link</a>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </main>
    <!-- Einde van de hoofdinhoud -->

    <!-- FOOTER invoegen: de onderbalk met copyright en links -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap 5 JavaScript laden (nodig voor de hamburger menu functionaliteit) -->
    <!-- bundle = bevat ook Popper.js dat nodig is voor dropdowns en tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Ons eigen JavaScript bestand laden (formulier validatie, etc.) -->
    <script src="script.js"></script>

    <!-- ================================================================
         HERINNERING POP-UPS (JavaScript)
         ================================================================
         Dit stukje JavaScript toont pop-up meldingen als er evenementen
         zijn die binnenkort beginnen (afhankelijk van de herinnering instelling).

         HOE HET WERKT:
         1. PHP haalt de herinneringen op uit de database (getReminders)
         2. json_encode() zet de PHP array om naar een JavaScript array
         3. forEach loopt door elke herinnering
         4. alert() toont een pop-up venster met de herinnering
         ================================================================ -->
    <script>
        // Zet de PHP herinneringen array om naar een JavaScript variabele
        // json_encode() maakt er een JSON string van die JavaScript kan lezen
        const herinneringen = <?php echo json_encode($herinneringen); ?>;

        // Loop door elke herinnering en toon een pop-up
        // forEach() voert de functie uit voor elk item in de array
        // De backticks (` `) zijn template literals waarmee je variabelen in tekst kunt zetten
        // ${...} voegt de waarde van een variabele in de tekst in
        herinneringen.forEach(herinnering => {
            alert(`🔔 Herinnering: ${herinnering['title']} om ${herinnering['time']}`);
        });
    </script>
</body>
</html>
