<?php
/**
 * ============================================================================
 * HEADER.PHP - NAVIGATION HEADER / NAVIGATIE HEADER
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This file contains the website header that appears on every page.
 * It includes the logo, navigation menu, and hamburger menu for mobile.
 * 
 * DUTCH:
 * Dit bestand bevat de website header die op elke pagina verschijnt.
 * Het bevat het logo, navigatiemenu, en hamburger menu voor mobiel.
 * 
 * DESIGN SPECS:
 * - Height: 80px as per design document
 * - Contains: Logo, menu, profile icon
 * - Color: Blue gradient with game-like feel
 * - Buttons: min 40px height for mobile usability
 * ============================================================================
 */
?>

<!-- ========================================================================
     HEADER ELEMENT - Fixed at top of page
     HEADER ELEMENT - Gefixeerd aan bovenkant van pagina
     ======================================================================== -->
<header class="fixed-top bg-primary p-0 mb-4">
    
    <!-- Container centers content and limits max-width -->
    <!-- Container centreert content en beperkt max-breedte -->
    <div class="container d-flex justify-content-between align-items-center py-2">
        
        <!-- ================================================================
             LOGO SECTION - Links to homepage
             LOGO SECTIE - Linkt naar homepagina
             ================================================================ -->
        <a href="index.php" class="text-decoration-none">
            <!-- 
                h1 tag: Main site title
                h1 tag: Hoofd site titel
                - text-white: White text color for visibility
                - mb-0: No bottom margin
            -->
            <h1 class="h4 mb-0 text-white">
                🎮 GamePlan Scheduler
            </h1>
        </a>
        
        <!-- ================================================================
             NAVIGATION SECTION - Menu items
             NAVIGATIE SECTIE - Menu items
             ================================================================ -->
        <nav class="navbar navbar-expand-lg navbar-dark p-0">
            
            <!-- 
                HAMBURGER BUTTON - Only visible on mobile (< 992px)
                HAMBURGER KNOP - Alleen zichtbaar op mobiel (< 992px)
                
                data-bs-toggle: Bootstrap function to open/close menu
                data-bs-target: Which element to show/hide (#navbarNav)
                aria-controls: For accessibility screen readers
            -->
            <button class="navbar-toggler" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                <!-- Three horizontal lines icon -->
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- 
                COLLAPSIBLE MENU - Hidden on mobile until hamburger clicked
                INKLAPBAAR MENU - Verborgen op mobiel tot hamburger geklikt
                
                collapse: Bootstrap class for collapsible element
                navbar-collapse: Standard Bootstrap navbar styling
            -->
            <div class="collapse navbar-collapse" id="navbarNav">
                
                <!-- 
                    NAVIGATION ITEMS LIST
                    NAVIGATIE ITEMS LIJST
                    
                    navbar-nav: Bootstrap nav styling
                    ms-auto: Push menu to right side (margin-start: auto)
                    gap-2: Add spacing between items
                -->
                <ul class="navbar-nav ms-auto gap-2">
                    
                    <!-- HOME LINK - Dashboard with calendar view -->
                    <!-- HOME LINK - Dashboard met kalender weergave -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">
                            🏠 Home / Dashboard
                        </a>
                    </li>
                    
                    <!-- PROFIEL LINK - Beheer favoriete spellen -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="profile.php">
                            👤 Profile / Profiel
                        </a>
                    </li>
                    
                    <!-- VRIENDEN LINK - Beheer gaming vrienden -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="add_friend.php">
                            👥 Friends / Vrienden
                        </a>
                    </li>
                    
                    <!-- SCHEMA TOEVOEGEN LINK - Voeg gaming sessies toe -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="add_schedule.php">
                            📅 Schedule / Planning
                        </a>
                    </li>
                    
                    <!-- EVENEMENT TOEVOEGEN KNOP - Opvallende groene knop -->
                    <li class="nav-item">
                        <a class="nav-link text-white btn btn-success ms-2 px-3" href="add_event.php">
                            🎯 Add Event / Evenement
                        </a>
                    </li>
                    
                    <!-- LOGOUT LINK - Beëindig sessie en redirect naar login -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="logout.php">
                            🚪 Logout / Uitloggen
                        </a>
                    </li>
                    
                </ul>
            </div>
        </nav>
        
    </div>
</header>

<?php
/**
 * ============================================================================
 * END OF HEADER.PHP / EINDE VAN HEADER.PHP
 * ============================================================================
 * 
 * ENGLISH:
 * This file is included in other PHP files using: include 'header.php';
 * It creates consistent navigation across all pages.
 * 
 * DUTCH:
 * Dit bestand wordt in andere PHP bestanden geinclude met: include 'header.php';
 * Het creëert consistente navigatie op alle pagina's.
 */
?>