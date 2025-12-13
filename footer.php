<?php
/**
 * ============================================================================
 * FOOTER.PHP - WEBSITE FOOTER / WEBSITE FOOTER
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This file contains the website footer that appears on every page.
 * It includes copyright information and links to Privacy and Contact pages.
 * 
 * DUTCH:
 * Dit bestand bevat de website footer die op elke pagina verschijnt.
 * Het bevat copyright informatie en links naar Privacy en Contact pagina's.
 * 
 * DESIGN SPECS:
 * - Height: 50px as per design document
 * - Contains: Copyright, Privacy link, Contact link
 * - Position: Fixed at bottom of page
 * ============================================================================
 */
?>

<!-- ========================================================================
     FOOTER ELEMENT - Fixed at bottom of page
     FOOTER ELEMENT - Gefixeerd aan onderkant van pagina
     
     Classes explained / Klassen uitgelegd:
     - bg-secondary: Dark gray background color
     - p-2: Padding on all sides (8px)
     - text-center: Center all text
     - fixed-bottom: Stick to bottom of viewport
     ======================================================================== -->
<footer class="bg-secondary p-2 text-center fixed-bottom">
    
    <!-- 
        COPYRIGHT TEXT
        COPYRIGHT TEKST
        
        © symbol: Copyright symbol
        Year: 2025 (project year)
        Name: Harsha Kanaparthi (developer)
    -->
    <span class="text-light">
        © 2025 GamePlan Scheduler by Harsha Kanaparthi
    </span>
    
    <!-- Separator between copyright and links -->
    <span class="text-secondary mx-2">|</span>
    
    <!-- 
        PRIVACY POLICY LINK
        PRIVACY BELEID LINK
        
        Links to privacy.php which contains privacy information.
        This is required for GDPR/AVG compliance.
    -->
    <a href="privacy.php" class="text-info text-decoration-none">
        Privacy Policy
    </a>
    
    <!-- Separator -->
    <span class="text-secondary mx-2">|</span>
    
    <!-- 
        CONTACT LINK
        CONTACT LINK
        
        Links to contact.php for support inquiries.
    -->
    <a href="contact.php" class="text-info text-decoration-none">
        Contact
    </a>
    
</footer>

<?php
/**
 * ============================================================================
 * END OF FOOTER.PHP / EINDE VAN FOOTER.PHP
 * ============================================================================
 * 
 * ENGLISH:
 * This file is included at the bottom of other PHP files.
 * No closing ?> tag to prevent whitespace issues.
 * 
 * DUTCH:
 * Dit bestand wordt onderaan andere PHP bestanden geinclude.
 * Geen afsluitende ?> tag om witruimte problemen te voorkomen.
 */