<?php
/**
 * ============================================================================
 * PRIVACY.PHP - PRIVACY POLICY PAGE / PRIVACY BELEID PAGINA
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This page displays the privacy policy for GamePlan Scheduler.
 * It explains what data is collected and how it's protected.
 * Required for GDPR/AVG compliance.
 * 
 * DUTCH:
 * Deze pagina toont het privacy beleid voor GamePlan Scheduler.
 * Het legt uit welke data verzameld wordt en hoe het beschermd wordt.
 * Vereist voor GDPR/AVG naleving.
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
    <title>Privacy Policy - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">🔒 Privacy Policy / Privacy Beleid</h1>

                <h3>📋 Data Collection / Data Verzameling</h3>
                <p>We collect only essential information:</p>
                <ul>
                    <li><strong>Username</strong> - Your display name / Jouw weergavenaam</li>
                    <li><strong>Email</strong> - For login only / Alleen voor inloggen</li>
                    <li><strong>Favorite Games</strong> - Games you add / Spellen die je toevoegt</li>
                    <li><strong>Schedules & Events</strong> - Your gaming plans / Jouw gaming plannen</li>
                </ul>

                <h3 class="mt-4">🔐 Data Security / Data Beveiliging</h3>
                <ul>
                    <li>Passwords are <strong>encrypted with bcrypt</strong> (never stored as plain text)</li>
                    <li>All database queries use <strong>prepared statements</strong> (SQL injection protection)</li>
                    <li>Sessions expire after <strong>30 minutes of inactivity</strong></li>
                    <li>All output is <strong>escaped</strong> to prevent XSS attacks</li>
                </ul>

                <h3 class="mt-4">🚫 What We Don't Do / Wat We Niet Doen</h3>
                <ul>
                    <li>We <strong>never sell</strong> your data to third parties</li>
                    <li>We don't share your information without permission</li>
                    <li>We don't use tracking cookies or analytics</li>
                    <li>We don't display ads that encourage excessive gaming</li>
                </ul>

                <h3 class="mt-4">🗑️ Your Rights / Jouw Rechten</h3>
                <ul>
                    <li>You can <strong>view all your data</strong> on your profile and dashboard</li>
                    <li>You can <strong>edit or delete</strong> any of your information</li>
                    <li>You can request <strong>account deletion</strong> by contacting us</li>
                </ul>

                <h3 class="mt-4">📬 Contact</h3>
                <p>For privacy questions: <a href="mailto:harsha.kanaparthi20062@gmail.com"
                        class="text-info">harsha.kanaparthi20062@gmail.com</a></p>

                <p class="mt-4 text-secondary">
                    <small>This privacy policy complies with AVG/GDPR regulations. / Dit privacy beleid voldoet aan
                        AVG/GDPR regelgeving.</small>
                </p>

                <a href="index.php" class="btn btn-primary mt-3">↩️ Back to Dashboard / Terug naar Dashboard</a>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>