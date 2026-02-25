<?php
/**
 * ============================================================================
 * EDIT_EVENT.PHP - EVENEMENT BEWERKEN PAGINA
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bewerk een bestaand gaming-evenement met alle velden en volledige validatie.
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

// Haal het evenement op
$events = getEvents($userId);
$event  = array_filter($events, function ($e) use ($id) {
    return $e['event_id'] == $id;
});
$event = reset($event);

if (!$event) {
    setMessage('danger', 'Evenement niet gevonden.');
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title         = $_POST['title']           ?? '';
    $date          = $_POST['date']            ?? '';
    $time          = $_POST['time']            ?? '';
    $description   = $_POST['description']    ?? '';
    $reminder      = $_POST['reminder']        ?? 'none';
    $externalLink  = $_POST['external_link']   ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = editEvent($userId, $id, $title, $date, $time, $description, $reminder, $externalLink, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Evenement bijgewerkt!');
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
    <title>Evenement bewerken - GamePlan Scheduler</title>
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
            <h2>✏️ Evenement bewerken</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST" onsubmit="return validateEventForm();">
                        <div class="mb-3">
                            <label for="title" class="form-label">📌 Titel *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                value="<?php echo safeEcho($event['title']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">📆 Datum *</label>
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>" value="<?php echo safeEcho($event['date']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">⏰ Tijd *</label>
                            <input type="time" id="time" name="time" class="form-control" required
                                value="<?php echo safeEcho($event['time']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">📝 Beschrijving</label>
                            <textarea id="description" name="description" class="form-control" rows="3"
                                maxlength="500"><?php echo safeEcho($event['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="reminder" class="form-label">🔔 Herinnering</label>
                            <select id="reminder" name="reminder" class="form-select">
                                <option value="none"   <?php if ($event['reminder'] === 'none')   echo 'selected'; ?>>Geen</option>
                                <option value="1_hour" <?php if ($event['reminder'] === '1_hour') echo 'selected'; ?>>1 uur ervoor</option>
                                <option value="1_day"  <?php if ($event['reminder'] === '1_day')  echo 'selected'; ?>>1 dag ervoor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="external_link" class="form-label">🔗 Externe link</label>
                            <input type="url" id="external_link" name="external_link" class="form-control"
                                value="<?php echo safeEcho($event['external_link']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">👀 Gedeeld met</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                value="<?php echo safeEcho($event['shared_with']); ?>">
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