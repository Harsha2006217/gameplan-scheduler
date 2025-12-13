<?php
// header.php - Common Header Component (Pagina Koptekst)
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: 
// It contains the Logo and Navigation Menu.
// Included at the top of every page.
?>
<!-- Fixed Header with Glassmorphism effect -->
<header class="fixed-top p-0 mb-4">
    <div class="container d-flex justify-content-between align-items-center py-2 h-100">
        <!-- Logo Section -->
        <a href="index.php" class="text-decoration-none d-flex align-items-center">
            <!-- Icon could go here -->
            <h1 class="h4 mb-0 text-white" style="text-shadow: 0 0 15px #0d6efd;">
                GamePlan <span class="text-primary">Scheduler</span>
            </h1>
        </a>
        
        <!-- Navigation Menu (Bootstrap Navbar) -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <!-- Hamburger Button for Mobile -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Menu Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav align-items-center gap-2">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_friend.php">Friends</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_schedule.php">Schedule</a>
                        </li>
                        <!-- Prominent Call-to-Action Button (#1003 Improvement) -->
                        <li class="nav-item">
                            <a class="btn btn-warning text-dark fw-bold px-3 ms-lg-2" href="add_event.php">+ Add Event</a>
                        </li>
                        
                        <!-- Logout -->
                        <li class="nav-item ms-lg-3">
                            <a class="nav-link text-danger" href="index.php?logout=1">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>