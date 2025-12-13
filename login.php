<?php
/**
 * ============================================================================
 * LOGIN.PHP - USER LOGIN PAGE / GEBRUIKER LOGIN PAGINA
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This page allows users to log into their GamePlan Scheduler account.
 * It validates email and password, then creates a session if valid.
 * 
 * DUTCH:
 * Deze pagina laat gebruikers inloggen op hun GamePlan Scheduler account.
 * Het valideert e-mail en wachtwoord, dan maakt een sessie als geldig.
 * 
 * FEATURES:
 * - Email and password validation
 * - Session creation on success
 * - Redirect to dashboard after login
 * - Link to register page for new users
 * ============================================================================
 */

// Include all functions (database, validation, session management)
// Include alle functies (database, validatie, sessie beheer)
require_once 'functions.php';

/**
 * SECURITY CHECK: Redirect if already logged in
 * VEILIGHEIDSCONTROLE: Redirect als al ingelogd
 * 
 * If user is already logged in, they don't need to see login page.
 * Redirect them to the dashboard instead.
 */
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Initialize error variable for displaying validation errors
// Initialiseer fout variabele voor het tonen van validatie fouten
$error = '';

/**
 * FORM PROCESSING SECTION
 * FORMULIER VERWERKING SECTIE
 * 
 * This code runs when the form is submitted (POST request).
 * $_SERVER['REQUEST_METHOD'] tells us how the page was accessed.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form values using null coalescing operator (??)
    // Haal formulier waarden op met null coalescing operator (??)
    // ?? '' means: if not set, use empty string
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Attempt to login user - returns error message or null on success
    // Probeer gebruiker in te loggen - retourneert foutmelding of null bij succes
    $error = loginUser($email, $password);
    
    // If no error, login was successful
    // Als geen fout, login was succesvol
    if (!$error) {
        // Redirect to dashboard
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ====================================================================
         META TAGS - Page settings
         META TAGS - Pagina instellingen
         ==================================================================== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login to GamePlan Scheduler - Manage your gaming schedules and events">
    
    <!-- Page title shown in browser tab -->
    <title>Login - GamePlan Scheduler</title>
    
    <!-- ====================================================================
         CSS INCLUDES - Stylesheets
         CSS INCLUDES - Stylesheets
         ==================================================================== -->
    <!-- Bootstrap 5 CSS from CDN for responsive grid and components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS with dark gaming theme -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- 
     BODY - Page content
     BODY - Pagina inhoud
     
     bg-dark: Dark background color
     text-light: Light text color for contrast
-->
<body class="bg-dark text-light">
    
    <!-- ====================================================================
         MAIN CONTENT - Login form container
         HOOFD INHOUD - Login formulier container
         ==================================================================== -->
    <div class="container mt-5 pt-5">
        
        <!-- Auth container with glassmorphism effect -->
        <div class="auth-container">
            
            <!-- 
                PAGE TITLE
                PAGINA TITEL
            -->
            <h1 class="text-center mb-4">🎮 Login</h1>
            
            <!-- ================================================================
                 ERROR MESSAGE DISPLAY
                 FOUTMELDING WEERGAVE
                 
                 Shows error if login failed (wrong email/password)
                 Toont fout als login mislukt (verkeerde e-mail/wachtwoord)
                 ================================================================ -->
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo safeEcho($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- ================================================================
                 LOGIN FORM
                 LOGIN FORMULIER
                 
                 method="POST": Send data securely (not in URL)
                 onsubmit: Run JavaScript validation before sending
                 ================================================================ -->
            <form method="POST" onsubmit="return validateLoginForm();">
                
                <!-- EMAIL INPUT FIELD -->
                <!-- E-MAIL INVOERVELD -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        📧 Email Address / E-mailadres
                    </label>
                    <!-- 
                        type="email": Browser validates email format
                        required: Field must be filled before submit
                        aria-label: For accessibility screen readers
                    -->
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control form-control-lg" 
                           required 
                           placeholder="your@email.com"
                           aria-label="Email address">
                </div>
                
                <!-- PASSWORD INPUT FIELD -->
                <!-- WACHTWOORD INVOERVELD -->
                <div class="mb-4">
                    <label for="password" class="form-label">
                        🔒 Password / Wachtwoord
                    </label>
                    <!-- 
                        type="password": Hides characters as dots
                        required: Must be filled
                    -->
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control form-control-lg" 
                           required 
                           placeholder="Enter your password"
                           aria-label="Password">
                </div>
                
                <!-- SUBMIT BUTTON -->
                <!-- VERZEND KNOP -->
                <!-- 
                    btn-lg: Large button for mobile usability (40px+)
                    w-100: Full width of container
                -->
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    🚀 Login
                </button>
                
            </form>
            
            <!-- ================================================================
                 REGISTER LINK
                 REGISTRATIE LINK
                 
                 For users who don't have an account yet
                 Voor gebruikers die nog geen account hebben
                 ================================================================ -->
            <p class="text-center mt-4 mb-0">
                Don't have an account? / Geen account? 
                <a href="register.php" class="text-info">
                    Register here / Registreer hier
                </a>
            </p>
            
        </div>
    </div>
    
    <!-- ====================================================================
         JAVASCRIPT INCLUDES
         JAVASCRIPT INCLUDES
         ==================================================================== -->
    <!-- Bootstrap JS for interactive components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript with form validation -->
    <script src="script.js"></script>
    
</body>
</html>
<?php
/**
 * ============================================================================
 * END OF LOGIN.PHP / EINDE VAN LOGIN.PHP
 * ============================================================================
 */
?>