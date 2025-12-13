<?php
/**
 * ============================================================================
 * login.php - INLOG PAGINA / LOGIN PAGE
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Dit is de inlogpagina waar gebruikers hun email en wachtwoord invoeren
 * om toegang te krijgen tot de applicatie.
 * 
 * BEVEILIGINGSMAATREGELEN:
 * - Wachtwoord wordt geverifieerd met password_verify() (bcrypt)
 * - Sessie ID wordt geregenereerd na inloggen (tegen session fixation)
 * - Foutmeldingen zijn generiek (geen onderscheid email/wachtwoord fout)
 * ============================================================================
 */

// Laad alle functies en start sessie
require_once 'functions.php';

// Als gebruiker al ingelogd is, stuur naar dashboard
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Initialiseer foutmelding variabele
$error = '';

// Verwerk het formulier wanneer het is verzonden (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Haal email en wachtwoord op uit het formulier
    // ?? '' zorgt dat de waarde leeg is als het veld niet bestaat
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Probeer in te loggen via de loginUser functie
    $error = loginUser($email, $password);

    // Als er geen fout is, login was succesvol
    if (!$error) {
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags voor character encoding en responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login to GamePlan Scheduler - Manage your gaming schedule">

    <title>Login - GamePlan Scheduler</title>

    <!-- Bootstrap 5 CSS voor moderne styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS voor dark gaming theme -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light min-vh-100 d-flex align-items-center">

    <!-- Centered container voor login formulier -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <!-- Login Card met glassmorphism effect -->
                <div class="card bg-secondary bg-opacity-25 border-0 shadow-lg">
                    <div class="card-body p-5">

                        <!-- Logo en titel -->
                        <div class="text-center mb-4">
                            <i class="bi bi-controller fs-1 text-primary"></i>
                            <h1 class="h3 mt-3 fw-bold">GamePlan Scheduler</h1>
                            <p class="text-muted">Login to manage your gaming schedule</p>
                        </div>

                        <!-- Toon foutmelding als er een is -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <?php echo safeEcho($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Login Formulier -->
                        <form method="POST" onsubmit="return validateLoginForm();">

                            <!-- Email invoerveld -->
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>Email
                                </label>
                                <input type="email" id="email" name="email"
                                    class="form-control form-control-lg bg-dark text-light border-secondary" required
                                    placeholder="your@email.com" aria-label="Email address">
                            </div>

                            <!-- Wachtwoord invoerveld -->
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>Password
                                </label>
                                <input type="password" id="password" name="password"
                                    class="form-control form-control-lg bg-dark text-light border-secondary" required
                                    placeholder="••••••••" aria-label="Password">
                            </div>

                            <!-- Login knop -->
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>

                        </form>

                        <!-- Link naar registratie pagina -->
                        <p class="text-center mb-0">
                            Don't have an account?
                            <a href="register.php" class="text-primary text-decoration-none fw-bold">Register here</a>
                        </p>

                    </div>
                </div>

                <!-- Copyright footer -->
                <p class="text-center text-muted mt-4 small">
                    © 2025 GamePlan Scheduler by Harsha Kanaparthi
                </p>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS voor interactieve componenten -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript voor form validatie -->
    <script src="script.js"></script>
</body>

</html>