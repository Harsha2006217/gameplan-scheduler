<?php
/**
 * ============================================================================
 * register.php - REGISTRATIE PAGINA / REGISTRATION PAGE
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Nieuwe gebruikers kunnen hier een account aanmaken.
 * Het formulier vraagt om: gebruikersnaam, email en wachtwoord.
 * 
 * BEVEILIGINGSMAATREGELEN:
 * - Wachtwoord wordt gehashed met bcrypt voordat het wordt opgeslagen
 * - Email moet uniek zijn (geen dubbele accounts)
 * - Wachtwoord moet minimaal 8 karakters zijn
 * - Inputvalidatie voorkomt lege velden of alleen spaties
 * ============================================================================
 */

// Laad alle functies
require_once 'functions.php';

// Als al ingelogd, ga naar dashboard
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

// Verwerk formulier bij POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // registerUser() valideert en slaat nieuwe gebruiker op
    $error = registerUser($username, $email, $password);

    if (!$error) {
        // Registratie succesvol - redirect naar login met succesmelding
        setMessage('success', 'Registration successful! Please login.');
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register for GamePlan Scheduler - Join the gaming community">

    <title>Register - GamePlan Scheduler</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light min-vh-100 d-flex align-items-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <div class="card bg-secondary bg-opacity-25 border-0 shadow-lg">
                    <div class="card-body p-5">

                        <!-- Header -->
                        <div class="text-center mb-4">
                            <i class="bi bi-person-plus fs-1 text-success"></i>
                            <h1 class="h3 mt-3 fw-bold">Create Account</h1>
                            <p class="text-muted">Join GamePlan Scheduler today</p>
                        </div>

                        <!-- Foutmelding -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <?php echo safeEcho($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Registratieformulier -->
                        <form method="POST" onsubmit="return validateRegisterForm();">

                            <!-- Gebruikersnaam -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person me-1"></i>Username
                                </label>
                                <input type="text" id="username" name="username"
                                    class="form-control form-control-lg bg-dark text-light border-secondary" required
                                    maxlength="50" placeholder="Your gamer name" aria-label="Username">
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>Email
                                </label>
                                <input type="email" id="email" name="email"
                                    class="form-control form-control-lg bg-dark text-light border-secondary" required
                                    placeholder="your@email.com" aria-label="Email address">
                            </div>

                            <!-- Wachtwoord -->
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>Password
                                </label>
                                <input type="password" id="password" name="password"
                                    class="form-control form-control-lg bg-dark text-light border-secondary" required
                                    minlength="8" placeholder="Minimum 8 characters" aria-label="Password">
                                <small class="text-muted">Must be at least 8 characters</small>
                            </div>

                            <!-- Register knop -->
                            <button type="submit" class="btn btn-success btn-lg w-100 mb-3">
                                <i class="bi bi-person-check me-2"></i>Create Account
                            </button>

                        </form>

                        <!-- Link naar login -->
                        <p class="text-center mb-0">
                            Already have an account?
                            <a href="login.php" class="text-primary text-decoration-none fw-bold">Login here</a>
                        </p>

                    </div>
                </div>

                <p class="text-center text-muted mt-4 small">
                    © 2025 GamePlan Scheduler by Harsha Kanaparthi
                </p>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>