<?php
/**
 * ============================================================================
 * EDIT_EVENT.PHP - EDIT EVENT PAGE / EVENEMENT BEWERKEN PAGINA
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH: Edit existing gaming event with all fields and validation.
 * DUTCH: Bewerk bestaand gaming evenement met alle velden en validatie.
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$id = $_GET['id'] ?? 0;

if (!is_numeric($id)) {
    header("Location: index.php");
    exit;
}

$events = getEvents($userId);
$event = array_filter($events, function ($e) use ($id) {
    return $e['event_id'] == $id; });
$event = reset($event);

if (!$event) {
    setMessage('danger', 'Event not found. / Evenement niet gevonden.');
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $description = $_POST['description'] ?? '';
    $reminder = $_POST['reminder'] ?? 'none';
    $externalLink = $_POST['external_link'] ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = editEvent($userId, $id, $title, $date, $time, $description, $reminder, $externalLink, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Event updated! / Evenement bijgewerkt!');
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
    <title>Edit Event - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5">
        <?php echo getMessage(); ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div><?php endif; ?>

        <section class="mb-5">
            <h2>✏️ Edit Event / Evenement Bewerken</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST" onsubmit="return validateEventForm();">
                        <div class="mb-3">
                            <label for="title" class="form-label">📌 Title / Titel *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                value="<?php echo safeEcho($event['title']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">📆 Date / Datum *</label>
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>" value="<?php echo safeEcho($event['date']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">⏰ Time / Tijd *</label>
                            <input type="time" id="time" name="time" class="form-control" required
                                value="<?php echo safeEcho($event['time']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">📝 Description / Beschrijving</label>
                            <textarea id="description" name="description" class="form-control" rows="3"
                                maxlength="500"><?php echo safeEcho($event['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="reminder" class="form-label">🔔 Reminder / Herinnering</label>
                            <select id="reminder" name="reminder" class="form-select">
                                <option value="none" <?php if ($event['reminder'] === 'none')
                                    echo 'selected'; ?>>None /
                                    Geen</option>
                                <option value="1_hour" <?php if ($event['reminder'] === '1_hour')
                                    echo 'selected'; ?>>1
                                    Hour Before / 1 Uur Ervoor</option>
                                <option value="1_day" <?php if ($event['reminder'] === '1_day')
                                    echo 'selected'; ?>>1 Day
                                    Before / 1 Dag Ervoor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="external_link" class="form-label">🔗 External Link / Externe Link</label>
                            <input type="url" id="external_link" name="external_link" class="form-control"
                                value="<?php echo safeEcho($event['external_link']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">👀 Shared With / Gedeeld Met</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                value="<?php echo safeEcho($event['shared_with']); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">💾 Update / Bijwerken</button>
                        <a href="index.php" class="btn btn-secondary">↩️ Cancel / Annuleren</a>
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