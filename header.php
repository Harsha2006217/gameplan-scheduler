<?php
/**
 * header.php - NAVIGATIE HEADER | HARSHA KANAPARTHI | 2195344
 * Responsive Bootstrap navbar met hamburger menu op mobiel
 */
?>
<header class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" style="height: 80px;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-controller me-2"></i>GamePlan Scheduler
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="bi bi-person me-1"></i>Profile</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="add_friend.php"><i
                            class="bi bi-people me-1"></i>Friends</a></li>
                <li class="nav-item"><a class="nav-link" href="add_schedule.php"><i
                            class="bi bi-calendar me-1"></i>Schedule</a></li>
                <li class="nav-item"><a class="nav-link btn btn-success text-white ms-2" href="add_event.php"><i
                            class="bi bi-plus-circle me-1"></i>Add Event</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?logout=1"><i
                            class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</header>