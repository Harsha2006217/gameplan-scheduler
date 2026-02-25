<?php
/**
 * ============================================================================
 * REGISTER.PHP - GEBRUIKER REGISTRATIEPAGINA
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat nieuwe gebruikers een GamePlan Scheduler-account aanmaken.
 * Het valideert gebruikersnaam, e-mailadres en wachtwoord, en maakt het account aan.
 *
 * BEVEILIGINGSFUNCTIES:
 * - Wachtwoord gehasht met bcrypt (nooit opgeslagen als leesbare tekst)
 * - Controle op uniek e-mailadres (één account per e-mailadres)
 * - Invoervalidatie (BUGFIX #1001: controle op spaties)
 * - Minimale wachtwoordlengte: 8 tekens
 * ============================================================================
 */

require_once 'functions.php';

// Stuur door naar dashboard als al ingelogd
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

// Verwerk het formulier als het is ingediend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';
    
    // Probeer te registreren — retourneert foutmelding of null bij succes
    $error = registerUser($username, $email, $password);
    
    if (!$error) {
        // Sla succesmelding op en stuur door naar loginpagina
        setMessage('success', 'Registratie succesvol! Log nu in.');
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registreren bij GamePlan Scheduler - Maak je gaming-profiel aan">
    <title>Registreren - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    
    <div class="container mt-5 pt-5">
        <div class="auth-container">
            
            <h1 class="text-center mb-4">🎮 Registreren</h1>
            
            <!-- Foutmelding weergeven -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
            <?php endif; ?>
            
            <!-- Registratieformulier -->
            <form method="POST" onsubmit="return validateRegisterForm();">
                
                <!-- Gebruikersnaamveld -->
                <div class="mb-3">
                    <label for="username" class="form-label">
                        👤 Gebruikersnaam
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control form-control-lg" 
                           required 
                           maxlength="50"
                           placeholder="Jouw gamingnaam"
                           aria-label="Gebruikersnaam">
                    <small class="text-secondary">
                        Max. 50 tekens
                    </small>
                </div>
                
                <!-- E-mailadresveld -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        📧 E-mailadres
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control form-control-lg" 
                           required 
                           placeholder="jouw@email.nl"
                           aria-label="E-mailadres">
                    <small class="text-secondary">
                        Gebruikt voor inloggen
                    </small>
                </div>
                
                <!-- Wachtwoordveld -->
                <div class="mb-4">
                    <label for="password" class="form-label">
                        🔒 Wachtwoord
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control form-control-lg" 
                           required 
                           minlength="8"
                           placeholder="Minimaal 8 tekens"
                           aria-label="Wachtwoord">
                    <small class="text-secondary">
                        Minimaal 8 tekens voor veiligheid
                    </small>
                </div>
                
                <!-- Verzendknop -->
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    ✨ Account aanmaken
                </button>
                
            </form>
            
            <!-- Link naar loginpagina voor bestaande gebruikers -->
            <p class="text-center mt-4 mb-0">
                Al een account? 
                <a href="login.php" class="text-info">Log hier in</a>
            </p>
            
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>