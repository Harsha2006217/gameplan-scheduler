<?php
/**
 * ============================================================================
 * HEADER.PHP - NAVIGATIE HEADER
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand bevat de website-header die op elke pagina verschijnt.
 * Het bevat het logo, het navigatiemenu en het hamburgermenu voor mobiel.
 *
 * ONTWERP:
 * - Hoogte: 80px
 * - Bevat: logo, menu, profielicoon
 * - Kleur: blauwe achtergrond met gaming-uitstraling
 * - Knoppen: minimaal 40px hoogte voor mobiele bruikbaarheid
 * ============================================================================
 */
?>

<!-- Header: gefixeerd bovenaan de pagina -->
<header class="fixed-top bg-primary p-0 mb-4">
    
    <!-- Container centreert de inhoud en beperkt de maximale breedte -->
    <div class="container d-flex justify-content-between align-items-center py-2">
        
        <!-- Logo: link naar de homepagina -->
        <a href="index.php" class="text-decoration-none">
            <h1 class="h4 mb-0 text-white">
                🎮 GamePlan Scheduler
            </h1>
        </a>
        
        <!-- Navigatiemenu -->
        <nav class="navbar navbar-expand-lg navbar-dark p-0">
            
            <!-- Hamburgerknop: alleen zichtbaar op mobiel (< 992px) -->
            <button class="navbar-toggler" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" 
                    aria-expanded="false" 
                    aria-label="Menu openen">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Inklapbaar menu: verborgen op mobiel totdat de hamburgerknop geklikt wordt -->
            <div class="collapse navbar-collapse" id="navbarNav">
                
                <!-- Navigatie-items: ms-auto schuift het menu naar rechts -->
                <ul class="navbar-nav ms-auto gap-2">
                    
                    <!-- Home: Dashboard met kalenderoverzicht -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">
                            🏠 Dashboard
                        </a>
                    </li>
                    
                    <!-- Profiel: beheer favoriete spellen -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="profile.php">
                            👤 Profiel
                        </a>
                    </li>
                    
                    <!-- Vrienden: beheer gaming-vrienden -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="add_friend.php">
                            👥 Vrienden
                        </a>
                    </li>
                    
                    <!-- Schema: voeg gaming-sessies toe -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="add_schedule.php">
                            📅 Schema
                        </a>
                    </li>
                    
                    <!-- Evenement toevoegen: opvallende groene knop -->
                    <li class="nav-item">
                        <a class="nav-link text-white btn btn-success ms-2 px-3" href="add_event.php">
                            🎯 Evenement
                        </a>
                    </li>
                    
                    <!-- Uitloggen: beëindig de sessie en stuur door naar de loginpagina -->
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

<?php
/**
 * ============================================================================
 * EINDE VAN HEADER.PHP
 * ============================================================================
 * Dit bestand wordt in andere PHP-bestanden geïnclude met: include 'header.php';
 * Het zorgt voor consistente navigatie op alle pagina's.
 */
?>