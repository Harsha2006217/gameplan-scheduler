<?php
/**
 * ============================================================================
 * index.php - DASHBOARD / HOOFDPAGINA
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Dit is de HOOFDPAGINA waar ingelogde gebruikers terechtkomen.
 * Het toont een overzicht van:
 * - Vriendenlijst
 * - Favoriete games
 * - Geplande speelsessies (schedules)
 * - Evenementen
 * - Kalender overzicht
 * - Herinneringen (reminders)
 * 
 * BEVEILIGING:
 * - Alleen toegankelijk voor ingelogde gebruikers
 * - Sessie timeout check
 * - Alle output wordt veilig geëscaped
 * ============================================================================
 */

// Laad alle functies
require_once 'functions.php';

// Controleer sessie timeout (30 minuten inactiviteit)
checkSessionTimeout();

// Als niet ingelogd, redirect naar login pagina
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Haal user ID op voor database queries
$userId = getUserId();

// Update laatste activiteit timestamp
updateLastActivity(getDBConnection(), $userId);

// Haal sorteer parameters op uit URL (voor sorteren van tabellen)
$sortSchedules = $_GET['sort_schedules'] ?? 'date ASC';
$sortEvents = $_GET['sort_events'] ?? 'date ASC';

// Haal alle data op voor de gebruiker
$friends = getFriends($userId);           // Vriendenlijst
$favorites = getFavoriteGames($userId);    // Favoriete games
$schedules = getSchedules($userId, $sortSchedules);  // Speelschema's
$events = getEvents($userId, $sortEvents);           // Evenementen
$calendarItems = getCalendarItems($userId);          // Gecombineerd voor kalender
$reminders = getReminders($userId);        // Actieve herinneringen

// Handel logout af als gevraagd
if (isset($_GET['logout'])) {
    logout();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GamePlan Scheduler Dashboard - Manage your gaming schedule">
    
    <title>Dashboard - GamePlan Scheduler</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    
    <?php include 'header.php'; ?>
    
    <main class="container mt-4">
        
        <!-- Toon succes/fout meldingen -->
        <?php echo getMessage(); ?>
        
        <!-- Welkom bericht -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-6">
                    <i class="bi bi-controller me-2"></i>
                    Welcome, <?php echo safeEcho($_SESSION['username'] ?? 'Gamer'); ?>!
                </h1>
                <p class="text-muted">Manage your gaming schedules and events</p>
            </div>
        </div>
        
        <!-- SECTIE 1: VRIENDEN LIJST -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><i class="bi bi-people me-2"></i>Friends</h2>
                <a href="add_friend.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus me-1"></i>Add Friend
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th><i class="bi bi-person me-1"></i>Username</th>
                            <th><i class="bi bi-circle-fill me-1"></i>Status</th>
                            <th><i class="bi bi-sticky me-1"></i>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($friends)): ?>
                            <tr><td colspan="4" class="text-center text-muted">No friends yet. Add some!</td></tr>
                        <?php else: ?>
                            <?php foreach ($friends as $friend): ?>
                                <tr>
                                    <td><?php echo safeEcho($friend['username']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $friend['status'] == 'Online' ? 'success' : 'secondary'; ?>">
                                            <?php echo safeEcho($friend['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo safeEcho($friend['note']); ?></td>
                                    <td>
                                        <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Remove this friend?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <!-- SECTIE 2: FAVORIETE GAMES -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><i class="bi bi-heart me-2"></i>Favorite Games</h2>
                <a href="profile.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Add Game
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th><i class="bi bi-controller me-1"></i>Game Title</th>
                            <th><i class="bi bi-info-circle me-1"></i>Description</th>
                            <th><i class="bi bi-sticky me-1"></i>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($favorites)): ?>
                            <tr><td colspan="4" class="text-center text-muted">No favorite games yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($favorites as $game): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo safeEcho($game['titel']); ?></td>
                                    <td><?php echo safeEcho($game['description']); ?></td>
                                    <td><?php echo safeEcho($game['note']); ?></td>
                                    <td>
                                        <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Remove from favorites?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <!-- SECTIE 3: SPEELSCHEMA'S -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><i class="bi bi-calendar-week me-2"></i>Schedules</h2>
                <div>
                    <a href="?sort_schedules=date ASC" class="btn btn-outline-light btn-sm">Date ↑</a>
                    <a href="?sort_schedules=date DESC" class="btn btn-outline-light btn-sm">Date ↓</a>
                    <a href="add_schedule.php" class="btn btn-primary btn-sm ms-2">
                        <i class="bi bi-plus-circle me-1"></i>Add
                    </a>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Game</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Friends</th>
                            <th>Shared With</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($schedules)): ?>
                            <tr><td colspan="6" class="text-center text-muted">No schedules yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo safeEcho($schedule['game_titel']); ?></td>
                                    <td><?php echo safeEcho($schedule['date']); ?></td>
                                    <td><?php echo safeEcho($schedule['time']); ?></td>
                                    <td><?php echo safeEcho($schedule['friends']); ?></td>
                                    <td><?php echo safeEcho($schedule['shared_with']); ?></td>
                                    <td>
                                        <a href="edit_schedule.php?id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?type=schedule&id=<?php echo $schedule['schedule_id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Delete this schedule?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <!-- SECTIE 4: EVENEMENTEN -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><i class="bi bi-trophy me-2"></i>Events</h2>
                <div>
                    <a href="?sort_events=date ASC" class="btn btn-outline-light btn-sm">Date ↑</a>
                    <a href="?sort_events=date DESC" class="btn btn-outline-light btn-sm">Date ↓</a>
                    <a href="add_event.php" class="btn btn-success btn-sm ms-2">
                        <i class="bi bi-plus-circle me-1"></i>Add Event
                    </a>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Description</th>
                            <th>Reminder</th>
                            <th>Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($events)): ?>
                            <tr><td colspan="7" class="text-center text-muted">No events yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo safeEcho($event['title']); ?></td>
                                    <td><?php echo safeEcho($event['date']); ?></td>
                                    <td><?php echo safeEcho($event['time']); ?></td>
                                    <td><?php echo safeEcho(substr($event['description'], 0, 50)) . (strlen($event['description']) > 50 ? '...' : ''); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo safeEcho($event['reminder']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($event['external_link']): ?>
                                            <a href="<?php echo safeEcho($event['external_link']); ?>" target="_blank" class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-link-45deg"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?type=event&id=<?php echo $event['event_id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Delete this event?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <!-- SECTIE 5: KALENDER OVERZICHT -->
        <section class="mb-5">
            <h2><i class="bi bi-calendar3 me-2"></i>Calendar Overview</h2>
            <div class="row">
                <?php if (empty($calendarItems)): ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center text-muted">
                                <i class="bi bi-calendar-x fs-1 mb-3"></i>
                                <p>No upcoming items. Add a schedule or event!</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($calendarItems as $item): ?>
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo safeEcho($item['title'] ?? $item['game_titel']); ?>
                                    </h5>
                                    <p class="card-text">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo safeEcho($item['date']); ?>
                                        <br>
                                        <i class="bi bi-clock me-1"></i>
                                        <?php echo safeEcho($item['time']); ?>
                                    </p>
                                    <?php if (isset($item['description']) && $item['description']): ?>
                                        <p class="small text-muted"><?php echo safeEcho(substr($item['description'], 0, 100)); ?></p>
                                    <?php endif; ?>
                                    <?php if (isset($item['reminder']) && $item['reminder'] != 'none'): ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-bell"></i> <?php echo safeEcho($item['reminder']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        
    </main>
    
    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    
    <!-- Reminder pop-ups via JavaScript -->
    <script>
        const reminders = <?php echo json_encode($reminders); ?>;
        if (reminders.length > 0) {
            reminders.forEach(reminder => {
                alert('🔔 Reminder: ' + reminder.title + ' at ' + reminder.time + '!');
            });
        }
    </script>
</body>
</html>