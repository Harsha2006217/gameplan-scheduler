<?php
/**
 * ============================================================================
 * footer.php - FOOTER COMPONENT
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Dit is de footer die onderaan ELKE pagina verschijnt.
 * Het wordt ingevoegd met <?php include 'footer.php'; ?>
 * 
 * INHOUD:
 * - Copyright informatie
 * - Link naar Privacy Policy
 * - Link naar Contact pagina
 * 
 * DESIGN KEUZES:
 * - fixed-bottom: Footer blijft altijd onderaan het scherm
 * - Donkere achtergrond passend bij het gaming thema
 * - Compacte hoogte (50px zoals in design specificatie)
 * ============================================================================
 */
?>

<!-- 
    FOOTER ELEMENT
    ==============
    fixed-bottom: Footer blijft altijd onderaan het scherm zichtbaar
    bg-dark: Donkere achtergrond
    bg-opacity-75: Licht transparant voor moderne look
    text-center: Tekst gecentreerd
    py-3: Padding top en bottom voor goede spacing
    border-top: Subtiele scheiding van content
    border-secondary: Grijze randkleur
-->
<footer class="fixed-bottom bg-dark bg-opacity-75 text-center py-3 border-top border-secondary">
    <div class="container">

        <!-- 
            COPYRIGHT & LINKS
            =================
            small: Kleine tekst (0.875em)
            text-muted: Grijze kleur, niet te opvallend
            mb-0: Geen margin-bottom
        -->
        <p class="text-muted small mb-0">

            <!-- Copyright met huidig jaar -->
            © 2025 GamePlan Scheduler by
            <span class="text-light">Harsha Kanaparthi</span>

            <!-- Scheidingstekens -->
            <span class="mx-2">|</span>

            <!-- Privacy Policy link -->
            <a href="privacy.php" class="text-info text-decoration-none">
                <i class="bi bi-shield-check me-1"></i>Privacy Policy
            </a>

            <span class="mx-2">|</span>

            <!-- Contact link -->
            <a href="contact.php" class="text-info text-decoration-none">
                <i class="bi bi-envelope me-1"></i>Contact
            </a>

        </p>

    </div>
</footer>

<!-- 
    LET OP: Geen PHP closing tag (?>) nodig
    =======================================
    Dit voorkomt onbedoelde whitespace output die
    "headers already sent" fouten kan veroorzaken.
-->