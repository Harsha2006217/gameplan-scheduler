<?php
/**
 * ==========================================================================
 * HEADER.PHP - NAVIGATIE HEADER
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand bevat de website header die op elke pagina verschijnt.
 * Het bevat het logo, navigatiemenu en hamburger menu voor mobiel.
 *
 * Ontwerp specificaties:
 * - Hoogte: 80px volgens ontwerpdocument
 * - Bevat: Logo, menu, profiel icoon
 * - Kleur: Blauw gradient met gaming gevoel
 * - Knoppen: minimaal 40px hoogte voor mobiele bruikbaarheid
 * ==========================================================================
 */
?>

<!-- Vaste header bovenaan elke pagina -->
<header class="fixed-top bg-primary p-0 mb-4">

    <!-- Container centreert inhoud en beperkt maximale breedte -->
    <div class="container d-flex justify-content-between align-items-center py-2">

        <!-- Logo sectie - linkt naar de homepagina -->
        <a href="index.php" class="text-decoration-none">
            <h1 class="h4 mb-0 text-white">
                🎮 GamePlan Scheduler
            </h1>
        </a>

        <!-- Navigatie sectie met alle menu items -->
        <nav class="navbar navbar-expand-lg navbar-dark p-0">

            <!-- Hamburger knop - alleen zichtbaar op mobiel (< 992px) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Navigatie openen">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Inklapbaar menu - verborgen op mobiel tot hamburger geklikt -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- Navigatie items lijst -->
                <ul class="navbar-nav ms-auto gap-2">

                    <!-- Dashboard link -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">
                            🏠 Dashboard
                        </a>
                    </li>

                    <!-- Profiel link - beheer favoriete spellen -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="profile.php">
                            👤 Profiel
                        </a>
                    </li>

                    <!-- Vrienden link - beheer gaming vrienden -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="add_friend.php">
                            👥 Vrienden
                        </a>
                    </li>

                    <!-- Schema toevoegen link -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="add_schedule.php">
                            📅 Planning
                        </a>
                    </li>

                    <!-- Evenement toevoegen knop - opvallende groene knop -->
                    <li class="nav-item">
                        <a class="nav-link text-white btn btn-success ms-2 px-3" href="add_event.php">
                            🎯 Evenement
                        </a>
                    </li>

                    <!-- Uitloggen link -->
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