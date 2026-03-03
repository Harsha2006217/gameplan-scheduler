<?php
/**
 * ==========================================================================
 * ADD_EVENT.PHP - EVENEMENT TOEVOEGEN PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers gaming evenementen toevoegen
 * (toernooien, streams, etc.). Bevat herinnering instellingen,
 * externe links en deel opties.
 * Bevat validatie voor BUG FIX #1001 (spaties) en #1004 (datums).
 *
 * Gebruikersverhaal: "Voeg evenementen toe zoals toernooien"
 * ==========================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$error = '';

// Verwerk formulier verzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titel = $_POST['title'] ?? '';
    $datum = $_POST['date'] ?? '';
    $tijd = $_POST['time'] ?? '';
    $beschrijving = $_POST['description'] ?? '';
    $herinnering = $_POST['reminder'] ?? 'none';
    $externeLink = $_POST['external_link'] ?? '';
    $gedeeldMetStr = $_POST['shared_with_str'] ?? '';

    $error = addEvent($userId, $titel, $datum, $tijd, $beschrijving, $herinnering, $externeLink, $gedeeldMetStr);

    if (!$error) {
        setMessage('success', 'Evenement toegevoegd!');
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenement Toevoegen - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5">
        <?php echo getMessage(); ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
        <?php endif; ?>

        <section class="mb-5">
            <h2>🎯 Evenement Toevoegen</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST" onsubmit="return validateEventForm();">

                        <!-- Evenement titel -->
                        <div class="mb-3">
                            <label for="title" class="form-label">📌 Evenement Titel *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                placeholder="Fortnite Toernooi, etc.">
                            <small class="text-secondary">Max 100 tekens, mag niet leeg zijn (BUG FIX #1001)</small>
                        </div>

                        <!-- Datum -->
                        <div class="mb-3">
                            <label for="date" class="form-label">📆 Datum *</label>
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>">
                            <small class="text-secondary">Moet vandaag of in de toekomst zijn (BUG FIX #1004)</small>
                        </div>

                        <!-- Tijd -->
                        <div class="mb-3">
                            <label for="time" class="form-label">⏰ Tijd *</label>
                            <input type="time" id="time" name="time" class="form-control" required>
                        </div>

                        <!-- Beschrijving -->
                        <div class="mb-3">
                            <label for="description" class="form-label">📝 Beschrijving</label>
                            <textarea id="description" name="description" class="form-control" rows="3" maxlength="500"
                                placeholder="Details over het evenement..."></textarea>
                            <small class="text-secondary">Max 500 tekens</small>
                        </div>

                        <!-- Herinnering -->
                        <div class="mb-3">
                            <label for="reminder" class="form-label">🔔 Herinnering</label>
                            <select id="reminder" name="reminder" class="form-select">
                                <option value="none">Geen</option>
                                <option value="1_hour">1 uur ervoor</option>
                                <option value="1_day">1 dag ervoor</option>
                            </select>
                        </div>

                        <!-- Externe link -->
                        <div class="mb-3">
                            <label for="external_link" class="form-label">🔗 Externe Link (optioneel)</label>
                            <input type="url" id="external_link" name="external_link" class="form-control"
                                placeholder="https://toernooi-pagina.nl">
                        </div>

                        <!-- Gedeeld met -->
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">👀 Gedeeld Met</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                placeholder="gebruiker1, gebruiker2">
                            <small class="text-secondary">Wie kan dit evenement zien</small>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg">🎯 Evenement Toevoegen</button>
                        <a href="index.php" class="btn btn-secondary btn-lg">↩️ Annuleren</a>

                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>