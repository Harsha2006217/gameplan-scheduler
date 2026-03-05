<?php
/**
 * ==========================================================================
 * HEADER.PHP - NAVIGATIE HEADER (BOVENBALK)
 * ==========================================================================
 * Bestandsnaam : header.php
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
 * Dit bestand bevat de vaste bovenbalk (header) die op ELKE pagina verschijnt.
 * De header bevat het logo en het navigatiemenu waarmee je naar
 * verschillende pagina's kunt gaan (Dashboard, Profiel, Vrienden, enz.).
 *
 * Dit bestand wordt INGEVOEGD in andere pagina's met:
 *   <?php include 'header.php'; ?>
 * Zo hoef je de header niet in elk bestand opnieuw te schrijven.
 * Dit is het DRY-principe: "Don't Repeat Yourself" (Herhaal Jezelf Niet).
 *
 * Samen met footer.php vormt dit de "wrapper" (omhulsel) van elke pagina:
 *   header.php → navigatiebalk BOVENAAN
 *   footer.php → onderbalk ONDERAAN
 *
 * ==========================================================================
 * STRUCTUUR EN ONTWERP
 * ==========================================================================
 * ┌──────────────────────┬──────────────────────────────────────────────┐
 * │ Eigenschap           │ Waarde                                       │
 * ├──────────────────────┼──────────────────────────────────────────────┤
 * │ Hoogte               │ ~80px (volgens ontwerp, via py-2/p-0)        │
 * │ Positie              │ fixed-top (altijd zichtbaar bovenaan)        │
 * │ Achtergrondkleur     │ bg-primary (overschreven door style.css)     │
 * │ Tekstkleur           │ text-white (logo + menu)                     │
 * │ Logo                 │ 🎮 GamePlan Scheduler (h4/h1)                │
 * │ Navigatie            │ Bootstrap navbar, hamburger menu mobiel      │
 * │ Menu-items           │ 6 links: Dashboard, Profiel, Vrienden,       │
 * │                      │ Planning, Evenement, Uitloggen               │
 * │ Knoppen              │ btn-success (groen) voor Evenement           │
 * │ Responsief           │ navbar-expand-lg, collapse, toggler          │
 * │ Padding/marges       │ p-0, py-2, mb-4, ms-auto, gap-2, ms-2, px-3  │
 * └──────────────────────┴──────────────────────────────────────────────┘
 *
 * ==========================================================================
 * VERGELIJKING MET FOOTER.PHP
 * ==========================================================================
 * ┌──────────────────────┬───────────────┬───────────────┐
 * │ Eigenschap           │ header.php    │ footer.php    │
 * ├──────────────────────┼───────────────┼───────────────┤
 * │ Positie op de pagina │ Bovenaan      │ Onderaan      │
 * │ HTML-element         │ <header>      │ <footer>      │
 * │ Bootstrap positie    │ fixed-top     │ fixed-bottom  │
 * │ Inhoud               │ Logo + menu   │ Copyright + links │
 * │ PHP-logica?          │ Ja (inlog-check)│ Nee (puur HTML) │
 * │ Aantal links         │ 6 (dynamisch) │ 2 (statisch)  │
 * │ Responsive menu?     │ Ja (hamburger)| Nee           │
 * │ Bevat JavaScript?    │ Nee           │ Nee           │
 * └──────────────────────┴───────────────┴───────────────┘
 *
 * ==========================================================================
 * BEVEILIGING EN GEBRUIK
 * ==========================================================================
 * 1. GEEN GEBRUIKERSINVOER: Alle menu-items zijn hardcoded, geen XSS mogelijk
 * 2. INLOG-CHECK: In sommige pagina's wordt header.php pas geladen NA inlogcontrole
 * 3. RESPONSIVE DESIGN: Hamburger menu voorkomt dat menu-items buiten beeld vallen
 * 4. DRY-principe: één centrale header voor alle pagina's
 *
 * ==========================================================================
 * HTML CONCEPTEN GEBRUIKT IN DIT BESTAND
 * ==========================================================================
 * - <header>               : Semantisch HTML5-element voor bovenbalk
 * - <nav>                  : Navigatiebalk (Bootstrap navbar)
 * - <ul>/<li>              : Menu-items als lijst
 * - <a href="...">         : Hyperlinks naar andere pagina's
 * - <button>               : Hamburger menu knop
 * - Bootstrap: fixed-top, bg-primary, text-white, navbar, navbar-toggler, collapse
 * - btn-success, ms-auto, gap-2, ms-2, px-3, mb-4, py-2
 * - h1/h4                  : Logo-tekst
 * ==========================================================================
 * CSS CONCEPTEN (style.css overschrijvingen):
 * - De header-achtergrondkleur en knoppen worden overschreven door style.css
 *   met het donkere gaming-thema (glassmorphism-effect).
 * ==========================================================================
 */
?>

<!-- HEADER ELEMENT: de vaste bovenbalk van de website -->
<!-- fixed-top = deze balk blijft ALTIJD bovenaan het scherm, zelfs als je scrollt -->
<!-- bg-primary = blauwe achtergrondkleur (Bootstrap klasse, wordt overschreven door CSS) -->
<!-- p-0 = geen binnenruimte (padding nul) -->
<!-- mb-4 = onderruimte (margin-bottom) van 1.5rem -->
<header class="fixed-top bg-primary p-0 mb-4">

    <!-- CONTAINER: centreert de inhoud en beperkt de maximale breedte -->
    <!-- d-flex = maak dit een flexbox container (voor het uitlijnen van elementen) -->
    <!-- justify-content-between = verdeel de ruimte zodat elementen links EN rechts staan -->
    <!--   (logo links, menu rechts) -->
    <!-- align-items-center = lijn alles verticaal in het midden uit -->
    <!-- py-2 = kleine verticale binnenruimte (padding boven en onder) -->
    <div class="container d-flex justify-content-between align-items-center py-2">

        <!-- LOGO SECTIE: klikbaar logo dat naar de homepagina linkt -->
        <!-- href="index.php" = als je op het logo klikt, ga je naar het dashboard -->
        <!-- text-decoration-none = geen onderstreping op de link -->
        <a href="index.php" class="text-decoration-none">
            <!-- h4 = een koptekst op niveau 4 (niet te groot, niet te klein) -->
            <!-- mb-0 = geen onderruimte (margin-bottom nul) -->
            <!-- text-white = witte tekstkleur -->
            <h1 class="h4 mb-0 text-white">
                🎮 GamePlan Scheduler
            </h1>
        </a>

        <!-- NAVIGATIE SECTIE: het menu met alle links naar pagina's -->
        <!-- navbar = Bootstrap navigatiebalk component -->
        <!-- navbar-expand-lg = het menu klapt uit op grote schermen (lg = large = meer dan 992px) -->
        <!-- navbar-dark = gebruik lichte tekst en iconen (voor donkere achtergrond) -->
        <!-- p-0 = geen binnenruimte -->
        <nav class="navbar navbar-expand-lg navbar-dark p-0">

            <!-- HAMBURGER KNOP: alleen zichtbaar op mobiele apparaten (minder dan 992px) -->
            <!-- Als het scherm kleiner is dan 992px, verschijnt deze knop -->
            <!-- Bij het klikken toont of verbergt het het menu (data-bs-toggle="collapse") -->
            <!-- data-bs-target="#navbarNav" = welk element moet getoond of verborgen worden -->
            <!-- aria-controls = toegankelijkheid: vertelt hulptechnieken welk element bestuurd wordt -->
            <!-- aria-expanded = toegankelijkheid: geeft aan of het menu open of dicht is -->
            <!-- aria-label = toegankelijkheid: beschrijving voor schermlezers -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Navigatie openen">
                <!-- Dit is het icoon met de drie horizontale streepjes (hamburger icoon) -->
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- INKLAPBAAR MENU: verborgen op mobiel, zichtbaar op desktop -->
            <!-- collapse navbar-collapse = dit element kan in en uitklappen -->
            <!-- id="navbarNav" = uniek ID zodat de hamburger knop dit element kan vinden -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- NAVIGATIE ITEMS LIJST -->
                <!-- navbar-nav = Bootstrap klasse voor navigatie items -->
                <!-- ms-auto = duw de items naar rechts (margin-start auto) -->
                <!-- gap-2 = kleine ruimte tussen elk menu item -->
                <ul class="navbar-nav ms-auto gap-2">

                    <!-- MENU ITEM 1: Dashboard (homepagina) -->
                    <!-- nav-item = Bootstrap klasse voor een enkel menu item -->
                    <li class="nav-item">
                        <!-- nav-link = Bootstrap klasse voor een navigatie link -->
                        <!-- text-white = witte tekstkleur -->
                        <!-- href="index.php" = ga naar de dashboard pagina -->
                        <a class="nav-link text-white" href="index.php">
                            🏠 Dashboard
                        </a>
                    </li>

                    <!-- MENU ITEM 2: Profiel (favoriete spellen beheren) -->
                    <li class="nav-item">
                        <!-- href="profile.php" = ga naar de profiel pagina -->
                        <a class="nav-link text-white" href="profile.php">
                            👤 Profiel
                        </a>
                    </li>

                    <!-- MENU ITEM 3: Vrienden (gaming vrienden beheren) -->
                    <li class="nav-item">
                        <!-- href="add_friend.php" = ga naar de vrienden pagina -->
                        <a class="nav-link text-white" href="add_friend.php">
                            👥 Vrienden
                        </a>
                    </li>

                    <!-- MENU ITEM 4: Planning (speelschema toevoegen) -->
                    <li class="nav-item">
                        <!-- href="add_schedule.php" = ga naar het schema toevoegen pagina -->
                        <a class="nav-link text-white" href="add_schedule.php">
                            📅 Planning
                        </a>
                    </li>

                    <!-- MENU ITEM 5: Evenement toevoegen (opvallende groene knop) -->
                    <!-- Deze knop is extra opvallend gemaakt met btn-success (groen) -->
                    <!-- ms-2 = extra linkerruimte om het los te houden van andere items -->
                    <!-- px-3 = extra horizontale binnenruimte voor bredere knop -->
                    <li class="nav-item">
                        <a class="nav-link text-white btn btn-success ms-2 px-3" href="add_event.php">
                            🎯 Evenement
                        </a>
                    </li>

                    <!-- MENU ITEM 6: Uitloggen -->
                    <!-- href="logout.php" = voert het uitlog script uit -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="logout.php">
                            🚪 Uitloggen
                        </a>
                    </li>

                </ul>
            </div>
        </nav>

    </div>
</header>