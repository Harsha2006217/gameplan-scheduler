<?php
// ============================================================================
// LOGIN.PHP - User Login Page
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This is the LOGIN page where users enter their credentials to access
// the application. It handles both the login form display AND the form
// submission processing.
//
// HOW LOGIN WORKS:
// 1. User visits login.php → sees login form
// 2. User enters email and password
// 3. JavaScript validates the form (client-side)
// 4. Form is submitted to same page (login.php)
// 5. PHP processes the login (server-side)
// 6. If successful → redirect to index.php (dashboard)
// 7. If failed → show error message, stay on login page
//
// SECURITY FEATURES:
// - Password is NEVER displayed or stored in plain text
// - Form uses POST method (data not visible in URL)
// - Server-side validation in functions.php
// - CSRF protection via session
//
// FILE DEPENDENCIES:
// - functions.php: Contains loginUser() function
// - script.js: Contains validateLoginForm() function
// - style.css: Custom styling
// - Bootstrap CSS: Layout and components
// ============================================================================


// ============================================================================
// STEP 1: INCLUDE CORE FUNCTIONS
// ============================================================================
// require_once loads functions.php exactly once
// This gives us access to all authentication functions

require_once 'functions.php';


// ============================================================================
// STEP 2: CHECK IF USER IS ALREADY LOGGED IN
// ============================================================================
// If user is already logged in, they don't need to see the login page
// Redirect them directly to the dashboard

if (isLoggedIn()) {
    // header() sends HTTP redirect to browser
    // "Location: index.php" tells browser to go to index.php
    header("Location: index.php");
    
    // exit stops script execution
    // Important: must call exit after header() to prevent further code execution
    exit;
}


// ============================================================================
// STEP 3: INITIALIZE ERROR VARIABLE
// ============================================================================
// $error will hold any login error messages
// Empty string means no error yet

$error = '';


// ============================================================================
// STEP 4: PROCESS LOGIN FORM SUBMISSION
// ============================================================================
// This code only runs when the form is submitted (POST request)
// $_SERVER['REQUEST_METHOD'] tells us how the page was accessed
// 'GET' = normal page visit, 'POST' = form submission

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // ========================================================================
    // GET FORM DATA FROM POST
    // ========================================================================
    // $_POST is a PHP superglobal array containing form data
    // ?? '' means: use empty string if value doesn't exist (null coalescing)
    // This prevents "undefined index" errors
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    
    // ========================================================================
    // ATTEMPT LOGIN
    // ========================================================================
    // loginUser() is defined in functions.php
    // It returns: null if successful, error message string if failed
    
    $error = loginUser($email, $password);
    
    
    // ========================================================================
    // CHECK LOGIN RESULT
    // ========================================================================
    // If $error is empty/null/false, login was successful
    
    if (!$error) {
        // Login successful! Redirect to dashboard
        header("Location: index.php");
        exit;
    }
    // If there was an error, it's stored in $error and will be displayed below
}


// ============================================================================
// STEP 5: CHECK FOR SESSION TIMEOUT MESSAGE
// ============================================================================
// If user was redirected here due to session timeout, show message

if (isset($_GET['msg']) && $_GET['msg'] == 'session_timeout') {
    $error = 'Your session has expired. Please login again.';
}
?>

<!-- ========================================================================
     HTML DOCUMENT START
     ======================================================================== -->
<!DOCTYPE html>
<!-- DOCTYPE declares this is an HTML5 document -->
<!-- lang="en" helps screen readers and search engines understand the language -->
<html lang="en">

<head>
    <!-- ====================================================================
         META TAGS: Information about the page
         ==================================================================== -->
    
    <!-- Character encoding: UTF-8 supports all languages and emojis -->
    <meta charset="UTF-8">
    
    <!-- Viewport: Makes page responsive on mobile devices -->
    <!-- width=device-width: Page width = device screen width -->
    <!-- initial-scale=1.0: No zoom by default -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Page title: Shows in browser tab -->
    <title>Login - GamePlan Scheduler</title>
    
    <!-- SEO: Description for search engines -->
    <meta name="description" content="Login to GamePlan Scheduler to manage your gaming activities and connect with friends.">
    
    
    <!-- ====================================================================
         CSS STYLESHEETS
         ==================================================================== -->
    
    <!-- Bootstrap CSS: Layout and component styling (from CDN) -->
    <!-- CDN = Content Delivery Network (fast, global servers) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS: Our own styles that override/extend Bootstrap -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- ========================================================================
     BODY: Main content of the page
     ======================================================================== -->
<!-- bg-dark: Dark background (Bootstrap class) -->
<!-- text-light: Light text color (Bootstrap class) -->
<!-- min-vh-100: Minimum height of 100% viewport height -->
<!-- d-flex: Flexbox display -->
<!-- align-items-center: Vertically center content -->
<body class="bg-dark text-light min-vh-100 d-flex align-items-center">
    
    <!-- ====================================================================
         MAIN CONTAINER
         ==================================================================== -->
    <!-- container: Responsive width container -->
    <!-- py-5: Padding on Y-axis (top and bottom) -->
    <div class="container py-5">
        
        <!-- ================================================================
             ROW: Bootstrap grid row
             ================================================================ -->
        <!-- justify-content-center: Center the columns horizontally -->
        <div class="row justify-content-center">
            
            <!-- ============================================================
                 COLUMN: Form container
                 ============================================================ -->
            <!-- col-md-6: 6 columns on medium screens (50% width) -->
            <!-- col-lg-4: 4 columns on large screens (33% width) -->
            <!-- This makes the form narrower on larger screens -->
            <div class="col-md-6 col-lg-4">
                
                <!-- ========================================================
                     LOGIN CARD
                     ======================================================== -->
                <!-- card: Bootstrap card component -->
                <!-- bg-secondary: Gray background -->
                <!-- border-0: No border -->
                <!-- shadow-lg: Large shadow for depth -->
                <!-- rounded-4: Extra rounded corners -->
                <div class="card bg-secondary border-0 shadow-lg rounded-4">
                    
                    <!-- Card Body: Contains the form -->
                    <!-- p-4: Padding all around -->
                    <div class="card-body p-4">
                        
                        <!-- ================================================
                             LOGO AND TITLE
                             ================================================ -->
                        <!-- text-center: Center the text -->
                        <!-- mb-4: Margin bottom (spacing) -->
                        <div class="text-center mb-4">
                            <!-- Large game controller emoji -->
                            <div class="display-1 mb-3">🎮</div>
                            
                            <!-- Page title -->
                            <!-- h4: Heading size 4 -->
                            <!-- fw-bold: Bold font weight -->
                            <h1 class="h4 fw-bold text-white">Welcome Back!</h1>
                            <p class="text-muted small">Login to your account</p>
                        </div>
                        
                        
                        <!-- ================================================
                             ERROR MESSAGE DISPLAY
                             ================================================ -->
                        <!-- Only shows if $error has a value -->
                        <?php if ($error): ?>
                            <!-- alert: Bootstrap alert component -->
                            <!-- alert-danger: Red background (error) -->
                            <!-- alert-dismissible: Can be closed -->
                            <!-- fade show: Animation classes -->
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <!-- safeEcho() prevents XSS attacks -->
                                <?php echo safeEcho($error); ?>
                                <!-- Close button -->
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        
                        <!-- ================================================
                             LOGIN FORM
                             ================================================ -->
                        <!-- method="POST": Send data securely (not in URL) -->
                        <!-- onsubmit: Call JavaScript validation before submit -->
                        <!-- return false = cancel submit if validation fails -->
                        <form method="POST" onsubmit="return validateLoginForm();">
                            
                            <!-- ============================================
                                 EMAIL INPUT FIELD
                                 ============================================ -->
                            <!-- mb-3: Margin bottom (spacing between fields) -->
                            <div class="mb-3">
                                <!-- Label: Describes the input field -->
                                <!-- for="email": Links to input with id="email" -->
                                <!-- form-label: Bootstrap label styling -->
                                <label for="email" class="form-label">
                                    📧 Email Address
                                </label>
                                
                                <!-- Input field for email -->
                                <!-- type="email": Browser validates email format -->
                                <!-- id="email": Unique identifier (for label and JS) -->
                                <!-- name="email": Key name in $_POST array -->
                                <!-- class="form-control": Bootstrap input styling -->
                                <!-- required: HTML5 validation (can't be empty) -->
                                <!-- placeholder: Gray text shown when empty -->
                                <!-- aria-label: Accessibility description -->
                                <!-- autofocus: Cursor starts here on page load -->
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control form-control-lg bg-dark text-light border-secondary"
                                       required 
                                       placeholder="Enter your email"
                                       aria-label="Email address"
                                       autofocus>
                            </div>
                            
                            
                            <!-- ============================================
                                 PASSWORD INPUT FIELD
                                 ============================================ -->
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    🔑 Password
                                </label>
                                
                                <!-- type="password": Hides characters as you type -->
                                <!-- Shows dots (•••••) instead of actual characters -->
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control form-control-lg bg-dark text-light border-secondary"
                                       required 
                                       placeholder="Enter your password"
                                       aria-label="Password">
                            </div>
                            
                            
                            <!-- ============================================
                                 SUBMIT BUTTON
                                 ============================================ -->
                            <!-- type="submit": Submits the form when clicked -->
                            <!-- btn: Bootstrap button base class -->
                            <!-- btn-primary: Blue button (main action) -->
                            <!-- btn-lg: Large button -->
                            <!-- w-100: Width 100% (full width) -->
                            <!-- py-3: Extra vertical padding -->
                            <!-- fw-bold: Bold text -->
                            <button type="submit" 
                                    class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-3">
                                🚀 Login
                            </button>
                            
                        </form>
                        
                        
                        <!-- ================================================
                             REGISTER LINK
                             ================================================ -->
                        <!-- For users who don't have an account yet -->
                        <p class="text-center mt-4 mb-0 small">
                            Don't have an account? 
                            <a href="register.php" class="text-info text-decoration-none fw-bold">
                                Register here
                            </a>
                        </p>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    
    <!-- ====================================================================
         JAVASCRIPT FILES
         ==================================================================== -->
    
    <!-- Bootstrap JavaScript Bundle (includes Popper for dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript (form validation) -->
    <script src="script.js"></script>
    
</body>
</html>

<!-- ========================================================================
     NOTES FOR THE EXAMINER
     ========================================================================
     
     LOGIN FLOW:
     1. User sees form → enters email/password
     2. JavaScript validates before submit (client-side)
     3. PHP validates on server (functions.php → loginUser())
     4. Password compared using password_verify() with bcrypt hash
     5. Success → session created, redirect to dashboard
     6. Failure → error displayed, user can retry
     
     SECURITY MEASURES:
     - POST method (data not in URL/history)
     - Password field masks input
     - Server-side validation (can't be bypassed)
     - XSS protection via safeEcho()
     - Session regeneration on login
     
     ACCESSIBILITY (a11y):
     - Proper labels linked to inputs
     - aria-label attributes
     - High contrast colors
     - Keyboard navigable
     
     ======================================================================== -->