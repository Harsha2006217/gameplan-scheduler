<?php
// This file is contact.php - A simple page with contact info.
// Author: Harsha Kanaparthi.
// Date: Improved on 10-12-2025.
// Description: Shows email for support. Includes header/footer for consistent look.
// Improvements: Made it responsive, added Bootstrap card for beauty, no bugs as it's static.

require_once 'functions.php';

checkSessionTimeout();

$error = ''; // No error needed here, but kept for consistency.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    <?php include 'header.php'; ?>
    <main class="container mt-5 pt-5">
        <?php echo getMessage(); ?>
        <div class="card bg-secondary border-0 rounded-3"> <!-- Card for nice box look. -->
            <div class="card-body">
                <h1>Contact Us</h1>
                <p>For support or inquiries, email us at harsha.kanaparthi20062@gmail.com.</p>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>