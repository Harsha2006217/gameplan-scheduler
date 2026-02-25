<?php
/**
 * ============================================================================
 * FOOTER.PHP - WEBSITE FOOTER
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand bevat de website-footer die op elke pagina verschijnt.
 * Het bevat copyrightinformatie en links naar de privacy- en contactpagina's.
 *
 * ONTWERP:
 * - Hoogte: 50px
 * - Bevat: copyright, privacylink, contactlink
 * - Positie: gefixeerd onderaan de pagina
 * ============================================================================
 */
?>

<!-- Footer: gefixeerd onderaan de pagina -->
<footer class="bg-secondary p-2 text-center fixed-bottom">
    
    <!-- Copyrighttekst -->
    <span class="text-light">
        © 2025 GamePlan Scheduler door Harsha Kanaparthi
    </span>
    
    <!-- Scheiding tussen copyright en links -->
    <span class="text-secondary mx-2">|</span>
    
    <!-- Link naar de privacypagina (vereist voor AVG-naleving) -->
    <a href="privacy.php" class="text-info text-decoration-none">
        Privacybeleid
    </a>
    
    <!-- Scheiding -->
    <span class="text-secondary mx-2">|</span>
    
    <!-- Link naar de contactpagina -->
    <a href="contact.php" class="text-info text-decoration-none">
        Contact
    </a>
    
</footer>

<?php
/**
 * ============================================================================
 * EINDE VAN FOOTER.PHP
 * ============================================================================
 * Dit bestand wordt onderaan andere PHP-bestanden geïnclude.
 * Geen afsluitende ?> tag om witruimteproblemen te voorkomen.
 */