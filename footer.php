<?php
/**
 * ==========================================================================
 * FOOTER.PHP - WEBSITE FOOTER
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand bevat de website footer die op elke pagina verschijnt.
 * Het bevat copyright informatie en links naar Privacy en Contact pagina's.
 *
 * Ontwerp specificaties:
 * - Hoogte: 50px volgens ontwerpdocument
 * - Bevat: Copyright, Privacy link, Contact link
 * - Positie: Vast aan de onderkant van de pagina
 * ==========================================================================
 */
?>

<!-- Footer - vast aan de onderkant van elke pagina -->
<footer class="bg-secondary p-2 text-center fixed-bottom">

    <!-- Copyright tekst -->
    <span class="text-light">
        © 2025 GamePlan Scheduler door Harsha Kanaparthi
    </span>

    <!-- Scheidingsteken -->
    <span class="text-secondary mx-2">|</span>

    <!-- Privacy beleid link (vereist voor AVG/GDPR naleving) -->
    <a href="privacy.php" class="text-info text-decoration-none">
        Privacybeleid
    </a>

    <!-- Scheidingsteken -->
    <span class="text-secondary mx-2">|</span>

    <!-- Contact link -->
    <a href="contact.php" class="text-info text-decoration-none">
        Contact
    </a>

</footer>