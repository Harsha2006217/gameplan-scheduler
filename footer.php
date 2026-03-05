<?php
/**
 * ==========================================================================
 * FOOTER.PHP - WEBSITE FOOTER (ONDERBALK)
 * ==========================================================================
 * Bestandsnaam : footer.php
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
 * Dit bestand bevat de vaste onderbalk (footer) die op ELKE pagina verschijnt.
 * De footer staat VAST aan de onderkant van het scherm (fixed-bottom) en bevat:
 *   - Copyright informatie (wie de app heeft gemaakt en in welk jaar)
 *   - Link naar het Privacybeleid (verplicht voor AVG/GDPR wetgeving)
 *   - Link naar de Contact pagina
 *
 * Dit bestand wordt INGEVOEGD in andere pagina's met de PHP include functie:
 *   <?php include 'footer.php'; ?>
 * Zo hoef je de footer niet in elk bestand opnieuw te schrijven.
 * Dit is het DRY-principe: "Don't Repeat Yourself" (Herhaal Jezelf Niet).
 *
 * Samen met header.php vormt dit de "wrapper" (omhulsel) van elke pagina:
 *   header.php → navigatiebalk BOVENAAN
 *   footer.php → onderbalk ONDERAAN
 *
 * ==========================================================================
 * HOE DIT BESTAND WORDT GEBRUIKT
 * ==========================================================================
 *
 * ┌─────────────────────────────────────────────────────────────────────┐
 * │ Elke pagina (bijv. index.php, profile.php, login.php) doet:       │
 * │                                                                     │
 * │   <?php include 'header.php'; ?>   ← navigatiebalk BOVENAAN       │
 * │                                                                     │
 * │   <main> ... pagina-inhoud ... </main>                              │
 * │                                                                     │
 * │   <?php include 'footer.php'; ?>   ← onderbalk ONDERAAN           │
 * │                                                                     │
 * │ De footer wordt als LAATSTE HTML-element geladen, net voor         │
 * │ de Bootstrap JS en script.js worden ingeladen.                     │
 * └─────────────────────────────────────────────────────────────────────┘
 *
 * ==========================================================================
 * ONTWERP SPECIFICATIES
 * ==========================================================================
 * ┌──────────────────────┬──────────────────────────────────────────────┐
 * │ Eigenschap           │ Waarde                                       │
 * ├──────────────────────┼──────────────────────────────────────────────┤
 * │ Hoogte               │ ~50px (automatisch via p-2 padding)          │
 * │ Positie              │ fixed-bottom (altijd zichtbaar onderaan)     │
 * │ Achtergrondkleur     │ bg-secondary (overschreven door style.css)   │
 * │ Tekstkleur           │ text-light (wit)                             │
 * │ Uitlijning           │ text-center (gecentreerd)                    │
 * │ Padding              │ p-2 (0.5rem = 8px rondom)                    │
 * │ Links kleur          │ text-info (cyaan/lichtblauw)                 │
 * │ Links onderstreping  │ text-decoration-none (geen)                  │
 * │ Scheidingstekens     │ | (verticale streep) in text-secondary       │
 * └──────────────────────┴──────────────────────────────────────────────┘
 *
 * ==========================================================================
 * VERSCHIL MET HEADER.PHP
 * ==========================================================================
 * ┌──────────────────────┬───────────────────┬─────────────────────────┐
 * │ Eigenschap           │ header.php        │ footer.php              │
 * ├──────────────────────┼───────────────────┼─────────────────────────┤
 * │ Positie op de pagina │ Bovenaan          │ Onderaan                │
 * │ HTML-element         │ <nav>             │ <footer>                │
 * │ Bootstrap positie    │ fixed-top         │ fixed-bottom            │
 * │ Inhoud               │ Logo + navigatie  │ Copyright + links       │
 * │ PHP-logica?          │ Ja (inlog-check)  │ Nee (puur HTML)         │
 * │ Aantal links         │ 5-7 (dynamisch)   │ 2 (statisch)            │
 * │ Responsive menu?     │ Ja (hamburger)    │ Nee (altijd zichtbaar)  │
 * │ Bevat JavaScript?    │ Nee               │ Nee                     │
 * └──────────────────────┴───────────────────┴─────────────────────────┘
 *
 * ==========================================================================
 * WAAR WORDT DIT BESTAND GE-INCLUDE?
 * ==========================================================================
 * Dit bestand wordt ge-include op ALLE pagina's van de applicatie:
 *   - index.php            (dashboard / hoofdpagina)
 *   - profile.php          (profielpagina)
 *   - login.php            (inlogpagina)
 *   - register.php         (registratiepagina)
 *   - add_event.php        (evenement toevoegen)
 *   - edit_event.php       (evenement bewerken)
 *   - add_schedule.php     (speelschema toevoegen)
 *   - edit_schedule.php    (speelschema bewerken)
 *   - add_friend.php       (vriend toevoegen + vriendenlijst)
 *   - edit_friend.php      (vriend bewerken)
 *   - edit_favorite.php    (favoriet spel bewerken)
 *   - contact.php          (contactpagina)
 *   - privacy.php          (privacybeleid)
 *   - delete.php           (verwijderpagina)
 *
 * ==========================================================================
 * HTML STRUCTUUR VAN DE FOOTER
 * ==========================================================================
 *
 *   <footer>
 *     ├── <span> Copyright © 2025 tekst
 *     ├── <span> | scheidingsteken
 *     ├── <a>    Link naar privacy.php (Privacybeleid)
 *     ├── <span> | scheidingsteken
 *     └── <a>    Link naar contact.php (Contact)
 *   </footer>
 *
 * ==========================================================================
 * BEVEILIGING EN WETGEVING
 * ==========================================================================
 * 1. GEEN PHP-LOGICA: Dit bestand bevat GEEN PHP-code (afgezien van de
 *    header-commentaar). Het is puur HTML en daardoor veilig.
 *
 * 2. AVG/GDPR COMPLIANCE: De link naar privacy.php is VERPLICHT volgens
 *    Europese privacywetgeving (Algemene Verordening Gegevensbescherming).
 *    Gebruikers moeten worden geïnformeerd over gegevensverzameling.
 *
 * 3. XSS-VEILIG: Alle tekst in de footer is STATISCH (hardcoded).
 *    Er wordt geen gebruikersinvoer weergegeven, dus XSS is niet mogelijk.
 *
 * ==========================================================================
 * HTML CONCEPTEN GEBRUIKT IN DIT BESTAND
 * ==========================================================================
 * - <footer>               : Semantisch HTML5-element voor onderbalk
 * - <span>                 : Inline container voor tekstreeksen
 * - <a href="...">         : Hyperlinks naar andere pagina's
 * - Bootstrap: bg-secondary, p-2, text-center, fixed-bottom
 * - Bootstrap: text-light, text-secondary, text-info, mx-2
 * - text-decoration-none   : CSS-klasse om onderstreping te verwijderen
 * - fixed-bottom           : CSS-positie vast aan onderkant viewport
 * - ©                      : HTML-entiteit voor copyright-symbool
 *
 * CSS CONCEPTEN (style.css overschrijvingen):
 * - De footer-achtergrondkleur wordt overschreven door style.css
 *   met het donkere gaming-thema (glassmorphism-effect).
 * ==========================================================================
 */
?>

<!-- FOOTER ELEMENT: de vaste onderbalk van de website -->
<!-- bg-secondary = grijze achtergrondkleur (Bootstrap klasse, overschreven door CSS) -->
<!-- p-2 = kleine binnenruimte rondom (padding van 0.5rem) -->
<!-- text-center = alle tekst wordt gecentreerd -->
<!-- fixed-bottom = deze balk blijft ALTIJD aan de onderkant van het scherm, -->
<!--   zelfs als je scrollt. De footer is altijd zichtbaar. -->
<footer class="bg-secondary p-2 text-center fixed-bottom">

    <!-- COPYRIGHT TEKST -->
    <!-- text-light = lichte (witte) tekstkleur zodat het leesbaar is op donkere achtergrond -->
    <!-- Het copyright symbool geeft aan dat de inhoud beschermd is -->
    <!-- 2025 = het jaar waarin de applicatie is gemaakt -->
    <span class="text-light">
        © 2025 GamePlan Scheduler door Harsha Kanaparthi
    </span>

    <!-- SCHEIDINGSTEKEN: een verticale streep als visuele scheiding -->
    <!-- text-secondary = grijze kleur zodat het niet te opvallend is -->
    <!-- mx-2 = horizontale ruimte links EN rechts (margin-x) -->
    <span class="text-secondary mx-2">|</span>

    <!-- PRIVACY BELEID LINK -->
    <!-- href="privacy.php" = ga naar de privacybeleid pagina -->
    <!-- text-info = cyaan of lichtblauwe kleur voor de link (goed zichtbaar op donker) -->
    <!-- text-decoration-none = geen onderstreping (netter uiterlijk) -->
    <!-- Deze link is VERPLICHT voor AVG/GDPR naleving (Europese privacywetgeving) -->
    <!-- De AVG (Algemene Verordening Gegevensbescherming) vereist dat je gebruikers -->
    <!-- informeert over welke gegevens je verzamelt en hoe je deze beschermt -->
    <a href="privacy.php" class="text-info text-decoration-none">
        Privacybeleid
    </a>

    <!-- SCHEIDINGSTEKEN: weer een verticale streep als visuele scheiding -->
    <span class="text-secondary mx-2">|</span>

    <!-- CONTACT LINK -->
    <!-- href="contact.php" = ga naar de contact pagina -->
    <!-- Hier kunnen gebruikers de ontwikkelaar bereiken voor hulp of vragen -->
    <a href="contact.php" class="text-info text-decoration-none">
        Contact
    </a>

</footer>