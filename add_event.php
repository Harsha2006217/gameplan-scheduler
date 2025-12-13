<?php
// add_event.php - Create Tournament/Event
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: Detailed event creation for things like Tournaments or Streams.

require_once 'functions.php';
checkSessionTimeout();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$error = '';

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
        setMessage('success', 'Event created successfully!');
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Event - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">
    
    <?php include 'header.php'; ?>

    <main class="container">
        <?php echo getMessage(); ?>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4">
                    <h2 class="text-success mb-4">Create New Event</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" onsubmit="return validateEventForm();">
                        <div class="mb-3">
                            <label for="title" class="form-label">Event Title</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100" placeholder="e.g. Fortnite World Cup">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" id="date" name="date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="time" class="form-label">Time</label>
                                <input type="time" id="time" name="time" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" maxlength="500"></textarea>
                        </div>
                        
                        <!-- Reminder Options (#1002 Improvement: Better UI for options) -->
                        <div class="mb-3">
                            <label for="reminder" class="form-label">Set Reminder</label>
                            <select id="reminder" name="reminder" class="form-select">
                                <option value="none">No Reminder</option>
                                <option value="1_hour">1 Hour Before</option>
                                <option value="1_day">1 Day Before</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="external_link" class="form-label">External Link (Optional)</label>
                            <input type="url" id="external_link" name="external_link" class="form-control" placeholder="https://...">
                        </div>
                        
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">Shared With</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control" placeholder="Comma separated names">
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 fw-bold">Create Event</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>