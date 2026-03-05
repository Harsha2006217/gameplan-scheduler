<?php
/**
 * ==========================================================================
 * FOOTER.PHP - WEBSITE FOOTER (ONDERBALK)
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * WAT DOET DIT BESTAND?
 * ---------------------
 * Dit bestand bevat de onderbalk (footer) die op ELKE pagina verschijnt.
 * De footer staat VAST aan de onderkant van het scherm en bevat:
 * - Copyright informatie (wie de app heeft gemaakt en in welk jaar)
 * - Link naar het Privacybeleid (verplicht voor AVG/GDPR wetgeving)
 * - Link naar de Contact pagina
 *
 * Dit bestand wordt INGEVOEGD in andere pagina's met de PHP include functie.
 * Zo hoef je de footer niet in elk bestand opnieuw te schrijven.
 *
 * ONTWERP SPECIFICATIES:
 * - Hoogte: 50px (volgens het ontwerpdocument)
 * - Positie: vast aan de onderkant (fixed-bottom)
 * - Kleur: donkere achtergrond met lichte tekst
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