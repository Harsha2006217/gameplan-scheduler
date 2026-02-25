<?php
/**
 * ============================================================================
 * EDIT_SCHEDULE.PHP - SCHEMA BEWERKEN PAGINA
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bewerk een bestaand gaming-schema met volledige validatie.
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$id     = $_GET['id'] ?? 0;

if (!is_numeric($id)) {
    header("Location: index.php");
    exit;
}

// Haal het schema op
$schedules = getSchedules($userId);
$schedule  = array_filter($schedules, function ($s) use ($id) {
    return $s['schedule_id'] == $id;
});
$schedule = reset($schedule);

if (!$schedule) {
    setMessage('danger', 'Schema niet gevonden.');
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gameTitle     = $_POST['game_title']      ?? '';
    $date          = $_POST['date']            ?? '';
    $time          = $_POST['time']            ?? '';
    $friendsStr    = $_POST['friends_str']     ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = editSchedule($userId, $id, $gameTitle, $date, $time, $friendsStr, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Schema bijgewerkt!');
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
    <title>Schema bewerken - GamePlan Scheduler</title>
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
            <h2>✏️ Schema bewerken</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST" onsubmit="return validateScheduleForm();">
                        <div class="mb-3">
                            <label for="game_title" class="form-label">🎮 Speltitel *</label>
                            <input type="text" id="game_title" name="game_title" class="form-control" required
                                maxlength="100" value="<?php echo safeEcho($schedule['game_titel']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">📆 Datum *</label>
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>" value="<?php echo safeEcho($schedule['date']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">⏰ Tijd *</label>
                            <input type="time" id="time" name="time" class="form-control" required
                                value="<?php echo safeEcho($schedule['time']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="friends_str" class="form-label">👥 Vrienden</label>
                            <input type="text" id="friends_str" name="friends_str" class="form-control"
                                value="<?php echo safeEcho($schedule['friends']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">👀 Gedeeld met</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                value="<?php echo safeEcho($schedule['shared_with']); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">💾 Opslaan</button>
                        <a href="index.php" class="btn btn-secondary">↩️ Annuleren</a>
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