<?php
// ============================================================================
// ADD_EVENT.PHP - Create New Event or Tournament
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This page allows users to create new events like gaming tournaments,
// streams, or special gaming occasions.
//
// USER STORY: "Add events like tournaments"
// - User can create events with title, date, time
// - Add description and external links
// - Set reminders (1 hour or 1 day before)
// - Share events with other users
//
// FORM FIELDS:
// - Title (required): Event name
// - Date (required): Event date
// - Time (required): Event start time
// - Description: Details about the event
// - Reminder: When to get notified
// - External Link: URL to event page/stream
// - Shared With: Who can see this event
//
// BUG FIXES APPLIED:
// - #1001: Title validation (no empty/spaces)
// - #1004: Date format and validity check
// ============================================================================


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

    // addEvent() validates all inputs
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5 pb-5">

        <?php echo getMessage(); ?>

        <h1 class="h3 fw-bold mb-4">🏆 Create New Event</h1>


        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card bg-secondary border-0 rounded-4 shadow">
                    <div class="card-body p-4">

                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo safeEcho($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>


                        <!-- Event Form -->
                        <form method="POST" onsubmit="return validateEventForm();">

                            <!-- Event Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    🏆 Event Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="title" name="title"
                                    class="form-control bg-dark text-light border-secondary form-control-lg" required
                                    maxlength="100" placeholder="e.g., Fortnite Tournament, Minecraft Stream"
                                    aria-label="Title">
                            </div>


                            <!-- Date and Time -->
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="date" class="form-label">
                                        📆 Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="date" name="date"
                                        class="form-control bg-dark text-light border-secondary form-control-lg"
                                        required min="<?php echo date('Y-m-d'); ?>" aria-label="Date">
                                </div>

                                <div class="col-md-6">
                                    <label for="time" class="form-label">
                                        ⏰ Time <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" id="time" name="time"
                                        class="form-control bg-dark text-light border-secondary form-control-lg"
                                        required aria-label="Time">
                                </div>
                            </div>


                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    📝 Description
                                </label>
                                <textarea id="description" name="description"
                                    class="form-control bg-dark text-light border-secondary" rows="3" maxlength="500"
                                    placeholder="Describe the event, rules, prizes, etc."
                                    aria-label="Description"></textarea>
                                <div class="form-text text-muted">
                                    Maximum 500 characters
                                </div>
                            </div>


                            <!-- Reminder -->
                            <div class="mb-3">
                                <label for="reminder" class="form-label">
                                    ⏰ Reminder
                                </label>
                                <select id="reminder" name="reminder"
                                    class="form-select bg-dark text-light border-secondary" aria-label="Reminder">
                                    <option value="none">No reminder</option>
                                    <option value="1_hour">1 hour before event</option>
                                    <option value="1_day">1 day before event</option>
                                </select>
                                <div class="form-text text-muted">
                                    Get a pop-up notification before the event
                                </div>
                            </div>


                            <!-- External Link -->
                            <div class="mb-3">
                                <label for="external_link" class="form-label">
                                    🔗 External Link
                                </label>
                                <input type="url" id="external_link" name="external_link"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="https://twitch.tv/your-stream" aria-label="External Link">
                                <div class="form-text text-muted">
                                    Link to stream, tournament page, etc.
                                </div>
                            </div>


                            <!-- Shared With -->
                            <div class="mb-4">
                                <label for="shared_with_str" class="form-label">
                                    👥 Share With
                                </label>
                                <input type="text" id="shared_with_str" name="shared_with_str"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="e.g., Friend1, Friend2" aria-label="Shared With">
                                <div class="form-text text-muted">
                                    Comma-separated usernames who can see this event
                                </div>
                            </div>


                            <!-- Submit Button -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    ✅ Create Event
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary btn-lg">
                                    Cancel
                                </a>
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