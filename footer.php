<?php
// ============================================================================
// FOOTER.PHP - Page Footer Component
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This file contains the footer that appears at the bottom of every page.
// It is included in other PHP files using: include 'footer.php';
//
// WHAT IS A FOOTER?
// The footer is the bottom section of a website that typically contains:
// - Copyright information
// - Links to privacy policy and contact pages
// - Company/author information
//
// WHY USE A SEPARATE FILE?
// Same reason as header.php:
// - DRY principle (Don't Repeat Yourself)
// - Change once = changes everywhere
// - Easier maintenance
//
// DESIGN SPECIFICATIONS (from design document):
// - Height: 50 pixels
// - Position: Fixed at bottom of screen
// - Content: Copyright © 2025 + Privacy link + Contact link
// - Colors: Dark background with light text
// ============================================================================
?>

<!-- ========================================================================
     HTML FOOTER SECTION
     ======================================================================== -->

<!-- FOOTER ELEMENT -->
<!-- fixed-bottom: Stays at the bottom of the viewport (always visible) -->
<!-- bg-dark: Dark background color (Bootstrap's dark gray) -->
<!-- text-light: Light colored text (for visibility on dark background) -->
<!-- py-3: Padding on Y-axis (top and bottom) -->
<!-- text-center: Center all text content -->
<footer class="fixed-bottom bg-dark text-light py-3 text-center">
    
    <!-- CONTAINER: Provides responsive width and centering -->
    <div class="container">
        
        <!-- ================================================================
             FOOTER CONTENT
             ================================================================ -->
        
        <!-- Copyright Notice -->
        <!-- small: Makes text slightly smaller (typical for footer) -->
        <!-- mb-0: No margin bottom -->
        <p class="small mb-0">
            
            <!-- Copyright Symbol and Year -->
            <!-- © is the HTML entity for the copyright symbol -->
            &copy; 2025 GamePlan Scheduler by Harsha Kanaparthi
            
            <!-- Vertical Separator -->
            <!-- mx-2: Margin on X-axis (left and right spacing) -->
            <span class="mx-2">|</span>
            
            <!-- Privacy Policy Link -->
            <!-- text-light: Light colored text (matches footer) -->
            <!-- text-decoration-none: Removes underline -->
            <!-- hover effects defined in CSS -->
            <a href="privacy.php" class="text-light text-decoration-none">
                🔒 Privacy Policy
            </a>
            
            <!-- Vertical Separator -->
            <span class="mx-2">|</span>
            
            <!-- Contact Link -->
            <a href="contact.php" class="text-light text-decoration-none">
                📧 Contact
            </a>
            
        </p>
        
    </div>
    
</footer>

<!-- ========================================================================
     NOTES FOR THE EXAMINER
     ========================================================================
     
     FOOTER FEATURES:
     1. FIXED POSITION: Always visible at bottom of screen
     2. MINIMAL DESIGN: Clean, uncluttered appearance
     3. ESSENTIAL LINKS: Privacy and Contact (legal requirements)
     4. COPYRIGHT: Required for professional applications
     
     DESIGN CHOICES:
     - Dark background contrasts with page content
     - Small text size (footer content is secondary)
     - Icons (emojis) add visual clarity
     - Separator pipes (|) divide content sections
     
     BOOTSTRAP CLASSES USED:
     - fixed-bottom: Pins to viewport bottom
     - bg-dark: Dark gray background
     - text-light: White/light gray text
     - py-3: Padding top and bottom
     - text-center: Centered text
     - container: Responsive width container
     - small: Reduced font size
     - mb-0: No margin bottom
     - mx-2: Horizontal margins for separators
     - text-decoration-none: No underline on links
     
     LEGAL CONSIDERATIONS:
     - Copyright notice protects intellectual property
     - Privacy policy link (required by GDPR/AVG in EU)
     - Contact information for user support
     
     ======================================================================== -->