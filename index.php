<?php
// index.php - Dashboard & Main Hub
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description:
// This is the main page users see after logging in.
// It displays a "Dashboard" view of everything: Friends, Games, Schedules, and Events.
// It also merges Schedules and Events into a unified "Calendar Overview".

require_once 'functions.php';

// Security Check: Ensure user is logged in.
checkSessionTimeout();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
// Update "Last Seen" timestamp for online status
updateLastActivity(getDBConnection(), $userId);

// --- Sorting Logic ---
// We get the sort preference from the URL (e.g. ?sort_events=time DESC)
// Default is Date Ascending (Earliest first).
$sortSchedules = $_GET['sort_schedules'] ?? 'date ASC';
$sortEvents = $_GET['sort_events'] ?? 'date ASC';

// Fetch Data
$friends = getFriends($userId);
$favorites = getFavoriteGames($userId);
$schedules = getSchedules($userId, $sortSchedules);
$events = getEvents($userId, $sortEvents);
$calendarItems = getCalendarItems($userId); // Merged list
$reminders = getReminders($userId); // Upcoming reminders

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">

    <?php include 'header.php'; ?>

    <main class="container mb-5">
        
        <!-- Flash Messages (Success/Error) -->
        <?php echo getMessage(); ?>

        <div class="row">
            <!-- Left Column: Friends & Favorites -->
            <div class="col-lg-4">
                
                <!-- Friends Section -->
                <div class="card p-3 mb-4">
                    <h3 class="h5 border-bottom pb-2">
                        <i class="fa-solid fa-user-group text-info"></i> Friends (<?php echo count($friends); ?>)
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($friends)): ?>
                                    <tr><td colspan="3" class="text-center text-muted">No friends added yet.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($friends as $friend): ?>
                                    <tr>
                                        <td><?php echo safeEcho($friend['username']); ?></td>
                                        <td>
                                            <!-- Color code status -->
                                            <span class="badge <?php echo ($friend['status']=='Online')?'bg-success':'bg-secondary'; ?>">
                                                <?php echo safeEcho($friend['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                            <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove friend?');" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <a href="add_friend.php" class="btn btn-sm btn-primary">+ Add Friend</a>
                    </div>
                </div>

                <!-- Favorites Section -->
                <div class="card p-3 mb-4">
                    <h3 class="h5 border-bottom pb-2">
                        <i class="fa-solid fa-gamepad text-danger"></i> Favorites
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-dark table-sm">
                            <thead>
                                <tr>
                                    <th>Game</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($favorites)): ?>
                                    <tr><td colspan="2" class="text-center text-muted">No favorites yet.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($favorites as $game): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo safeEcho($game['titel']); ?></strong><br>
                                            <small class="text-muted"><?php echo safeEcho($game['note']); ?></small>
                                        </td>
                                        <td>
                                            <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen"></i></a>
                                            <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete game?');"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Schedule, Events & Calendar -->
            <div class="col-lg-8">
                
                <!-- Schedules -->
                <div class="card p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h3 class="h5 mb-0"><i class="fa-solid fa-calendar text-warning"></i> Play Schedule</h3>
                        
                        <!-- Sorting Buttons (#1006) -->
                        <div class="btn-group btn-group-sm">
                            <a href="?sort_schedules=date ASC" class="btn btn-outline-light <?php echo $sortSchedules=='date ASC'?'active':''; ?>">Date <i class="fa-solid fa-arrow-up"></i></a>
                            <a href="?sort_schedules=date DESC" class="btn btn-outline-light <?php echo $sortSchedules=='date DESC'?'active':''; ?>">Date <i class="fa-solid fa-arrow-down"></i></a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Game</th>
                                    <th>Date/Time</th>
                                    <th>With</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($schedules)): ?>
                                    <tr><td colspan="4" class="text-center text-muted">No sessions planned.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($schedules as $schedule): ?>
                                    <tr>
                                        <td><?php echo safeEcho($schedule['game_titel']); ?></td>
                                        <td>
                                            <?php echo safeEcho($schedule['date']); ?> <br>
                                            <small class="text-info"><?php echo safeEcho($schedule['time']); ?></small>
                                        </td>
                                        <td><?php echo safeEcho($schedule['friends']); ?></td>
                                        <td>
                                            <a href="edit_schedule.php?id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen"></i></a>
                                            <a href="delete.php?type=schedule&id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete schedule?');"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Events -->
                <div class="card p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h3 class="h5 mb-0"><i class="fa-solid fa-trophy text-success"></i> Events</h3>
                        
                        <!-- Sorting Buttons -->
                        <div class="btn-group btn-group-sm">
                            <a href="?sort_events=date ASC" class="btn btn-outline-light <?php echo $sortEvents=='date ASC'?'active':''; ?>">Date <i class="fa-solid fa-arrow-up"></i></a>
                            <a href="?sort_events=date DESC" class="btn btn-outline-light <?php echo $sortEvents=='date DESC'?'active':''; ?>">Date <i class="fa-solid fa-arrow-down"></i></a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>When</th>
                                    <th>Details</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($events)): ?>
                                    <tr><td colspan="4" class="text-center text-muted">No events planned.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td>
                                            <?php echo safeEcho($event['title']); ?>
                                            <?php if ($event['external_link']): ?>
                                                <a href="<?php echo safeEcho($event['external_link']); ?>" target="_blank" class="ms-1 text-info"><i class="fa-solid fa-link"></i></a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo safeEcho($event['date']); ?> <br>
                                            <span class="badge bg-secondary"><?php echo safeEcho($event['time']); ?></span>
                                        </td>
                                        <td>
                                            <?php echo safeEcho($event['description']); ?>
                                            <?php if ($event['reminder'] != 'none'): ?>
                                                <br><small class="text-warning"><i class="fa-regular fa-bell"></i> <?php echo safeEcho($event['reminder']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen"></i></a>
                                            <a href="delete.php?type=event&id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete event?');"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Combined Calendar Cards -->
                <div class="mb-4">
                    <h3 class="h4">Timeline</h3>
                    <div class="row">
                        <?php foreach ($calendarItems as $item): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-start border-4 <?php echo isset($item['game_titel']) ? 'border-primary' : 'border-success'; ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title"><?php echo safeEcho($item['title'] ?? $item['game_titel']); ?></h5>
                                            <small class="text-muted"><?php echo safeEcho($item['date']); ?></small>
                                        </div>
                                        <p class="card-text">
                                            At <strong><?php echo safeEcho($item['time']); ?></strong>
                                            <?php if (isset($item['description'])): ?>
                                                <br><em><?php echo safeEcho($item['description']); ?></em>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    
    <!-- Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    
    <!-- Reminder Injection -->
    <script>
        // Pass PHP array to JS safely
        const reminders = <?php echo json_encode($reminders); ?>;
        
        // Simple immediate alert for demo purposes
        reminders.forEach(reminder => {
            // In a real app, we check current time vs event time.
            console.log("Reminder loaded for: " + reminder.title);
        });
    </script>
</body>
</html>