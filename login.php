<?php
// login.php - User Login Page
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: 
// This is the entry point for existing users.
// It checks credentials against the database and starts a secure session.

/* --- Logic Section --- */
require_once 'functions.php';

// If user is already logged in, redirect them to Dashboard instantly.
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

// Handle Form Submission (POST Request)
// This code runs when the user clicks "Login".
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect inputs using Null Coalescing Operator (??) to prevent warnings.
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Attempt login via generic function in functions.php
    $error = loginUser($email, $password);
    
    // If no error string returned, login was successful.
    if (!$error) {
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GamePlan Scheduler</title>
    <!-- Use Bootstrap 5 via CDN for layout -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS for Legendary Theme -->
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Container to center the content -->
    <main class="container d-flex flex-grow-1 align-items-center justify-content-center">
        
        <!-- Glassmorphism Card -->
        <div class="card p-5" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <h1 class="h3">Welcome Back</h1>
                <p class="text-muted">Login to manage your schedule</p>
            </div>

            <!-- Error Notification -->
            <?php if ($error): ?>
                <div class="alert alert-danger text-center">
                    <?php echo safeEcho($error); ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <!-- onsubmit executes JS validation before sending data -->
            <form method="POST" onsubmit="return validateLoginForm();">
                
                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="user@example.com" required aria-label="Email address">
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="********" required aria-label="Password">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
            </form>

            <div class="text-center mt-3">
                <p>New player? <a href="register.php" class="text-info fw-bold">Create Account</a></p>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>