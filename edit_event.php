<?php
// ============================================================================
// EDIT_EVENT.PHP - Edit Existing Event
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This page allows users to edit their existing events.
// It fetches the event data and pre-fills the form for editing.
//
// SECURITY:
// - Checks if user is logged in
// - Verifies user owns the event (ownership check)
// - Validates all input before updating
// ============================================================================


require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$id = $_GET['id'] ?? 0;

// Validate ID is numeric
if (!is_numeric($id)) {
    header("Location: index.php");
    exit;
}

// Get user's events and find the specific one
$events = getEvents($userId);
$event = array_filter($events, function ($e) use ($id) {
    return $e['event_id'] == $id;
});
$event = reset($event); // Get first (and only) result

// Check if event exists and belongs to user
if (!$event) {
    setMessage('danger', 'Event not found or you do not have permission to edit it.');
    header("Location: index.php");
    exit;
}

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
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5 pb-5">

        <?php echo getMessage(); ?>

        <h1 class="h3 fw-bold mb-4">✏️ Edit Event</h1>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card bg-secondary border-0 rounded-4 shadow">
                    <div class="card-body p-4">

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" onsubmit="return validateEventForm();">

                            <div class="mb-3">
                                <label for="title" class="form-label">🏆 Event Title *</label>
                                <input type="text" id="title" name="title"
                                    class="form-control bg-dark text-light border-secondary form-control-lg" required
                                    maxlength="100" value="<?php echo safeEcho($event['title']); ?>">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="date" class="form-label">📆 Date *</label>
                                    <input type="date" id="date" name="date"
                                        class="form-control bg-dark text-light border-secondary form-control-lg"
                                        required min="<?php echo date('Y-m-d'); ?>"
                                        value="<?php echo safeEcho($event['date']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="time" class="form-label">⏰ Time *</label>
                                    <input type="time" id="time" name="time"
                                        class="form-control bg-dark text-light border-secondary form-control-lg"
                                        required value="<?php echo safeEcho($event['time']); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">📝 Description</label>
                                <textarea id="description" name="description"
                                    class="form-control bg-dark text-light border-secondary" rows="3"
                                    maxlength="500"><?php echo safeEcho($event['description']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="reminder" class="form-label">⏰ Reminder</label>
                                <select id="reminder" name="reminder"
                                    class="form-select bg-dark text-light border-secondary">
                                    <option value="none" <?php if ($event['reminder'] == 'none')
                                        echo 'selected'; ?>>No
                                        reminder</option>
                                    <option value="1_hour" <?php if ($event['reminder'] == '1_hour')
                                        echo 'selected'; ?>>1
                                        hour before</option>
                                    <option value="1_day" <?php if ($event['reminder'] == '1_day')
                                        echo 'selected'; ?>>1
                                        day before</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="external_link" class="form-label">🔗 External Link</label>
                                <input type="url" id="external_link" name="external_link"
                                    class="form-control bg-dark text-light border-secondary"
                                    value="<?php echo safeEcho($event['external_link']); ?>">
                            </div>

                            <div class="mb-4">
                                <label for="shared_with_str" class="form-label">👥 Shared With</label>
                                <input type="text" id="shared_with_str" name="shared_with_str"
                                    class="form-control bg-dark text-light border-secondary"
                                    value="<?php echo safeEcho($event['shared_with']); ?>">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-lg px-5">💾 Update Event</button>
                                <a href="index.php" class="btn btn-outline-secondary btn-lg">Cancel</a>
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