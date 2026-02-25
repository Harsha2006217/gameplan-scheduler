<?php
/**
 * ============================================================================
 * LOGIN.PHP - GEBRUIKER INLOGPAGINA
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers inloggen op hun GamePlan Scheduler-account.
 * Het valideert e-mailadres en wachtwoord, en maakt een sessie aan bij succes.
 *
 * FUNCTIES:
 * - Validatie van e-mailadres en wachtwoord
 * - Sessie aanmaken bij geslaagde inlog
 * - Doorverwijzing naar dashboard na inloggen
 * - Link naar registratiepagina voor nieuwe gebruikers
 * ============================================================================
 */

// Laad alle functies (database, validatie, sessiebeheer)
require_once 'functions.php';

// Veiligheidscontrole: stuur door naar dashboard als al ingelogd
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Initialiseer foutvariabele voor het weergeven van validatiefouten
$error = '';

// Verwerk het formulier als het is ingediend (POST-verzoek)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Haal formulierwaarden op (null coalescing: ?? '' geeft lege string als niet ingesteld)
    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';
    
    // Probeer de gebruiker in te loggen — retourneert foutmelding of null bij succes
    $error = loginUser($email, $password);
    
    // Bij geen fout is het inloggen gelukt
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
    <meta name="description" content="Inloggen bij GamePlan Scheduler - Beheer je gaming-schema's en evenementen">
    <title>Inloggen - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    
    <div class="container mt-5 pt-5">
        
        <!-- Authenticatiecontainer met glassmorphism-effect -->
        <div class="auth-container">
            
            <h1 class="text-center mb-4">🎮 Inloggen</h1>
            
            <!-- Foutmelding weergeven als het inloggen mislukt -->
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo safeEcho($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Inlogformulier: method="POST" verzendt gegevens veilig (niet in de URL) -->
            <form method="POST" onsubmit="return validateLoginForm();">
                
                <!-- E-mailadresveld -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        📧 E-mailadres
                    </label>
                    <!-- type="email": browser valideert het e-mailformaat -->
                    <!-- required: veld moet ingevuld zijn voor verzending -->
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control form-control-lg" 
                           required 
                           placeholder="jouw@email.nl"
                           aria-label="E-mailadres">
                </div>
                
                <!-- Wachtwoordveld -->
                <div class="mb-4">
                    <label for="password" class="form-label">
                        🔒 Wachtwoord
                    </label>
                    <!-- type="password": toont tekens als stippen -->
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control form-control-lg" 
                           required 
                           placeholder="Voer je wachtwoord in"
                           aria-label="Wachtwoord">
                </div>
                
                <!-- Verzendknop: btn-lg voor mobiele bruikbaarheid, w-100 voor volledige breedte -->
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    🚀 Inloggen
                </button>
                
            </form>
            
            <!-- Link naar registratiepagina voor nieuwe gebruikers -->
            <p class="text-center mt-4 mb-0">
                Nog geen account? 
                <a href="register.php" class="text-info">
                    Registreer hier
                </a>
            </p>
            
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    
</body>
</html>
<?php
/**
 * ============================================================================
 * EINDE VAN LOGIN.PHP
 * ============================================================================
 */
?>