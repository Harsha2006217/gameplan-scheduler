<?php
// privacy.php - Privacy Policy Page
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: explains how user data is handled (GDPR compliance).

require_once 'functions.php';
checkSessionTimeout(); // Ensure session is valid if logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">
    
    <?php include 'header.php'; ?>
    
    <main class="container mt-5 pt-3 mb-5">
        <div class="card p-4">
            <h1 class="text-primary">Privacy Policy</h1>
            <hr>
            <h3>What data do we collect?</h3>
            <p>We store minimal information needed for the application to function:</p>
            <ul>
                <li>Username and Email (for identification)</li>
                <li>Hashed Passwords (security)</li>
                <li>Your gaming preferences and schedules</li>
            </ul>
            
            <h3>How do we protect it?</h3>
            <p>
                All passwords are encrypted using Bcrypt. 
                We use secure database connections (PDO) to prevent unauthorized access.
                Your data is never sold to third parties.
            </p>
            
            <h3>Your Rights</h3>
            <p>
                You have the right to delete your account or modify your data at any time via the Profile page.
            </p>
            
            <a href="index.php" class="btn btn-outline-light mt-3">Back to Home</a>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>