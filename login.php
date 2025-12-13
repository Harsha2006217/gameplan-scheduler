<?php
/**
 * ============================================================================
 * login.php - Gebruiker Login Pagina (User Login Page)
 * ============================================================================
 * 
 * @author      Harsha Kanaparthi
 * @student     2195344
 * @date        30-09-2025
 * @version     1.0
 * @project     GamePlan Scheduler
 * 
 * ============================================================================
 * BESCHRIJVING / DESCRIPTION:
 * ============================================================================
 * Dit is de login pagina waar gebruikers kunnen inloggen met hun email en
 * wachtwoord. De pagina:
 * 
 * 1. Controleert of gebruiker al is ingelogd (redirect naar dashboard)
 * 2. Verwerkt het login formulier bij POST request
 * 3. Toont foutmeldingen bij ongeldige gegevens
 * 4. Redirect naar dashboard bij succesvolle login
 * 
 * This is the login page where users can log in with their email and
 * password. It handles form submission, validation, and session creation.
 * 
 * ============================================================================
 * BEVEILIGING / SECURITY:
 * ============================================================================
 * - Wachtwoord verificatie met password_verify() (bcrypt)
 * - Generieke foutmelding (niet of email of wachtwoord fout is)
 * - Sessie regeneratie na login (tegen session fixation)
 * - Prepared statements in de loginUser() functie
 * ============================================================================
 */

// ============================================================================
// FUNCTIONS.PHP LADEN
// ============================================================================
// require_once laadt het bestand precies één keer.
// Dit bestand bevat alle functies die we nodig hebben, zoals:
// - isLoggedIn() - controleert of gebruiker al ingelogd is
// - loginUser() - verifieert email/wachtwoord en start sessie
// - safeEcho() - beveiligt output tegen XSS
// ============================================================================
require_once 'functions.php';

// ============================================================================
// CONTROLEER OF GEBRUIKER AL IS INGELOGD
// ============================================================================
// Als de gebruiker al is ingelogd, hoeft hij niet opnieuw in te loggen.
// We sturen hem direct door naar het dashboard (index.php).
// 
// isLoggedIn() controleert of $_SESSION['user_id'] bestaat
// header() stuurt een HTTP redirect naar de browser
// exit; stopt verdere uitvoering van dit script
// ============================================================================
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// ============================================================================
// INITIALISEER VARIABELEN
// ============================================================================
// $error houdt eventuele foutmeldingen bij
// We zetten dit op lege string zodat we later kunnen checken of er een fout is
// ============================================================================
$error = '';

// ============================================================================
// VERWERK FORMULIER SUBMISSION (POST REQUEST)
// ============================================================================
// $_SERVER['REQUEST_METHOD'] bevat de HTTP methode (GET, POST, etc.)
// We verwerken alleen als het een POST request is (formulier verzonden)
// 
// HOE WERKT DIT?
// 1. Gebruiker vult formulier in en klikt "Login"
// 2. Browser stuurt POST request naar deze pagina met de form data
// 3. We lezen de data uit $_POST en valideren het
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // ========================================================================
    // HAAL FORMULIER DATA OP
    // ========================================================================
    // $_POST bevat alle verzonden formulier data
    // ?? '' is de null coalescing operator: als $_POST['email'] niet bestaat,
    // gebruik dan een lege string als standaardwaarde
    // Dit voorkomt "undefined index" errors
    // ========================================================================
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // ========================================================================
    // PROBEER IN TE LOGGEN
    // ========================================================================
    // loginUser() doet het volgende:
    // 1. Valideert dat email en wachtwoord niet leeg zijn
    // 2. Zoekt de gebruiker in de database op email
    // 3. Vergelijkt het ingevoerde wachtwoord met de hash in de database
    // 4. Als correct: start sessie en return null (geen error)
    // 5. Als incorrect: return een foutmelding
    // ========================================================================
    $error = loginUser($email, $password);
    
    // ========================================================================
    // CONTROLEER RESULTAAT
    // ========================================================================
    // Als $error leeg is (! = not), was de login succesvol
    // We redirecten dan naar het dashboard
    // ========================================================================
    if (!$error) {
        header("Location: index.php");
        exit;
    }
    // Als er wel een error is, valt de code door naar de HTML
    // en wordt de foutmelding getoond
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- ======================================================================
     HTML DOCUMENT STRUCTUUR
     ======================================================================
     <!DOCTYPE html> - Vertelt browser dat dit HTML5 is
     <html lang="en"> - Root element, taal is Engels
     ====================================================================== -->
<head>
    <!-- ==================================================================
         META TAGS
         ==================================================================
         charset="UTF-8" - Karakterset voor speciale tekens en emoji's
         viewport - Maakt de pagina responsive op mobiele apparaten
         ================================================================== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ==================================================================
         SEO META TAGS
         ==================================================================
         title - Wordt getoond in browser tab en zoekresultaten
         description - Korte beschrijving voor zoekmachines
         ================================================================== -->
    <title>Login - GamePlan Scheduler</title>
    <meta name="description" content="Log in to GamePlan Scheduler to manage your gaming schedules and events.">
    
    <!-- ==================================================================
         BOOTSTRAP 5 CSS
         ==================================================================
         We laden Bootstrap van een CDN (Content Delivery Network)
         Dit is sneller dan het zelf hosten en wordt gecached
         
         Bootstrap geeft ons:
         - Responsive grid systeem (container, row, col-*)
         - Vormgeving voor formulieren (form-control, form-label)
         - Knoppen (btn, btn-primary, btn-lg)
         - Alert meldingen (alert, alert-danger)
         - Dark mode kleuren
         ================================================================== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ==================================================================
         BOOTSTRAP ICONS
         ==================================================================
         Iconen bibliotheek voor mooie symbolen
         ================================================================== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- ==================================================================
         CUSTOM CSS
         ==================================================================
         Onze eigen stijlen die Bootstrap aanvullen/overschrijven
         ================================================================== -->
    <link rel="stylesheet" href="style.css">
</head>
<!-- ======================================================================
     BODY - DE ZICHTBARE INHOUD
     ======================================================================
     class="bg-dark text-light" - Bootstrap dark mode achtergrond en tekst
     ====================================================================== -->
<body class="bg-dark text-light min-vh-100 d-flex align-items-center">
    
    <!-- ==================================================================
         MAIN CONTAINER
         ==================================================================
         container - Bootstrap container met responsive breedte
         
         We gebruiken GEEN header/footer hier omdat de login pagina
         een standalone pagina is (gebruiker is nog niet ingelogd)
         ================================================================== -->
    <main class="container">
        
        <!-- ==============================================================
             ROW & COLUMN VOOR CENTERING
             ==============================================================
             row - Bootstrap rij
             justify-content-center - Horizontaal centreren
             col-md-6 col-lg-4 - Breedte:
             - Op small screens: volle breedte
             - Op medium screens: 6/12 = 50%
             - Op large screens: 4/12 = 33%
             ============================================================== -->
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                
                <!-- ======================================================
                     LOGIN CARD
                     ======================================================
                     card - Bootstrap kaart component
                     bg-secondary - Grijze achtergrond
                     border-0 - Geen rand
                     shadow-lg - Grote schaduw voor diepte-effect
                     ====================================================== -->
                <div class="card bg-secondary border-0 shadow-lg">
                    <div class="card-body p-4">
                        
                        <!-- ================================================
                             LOGO EN TITEL
                             ================================================
                             text-center - Centreer tekst
                             mb-4 - Margin bottom 4 (ruimte onder)
                             bi bi-controller - Bootstrap icon: controller
                             ================================================ -->
                        <div class="text-center mb-4">
                            <i class="bi bi-controller text-primary" style="font-size: 3rem;"></i>
                            <h1 class="h3 mt-2">GamePlan Scheduler</h1>
                            <p class="text-muted">Sign in to your account</p>
                        </div>
                        
                        <!-- ================================================
                             ERROR MELDING
                             ================================================
                             <?php if ($error): ?> - Toon alleen als er fout is
                             alert alert-danger - Bootstrap rode waarschuwing
                             safeEcho() - Beveiligt output tegen XSS
                             ================================================ -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?php echo safeEcho($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- ================================================
                             LOGIN FORMULIER
                             ================================================
                             method="POST" - Data wordt via POST verstuurd
                             onsubmit="return validateLoginForm();" - JavaScript 
                                validatie voordat formulier wordt verstuurd
                             
                             Als validateLoginForm() false returned, wordt
                             het formulier NIET verstuurd (preventie)
                             ================================================ -->
                        <form method="POST" onsubmit="return validateLoginForm();">
                            
                            <!-- ============================================
                                 EMAIL VELD
                                 ============================================
                                 mb-3 - Margin bottom 3
                                 form-label - Bootstrap label styling
                                 form-control - Bootstrap input styling
                                 type="email" - Browser valideert email format
                                 required - Browser staat geen leeg veld toe
                                 aria-label - Toegankelijkheid voor screenreaders
                                 ============================================ -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>Email Address
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-control form-control-lg bg-dark text-light border-secondary" 
                                    placeholder="your@email.com"
                                    required 
                                    autocomplete="email"
                                    aria-label="Email address">
                            </div>
                            
                            <!-- ============================================
                                 WACHTWOORD VELD
                                 ============================================
                                 type="password" - Verbergt ingevoerde tekst
                                 autocomplete="current-password" - Helpt 
                                    password managers
                                 ============================================ -->
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>Password
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-control form-control-lg bg-dark text-light border-secondary" 
                                    placeholder="Enter your password"
                                    required 
                                    autocomplete="current-password"
                                    aria-label="Password">
                            </div>
                            
                            <!-- ============================================
                                 SUBMIT BUTTON
                                 ============================================
                                 btn btn-primary - Bootstrap primaire knop
                                 btn-lg - Grote knop
                                 w-100 - Width 100% (volle breedte)
                                 ============================================ -->
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                        </form>
                        
                        <!-- ================================================
                             REGISTRATIE LINK
                             ================================================
                             text-center mt-3 - Gecentreerd met margin-top
                             text-decoration-none - Geen onderstreping
                             ================================================ -->
                        <p class="text-center mt-3 mb-0">
                            Don't have an account? 
                            <a href="register.php" class="text-primary text-decoration-none fw-bold">
                                Create one
                            </a>
                        </p>
                        
                    </div>
                </div>
                
                <!-- ======================================================
                     COPYRIGHT FOOTER
                     ====================================================== -->
                <p class="text-center text-muted mt-3 small">
                    © 2025 GamePlan Scheduler by Harsha Kanaparthi
                </p>
                
            </div>
        </div>
    </main>
    
    <!-- ==================================================================
         BOOTSTRAP JAVASCRIPT
         ==================================================================
         Nodig voor interactieve componenten zoals:
         - Alert dismiss button
         - Modals
         - Dropdowns
         
         bundle.min.js bevat zowel Bootstrap JS als Popper.js
         ================================================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- ==================================================================
         CUSTOM JAVASCRIPT
         ==================================================================
         Ons eigen script met form validatie functies
         ================================================================== -->
    <script src="script.js"></script>
</body>
</html>