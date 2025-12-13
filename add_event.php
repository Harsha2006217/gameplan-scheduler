<?php
/**
 * ============================================================================
 * ADD_EVENT.PHP - ADD EVENT PAGE / EVENEMENT TOEVOEGEN PAGINA
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This page allows users to add gaming events (tournaments, streams, etc.).
 * Includes reminder settings, external links, and sharing options.
 * Validation includes BUG FIX #1001 (spaces) and #1004 (dates).
 * 
 * DUTCH:
 * Deze pagina laat gebruikers gaming evenementen toevoegen (toernooien, streams, etc.).
 * Bevat herinnering instellingen, externe links, en deel opties.
 * Validatie bevat BUG FIX #1001 (spaties) en #1004 (datums).
 * 
 * USER STORY: "Add events like tournaments"
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $description = $_POST['description'] ?? '';
    $reminder = $_POST['reminder'] ?? 'none';
    $externalLink = $_POST['external_link'] ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Event added! / Evenement toegevoegd!');
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event - GamePlan Scheduler</title>
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
            <h2>🎯 Add Event / Evenement Toevoegen</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST" onsubmit="return validateEventForm();">

                        <!-- TITLE -->
                        <div class="mb-3">
                            <label for="title" class="form-label">📌 Event Title / Evenement Titel *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                placeholder="Fortnite Tournament, etc.">
                            <small class="text-secondary">Max 100 characters, cannot be empty (BUG FIX #1001)</small>
                        </div>

                        <!-- DATE -->
                        <div class="mb-3">
                            <label for="date" class="form-label">📆 Date / Datum *</label>
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>">
                            <small class="text-secondary">Must be valid and in future (BUG FIX #1004)</small>
                        </div>

                        <!-- TIME -->
                        <div class="mb-3">
                            <label for="time" class="form-label">⏰ Time / Tijd *</label>
                            <input type="time" id="time" name="time" class="form-control" required>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="mb-3">
                            <label for="description" class="form-label">📝 Description / Beschrijving</label>
                            <textarea id="description" name="description" class="form-control" rows="3" maxlength="500"
                                placeholder="Event details..."></textarea>
                            <small class="text-secondary">Max 500 characters / Max 500 tekens</small>
                        </div>

                        <!-- REMINDER -->
                        <div class="mb-3">
                            <label for="reminder" class="form-label">🔔 Reminder / Herinnering</label>
                            <select id="reminder" name="reminder" class="form-select">
                                <option value="none">None / Geen</option>
                                <option value="1_hour">1 Hour Before / 1 Uur Ervoor</option>
                                <option value="1_day">1 Day Before / 1 Dag Ervoor</option>
                            </select>
                        </div>

                        <!-- EXTERNAL LINK -->
                        <div class="mb-3">
                            <label for="external_link" class="form-label">🔗 External Link / Externe Link
                                (optional)</label>
                            <input type="url" id="external_link" name="external_link" class="form-control"
                                placeholder="https://tournament-page.com">
                        </div>

                        <!-- SHARED WITH -->
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">👀 Shared With / Gedeeld Met</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                placeholder="user1, user2">
                        </div>

                        <button type="submit" class="btn btn-success btn-lg">🎯 Add Event / Evenement Toevoegen</button>
                        <a href="index.php" class="btn btn-secondary btn-lg">↩️ Cancel / Annuleren</a>

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