<?php
/**
 * ============================================================================
 * privacy.php - PRIVACY POLICY PAGINA
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Toont de privacy policy van de applicatie.
 * Dit is belangrijk voor AVG/GDPR compliance.
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="bi bi-shield-check me-2"></i>Privacy Policy</h4>
                    </div>
                    <div class="card-body">
                        <h5>Data We Collect</h5>
                        <p>We store only essential information for the app to function:</p>
                        <ul>
                            <li><strong>Username & Email</strong> - For account identification and login</li>
                            <li><strong>Favorite Games</strong> - To personalize your experience</li>
                            <li><strong>Schedules & Events</strong> - To manage your gaming calendar</li>
                            <li><strong>Friends List</strong> - To coordinate gaming sessions</li>
                        </ul>

                        <h5>Security Measures</h5>
                        <ul>
                            <li>Passwords are hashed using bcrypt (never stored in plain text)</li>
                            <li>Session timeout after 30 minutes of inactivity</li>
                            <li>SQL injection protection via prepared statements</li>
                            <li>XSS protection via output escaping</li>
                        </ul>

                        <h5>Your Rights</h5>
                        <ul>
                            <li>You can view all your data at any time</li>
                            <li>You can edit or delete your data</li>
                            <li>Your data is never sold to third parties</li>
                            <li>Your data is never shared without your consent</li>
                        </ul>

                        <h5>Contact</h5>
                        <p>For privacy questions, contact:
                            <a href="mailto:harsha.kanaparthi20062@gmail.com" class="text-info">
                                harsha.kanaparthi20062@gmail.com
                            </a>
                        </p>

                        <p class="text-muted small mt-4">
                            This policy complies with AVG/GDPR regulations. Last updated: 30-09-2025.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>