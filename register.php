<?php
// register.php - User Registration Page
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: Allows new users to create an account.

require_once 'functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Call registration function
    $error = registerUser($username, $email, $password);
    
    if (!$error) {
        // Success: Redirect to login with success message
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
    <title>Register - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <main class="container d-flex flex-grow-1 align-items-center justify-content-center">
        
        <div class="card p-5" style="max-width: 450px; width: 100%;">
            <div class="text-center mb-4">
                <h1 class="h3">Join the Game</h1>
                <p class="text-muted">Create your profile to start planning</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?php echo safeEcho($error); ?></div>
            <?php endif; ?>

            <form method="POST" onsubmit="return validateRegisterForm();">
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required maxlength="50" placeholder="GamerTag123">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required placeholder="name@example.com">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <!-- minlength handled by JS, but HTML attribute as backup -->
                    <input type="password" id="password" name="password" class="form-control" required minlength="8" placeholder="Min. 8 characters">
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
            </form>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php" class="text-info fw-bold">Login</a></p>
            </div>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>