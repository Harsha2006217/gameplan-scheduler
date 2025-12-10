<?php
// This file is privacy.php - Privacy policy.
// Author: Harsha Kanaparthi.
// Date: Improved on 10-12-2025.
// Description: Static text.

require_once 'functions.php';

checkSessionTimeout();
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
<body class="bg-dark text-light">
    <?php include 'header.php'; ?>
    <main class="container mt-5 pt-5">
        <div class="card bg-secondary border-0 rounded-3">
            <div class="card-body">
                <h1>Privacy Policy</h1>
                <p>We store only your name, email, favorite games, and schedules for planning purposes. Data is not sold or shared without permission. Passwords are hashed and secure. You can delete your data via profile settings. This complies with AVG/GDPR regulations.</p>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>