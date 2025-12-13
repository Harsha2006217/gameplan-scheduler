<?php
/**
 * ============================================================================
 * footer.php - Gemeenschappelijke Footer Component
 * ============================================================================
 * 
 * @author      Harsha Kanaparthi
 * @student     2195344
 * @date        30-09-2025
 * @version     1.0
 * @project     GamePlan Scheduler
 * 
 * ============================================================================
 * BESCHRIJVING / DESCRIPTION:
 * ============================================================================
 * Dit bestand bevat de footer die onderaan elke pagina wordt getoond.
 * Het wordt ingesloten met: <?php include 'footer.php'; ?>
 * 
 * De footer bevat:
 * - Copyright informatie
 * - Link naar Privacy Policy pagina
 * - Link naar Contact pagina
 * 
 * This file contains the footer component included at the bottom of every page.
 * 
 * ============================================================================
 * DESIGN KEUZES:
 * ============================================================================
 * - fixed-bottom: footer blijft onderaan viewport (niet ideaal voor lange
 *   pagina's, maar werkt goed voor deze applicatie)
 * - Minimalistisch design: niet te veel informatie, focus op hoofdinhoud
 * ============================================================================
 */
// Geen PHP logica nodig in footer, alleen HTML output
?>
<!-- ======================================================================
     FOOTER ELEMENT
     ======================================================================
     <footer> is een semantisch HTML5 element dat aangeeft dat dit de
     footer van de pagina is.
     
     Bootstrap classes:
     - fixed-bottom: footer blijft onderaan scherm
     - bg-dark: donkere achtergrond (consistent met thema)
     - border-top border-secondary: subtiele bovenlijn
     - py-3: padding verticaal
     ====================================================================== -->
<footer class="fixed-bottom bg-dark border-top border-secondary py-3">

    <!-- ==================================================================
         CONTAINER
         ==================================================================
         container: centreert inhoud met responsive marges
         ================================================================== -->
    <div class="container">

        <!-- ==============================================================
             ROW VOOR RESPONSIVE LAYOUT
             ==============================================================
             row: Bootstrap grid row
             align-items-center: verticaal centreren
             ============================================================== -->
        <div class="row align-items-center">

            <!-- ======================================================
                 COPYRIGHT TEKST (LINKS)
                 ====================================================== -->
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <small class="text-muted">
                    <!-- &copy; is HTML entity voor © -->
                    &copy; 2025 GamePlan Scheduler by
                    <span class="text-light">Harsha Kanaparthi</span>
                </small>
            </div>

            <!-- ======================================================
                 FOOTER LINKS (RECHTS)
                 ======================================================
                 text-md-end: rechts uitlijnen op medium+ screens
                 ====================================================== -->
            <div class="col-md-6 text-center text-md-end">
                <nav aria-label="Footer navigation">
                    <!-- ================================================
                         PRIVACY LINK
                         ================================================ -->
                    <a href="privacy.php" class="text-muted text-decoration-none me-3 small">
                        <i class="bi bi-shield-check me-1"></i>Privacy Policy
                    </a>

                    <!-- ================================================
                         CONTACT LINK
                         ================================================ -->
                    <a href="contact.php" class="text-muted text-decoration-none small">
                        <i class="bi bi-envelope me-1"></i>Contact
                    </a>
                </nav>
            </div>

        </div>
    </div>
</footer>

<!-- ======================================================================
     OPMERKING: GEEN SLUITENDE PHP TAG
     ======================================================================
     We sluiten de PHP tag niet af om problemen met whitespace te voorkomen.
     Dit is een PHP best practice.
     ====================================================================== -->