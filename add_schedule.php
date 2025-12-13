<?php
// ============================================================================
// ADD_SCHEDULE.PHP - Create New Gaming Schedule
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This page allows users to create new gaming schedules - planning when
// they will play games with friends.
//
// USER STORY: "Share play schedules in a calendar"
// - User can schedule gaming sessions
// - Specify game, date, time, and friends joining
// - Share schedule with other users
//
// FORM FIELDS:
// - Game Title (required): What game will be played
// - Date (required): When the session is planned
// - Time (required): Start time of the session
// - Friends: Who will join the session
// - Shared With: Who can see this schedule
//
// VALIDATION (Bug Fix #1001, #1004):
// - Game title cannot be empty or just spaces
// - Date must be valid format and in the future
// - Time must be valid HH:MM format
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
    $gameTitle = $_POST['game_title'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $friendsStr = $_POST['friends_str'] ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    // addSchedule() validates all inputs (Bug fixes #1001, #1004)
    $error = addSchedule($userId, $gameTitle, $date, $time, $friendsStr, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Schedule created successfully!');
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

    <main class="container mt-5 pt-5 pb-5">

        <?php echo getMessage(); ?>

        <h1 class="h3 fw-bold mb-4">📅 Create Gaming Schedule</h1>


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


                        <!-- Schedule Form -->
                        <!-- onsubmit validates with JavaScript before sending -->
                        <form method="POST" onsubmit="return validateScheduleForm();">

                            <!-- Game Title -->
                            <div class="mb-3">
                                <label for="game_title" class="form-label">
                                    🎮 Game Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="game_title" name="game_title"
                                    class="form-control bg-dark text-light border-secondary form-control-lg" required
                                    maxlength="100" placeholder="What game will you play?" aria-label="Game Title">
                            </div>


                            <!-- Date and Time Row -->
                            <div class="row mb-3">
                                <!-- Date -->
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="date" class="form-label">
                                        📆 Date <span class="text-danger">*</span>
                                    </label>
                                    <!-- min attribute prevents past dates -->
                                    <input type="date" id="date" name="date"
                                        class="form-control bg-dark text-light border-secondary form-control-lg"
                                        required min="<?php echo date('Y-m-d'); ?>" aria-label="Date">
                                </div>

                                <!-- Time -->
                                <div class="col-md-6">
                                    <label for="time" class="form-label">
                                        ⏰ Time <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" id="time" name="time"
                                        class="form-control bg-dark text-light border-secondary form-control-lg"
                                        required aria-label="Time">
                                </div>
                            </div>


                            <!-- Friends Joining -->
                            <div class="mb-3">
                                <label for="friends_str" class="form-label">
                                    👥 Friends Joining
                                </label>
                                <input type="text" id="friends_str" name="friends_str"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="e.g., Player1, Player2, Player3" aria-label="Friends">
                                <div class="form-text text-muted">
                                    Comma-separated list of friends who will join
                                </div>
                            </div>


                            <!-- Shared With -->
                            <div class="mb-4">
                                <label for="shared_with_str" class="form-label">
                                    🔗 Share With
                                </label>
                                <input type="text" id="shared_with_str" name="shared_with_str"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="e.g., User1, User2" aria-label="Shared With">
                                <div class="form-text text-muted">
                                    Comma-separated usernames who can see this schedule
                                </div>
                            </div>


                            <!-- Submit Button -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    ✅ Create Schedule
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