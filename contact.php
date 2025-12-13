<?php
/**
 * ============================================================================
 * CONTACT.PHP - CONTACT PAGE / CONTACT PAGINA
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH: Contact information page for support and inquiries.
 * DUTCH: Contact informatie pagina voor ondersteuning en vragen.
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();
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
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">📬 Contact Us / Neem Contact Op</h1>

                <h3>📧 Email</h3>
                <p>For support or inquiries, email us at:</p>
                <p class="h5">
                    <a href="mailto:harsha.kanaparthi20062@gmail.com" class="text-info">
                        harsha.kanaparthi20062@gmail.com
                    </a>
                </p>

                <h3 class="mt-4">👨‍💻 Developer / Ontwikkelaar</h3>
                <ul>
                    <li><strong>Name:</strong> Harsha Kanaparthi</li>
                    <li><strong>Student Number:</strong> 2195344</li>
                    <li><strong>Course:</strong> MBO-4 Software Development</li>
                    <li><strong>Project:</strong> GamePlan Scheduler</li>
                </ul>

                <h3 class="mt-4">🔗 GitHub Repository</h3>
                <p>
                    <a href="https://github.com/Harsha2006217/GamePlan-Scheduler" target="_blank" class="text-info">
                        github.com/Harsha2006217/GamePlan-Scheduler
                    </a>
                </p>

                <h3 class="mt-4">⚡ Quick Help / Snelle Hulp</h3>
                <ul>
                    <li><strong>Login issues?</strong> Make sure you registered with the correct email.</li>
                    <li><strong>Forgot password?</strong> Contact us for a reset.</li>
                    <li><strong>Bug report?</strong> Send details to the email above.</li>
                </ul>

                <a href="index.php" class="btn btn-primary mt-3">↩️ Back to Dashboard / Terug naar Dashboard</a>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>