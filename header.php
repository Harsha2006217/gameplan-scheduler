<?php
// This file is header.php - Top part of every page.
// Author: Harsha Kanaparthi.
// Date: Improved on 10-12-2025.
// Description: Logo, menu with links, Bootstrap navbar for responsive menu (hamburger on mobile).
// Improvements: Made navbar collapse on small screens, highlighted "Add Event" as green button.

?>
<header class="fixed-top bg-primary p-3 mb-4"> <!-- fixed-top sticks to top, p-3 padding. -->
    <div class="container d-flex justify-content-between align-items-center"> <!-- Container centers, d-flex for layout. -->
        <a href="index.php" class="text-decoration-none"> <!-- Link to home, no underline. -->
            <h1 class="h4 mb-0 text-white">GamePlan Scheduler</h1> <!-- Logo text. -->
        </a>
        <nav class="navbar navbar-expand-lg"> <!-- Navbar for menu. -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> <!-- Hamburger button on mobile. -->
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav"> <!-- Collapse hides on small screens. -->
                <ul class="navbar-nav"> <!-- List of links. -->
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li> <!-- Each li is a menu item. -->
                    <li class="nav-item"><a class="nav-link text-white" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="add_friend.php">Friends</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="add_schedule.php">Add Schedule</a></li>
                    <li class="nav-item"><a class="nav-link text-white btn btn-success ms-2" href="add_event.php">Add Event</a></li> <!-- Green button for highlight. -->
                    <li class="nav-item"><a class="nav-link text-white" href="index.php?logout=1">Logout</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>