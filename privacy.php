<?php
/**
 * ============================================================================
 * PRIVACY.PHP - PRIVACYBELEID PAGINA
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina toont het privacybeleid voor GamePlan Scheduler.
 * Het legt uit welke gegevens worden verzameld en hoe die worden beschermd.
 * Vereist voor AVG/GDPR-naleving.
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
    <title>Privacybeleid - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">🔒 Privacybeleid</h1>

                <h3>📋 Gegevensverzameling</h3>
                <p>Wij verzamelen alleen essentiële informatie:</p>
                <ul>
                    <li><strong>Gebruikersnaam</strong> — Jouw weergavenaam</li>
                    <li><strong>E-mailadres</strong> — Alleen voor inloggen</li>
                    <li><strong>Favoriete spellen</strong> — Spellen die je toevoegt</li>
                    <li><strong>Schema's en evenementen</strong> — Jouw gamingplannen</li>
                </ul>

                <h3 class="mt-4">🔐 Gegevensbeveiliging</h3>
                <ul>
                    <li>Wachtwoorden worden <strong>versleuteld met bcrypt</strong> (nooit als leesbare tekst opgeslagen)</li>
                    <li>Alle databasevragen gebruiken <strong>prepared statements</strong> (bescherming tegen SQL-injectie)</li>
                    <li>Sessies verlopen na <strong>30 minuten inactiviteit</strong></li>
                    <li>Alle uitvoer wordt <strong>geëscaped</strong> om XSS-aanvallen te voorkomen</li>
                </ul>

                <h3 class="mt-4">🚫 Wat wij niet doen</h3>
                <ul>
                    <li>Wij <strong>verkopen nooit</strong> jouw gegevens aan derden</li>
                    <li>Wij delen jouw informatie niet zonder toestemming</li>
                    <li>Wij gebruiken geen trackingcookies of analysetools</li>
                    <li>Wij tonen geen advertenties die overmatig gamen aanmoedigen</li>
                </ul>

                <h3 class="mt-4">🗑️ Jouw rechten</h3>
                <ul>
                    <li>Je kunt <strong>al jouw gegevens inzien</strong> via je profiel en dashboard</li>
                    <li>Je kunt <strong>alle informatie bewerken of verwijderen</strong></li>
                    <li>Je kunt <strong>accountverwijdering aanvragen</strong> via het contactformulier</li>
                </ul>

                <h3 class="mt-4">📬 Contact</h3>
                <p>Voor privacyvragen: <a href="mailto:harsha.kanaparthi20062@gmail.com"
                        class="text-info">harsha.kanaparthi20062@gmail.com</a></p>

                <p class="mt-4 text-secondary">
                    <small>Dit privacybeleid voldoet aan de AVG/GDPR-regelgeving.</small>
                </p>

                <a href="index.php" class="btn btn-primary mt-3">↩️ Terug naar dashboard</a>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>