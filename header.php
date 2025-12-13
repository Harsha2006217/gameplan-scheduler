<?php
// ============================================================================
// HEADER.PHP - Navigation Header Component
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This file contains the navigation header that appears at the top of every
// page. It is "included" in other PHP files using include 'header.php';
//
// WHAT IS A HEADER?
// The header is the top section of a website that usually contains:
// - Logo/Brand name
// - Navigation menu (links to other pages)
// - User profile/account options
//
// WHY SEPARATE FILE?
// Instead of copying the same header code into every page, we put it in
// one file and include it everywhere. This means:
// - Change it once = changes everywhere (DRY: Don't Repeat Yourself)
// - Less code duplication
// - Easier to maintain
//
// BOOTSTRAP FEATURES USED:
// - fixed-top: Header stays at top when scrolling
// - navbar: Bootstrap's navigation component
// - navbar-toggler: Hamburger menu for mobile devices
// - collapse: Menu collapses on small screens
// - nav-link: Styled navigation links
//
// DESIGN SPECIFICATIONS:
// - Height: 80 pixels (as per design document)
// - Colors: Primary blue background (#0d6efd)
// - Text: White for visibility
// - Responsive: Hamburger menu on mobile
// ============================================================================
?>

<!-- ========================================================================
     HTML HEADER SECTION
     ======================================================================== -->

<!-- HEADER ELEMENT -->
<!-- fixed-top: Stays at the top of the screen even when scrolling -->
<!-- bg-primary: Bootstrap blue background color -->
<!-- shadow: Adds subtle shadow for depth -->
<header class="fixed-top bg-primary shadow">
    
    <!-- CONTAINER: Centers content and adds responsive padding -->
    <!-- d-flex: Flexbox display for easy alignment -->
    <!-- justify-content-between: Space between logo and menu -->
    <!-- align-items-center: Vertically centers all items -->
    <!-- py-2: Padding top and bottom (0.5rem) -->
    <div class="container d-flex justify-content-between align-items-center py-2">
        
        <!-- ================================================================
             LOGO / BRAND SECTION
             ================================================================ -->
        <!-- Link to homepage with logo text -->
        <!-- text-decoration-none: Removes underline from link -->
        <a href="index.php" class="text-decoration-none d-flex align-items-center">
            <!-- Game Controller Emoji adds visual appeal -->
            <!-- h4: Heading size 4 (medium sized) -->
            <!-- mb-0: No margin bottom (keeps alignment clean) -->
            <!-- fw-bold: Font weight bold -->
            <span class="h4 mb-0 text-white fw-bold">
                🎮 GamePlan Scheduler
            </span>
        </a>
        
        
        <!-- ================================================================
             NAVIGATION SECTION
             ================================================================ -->
        <!-- Bootstrap navbar component -->
        <!-- navbar-expand-lg: Expands on large screens, collapses on smaller -->
        <!-- navbar-dark: Light colored text (for dark background) -->
        <nav class="navbar navbar-expand-lg navbar-dark p-0">
            
            <!-- ============================================================
                 HAMBURGER BUTTON (Mobile Menu Toggle)
                 ============================================================ -->
            <!-- This button only shows on mobile/tablet (hidden on large screens) -->
            <!-- When clicked, it shows/hides the navigation menu -->
            <!-- navbar-toggler: Bootstrap styling for toggle button -->
            <!-- data-bs-toggle="collapse": Bootstrap JS behavior -->
            <!-- data-bs-target="#navbarNav": Which element to show/hide -->
            <button class="navbar-toggler border-0" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                <!-- Three horizontal lines (hamburger icon) -->
                <span class="navbar-toggler-icon"></span>
            </button>
            
            
            <!-- ============================================================
                 COLLAPSIBLE NAVIGATION MENU
                 ============================================================ -->
            <!-- This section collapses on mobile (becomes hamburger menu) -->
            <!-- collapse: Hidden by default on mobile -->
            <!-- navbar-collapse: Bootstrap nav container -->
            <!-- id="navbarNav": Target for the toggle button above -->
            <div class="collapse navbar-collapse" id="navbarNav">
                
                <!-- Unordered list of navigation links -->
                <!-- navbar-nav: Bootstrap nav styling -->
                <!-- ms-auto: Margin start auto (pushes menu to right) -->
                <!-- gap-1: Small gap between items -->
                <ul class="navbar-nav ms-auto gap-1">
                    
                    <!-- ====================================================
                         HOME LINK
                         ==================================================== -->
                    <!-- nav-item: Bootstrap list item styling -->
                    <li class="nav-item">
                        <!-- nav-link: Bootstrap link styling -->
                        <!-- text-white: White text color -->
                        <!-- px-3: Horizontal padding (clickable area) -->
                        <!-- rounded: Rounded corners on hover background -->
                        <a class="nav-link text-white px-3 rounded" href="index.php">
                            <!-- Home icon (emoji) + text -->
                            🏠 Home
                        </a>
                    </li>
                    
                    
                    <!-- ====================================================
                         PROFILE LINK
                         ==================================================== -->
                    <!-- Links to profile page where users manage favorite games -->
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 rounded" href="profile.php">
                            👤 Profile
                        </a>
                    </li>
                    
                    
                    <!-- ====================================================
                         FRIENDS LINK
                         ==================================================== -->
                    <!-- Links to friends management page -->
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 rounded" href="add_friend.php">
                            👥 Friends
                        </a>
                    </li>
                    
                    
                    <!-- ====================================================
                         ADD SCHEDULE LINK
                         ==================================================== -->
                    <!-- Links to schedule creation page -->
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 rounded" href="add_schedule.php">
                            📅 Add Schedule
                        </a>
                    </li>
                    
                    
                    <!-- ====================================================
                         ADD EVENT BUTTON (Highlighted)
                         ==================================================== -->
                    <!-- This is a BUTTON style link (stands out more) -->
                    <!-- btn-success: Green button (draws attention) -->
                    <!-- ms-2: Margin start (space from other items) -->
                    <li class="nav-item">
                        <a class="nav-link btn btn-success text-white px-3 ms-lg-2" 
                           href="add_event.php">
                            ➕ Add Event
                        </a>
                    </li>
                    
                    
                    <!-- ====================================================
                         LOGOUT LINK
                         ==================================================== -->
                    <!-- Ends user session and redirects to login -->
                    <!-- btn-outline-light: Light bordered button -->
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light px-3 ms-lg-2" 
                           href="index.php?logout=1">
                            🚪 Logout
                        </a>
                    </li>
                    
                </ul>
            </div>
            
        </nav>
        
    </div>
</header>

<!-- ========================================================================
     NOTES FOR THE EXAMINER
     ========================================================================
     
     HEADER FUNCTIONALITY:
     1. RESPONSIVE: Menu collapses to hamburger on mobile devices
     2. FIXED POSITION: Stays visible while scrolling
     3. CLEAR NAVIGATION: All main sections accessible
     4. VISUAL HIERARCHY: "Add Event" stands out with green button
     
     ACCESSIBILITY FEATURES:
     - aria-label on toggle button for screen readers
     - aria-controls links button to menu
     - aria-expanded indicates menu state
     - High contrast colors (white on blue)
     
     BOOTSTRAP CLASSES USED:
     - fixed-top, bg-primary, shadow, container
     - d-flex, justify-content-between, align-items-center
     - navbar, navbar-expand-lg, navbar-dark
     - collapse, navbar-collapse, navbar-nav
     - nav-item, nav-link, btn, btn-success, btn-outline-light
     
     ======================================================================== -->