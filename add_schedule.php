<?php
// add_schedule.php - Plan a Game Session
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: Create a new specific gaming session.

require_once 'functions.php';
checkSessionTimeout();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gameTitle = $_POST['game_title'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $friendsStr = $_POST['friends_str'] ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = addSchedule($userId, $gameTitle, $date, $time, $friendsStr, $sharedWithStr);
    if (!$error) {
        setMessage('success', 'Session scheduled!');
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Schedule - GamePlan Scheduler</title>
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
                    <h2 class="text-warning text-center mb-4">Plan Gaming Session</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                    <?php endif; ?>

                    <form method="POST" onsubmit="return validateScheduleForm();">
                        <div class="mb-3">
                            <label for="game_title" class="form-label">Game Title</label>
                            <input type="text" id="game_title" name="game_title" class="form-control" required
                                placeholder="What are you playing?">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" id="date" name="date" class="form-control" required
                                    min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="time" class="form-label">Time</label>
                                <input type="time" id="time" name="time" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="friends_str" class="form-label">Invited Friends</label>
                            <input type="text" id="friends_str" name="friends_str" class="form-control"
                                placeholder="Tom, Jerry (Comma separated)">
                        </div>

                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">Shared With</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                placeholder="Who can see this?">
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold">Schedule It</button>
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