<?php
/**
 * ============================================================================
 * REGISTER.PHP - USER REGISTRATION PAGE / GEBRUIKER REGISTRATIE PAGINA
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This page allows new users to create a GamePlan Scheduler account.
 * It validates username, email, and password, then creates the account.
 * 
 * DUTCH:
 * Deze pagina laat nieuwe gebruikers een GamePlan Scheduler account aanmaken.
 * Het valideert gebruikersnaam, e-mail, en wachtwoord, dan maakt het account.
 * 
 * SECURITY FEATURES:
 * - Password hashed with bcrypt (never stored as plain text)
 * - Email uniqueness check (one account per email)
 * - Input validation (BUG FIX #1001: spaces check)
 * - Minimum password length: 8 characters
 * ============================================================================
 */

require_once 'functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Attempt registration - returns error or null on success
    $error = registerUser($username, $email, $password);
    
    if (!$error) {
        // Set success message and redirect to login
        setMessage('success', 'Registration successful! Please login. / Registratie succesvol! Log alsjeblieft in.');
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
    <meta name="description" content="Register for GamePlan Scheduler - Create your gaming profile">
    <title>Register - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    
    <div class="container mt-5 pt-5">
        <div class="auth-container">
            
            <h1 class="text-center mb-4">🎮 Register / Registreren</h1>
            
            <!-- Error message display -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
            <?php endif; ?>
            
            <!-- Registration form -->
            <form method="POST" onsubmit="return validateRegisterForm();">
                
                <!-- USERNAME FIELD -->
                <div class="mb-3">
                    <label for="username" class="form-label">
                        👤 Username / Gebruikersnaam
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control form-control-lg" 
                           required 
                           maxlength="50"
                           placeholder="Your gaming name"
                           aria-label="Username">
                    <small class="text-secondary">
                        Max 50 characters / Max 50 tekens
                    </small>
                </div>
                
                <!-- EMAIL FIELD -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        📧 Email Address / E-mailadres
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control form-control-lg" 
                           required 
                           placeholder="your@email.com"
                           aria-label="Email address">
                    <small class="text-secondary">
                        Used for login / Gebruikt voor inloggen
                    </small>
                </div>
                
                <!-- PASSWORD FIELD -->
                <div class="mb-4">
                    <label for="password" class="form-label">
                        🔒 Password / Wachtwoord
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control form-control-lg" 
                           required 
                           minlength="8"
                           placeholder="Minimum 8 characters"
                           aria-label="Password">
                    <small class="text-secondary">
                        Minimum 8 characters for security / Minimaal 8 tekens voor veiligheid
                    </small>
                </div>
                
                <!-- SUBMIT BUTTON -->
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    ✨ Create Account / Account Aanmaken
                </button>
                
            </form>
            
            <!-- Login link for existing users -->
            <p class="text-center mt-4 mb-0">
                Already have an account? / Al een account? 
                <a href="login.php" class="text-info">Login here / Log hier in</a>
            </p>
            
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>