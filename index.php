<?php
// ============================================================================
// INDEX.PHP - Dashboard and Calendar View (Main Page)
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This is the MAIN DASHBOARD page - the home page after login.
// It displays all user data: friends, favorite games, schedules, events,
// and a merged calendar view.
//
// FEATURES ON THIS PAGE:
// 1. Friends List - Shows all friends with status and actions
// 2. Favorite Games - Shows user's favorite games
// 3. Schedules Table - Gaming sessions with sorting
// 4. Events Table - Tournaments/events with sorting
// 5. Calendar Overview - Merged view of schedules + events
// 6. Reminder Pop-ups - JavaScript alerts for upcoming events
//
// DATA FLOW:
// 1. Check if user is logged in (redirect if not)
// 2. Get user ID from session
// 3. Fetch all data from database (friends, games, schedules, events)
// 4. Display everything in organized tables and cards
// 5. Handle logout link
//
// FILE DEPENDENCIES:
// - functions.php: All database functions
// - header.php: Navigation header
// - footer.php: Page footer
// - style.css: Custom styling
// - script.js: Client-side functionality
// ============================================================================


// Include core functions
require_once 'functions.php';


// Check session timeout (logs out inactive users after 30 minutes)
checkSessionTimeout();


// If not logged in, redirect to login page
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}


// Get current user's ID from session
$userId = getUserId();


// Update last activity timestamp (for session tracking)
updateLastActivity(getDBConnection(), $userId);


// ============================================================================
// GET SORTING OPTIONS FROM URL
// ============================================================================
// Users can click links to change how data is sorted
// $_GET contains URL parameters (e.g., ?sort_schedules=date%20DESC)

// Schedule sorting (default: date ascending)
$sortSchedules = $_GET['sort_schedules'] ?? 'date ASC';

// Event sorting (default: date ascending)
$sortEvents = $_GET['sort_events'] ?? 'date ASC';


// ============================================================================
// FETCH ALL USER DATA FROM DATABASE
// ============================================================================
// These functions are defined in functions.php
// They return arrays of data from the database

// Get friends list
$friends = getFriends($userId);

// Get favorite games
$favorites = getFavoriteGames($userId);

// Get schedules with sorting
$schedules = getSchedules($userId, $sortSchedules);

// Get events with sorting
$events = getEvents($userId, $sortEvents);

// Get merged calendar items (schedules + events sorted by date/time)
$calendarItems = getCalendarItems($userId);

// Get reminders that should pop up now
$reminders = getReminders($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Page title -->
    <title>Dashboard - GamePlan Scheduler</title>
    
    <!-- Meta description for SEO -->
    <meta name="description" content="Your GamePlan Scheduler dashboard - view friends, schedules, events, and calendar.">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">
    
    <!-- Include navigation header -->
    <?php include 'header.php'; ?>
    
    
    <!-- ====================================================================
         MAIN CONTENT AREA
         ==================================================================== -->
    <!-- mt-5 pt-5: Margin and padding top to account for fixed header -->
    <!-- pb-5: Padding bottom for fixed footer -->
    <main class="container mt-5 pt-5 pb-5">
        
        <!-- Display any flash messages (success/error) -->
        <?php echo getMessage(); ?>
        
        
        <!-- ================================================================
             WELCOME SECTION
             ================================================================ -->
        <div class="mb-4">
            <h1 class="h3 fw-bold">
                Welcome back, <?php echo safeEcho($_SESSION['username']); ?>! 👋
            </h1>
            <p class="text-muted">Here's your gaming overview</p>
        </div>
        
        
        <!-- ================================================================
             SECTION: FRIENDS LIST
             ================================================================ -->
        <!-- Each section is in a card for visual separation -->
        <section class="mb-5">
            <div class="card bg-secondary border-0 rounded-4 shadow">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 px-4">
                    <h2 class="h5 mb-0 fw-bold">👥 Friends List</h2>
                    <!-- Quick link to add friend -->
                    <a href="add_friend.php" class="btn btn-sm btn-outline-light">
                        + Add Friend
                    </a>
                </div>
                <div class="card-body px-4 pb-4">
                    
                    <!-- Check if user has friends -->
                    <?php if (empty($friends)): ?>
                        <!-- Empty state message -->
                        <p class="text-muted text-center py-4">
                            No friends yet. <a href="add_friend.php" class="text-info">Add your first friend!</a>
                        </p>
                    <?php else: ?>
                        <!-- Friends table -->
                        <!-- table-responsive: Horizontal scroll on small screens -->
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <!-- Table header -->
                                <thead class="table-primary">
                                    <tr>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Loop through each friend -->
                                    <?php foreach ($friends as $friend): ?>
                                        <tr>
                                            <!-- Friend username -->
                                            <td class="fw-semibold">
                                                <?php echo safeEcho($friend['username']); ?>
                                            </td>
                                            
                                            <!-- Status with color coding -->
                                            <td>
                                                <!-- Status badge with dynamic color -->
                                                <span class="badge <?php 
                                                    // Choose badge color based on status
                                                    $status = strtolower($friend['status']);
                                                    if ($status === 'online') echo 'bg-success';
                                                    elseif ($status === 'playing') echo 'bg-warning text-dark';
                                                    else echo 'bg-secondary';
                                                ?>">
                                                    <?php echo safeEcho($friend['status']); ?>
                                                </span>
                                            </td>
                                            
                                            <!-- Note -->
                                            <td class="text-muted">
                                                <?php echo safeEcho($friend['note']); ?>
                                            </td>
                                            
                                            <!-- Action buttons -->
                                            <td class="text-end">
                                                <!-- Edit button -->
                                                <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>" 
                                                   class="btn btn-sm btn-outline-warning me-1">
                                                    ✏️ Edit
                                                </a>
                                                
                                                <!-- Delete button with confirmation -->
                                                <!-- onclick: JavaScript confirm dialog -->
                                                <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Are you sure you want to remove this friend?');">
                                                    🗑️ Remove
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </section>
        
        
        <!-- ================================================================
             SECTION: FAVORITE GAMES
             ================================================================ -->
        <section class="mb-5">
            <div class="card bg-secondary border-0 rounded-4 shadow">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 px-4">
                    <h2 class="h5 mb-0 fw-bold">🎮 Favorite Games</h2>
                    <a href="profile.php" class="btn btn-sm btn-outline-light">
                        + Add Game
                    </a>
                </div>
                <div class="card-body px-4 pb-4">
                    
                    <?php if (empty($favorites)): ?>
                        <p class="text-muted text-center py-4">
                            No favorite games yet. <a href="profile.php" class="text-info">Add your favorites!</a>
                        </p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Game Title</th>
                                        <th>Description</th>
                                        <th>My Note</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($favorites as $game): ?>
                                        <tr>
                                            <td class="fw-semibold">
                                                <?php echo safeEcho($game['titel']); ?>
                                            </td>
                                            <td class="text-muted">
                                                <?php echo safeEcho($game['description']); ?>
                                            </td>
                                            <td class="text-muted">
                                                <?php echo safeEcho($game['note']); ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>" 
                                                   class="btn btn-sm btn-outline-warning me-1">
                                                    ✏️ Edit
                                                </a>
                                                <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Remove this game from favorites?');">
                                                    🗑️ Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </section>
        
        
        <!-- ================================================================
             SECTION: SCHEDULES
             ================================================================ -->
        <section class="mb-5">
            <div class="card bg-secondary border-0 rounded-4 shadow">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center flex-wrap gap-2 pt-4 px-4">
                    <h2 class="h5 mb-0 fw-bold">📅 Gaming Schedules</h2>
                    
                    <!-- Sorting buttons and add link -->
                    <div class="d-flex gap-2 flex-wrap">
                        <!-- Sort buttons -->
                        <div class="btn-group btn-group-sm">
                            <a href="?sort_schedules=date ASC" 
                               class="btn <?php echo $sortSchedules == 'date ASC' ? 'btn-info' : 'btn-outline-info'; ?>">
                                Date ↑
                            </a>
                            <a href="?sort_schedules=date DESC" 
                               class="btn <?php echo $sortSchedules == 'date DESC' ? 'btn-info' : 'btn-outline-info'; ?>">
                                Date ↓
                            </a>
                        </div>
                        <a href="add_schedule.php" class="btn btn-sm btn-outline-light">
                            + Add Schedule
                        </a>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    
                    <?php if (empty($schedules)): ?>
                        <p class="text-muted text-center py-4">
                            No schedules yet. <a href="add_schedule.php" class="text-info">Create your first schedule!</a>
                        </p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Game</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Friends</th>
                                        <th>Shared With</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($schedules as $schedule): ?>
                                        <tr>
                                            <td class="fw-semibold">
                                                <?php echo safeEcho($schedule['game_titel']); ?>
                                            </td>
                                            <td>
                                                <!-- Format date nicely -->
                                                <?php echo date('D, M j', strtotime($schedule['date'])); ?>
                                            </td>
                                            <td>
                                                <?php echo date('H:i', strtotime($schedule['time'])); ?>
                                            </td>
                                            <td class="text-muted">
                                                <?php echo safeEcho($schedule['friends']); ?>
                                            </td>
                                            <td class="text-muted">
                                                <?php echo safeEcho($schedule['shared_with']); ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="edit_schedule.php?id=<?php echo $schedule['schedule_id']; ?>" 
                                                   class="btn btn-sm btn-outline-warning me-1">
                                                    ✏️ Edit
                                                </a>
                                                <a href="delete.php?type=schedule&id=<?php echo $schedule['schedule_id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Delete this schedule?');">
                                                    🗑️ Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </section>
        
        
        <!-- ================================================================
             SECTION: EVENTS
             ================================================================ -->
        <section class="mb-5">
            <div class="card bg-secondary border-0 rounded-4 shadow">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center flex-wrap gap-2 pt-4 px-4">
                    <h2 class="h5 mb-0 fw-bold">🏆 Events & Tournaments</h2>
                    
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="btn-group btn-group-sm">
                            <a href="?sort_events=date ASC" 
                               class="btn <?php echo $sortEvents == 'date ASC' ? 'btn-info' : 'btn-outline-info'; ?>">
                                Date ↑
                            </a>
                            <a href="?sort_events=date DESC" 
                               class="btn <?php echo $sortEvents == 'date DESC' ? 'btn-info' : 'btn-outline-info'; ?>">
                                Date ↓
                            </a>
                        </div>
                        <a href="add_event.php" class="btn btn-sm btn-success">
                            + Add Event
                        </a>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    
                    <?php if (empty($events)): ?>
                        <p class="text-muted text-center py-4">
                            No events yet. <a href="add_event.php" class="text-info">Create your first event!</a>
                        </p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Event Title</th>
                                        <th>Date & Time</th>
                                        <th>Description</th>
                                        <th>Reminder</th>
                                        <th>Link</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($events as $event): ?>
                                        <tr>
                                            <td class="fw-semibold">
                                                <?php echo safeEcho($event['title']); ?>
                                            </td>
                                            <td>
                                                <?php echo date('D, M j', strtotime($event['date'])); ?>
                                                at <?php echo date('H:i', strtotime($event['time'])); ?>
                                            </td>
                                            <td class="text-muted" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                <?php echo safeEcho($event['description']); ?>
                                            </td>
                                            <td>
                                                <!-- Reminder badge -->
                                                <span class="badge <?php echo $event['reminder'] == 'none' ? 'bg-secondary' : 'bg-info'; ?>">
                                                    <?php 
                                                    // Display friendly reminder text
                                                    switch($event['reminder']) {
                                                        case '1_hour': echo '1 Hour Before'; break;
                                                        case '1_day': echo '1 Day Before'; break;
                                                        default: echo 'None';
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($event['external_link'])): ?>
                                                    <a href="<?php echo safeEcho($event['external_link']); ?>" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-info">
                                                        🔗 View
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" 
                                                   class="btn btn-sm btn-outline-warning me-1">
                                                    ✏️ Edit
                                                </a>
                                                <a href="delete.php?type=event&id=<?php echo $event['event_id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Delete this event?');">
                                                    🗑️ Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </section>
        
        
        <!-- ================================================================
             SECTION: CALENDAR OVERVIEW (Cards View)
             ================================================================ -->
        <section class="mb-5">
            <h2 class="h5 fw-bold mb-4">📆 Calendar Overview</h2>
            
            <?php if (empty($calendarItems)): ?>
                <div class="card bg-secondary border-0 rounded-4 shadow">
                    <div class="card-body text-center py-5">
                        <p class="text-muted mb-0">
                            Your calendar is empty. Add schedules or events to see them here!
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Grid of calendar cards -->
                <div class="row g-4">
                    <?php foreach ($calendarItems as $item): ?>
                        <!-- Each card is 4 columns wide on medium screens (3 per row) -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-dark border border-secondary rounded-4 h-100 hover-shadow">
                                <div class="card-body p-4">
                                    
                                    <!-- Title (event title or game title) -->
                                    <h5 class="card-title fw-bold text-white mb-2">
                                        <?php echo safeEcho($item['title'] ?? $item['game_titel']); ?>
                                    </h5>
                                    
                                    <!-- Date and time -->
                                    <p class="text-info mb-3">
                                        📅 <?php echo date('l, F j, Y', strtotime($item['date'])); ?>
                                        <br>
                                        ⏰ <?php echo date('H:i', strtotime($item['time'])); ?>
                                    </p>
                                    
                                    <!-- Description (if exists) -->
                                    <?php if (!empty($item['description'])): ?>
                                        <p class="text-muted small mb-2">
                                            <?php echo safeEcho(substr($item['description'], 0, 100)); ?>
                                            <?php if (strlen($item['description']) > 100) echo '...'; ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <!-- Reminder (if exists) -->
                                    <?php if (!empty($item['reminder']) && $item['reminder'] != 'none'): ?>
                                        <p class="mb-2">
                                            <span class="badge bg-warning text-dark">
                                                ⏰ Reminder: <?php echo $item['reminder'] == '1_hour' ? '1 Hour' : '1 Day'; ?> before
                                            </span>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <!-- External link (if exists) -->
                                    <?php if (!empty($item['external_link'])): ?>
                                        <a href="<?php echo safeEcho($item['external_link']); ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-info mt-2">
                                            🔗 Open Link
                                        </a>
                                    <?php endif; ?>
                                    
                                    <!-- Shared with (if exists) -->
                                    <?php if (!empty($item['shared_with'])): ?>
                                        <p class="text-muted small mt-2 mb-0">
                                            👥 Shared: <?php echo safeEcho($item['shared_with']); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        
    </main>
    
    
    <!-- Include footer -->
    <?php include 'footer.php'; ?>
    
    
    <!-- JavaScript files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    
    <!-- ====================================================================
         REMINDER POP-UPS
         ==================================================================== -->
    <!-- This script shows alerts for events that need reminders -->
    <script>
        // Get reminders from PHP (converted to JavaScript array)
        const reminders = <?php echo json_encode($reminders); ?>;
        
        // Show alert for each reminder
        reminders.forEach(reminder => {
            // Alert shows event title and time
            alert(`🔔 Reminder: "${reminder.title}" starts at ${reminder.time}!`);
        });
    </script>
    
</body>
</html>

<?php
// ============================================================================
// HANDLE LOGOUT
// ============================================================================
// If ?logout=1 is in URL, log the user out
// This is triggered by clicking the Logout link in the header

if (isset($_GET['logout'])) {
    logout(); // Destroys session and redirects to login
}
?>