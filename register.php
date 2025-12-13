<?php
/**
 * ============================================================================
 * register.php - Gebruiker Registratie Pagina (User Registration Page)
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
 * Dit is de registratie pagina waar nieuwe gebruikers een account kunnen
 * aanmaken. De pagina:
 * 
 * 1. Controleert of gebruiker al is ingelogd (redirect naar dashboard)
 * 2. Toont een registratie formulier met validatie
 * 3. Verwerkt het formulier bij POST request
 * 4. Maakt een nieuw account aan bij succesvolle validatie
 * 5. Redirect naar login pagina met succesmelding
 * 
 * This is the registration page where new users can create an account.
 * It validates input, hashes passwords securely, and creates new user records.
 * 
 * ============================================================================
 * BEVEILIGING / SECURITY:
 * ============================================================================
 * - Wachtwoord wordt gehasht met bcrypt (password_hash)
 * - Email wordt gecontroleerd op duplicaten
 * - Minimale wachtwoordlengte: 8 karakters
 * - Prepared statements tegen SQL-injectie
 * - Output escaping tegen XSS
 * ============================================================================
 */

// ============================================================================
// FUNCTIONS.PHP LADEN
// ============================================================================
require_once 'functions.php';

// ============================================================================
// CONTROLEER OF GEBRUIKER AL IS INGELOGD
// ============================================================================
// Als je al ingelogd bent, heb je geen nieuw account nodig
// ============================================================================
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// ============================================================================
// INITIALISEER VARIABELEN
// ============================================================================
$error = '';

// ============================================================================
// VERWERK FORMULIER SUBMISSION (POST REQUEST)
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ========================================================================
    // HAAL FORMULIER DATA OP
    // ========================================================================
    // ?? '' geeft lege string als de key niet bestaat
    // ========================================================================
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // ========================================================================
    // REGISTREER NIEUWE GEBRUIKER
    // ========================================================================
    // registerUser() doet het volgende:
    // 1. Valideert username (niet leeg, max 50 karakters)
    // 2. Valideert email format
    // 3. Valideert wachtwoord (niet leeg, min 8 karakters)
    // 4. Controleert of email al bestaat in database
    // 5. Hasht het wachtwoord met bcrypt
    // 6. Slaat de gebruiker op in de database
    // 7. Return null bij succes, foutmelding bij error
    // ========================================================================
    $error = registerUser($username, $email, $password);

    // ========================================================================
    // CONTROLEER RESULTAAT
    // ========================================================================
    if (!$error) {
        // Succesvol geregistreerd!
        // Zet een succes bericht in de sessie
        setMessage('success', 'Registration successful! Please login with your new account.');
        // Redirect naar login pagina
        header("Location: login.php");
        exit;
    }
    // Bij error valt code door en wordt foutmelding getoond
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ==================================================================
         META TAGS VOOR RESPONSIVE DESIGN EN CHARACTER ENCODING
         ================================================================== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ==================================================================
         SEO OPTIMALISATIE
         ================================================================== -->
    <title>Register - GamePlan Scheduler</title>
    <meta name="description"
        content="Create your GamePlan Scheduler account to manage gaming schedules, connect with friends, and plan events.">

    <!-- ==================================================================
         STYLESHEETS
         ================================================================== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light min-vh-100 d-flex align-items-center">

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <!-- ======================================================
                     REGISTRATION CARD
                     ======================================================
                     Iets breder dan login card (col-lg-5 vs col-lg-4)
                     omdat we meer velden hebben
                     ====================================================== -->
                <div class="card bg-secondary border-0 shadow-lg">
                    <div class="card-body p-4">

                        <!-- ================================================
                             LOGO EN TITEL
                             ================================================ -->
                        <div class="text-center mb-4">
                            <i class="bi bi-person-plus text-primary" style="font-size: 3rem;"></i>
                            <h1 class="h3 mt-2">Create Account</h1>
                            <p class="text-muted">Join GamePlan Scheduler today</p>
                        </div>

                        <!-- ================================================
                             ERROR MELDING
                             ================================================ -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?php echo safeEcho($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- ================================================
                             REGISTRATIE FORMULIER
                             ================================================
                             onsubmit="return validateRegisterForm();" roept
                             JavaScript validatie aan voordat het formulier
                             wordt verstuurd. Als de functie false teruggeeft,
                             wordt het formulier NIET verstuurd.
                             ================================================ -->
                        <form method="POST" onsubmit="return validateRegisterForm();">

                            <!-- ============================================
                                 USERNAME VELD
                                 ============================================
                                 maxlength="50" - Browser limiteert invoer
                                 autocomplete="username" - Helpt browsers
                                 ============================================ -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person me-1"></i>Username
                                </label>
                                <input type="text" id="username" name="username"
                                    class="form-control form-control-lg bg-dark text-light border-secondary"
                                    placeholder="Choose a username" required maxlength="50" autocomplete="username"
                                    aria-label="Username">
                                <div class="form-text text-muted">
                                    <small>Maximum 50 characters</small>
                                </div>
                            </div>

                            <!-- ============================================
                                 EMAIL VELD
                                 ============================================ -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>Email Address
                                </label>
                                <input type="email" id="email" name="email"
                                    class="form-control form-control-lg bg-dark text-light border-secondary"
                                    placeholder="your@email.com" required autocomplete="email"
                                    aria-label="Email address">
                                <div class="form-text text-muted">
                                    <small>We'll never share your email</small>
                                </div>
                            </div>

                            <!-- ============================================
                                 WACHTWOORD VELD
                                 ============================================
                                 minlength="8" - Browser vereist min 8 chars
                                 autocomplete="new-password" - Helpt password
                                    managers te weten dat dit een nieuw
                                    wachtwoord is, niet een bestaand
                                 ============================================ -->
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>Password
                                </label>
                                <input type="password" id="password" name="password"
                                    class="form-control form-control-lg bg-dark text-light border-secondary"
                                    placeholder="Create a strong password" required minlength="8"
                                    autocomplete="new-password" aria-label="Password">
                                <div class="form-text text-muted">
                                    <small>Minimum 8 characters for security</small>
                                </div>
                            </div>

                            <!-- ============================================
                                 SUBMIT BUTTON
                                 ============================================ -->
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </button>
                        </form>

                        <!-- ================================================
                             LOGIN LINK
                             ================================================ -->
                        <p class="text-center mt-3 mb-0">
                            Already have an account?
                            <a href="login.php" class="text-primary text-decoration-none fw-bold">
                                Sign in
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
         JAVASCRIPT BESTANDEN
         ================================================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>