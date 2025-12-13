<?php
/**
 * ============================================================================
 * ADD_SCHEDULE.PHP - ADD SCHEDULE PAGE / SCHEMA TOEVOEGEN PAGINA
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This page allows users to add gaming play schedules.
 * Users can specify game, date, time, and friends joining.
 * Includes validation for BUG FIX #1001 (spaces) and #1004 (dates).
 * 
 * DUTCH:
 * Deze pagina laat gebruikers gaming speelschema's toevoegen.
 * Gebruikers kunnen spel, datum, tijd, en meespelende vrienden opgeven.
 * Bevat validatie voor BUG FIX #1001 (spaties) en #1004 (datums).
 * 
 * USER STORY: "Share play schedules with friends in a calendar"
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
    $gameTitle = $_POST['game_title'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $friendsStr = $_POST['friends_str'] ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = addSchedule($userId, $gameTitle, $date, $time, $friendsStr, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Schedule added! / Schema toegevoegd!');
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
    <title>Add Schedule - GamePlan Scheduler</title>
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
            <h2>📅 Add Schedule / Schema Toevoegen</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST" onsubmit="return validateScheduleForm();">

                        <!-- GAME TITLE -->
                        <div class="mb-3">
                            <label for="game_title" class="form-label">🎮 Game Title / Speltitel *</label>
                            <input type="text" id="game_title" name="game_title" class="form-control" required
                                maxlength="100" placeholder="Which game will you play?">
                            <small class="text-secondary">Cannot be empty or spaces only / Mag niet leeg of alleen
                                spaties zijn (BUG FIX #1001)</small>
                        </div>

                        <!-- DATE -->
                        <div class="mb-3">
                            <label for="date" class="form-label">📆 Date / Datum *</label>
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>">
                            <small class="text-secondary">Must be today or future / Moet vandaag of toekomst zijn (BUG
                                FIX #1004)</small>
                        </div>

                        <!-- TIME -->
                        <div class="mb-3">
                            <label for="time" class="form-label">⏰ Time / Tijd *</label>
                            <input type="time" id="time" name="time" class="form-control" required>
                        </div>

                        <!-- FRIENDS -->
                        <div class="mb-3">
                            <label for="friends_str" class="form-label">👥 Friends Joining / Meespelende
                                Vrienden</label>
                            <input type="text" id="friends_str" name="friends_str" class="form-control"
                                placeholder="player1, player2, player3">
                            <small class="text-secondary">Comma-separated usernames / Komma-gescheiden
                                gebruikersnamen</small>
                        </div>

                        <!-- SHARED WITH -->
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">👀 Shared With / Gedeeld Met</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                placeholder="user1, user2">
                            <small class="text-secondary">Who can see this schedule / Wie kan dit schema zien</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">➕ Add Schedule / Schema Toevoegen</button>
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