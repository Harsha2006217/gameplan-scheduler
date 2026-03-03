<?php
/**
 * ==========================================================================
 * LOGIN.PHP - INLOG PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers inloggen op hun GamePlan Scheduler account.
 * Het valideert e-mail en wachtwoord, en maakt een sessie aan bij succes.
 *
 * Functies:
 * - E-mail en wachtwoord validatie
 * - Sessie aanmaken bij succes
 * - Redirect naar dashboard na inloggen
 * - Link naar registratie pagina voor nieuwe gebruikers
 * ==========================================================================
 */

// Laad alle functies (database, validatie, sessie beheer)
require_once 'functions.php';

// Redirect als de gebruiker al ingelogd is
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Initialiseer fout variabele
$error = '';

// Verwerk het formulier als het verzonden is (POST verzoek)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Probeer in te loggen - retourneert foutmelding of null bij succes
    $error = loginUser($email, $password);

    if (!$error) {
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inloggen bij GamePlan Scheduler - Beheer je gaming schema's en evenementen">
    <title>Inloggen - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <div class="container mt-5 pt-5">
        <div class="auth-container">

            <h1 class="text-center mb-4">🎮 Inloggen</h1>

            <!-- Toon foutmelding als inloggen mislukt -->
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo safeEcho($error); ?>
                </div>
            <?php endif; ?>

            <!-- Login formulier -->
            <form method="POST" onsubmit="return validateLoginForm();">

                <!-- E-mail invoerveld -->
                <div class="mb-3">
                    <label for="email" class="form-label">📧 E-mailadres</label>
                    <input type="email" id="email" name="email" class="form-control form-control-lg" required
                        placeholder="jouw@email.com" aria-label="E-mailadres">
                </div>

                <!-- Wachtwoord invoerveld -->
                <div class="mb-4">
                    <label for="password" class="form-label">🔒 Wachtwoord</label>
                    <input type="password" id="password" name="password" class="form-control form-control-lg" required
                        placeholder="Voer je wachtwoord in" aria-label="Wachtwoord">
                </div>

                <!-- Verzend knop -->
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    🚀 Inloggen
                </button>

            </form>

            <!-- Link naar registratie pagina -->
            <p class="text-center mt-4 mb-0">
                Nog geen account?
                <a href="register.php" class="text-info">Registreer hier</a>
            </p>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

</body>

</html>