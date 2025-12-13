<?php
// contact.php - Contact Page
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: Simple contact info.

require_once 'functions.php';
checkSessionTimeout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">
    
    <?php include 'header.php'; ?>
    
    <main class="container mt-5 pt-3 mb-5">
        <div class="card p-4">
            <h1 class="text-primary">Contact Us</h1>
            <p class="lead">Have functionality questions or found a bug?</p>
            
            <div class="alert alert-info">
                <strong>Support Email:</strong> <br>
                <a href="mailto:harsha.kanaparthi20062@gmail.com" class="alert-link">harsha.kanaparthi20062@gmail.com</a>
            </div>
            
            <p>We usually respond within 24 hours.</p>
            
            <a href="index.php" class="btn btn-outline-light">Back to Dashboard</a>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>