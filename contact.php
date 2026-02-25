<?php
/**
 * ============================================================================
 * CONTACT.PHP - CONTACTPAGINA
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Contactinformatiepagina voor ondersteuning en vragen over het project.
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();
?>
<!DOCTYPE html>
<html lang="nl">
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
                <h1 class="mb-4">📬 Neem contact op</h1>

                <h3>📧 E-mailadres</h3>
                <p>Voor ondersteuning of vragen, stuur een e-mail naar:</p>
                <p class="h5">
                    <a href="mailto:harsha.kanaparthi20062@gmail.com" class="text-info">
                        harsha.kanaparthi20062@gmail.com
                    </a>
                </p>

                <h3 class="mt-4">👨‍💻 Ontwikkelaar</h3>
                <ul>
                    <li><strong>Naam:</strong> Harsha Kanaparthi</li>
                    <li><strong>Studentnummer:</strong> 2195344</li>
                    <li><strong>Opleiding:</strong> MBO-4 Software Development</li>
                    <li><strong>Project:</strong> GamePlan Scheduler</li>
                </ul>

                <h3 class="mt-4">🔗 GitHub Repository</h3>
                <p>
                    <a href="https://github.com/Harsha2006217/GamePlan-Scheduler" target="_blank" class="text-info">
                        github.com/Harsha2006217/GamePlan-Scheduler
                    </a>
                </p>

                <h3 class="mt-4">⚡ Veelgestelde vragen</h3>
                <ul>
                    <li><strong>Inlogproblemen?</strong> Controleer of je het juiste e-mailadres hebt gebruikt bij de registratie.</li>
                    <li><strong>Wachtwoord vergeten?</strong> Neem contact op voor een reset.</li>
                    <li><strong>Bug gevonden?</strong> Stuur de details naar bovenstaand e-mailadres.</li>
                </ul>

                <a href="index.php" class="btn btn-primary mt-3">↩️ Terug naar dashboard</a>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>