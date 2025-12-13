<?php
/**
 * ============================================================================
 * INDEX.PHP - DASHBOARD / KALENDER OVERZICHT
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * Main dashboard page showing friends, favorites, schedules, events, and calendar.
 * This is the home page after login - the central hub of the application.
 * 
 * DUTCH:
 * Hoofd dashboard pagina met vrienden, favorieten, schema's, evenementen, en kalender.
 * Dit is de homepagina na inloggen - het centrale punt van de applicatie.
 * 
 * FEATURES:
 * - Friends list with status
 * - Favorite games table
 * - Schedules with sorting
 * - Events with sorting
 * - Combined calendar view
 * - Reminder pop-ups (JavaScript)
 * ============================================================================
 */

require_once 'functions.php';

// Check session timeout (30 min inactivity) / Controleer sessie timeout (30 min inactiviteit)
checkSessionTimeout();

// Redirect to login if not logged in / Redirect naar login als niet ingelogd
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get current user ID / Haal huidige gebruiker ID op
$userId = getUserId();

// Update last activity for session tracking / Update laatste activiteit voor sessie tracking
updateLastActivity(getDBConnection(), $userId);

// Get sort parameters from URL / Haal sorteer parameters uit URL
$sortSchedules = $_GET['sort_schedules'] ?? 'date ASC';
$sortEvents = $_GET['sort_events'] ?? 'date ASC';

// Fetch all user data / Haal alle gebruikersdata op
$friends = getFriends($userId);
$favorites = getFavoriteGames($userId);
$schedules = getSchedules($userId, $sortSchedules);
$events = getEvents($userId, $sortEvents);
$calendarItems = getCalendarItems($userId);
$reminders = getReminders($userId);

// Handle logout / Afhandeling uitloggen
if (isset($_GET['logout'])) {
    logout();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GamePlan Scheduler Dashboard - Your gaming calendar and schedule manager">
    <title>Dashboard - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    <?php include 'header.php'; ?>
    
    <main class="container mt-5 pt-5">
        <!-- Session messages (success/error) -->
        <?php echo getMessage(); ?>
        
        <!-- ================================================================
             SECTION 1: FRIENDS LIST / VRIENDEN LIJST
             ================================================================ -->
        <section class="mb-5">
            <h2>👥 Friends List / Vriendenlijst</h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Username / Gebruikersnaam</th>
                            <th>Status</th>
                            <th>Note / Notitie</th>
                            <th>Actions / Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($friends)): ?>
                            <tr><td colspan="4" class="text-center text-secondary">No friends yet. Add some! / Nog geen vrienden. Voeg toe!</td></tr>
                        <?php else: ?>
                            <?php foreach ($friends as $friend): ?>
                                <tr>
                                    <td><?php echo safeEcho($friend['username']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $friend['status'] === 'Online' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo safeEcho($friend['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo safeEcho($friend['note']); ?></td>
                                    <td>
                                        <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                                        <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this friend? / Deze vriend verwijderen?');">🗑️ Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="add_friend.php" class="btn btn-primary">➕ Add Friend / Vriend Toevoegen</a>
        </section>
        
        <!-- ================================================================
             SECTION 2: FAVORITE GAMES / FAVORIETE SPELLEN
             ================================================================ -->
        <section class="mb-5">
            <h2>🎮 Favorite Games / Favoriete Spellen</h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title / Titel</th>
                            <th>Description / Beschrijving</th>
                            <th>Note / Notitie</th>
                            <th>Actions / Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($favorites)): ?>
                            <tr><td colspan="4" class="text-center text-secondary">No favorites yet. Add some! / Nog geen favorieten. Voeg toe!</td></tr>
                        <?php else: ?>
                            <?php foreach ($favorites as $game): ?>
                                <tr>
                                    <td><?php echo safeEcho($game['titel']); ?></td>
                                    <td><?php echo safeEcho($game['description']); ?></td>
                                    <td><?php echo safeEcho($game['note']); ?></td>
                                    <td>
                                        <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                                        <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remove from favorites? / Uit favorieten verwijderen?');">🗑️ Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="profile.php" class="btn btn-primary">➕ Add Favorite / Favoriet Toevoegen</a>
        </section>
        
        <!-- ================================================================
             SECTION 3: SCHEDULES / SCHEMA'S (with sorting)
             ================================================================ -->
        <section class="mb-5">
            <h2>
                📅 Schedules / Schema's
                <a href="?sort_schedules=date ASC" class="btn btn-sm btn-light ms-2">📆 Date ↑</a>
                <a href="?sort_schedules=date DESC" class="btn btn-sm btn-light">📆 Date ↓</a>
            </h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Game / Spel</th>
                            <th>Date / Datum</th>
                            <th>Time / Tijd</th>
                            <th>Friends / Vrienden</th>
                            <th>Shared / Gedeeld</th>
                            <th>Actions / Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($schedules)): ?>
                            <tr><td colspan="6" class="text-center text-secondary">No schedules yet. / Nog geen schema's.</td></tr>
                        <?php else: ?>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><?php echo safeEcho($schedule['game_titel']); ?></td>
                                    <td><?php echo safeEcho($schedule['date']); ?></td>
                                    <td><?php echo safeEcho($schedule['time']); ?></td>
                                    <td><?php echo safeEcho($schedule['friends']); ?></td>
                                    <td><?php echo safeEcho($schedule['shared_with']); ?></td>
                                    <td>
                                        <a href="edit_schedule.php?id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <a href="delete.php?type=schedule&id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete? / Verwijderen?');">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="add_schedule.php" class="btn btn-primary">➕ Add Schedule / Schema Toevoegen</a>
        </section>
        
        <!-- ================================================================
             SECTION 4: EVENTS / EVENEMENTEN (with sorting)
             ================================================================ -->
        <section class="mb-5">
            <h2>
                🎯 Events / Evenementen
                <a href="?sort_events=date ASC" class="btn btn-sm btn-light ms-2">📆 Date ↑</a>
                <a href="?sort_events=date DESC" class="btn btn-sm btn-light">📆 Date ↓</a>
            </h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title / Titel</th>
                            <th>Date / Datum</th>
                            <th>Time / Tijd</th>
                            <th>Description / Beschrijving</th>
                            <th>Reminder / Herinnering</th>
                            <th>Link</th>
                            <th>Actions / Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($events)): ?>
                            <tr><td colspan="7" class="text-center text-secondary">No events yet. / Nog geen evenementen.</td></tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?php echo safeEcho($event['title']); ?></td>
                                    <td><?php echo safeEcho($event['date']); ?></td>
                                    <td><?php echo safeEcho($event['time']); ?></td>
                                    <td><?php echo safeEcho(substr($event['description'] ?? '', 0, 50)); ?>...</td>
                                    <td><span class="badge bg-info"><?php echo safeEcho($event['reminder']); ?></span></td>
                                    <td>
                                        <?php if (!empty($event['external_link'])): ?>
                                            <a href="<?php echo safeEcho($event['external_link']); ?>" target="_blank" class="btn btn-sm btn-outline-info">🔗 Open</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <a href="delete.php?type=event&id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete? / Verwijderen?');">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="add_event.php" class="btn btn-success">➕ Add Event / Evenement Toevoegen</a>
        </section>
        
        <!-- ================================================================
             SECTION 5: CALENDAR OVERVIEW / KALENDER OVERZICHT
             ================================================================ -->
        <section class="mb-5">
            <h2>📆 Calendar Overview / Kalender Overzicht</h2>
            <div class="row">
                <?php if (empty($calendarItems)): ?>
                    <div class="col-12">
                        <p class="text-secondary text-center">No upcoming items. Add schedules or events! / Geen komende items. Voeg schema's of evenementen toe!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($calendarItems as $item): ?>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-cyan">
                                        <?php echo safeEcho($item['title'] ?? $item['game_titel']); ?>
                                    </h5>
                                    <p class="mb-1">
                                        <strong>📅</strong> <?php echo safeEcho($item['date']); ?> 
                                        <strong>⏰</strong> <?php echo safeEcho($item['time']); ?>
                                    </p>
                                    <?php if (!empty($item['description'])): ?>
                                        <p class="text-secondary small"><?php echo safeEcho(substr($item['description'], 0, 100)); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($item['reminder']) && $item['reminder'] !== 'none'): ?>
                                        <span class="badge bg-warning text-dark">🔔 <?php echo safeEcho($item['reminder']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($item['external_link'])): ?>
                                        <a href="<?php echo safeEcho($item['external_link']); ?>" target="_blank" class="btn btn-sm btn-outline-info mt-2">🔗 Link</a>
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
    
    <!-- Reminder pop-ups -->
    <script>
        const reminders = <?php echo json_encode($reminders); ?>;
        reminders.forEach(reminder => {
            alert(`🔔 Reminder: ${reminder['title']} at ${reminder['time']}\n🔔 Herinnering: ${reminder['title']} om ${reminder['time']}`);
        });
    </script>
</body>
</html>