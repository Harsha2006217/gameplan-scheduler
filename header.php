<?php
/**
 * ============================================================================
 * header.php - NAVIGATIE HEADER COMPONENT
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Dit is de navigatie balk die bovenaan ELKE pagina verschijnt.
 * Het wordt ingevoegd met <?php include 'header.php'; ?>
 * 
 * FUNCTIONALITEIT:
 * - Logo met link naar home (index.php)
 * - Navigatie menu met alle belangrijke pagina's
 * - Hamburger menu op mobiel (responsive)
 * - Uitlog knop
 * 
 * DESIGN KEUZES:
 * - Fixed-top: header blijft zichtbaar bij scrollen
 * - Dark theme met blauwe accenten (gaming look)
 * - Bootstrap navbar voor responsiviteit
 * ============================================================================
 */
?>

<!-- 
    BOOTSTRAP NAVBAR COMPONENT
    ==========================
    fixed-top: Header blijft altijd bovenaan, ook bij scrollen
    navbar-expand-lg: Op grote schermen (>992px) zijn menu items zichtbaar
                      Op kleine schermen wordt het een hamburger menu
    bg-primary: Blauwe achtergrond (Bootstrap primary kleur)
    shadow: Subtiele schaduw voor diepte effect
-->
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
        <div class="container">

            <!-- 
                LOGO / BRAND
                ============
                Link naar de homepage met het app logo en naam
                bi-controller: Bootstrap icon voor game controller
            -->
            <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php">
                <i class="bi bi-controller fs-4 me-2"></i>
                GamePlan Scheduler
            </a>

            <!-- 
                HAMBURGER BUTTON (alleen zichtbaar op mobiel)
                =============================================
                data-bs-toggle="collapse": Bootstrap JavaScript opent het menu
                data-bs-target="#navbarNav": ID van het menu dat opent
                aria-controls: Accessibility - welk element wordt bestuurd
                aria-expanded: Accessibility - is menu open of dicht
            -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- 
                NAVIGATIE MENU
                ==============
                collapse navbar-collapse: Verborgen op mobiel, zichtbaar op desktop
                id="navbarNav": Moet overeenkomen met data-bs-target hierboven
            -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- ms-auto: Menu items rechts uitlijnen -->
                <ul class="navbar-nav ms-auto align-items-center">

                    <!-- HOME link -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house-door me-1"></i>Home
                        </a>
                    </li>

                    <!-- PROFIEL link -->
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <i class="bi bi-person me-1"></i>Profile
                        </a>
                    </li>

                    <!-- VRIENDEN link -->
                    <li class="nav-item">
                        <a class="nav-link" href="add_friend.php">
                            <i class="bi bi-people me-1"></i>Friends
                        </a>
                    </li>

                    <!-- SCHEDULE TOEVOEGEN link -->
                    <li class="nav-item">
                        <a class="nav-link" href="add_schedule.php">
                            <i class="bi bi-calendar-plus me-1"></i>Add Schedule
                        </a>
                    </li>

                    <!-- 
                        ADD EVENT BUTTON
                        ================
                        Dit is een opvallende knop (btn-success = groen)
                        Volgens verbetering #1003: "Evenement toevoegen" prominent maken
                    -->
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-success" href="add_event.php">
                            <i class="bi bi-plus-circle me-1"></i>Add Event
                        </a>
                    </li>

                    <!-- 
                        LOGOUT LINK
                        ===========
                        Stuurt naar index.php met logout parameter
                        index.php handelt de logout af via logout() functie
                    -->
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link text-warning" href="index.php?logout=1">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </li>

                </ul>
            </div>

        </div>
    </nav>
</header>