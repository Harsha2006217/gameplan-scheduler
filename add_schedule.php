<?php
/**
 * ============================================================================
 * ADD_SCHEDULE.PHP - SCHEMA TOEVOEGEN PAGINA
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers gaming-speelschema's toevoegen.
 * Gebruikers kunnen spel, datum, tijd en meespelende vrienden opgeven.
 * Validatie bevat BUGFIX #1001 (spaties) en BUGFIX #1004 (datums).
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$error  = '';

// Verwerk het formulier als het is ingediend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gameTitle     = $_POST['game_title']      ?? '';
    $date          = $_POST['date']            ?? '';
    $time          = $_POST['time']            ?? '';
    $friendsStr    = $_POST['friends_str']     ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = addSchedule($userId, $gameTitle, $date, $time, $friendsStr, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Schema toegevoegd!');
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
    <title>Schema toevoegen - GamePlan Scheduler</title>
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
            <h2>📅 Schema toevoegen</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST" onsubmit="return validateScheduleForm();">

                        <!-- Speltitel -->
                        <div class="mb-3">
                            <label for="game_title" class="form-label">🎮 Speltitel *</label>
                            <input type="text" id="game_title" name="game_title" class="form-control" required
                                maxlength="100" placeholder="Welk spel ga je spelen?">
                            <small class="text-secondary">Mag niet leeg of alleen spaties zijn (BUGFIX #1001)</small>
                        </div>

                        <!-- Datum -->
                        <div class="mb-3">
                            <label for="date" class="form-label">📆 Datum *</label>
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>">
                            <small class="text-secondary">Moet vandaag of in de toekomst zijn (BUGFIX #1004)</small>
                        </div>

                        <!-- Tijd -->
                        <div class="mb-3">
                            <label for="time" class="form-label">⏰ Tijd *</label>
                            <input type="time" id="time" name="time" class="form-control" required>
                        </div>

                        <!-- Meespelende vrienden -->
                        <div class="mb-3">
                            <label for="friends_str" class="form-label">👥 Meespelende vrienden</label>
                            <input type="text" id="friends_str" name="friends_str" class="form-control"
                                placeholder="speler1, speler2, speler3">
                            <small class="text-secondary">Kommagescheiden gebruikersnamen</small>
                        </div>

                        <!-- Gedeeld met -->
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">👀 Gedeeld met</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                placeholder="gebruiker1, gebruiker2">
                            <small class="text-secondary">Wie kan dit schema zien</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">➕ Schema toevoegen</button>
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