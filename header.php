<?php
/**
 * ============================================================================
 * header.php - Gemeenschappelijke Header Component
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
 * Dit bestand bevat de header die op ELKE pagina wordt getoond (behalve
 * login/register). Het wordt ingesloten met: <?php include 'header.php'; ?>
 * 
 * De header bevat:
 * - Het logo en app naam
 * - Navigatiemenu met links naar alle pagina's
 * - Responsive hamburger menu voor mobiel
 * - Logout knop
 * 
 * This file contains the header component included on every page.
 * It provides consistent navigation across the entire application.
 * 
 * ============================================================================
 * RESPONSIVE DESIGN:
 * ============================================================================
 * - navbar-expand-lg: horizontaal menu op grote schermen
 * - hamburger menu: collapse menu op kleine schermen
 * - Bootstrap responsive classes passen automatisch aan
 * ============================================================================
 */
?>
<!-- ======================================================================
     HEADER ELEMENT
     ======================================================================
     <header> is een semantisch HTML5 element dat aangeeft dat dit de
     header van de pagina is. Dit helpt zoekmachines en screenreaders.
     
     Bootstrap classes:
     - fixed-top: header blijft bovenaan scherm bij scrollen
     - bg-primary: blauwe achtergrondkleur
     - shadow: subtiele schaduw voor diepte
     ====================================================================== -->
<header class="fixed-top bg-primary shadow">

    <!-- ==================================================================
         NAVBAR COMPONENT
         ==================================================================
         navbar: Bootstrap navigatiebalk component
         navbar-expand-lg: horizontaal op large screens, hamburger op kleiner
         navbar-dark: witte tekst (voor op donkere achtergrond)
         py-2: padding verticaal (boven en onder)
         ================================================================== -->
    <nav class="navbar navbar-expand-lg navbar-dark py-2">

        <!-- ==============================================================
             CONTAINER
             ==============================================================
             container: centreert inhoud en geeft responsive breedte
             ============================================================== -->
        <div class="container">

            <!-- ======================================================
                 LOGO EN MERKNAAM
                 ======================================================
                 navbar-brand: de hoofdlink/logo van de navbar
                 text-decoration-none: geen onderstreping
                 d-flex align-items-center: flexbox voor logo + tekst
                 
                 bi bi-controller: Bootstrap icon van een game controller
                 ====================================================== -->
            <a href="index.php" class="navbar-brand d-flex align-items-center text-decoration-none">
                <i class="bi bi-controller me-2" style="font-size: 1.8rem;"></i>
                <span class="fw-bold">GamePlan Scheduler</span>
            </a>

            <!-- ======================================================
                 HAMBURGER MENU BUTTON (ALLEEN OP MOBIEL)
                 ======================================================
                 navbar-toggler: Bootstrap hamburger menu knop
                 data-bs-toggle="collapse": Bootstrap collapse functie
                 data-bs-target="#navbarNav": welk element te tonen/verbergen
                 
                 aria-controls, aria-expanded, aria-label: toegankelijkheid
                 voor screenreaders en assistieve technologieën
                 ====================================================== -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <!-- Drie horizontale lijnen (hamburger icon) -->
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- ======================================================
                 NAVIGATIE LINKS (COLLAPSIBLE)
                 ======================================================
                 collapse navbar-collapse: dit element wordt verborgen
                    op kleine schermen en getoond bij klik op hamburger
                 id="navbarNav": correspondeert met data-bs-target boven
                 ====================================================== -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- ================================================
                     NAVIGATIE MENU ITEMS
                     ================================================
                     navbar-nav: stijl voor lijst van nav items
                     ms-auto: margin-start auto = duwt naar rechts
                     align-items-center: verticaal centreren
                     gap-2: ruimte tussen items
                     ================================================ -->
                <ul class="navbar-nav ms-auto align-items-center gap-2">

                    <!-- ============================================
                         HOME LINK
                         ============================================
                         nav-item: wrapper voor elke menu item
                         nav-link: stijl voor de link
                         text-white: witte tekst
                         ============================================ -->
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="index.php">
                            <i class="bi bi-house-door me-1"></i>Home
                        </a>
                    </li>

                    <!-- ============================================
                         PROFILE LINK
                         ============================================ -->
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="profile.php">
                            <i class="bi bi-person-circle me-1"></i>Profile
                        </a>
                    </li>

                    <!-- ============================================
                         FRIENDS LINK
                         ============================================ -->
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="add_friend.php">
                            <i class="bi bi-people me-1"></i>Friends
                        </a>
                    </li>

                    <!-- ============================================
                         ADD SCHEDULE LINK
                         ============================================ -->
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="add_schedule.php">
                            <i class="bi bi-calendar-plus me-1"></i>Schedule
                        </a>
                    </li>

                    <!-- ============================================
                         ADD EVENT BUTTON (PROMINENT)
                         ============================================
                         btn btn-success: groene knop voor nadruk
                         ms-2: extra margin links
                         
                         Dit is de "Call to Action" knop, daarom
                         prominenter gestyled dan de andere links
                         ============================================ -->
                    <li class="nav-item">
                        <a class="btn btn-success px-3" href="add_event.php">
                            <i class="bi bi-calendar-event me-1"></i>Add Event
                        </a>
                    </li>

                    <!-- ============================================
                         LOGOUT LINK
                         ============================================
                         btn btn-outline-light: witte outline knop
                         
                         ?logout=1 trigger in index.php
                         ============================================ -->
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-light px-3" href="index.php?logout=1">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</header>