<?php
/**
 * ============================================================================
 * index.php - Dashboard en Kalender Overzicht (Main Dashboard)
 * ============================================================================
 * 
 * @author      Harsha Kanaparthi
 * @student     2195344
 * @date        30-09-2025
 * @version     1.0
 * @project     GamePlan Scheduler
 * 
 * ============================================================================
 * BESCHRIJVING / DESCRIPTION:
 * ============================================================================
 * Dit is de HOOFDPAGINA van de applicatie - het dashboard dat gebruikers
 * zien na het inloggen. Het toont een overzicht van:
 * 
 * 1. VRIENDEN LIJST - Alle toegevoegde vrienden met status
 * 2. FAVORIETE GAMES - Games toegevoegd aan profiel
 * 3. SPEELSCHEMA'S - Geplande gaming sessies
 * 4. EVENEMENTEN - Toernooien en speciale events
 * 5. KALENDER OVERZICHT - Gecombineerde chronologische weergave
 * 
 * This is the MAIN PAGE of the application - the dashboard users see
 * after logging in. It displays an overview of all user data.
 * 
 * ============================================================================
 * FEATURES:
 * ============================================================================
 * - Sorteer opties voor schedules en events
 * - Kalender view met gecombineerde items
 * - Reminder pop-ups via JavaScript
 * - Responsive tabellen voor alle data
 * - Edit/Delete acties voor elk item
 * ============================================================================
 */

// ============================================================================
// FUNCTIONS.PHP LADEN
// ============================================================================
require_once 'functions.php';

// ============================================================================
// SESSIE TIMEOUT CONTROLEREN
// ============================================================================
// Als de gebruiker langer dan 30 minuten inactief is geweest,
// wordt hij automatisch uitgelogd (security feature)
// ============================================================================
checkSessionTimeout();

// ============================================================================
// CONTROLEER OF GEBRUIKER IS INGELOGD
// ============================================================================
// Niet-ingelogde gebruikers worden doorverwezen naar login pagina
// ============================================================================
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// ============================================================================
// GEBRUIKER DATA OPHALEN
// ============================================================================
$userId = getUserId();

// Update de "laatst actief" timestamp
updateLastActivity(getDBConnection(), $userId);

// ============================================================================
// SORTEER PARAMETERS OPHALEN
// ============================================================================
// $_GET bevat URL parameters, bijv: index.php?sort_schedules=date+DESC
// ?? geeft standaardwaarde als parameter niet bestaat
// ============================================================================
$sortSchedules = $_GET['sort_schedules'] ?? 'date ASC';
$sortEvents = $_GET['sort_events'] ?? 'date ASC';

// ============================================================================
// ALLE DATA OPHALEN UIT DATABASE
// ============================================================================
// Elke functie voert een SQL query uit en geeft een array terug
// ============================================================================
$friends = getFriends($userId);           // Alle vrienden van gebruiker
$favorites = getFavoriteGames($userId);    // Alle favoriete games
$schedules = getSchedules($userId, $sortSchedules); // Speelschema's (gesorteerd)
$events = getEvents($userId, $sortEvents); // Evenementen (gesorteerd)
$calendarItems = getCalendarItems($userId); // Gecombineerd voor kalender
$reminders = getReminders($userId);        // Actieve reminders voor JS

// ============================================================================
// LOGOUT AFHANDELEN
// ============================================================================
// Als ?logout=1 in de URL staat, log de gebruiker uit
// Dit staat onderaan zodat we eerst de pagina kunnen tonen
// ============================================================================
if (isset($_GET['logout'])) {
    logout(); // redirect naar login.php
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ==================================================================
         META TAGS
         ================================================================== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ==================================================================
         SEO
         ================================================================== -->
    <title>Dashboard - GamePlan Scheduler</title>
    <meta name="description"
        content="Your GamePlan Scheduler dashboard - manage gaming schedules, friends, and events all in one place.">

    <!-- ==================================================================
         STYLESHEETS
         ================================================================== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <!-- ==================================================================
         HEADER INVOEGEN
         ==================================================================
         include voegt de inhoud van header.php hier in
         ================================================================== -->
    <?php include 'header.php'; ?>

    <!-- ==================================================================
         MAIN CONTENT
         ==================================================================
         mt-5 pt-5: margin-top en padding-top voor ruimte onder fixed header
         pb-5: padding-bottom voor ruimte boven fixed footer
         ================================================================== -->
    <main class="container mt-5 pt-5 pb-5">

        <!-- ==============================================================
             SESSIE BERICHTEN TONEN
             ==============================================================
             getMessage() toont en verwijdert berichten uit de sessie
             Bijv: "Event added successfully!" na het toevoegen
             ============================================================== -->
        <?php echo getMessage(); ?>

        <!-- ==============================================================
             WELKOM BERICHT
             ============================================================== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </h1>
            <span class="text-muted">
                Welcome, <strong><?php echo safeEcho($_SESSION['username'] ?? 'Gamer'); ?></strong>
            </span>
        </div>

        <!-- ##############################################################
             SECTIE 1: VRIENDEN LIJST
             ##############################################################
             Toont alle vrienden van de gebruiker in een tabel
             ############################################################## -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">
                    <i class="bi bi-people text-info me-2"></i>Friends List
                </h2>
                <a href="add_friend.php" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Add Friend
                </a>
            </div>

            <!-- ======================================================
                 RESPONSIVE TABEL WRAPPER
                 ======================================================
                 table-responsive: horizontaal scrollen op kleine schermen
                 ====================================================== -->
            <div class="table-responsive">
                <table class="table table-dark table-hover table-bordered align-middle">
                    <!-- ================================================
                         TABEL HEADER
                         ================================================ -->
                    <thead class="table-primary">
                        <tr>
                            <th><i class="bi bi-person me-1"></i>Username</th>
                            <th><i class="bi bi-circle me-1"></i>Status</th>
                            <th><i class="bi bi-sticky me-1"></i>Note</th>
                            <th class="text-center" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ============================================
                             PHP LOOP DOOR VRIENDEN
                             ============================================
                             foreach loopt door elke vriend in de array
                             $friend bevat bij elke iteratie één vriend
                             ============================================ -->
                        <?php if (empty($friends)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-people display-6 d-block mb-2"></i>
                                    No friends added yet. <a href="add_friend.php">Add your first friend!</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($friends as $friend): ?>
                                <tr>
                                    <!-- Username -->
                                    <td>
                                        <i class="bi bi-person-circle text-info me-1"></i>
                                        <?php echo safeEcho($friend['username']); ?>
                                    </td>
                                    <!-- Status met kleur indicator -->
                                    <td>
                                        <?php
                                        $statusClass = 'secondary';
                                        if (strtolower($friend['status']) === 'online')
                                            $statusClass = 'success';
                                        if (strtolower($friend['status']) === 'gaming')
                                            $statusClass = 'warning';
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>">
                                            <?php echo safeEcho($friend['status']); ?>
                                        </span>
                                    </td>
                                    <!-- Note -->
                                    <td><?php echo safeEcho($friend['note']); ?></td>
                                    <!-- Actie knoppen -->
                                    <td class="text-center">
                                        <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>"
                                            class="btn btn-warning btn-sm me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to remove this friend?');"
                                            title="Delete">
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

        <!-- ##############################################################
             SECTIE 2: FAVORIETE GAMES
             ############################################################## -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">
                    <i class="bi bi-controller text-success me-2"></i>Favorite Games
                </h2>
                <a href="profile.php" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Add Game
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover table-bordered align-middle">
                    <thead class="table-success">
                        <tr>
                            <th><i class="bi bi-joystick me-1"></i>Title</th>
                            <th><i class="bi bi-info-circle me-1"></i>Description</th>
                            <th><i class="bi bi-sticky me-1"></i>Note</th>
                            <th class="text-center" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($favorites)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-controller display-6 d-block mb-2"></i>
                                    No favorite games yet. <a href="profile.php">Add your favorites!</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($favorites as $game): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-joystick text-success me-1"></i>
                                        <strong><?php echo safeEcho($game['titel']); ?></strong>
                                    </td>
                                    <td><?php echo safeEcho($game['description']); ?></td>
                                    <td><?php echo safeEcho($game['note']); ?></td>
                                    <td class="text-center">
                                        <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-warning btn-sm me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Remove this game from favorites?');" title="Delete">
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

        <!-- ##############################################################
             SECTIE 3: SPEELSCHEMA'S
             ##############################################################
             Met sorteer knoppen voor datum
             ############################################################## -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">
                    <i class="bi bi-calendar-week text-warning me-2"></i>Schedules
                </h2>
                <div class="d-flex gap-2 align-items-center">
                    <!-- Sorteer knoppen -->
                    <span class="text-muted small me-2">Sort:</span>
                    <a href="?sort_schedules=date%20ASC"
                        class="btn btn-outline-light btn-sm <?php echo $sortSchedules === 'date ASC' ? 'active' : ''; ?>">
                        <i class="bi bi-sort-up"></i> Date ↑
                    </a>
                    <a href="?sort_schedules=date%20DESC"
                        class="btn btn-outline-light btn-sm <?php echo $sortSchedules === 'date DESC' ? 'active' : ''; ?>">
                        <i class="bi bi-sort-down"></i> Date ↓
                    </a>
                    <a href="add_schedule.php" class="btn btn-outline-warning btn-sm ms-2">
                        <i class="bi bi-plus-lg me-1"></i>Add
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover table-bordered align-middle">
                    <thead class="table-warning text-dark">
                        <tr>
                            <th><i class="bi bi-joystick me-1"></i>Game</th>
                            <th><i class="bi bi-calendar me-1"></i>Date</th>
                            <th><i class="bi bi-clock me-1"></i>Time</th>
                            <th><i class="bi bi-people me-1"></i>Friends</th>
                            <th><i class="bi bi-share me-1"></i>Shared With</th>
                            <th class="text-center" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($schedules)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-week display-6 d-block mb-2"></i>
                                    No schedules planned. <a href="add_schedule.php">Create one!</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><strong><?php echo safeEcho($schedule['game_titel']); ?></strong></td>
                                    <td>
                                        <i class="bi bi-calendar3 text-warning me-1"></i>
                                        <?php echo safeEcho($schedule['date']); ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-clock text-warning me-1"></i>
                                        <?php echo safeEcho($schedule['time']); ?>
                                    </td>
                                    <td><?php echo safeEcho($schedule['friends']); ?></td>
                                    <td><?php echo safeEcho($schedule['shared_with']); ?></td>
                                    <td class="text-center">
                                        <a href="edit_schedule.php?id=<?php echo $schedule['schedule_id']; ?>"
                                            class="btn btn-warning btn-sm me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?type=schedule&id=<?php echo $schedule['schedule_id']; ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Delete this schedule?');"
                                            title="Delete">
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

        <!-- ##############################################################
             SECTIE 4: EVENEMENTEN
             ############################################################## -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">
                    <i class="bi bi-calendar-event text-danger me-2"></i>Events
                </h2>
                <div class="d-flex gap-2 align-items-center">
                    <span class="text-muted small me-2">Sort:</span>
                    <a href="?sort_events=date%20ASC"
                        class="btn btn-outline-light btn-sm <?php echo $sortEvents === 'date ASC' ? 'active' : ''; ?>">
                        <i class="bi bi-sort-up"></i> Date ↑
                    </a>
                    <a href="?sort_events=date%20DESC"
                        class="btn btn-outline-light btn-sm <?php echo $sortEvents === 'date DESC' ? 'active' : ''; ?>">
                        <i class="bi bi-sort-down"></i> Date ↓
                    </a>
                    <a href="add_event.php" class="btn btn-success btn-sm ms-2">
                        <i class="bi bi-plus-lg me-1"></i>Add Event
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover table-bordered align-middle">
                    <thead class="table-danger">
                        <tr>
                            <th><i class="bi bi-trophy me-1"></i>Title</th>
                            <th><i class="bi bi-calendar me-1"></i>Date</th>
                            <th><i class="bi bi-clock me-1"></i>Time</th>
                            <th><i class="bi bi-card-text me-1"></i>Description</th>
                            <th><i class="bi bi-bell me-1"></i>Reminder</th>
                            <th><i class="bi bi-link-45deg me-1"></i>Link</th>
                            <th class="text-center" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($events)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-event display-6 d-block mb-2"></i>
                                    No events yet. <a href="add_event.php">Create your first event!</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><strong><?php echo safeEcho($event['title']); ?></strong></td>
                                    <td>
                                        <i class="bi bi-calendar3 text-danger me-1"></i>
                                        <?php echo safeEcho($event['date']); ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-clock text-danger me-1"></i>
                                        <?php echo safeEcho($event['time']); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $desc = $event['description'];
                                        echo safeEcho(strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc);
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($event['reminder'] !== 'none'): ?>
                                            <span class="badge bg-info">
                                                <i class="bi bi-bell me-1"></i>
                                                <?php echo safeEcho(str_replace('_', ' ', $event['reminder'])); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">None</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($event['external_link'])): ?>
                                            <a href="<?php echo safeEcho($event['external_link']); ?>" target="_blank"
                                                class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit_event.php?id=<?php echo $event['event_id']; ?>"
                                            class="btn btn-warning btn-sm me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?type=event&id=<?php echo $event['event_id']; ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Delete this event?');"
                                            title="Delete">
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

        <!-- ##############################################################
             SECTIE 5: KALENDER OVERZICHT
             ##############################################################
             Gecombineerde weergave van schedules + events als kaarten
             ############################################################## -->
        <section class="mb-5">
            <h2 class="h4 mb-4">
                <i class="bi bi-calendar3 text-primary me-2"></i>Calendar Overview
            </h2>

            <?php if (empty($calendarItems)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar3 display-3 d-block mb-3"></i>
                    <p>Your calendar is empty. Add schedules or events to see them here!</p>
                </div>
            <?php else: ?>
                <!-- Grid van kaarten -->
                <div class="row">
                    <?php foreach ($calendarItems as $item): ?>
                        <!-- ================================================
                             CALENDAR ITEM CARD
                             ================================================
                             col-md-6 col-lg-4: 3 kolommen op large, 2 op medium
                             mb-4: margin bottom
                             ================================================ -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card bg-secondary border-0 h-100 shadow">
                                <div class="card-body">
                                    <!-- Titel -->
                                    <h5 class="card-title">
                                        <?php if (isset($item['game_titel'])): ?>
                                            <i class="bi bi-joystick text-warning me-2"></i>
                                        <?php else: ?>
                                            <i class="bi bi-trophy text-danger me-2"></i>
                                        <?php endif; ?>
                                        <?php echo safeEcho($item['title'] ?? $item['game_titel']); ?>
                                    </h5>

                                    <!-- Datum en tijd -->
                                    <p class="card-text">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo safeEcho($item['date']); ?>
                                        <i class="bi bi-clock ms-2 me-1"></i>
                                        <?php echo safeEcho($item['time']); ?>
                                    </p>

                                    <!-- Beschrijving (alleen voor events) -->
                                    <?php if (isset($item['description']) && !empty($item['description'])): ?>
                                        <p class="card-text small text-muted">
                                            <?php echo safeEcho(substr($item['description'], 0, 100)); ?>
                                        </p>
                                    <?php endif; ?>

                                    <!-- Reminder badge -->
                                    <?php if (isset($item['reminder']) && $item['reminder'] !== 'none'): ?>
                                        <span class="badge bg-info">
                                            <i class="bi bi-bell me-1"></i>Reminder set
                                        </span>
                                    <?php endif; ?>

                                    <!-- Shared with -->
                                    <?php if (isset($item['shared_with']) && !empty($item['shared_with'])): ?>
                                        <p class="card-text small mt-2">
                                            <i class="bi bi-share me-1"></i>
                                            Shared: <?php echo safeEcho($item['shared_with']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- External link footer -->
                                <?php if (isset($item['external_link']) && !empty($item['external_link'])): ?>
                                    <div class="card-footer bg-transparent border-secondary">
                                        <a href="<?php echo safeEcho($item['external_link']); ?>" target="_blank"
                                            class="btn btn-outline-info btn-sm w-100">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>Open Link
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <!-- ==================================================================
         FOOTER INVOEGEN
         ================================================================== -->
    <?php include 'footer.php'; ?>

    <!-- ==================================================================
         JAVASCRIPT BESTANDEN
         ================================================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

    <!-- ==================================================================
         REMINDER POP-UPS
         ==================================================================
         We geven de reminders array door aan JavaScript om pop-ups te tonen
         json_encode() zet PHP array om naar JavaScript formaat
         ================================================================== -->
    <script>
        // Reminder pop-ups vanuit PHP data
        const reminders = <?php echo json_encode($reminders); ?>;

        // Toon pop-up voor elke reminder
        reminders.forEach(reminder => {
            // Gebruik een mooiere notificatie in plaats van alert
            if (Notification.permission === 'granted') {
                new Notification('GamePlan Reminder', {
                    body: `${reminder['title']} at ${reminder['time']}`,
                    icon: '/favicon.ico'
                });
            } else {
                alert(`🎮 Reminder: ${reminder['title']} at ${reminder['time']}`);
            }
        });

        // Vraag toestemming voor notificaties
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    </script>
</body>

</html>