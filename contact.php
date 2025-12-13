<?php
/**
 * ============================================================================
 * contact.php - CONTACT PAGINA
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Toont contactinformatie voor support of vragen.
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-envelope me-2"></i>Contact Us</h4>
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-headset display-1 text-primary mb-4"></i>

                        <h5>Need Help?</h5>
                        <p class="mb-4">For support, feature requests, or bug reports, reach out to us!</p>

                        <div class="mb-4">
                            <h6><i class="bi bi-envelope-fill me-2"></i>Email</h6>
                            <a href="mailto:harsha.kanaparthi20062@gmail.com" class="btn btn-outline-primary btn-lg">
                                harsha.kanaparthi20062@gmail.com
                            </a>
                        </div>

                        <div class="mb-4">
                            <h6><i class="bi bi-github me-2"></i>GitHub</h6>
                            <a href="https://github.com/Harsha2006217/GamePlan-Scheduler" target="_blank"
                                class="btn btn-outline-light btn-lg">
                                View on GitHub
                            </a>
                        </div>

                        <hr class="my-4">

                        <p class="text-muted">
                            <strong>Developer:</strong> Harsha Kanaparthi<br>
                            <strong>Student Number:</strong> 2195344<br>
                            <strong>Project:</strong> MBO-4 Software Development
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