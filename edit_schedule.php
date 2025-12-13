<?php
// ============================================================================
// EDIT_SCHEDULE.PHP - Edit Existing Schedule
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// Allows users to edit their gaming schedules.
// Pre-fills form with existing data and validates changes.
// ============================================================================


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

// Get schedule and verify ownership
$schedules = getSchedules($userId);
$schedule = array_filter($schedules, function ($s) use ($id) {
    return $s['schedule_id'] == $id;
});
$schedule = reset($schedule);

if (!$schedule) {
    setMessage('danger', 'Schedule not found or no permission.');
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gameTitle = $_POST['game_title'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $friendsStr = $_POST['friends_str'] ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = editSchedule($userId, $id, $gameTitle, $date, $time, $friendsStr, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Schedule updated successfully!');
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
    <title>Edit Schedule - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5 pb-5">

        <?php echo getMessage(); ?>

        <h1 class="h3 fw-bold mb-4">✏️ Edit Schedule</h1>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card bg-secondary border-0 rounded-4 shadow">
                    <div class="card-body p-4">

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" onsubmit="return validateScheduleForm();">

                            <div class="mb-3">
                                <label for="game_title" class="form-label">🎮 Game Title *</label>
                                <input type="text" id="game_title" name="game_title"
                                    class="form-control bg-dark text-light border-secondary form-control-lg" required
                                    maxlength="100" value="<?php echo safeEcho($schedule['game_titel']); ?>">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="date" class="form-label">📆 Date *</label>
                                    <input type="date" id="date" name="date"
                                        class="form-control bg-dark text-light border-secondary form-control-lg"
                                        required min="<?php echo date('Y-m-d'); ?>"
                                        value="<?php echo safeEcho($schedule['date']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="time" class="form-label">⏰ Time *</label>
                                    <input type="time" id="time" name="time"
                                        class="form-control bg-dark text-light border-secondary form-control-lg"
                                        required value="<?php echo safeEcho($schedule['time']); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="friends_str" class="form-label">👥 Friends Joining</label>
                                <input type="text" id="friends_str" name="friends_str"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="Comma-separated usernames"
                                    value="<?php echo safeEcho($schedule['friends']); ?>">
                            </div>

                            <div class="mb-4">
                                <label for="shared_with_str" class="form-label">🔗 Shared With</label>
                                <input type="text" id="shared_with_str" name="shared_with_str"
                                    class="form-control bg-dark text-light border-secondary"
                                    value="<?php echo safeEcho($schedule['shared_with']); ?>">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-lg px-5">💾 Update Schedule</button>
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