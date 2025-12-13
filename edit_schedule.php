<?php
// edit_schedule.php - Update Schedule
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: Modify an existing gaming session plan.

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

// Fetch existing schedule
$schedules = getSchedules($userId);
$schedule = array_filter($schedules, function($s) use ($id) { return $s['schedule_id'] == $id; });
$schedule = reset($schedule);

// Security: If not found or not owned by user, redirect
if (!$schedule) {
    setMessage('danger', 'Schedule not found or permission denied.');
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
    <title>Edit Schedule - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">
    
    <?php include 'header.php'; ?>

    <main class="container">
        <?php echo getMessage(); ?>
        
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card p-4">
                    <h2 class="text-warning text-center mb-4">Edit Session</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" onsubmit="return validateScheduleForm();">
                        <div class="mb-3">
                            <label for="game_title" class="form-label">Game Title</label>
                            <input type="text" id="game_title" name="game_title" class="form-control" required maxlength="100" value="<?php echo safeEcho($schedule['game_titel']); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" id="date" name="date" class="form-control" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo safeEcho($schedule['date']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="time" class="form-label">Time</label>
                                <input type="time" id="time" name="time" class="form-control" required value="<?php echo safeEcho($schedule['time']); ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="friends_str" class="form-label">Invited Friends</label>
                            <input type="text" id="friends_str" name="friends_str" class="form-control" value="<?php echo safeEcho($schedule['friends']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">Shared With</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control" value="<?php echo safeEcho($schedule['shared_with']); ?>">
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning flex-grow-1">Save Changes</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </div>
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