<?php
/**
 * ============================================================================
 * edit_event.php - EVENEMENT BEWERKEN PAGINA
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Formulier om een bestaand evenement te bewerken (User Story 6: Edit/Delete).
 * Laadt de bestaande data en laat de gebruiker wijzigen.
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

// Valideer ID
if (!is_numeric($id)) {
    header("Location: index.php");
    exit;
}

// Haal event op en controleer eigenaarschap
$events = getEvents($userId);
$event = array_filter($events, function ($e) use ($id) {
    return $e['event_id'] == $id; });
$event = reset($event);

if (!$event) {
    setMessage('danger', 'Event not found or no permission.');
    header("Location: index.php");
    exit;
}

$error = '';

// Formulier verwerking
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
        setMessage('success', 'Event updated successfully!');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-4">
        <?php echo getMessage(); ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div><?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Event</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" onsubmit="return validateEventForm();">
                            <div class="mb-3">
                                <label for="title" class="form-label">Event Title *</label>
                                <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                    value="<?php echo safeEcho($event['title']); ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date *</label>
                                    <input type="date" id="date" name="date" class="form-control" required
                                        value="<?php echo safeEcho($event['date']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="time" class="form-label">Time *</label>
                                    <input type="time" id="time" name="time" class="form-control" required
                                        value="<?php echo safeEcho($event['time']); ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3"
                                    maxlength="500"><?php echo safeEcho($event['description']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="reminder" class="form-label">Reminder</label>
                                <select id="reminder" name="reminder" class="form-select">
                                    <option value="none" <?php if ($event['reminder'] == 'none')
                                        echo 'selected'; ?>>No
                                        reminder</option>
                                    <option value="1_hour" <?php if ($event['reminder'] == '1_hour')
                                        echo 'selected'; ?>>1
                                        Hour Before</option>
                                    <option value="1_day" <?php if ($event['reminder'] == '1_day')
                                        echo 'selected'; ?>>1
                                        Day Before</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="external_link" class="form-label">External Link</label>
                                <input type="url" id="external_link" name="external_link" class="form-control"
                                    value="<?php echo safeEcho($event['external_link']); ?>">
                            </div>
                            <div class="mb-4">
                                <label for="shared_with_str" class="form-label">Shared With</label>
                                <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                    value="<?php echo safeEcho($event['shared_with']); ?>">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle me-1"></i>Update Event
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>