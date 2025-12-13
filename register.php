<?php
// ============================================================================
// REGISTER.PHP - User Registration Page
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This is the REGISTRATION page where new users can create an account.
// It collects username, email, and password, then creates a new user record.
//
// REGISTRATION FLOW:
// 1. User visits register.php → sees registration form
// 2. User fills in username, email, and password
// 3. JavaScript validates form (client-side check)
// 4. Form submitted to same page via POST
// 5. PHP validates and processes (server-side)
// 6. If successful → redirect to login.php with success message
// 7. If error → show error, user can correct and retry
//
// SECURITY FEATURES:
// - Password hashed with bcrypt (never stored plain)
// - Minimum 8 character password requirement
// - Email uniqueness check (no duplicate accounts)
// - Input validation (Bug Fix #1001)
// ============================================================================


// Include core functions (gives us registerUser())
require_once 'functions.php';


// If already logged in, go to dashboard (no need to register)
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}


// Initialize error variable (empty = no error)
$error = '';


// Process form when submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data from POST array
    // ?? '' provides default empty string if value missing
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Attempt registration
    // registerUser() returns null on success, error message on failure
    $error = registerUser($username, $email, $password);
    
    // If no error, registration was successful
    if (!$error) {
        // Set success message for next page
        setMessage('success', 'Registration successful! Please login with your new account.');
        
        // Redirect to login page
        header("Location: login.php");
        exit;
    }
    // Error is shown below if registration failed
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character encoding and viewport for mobile -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Page title -->
    <title>Register - GamePlan Scheduler</title>
    
    <!-- SEO description -->
    <meta name="description" content="Create a free GamePlan Scheduler account to start managing your gaming activities.">
    
    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- Body with dark theme and centered content -->
<body class="bg-dark text-light min-vh-100 d-flex align-items-center">
    
    <!-- Main container -->
    <div class="container py-5">
        
        <!-- Centered row -->
        <div class="row justify-content-center">
            
            <!-- Responsive column width -->
            <div class="col-md-6 col-lg-5">
                
                <!-- Registration card -->
                <div class="card bg-secondary border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        
                        <!-- Logo and title section -->
                        <div class="text-center mb-4">
                            <div class="display-1 mb-3">🎮</div>
                            <h1 class="h4 fw-bold text-white">Create Your Account</h1>
                            <p class="text-muted small">Join the GamePlan community!</p>
                        </div>
                        
                        
                        <!-- Error message display -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo safeEcho($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        
                        <!-- ================================================
                             REGISTRATION FORM
                             ================================================ -->
                        <!-- onsubmit calls JavaScript validation -->
                        <form method="POST" onsubmit="return validateRegisterForm();">
                            
                            <!-- ============================================
                                 USERNAME FIELD
                                 ============================================ -->
                            <!-- username: Display name shown in the app -->
                            <!-- maxlength="50": Must match database limit -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    👤 Username
                                </label>
                                <input type="text" 
                                       id="username" 
                                       name="username" 
                                       class="form-control form-control-lg bg-dark text-light border-secondary"
                                       required 
                                       maxlength="50"
                                       placeholder="Choose a username"
                                       aria-label="Username"
                                       autofocus>
                                <!-- Help text -->
                                <div class="form-text text-muted">
                                    This will be visible to other users
                                </div>
                            </div>
                            
                            
                            <!-- ============================================
                                 EMAIL FIELD
                                 ============================================ -->
                            <!-- type="email": Browser validates email format -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    📧 Email Address
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control form-control-lg bg-dark text-light border-secondary"
                                       required 
                                       placeholder="your.email@example.com"
                                       aria-label="Email address">
                                <div class="form-text text-muted">
                                    Used for login (must be unique)
                                </div>
                            </div>
                            
                            
                            <!-- ============================================
                                 PASSWORD FIELD
                                 ============================================ -->
                            <!-- minlength="8": HTML5 validation for security -->
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    🔑 Password
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control form-control-lg bg-dark text-light border-secondary"
                                       required 
                                       minlength="8"
                                       placeholder="Minimum 8 characters"
                                       aria-label="Password">
                                <div class="form-text text-muted">
                                    At least 8 characters for security
                                </div>
                            </div>
                            
                            
                            <!-- Submit button -->
                            <button type="submit" 
                                    class="btn btn-success btn-lg w-100 py-3 fw-bold rounded-3">
                                ✅ Create Account
                            </button>
                            
                        </form>
                        
                        
                        <!-- Login link for existing users -->
                        <p class="text-center mt-4 mb-0 small">
                            Already have an account? 
                            <a href="login.php" class="text-info text-decoration-none fw-bold">
                                Login here
                            </a>
                        </p>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- JavaScript files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    
</body>
</html>
<!-- Registration page complete -->