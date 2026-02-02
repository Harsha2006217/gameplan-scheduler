# FILE DOCUMENTATION: add_friend.php (A-Z Deep Dive)
## GamePlan Scheduler - Friends Management Page Logic

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `add_friend.php` | **Total Lines**: 150

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\add_friend.php`
**Purpose**: Manages the user's friend list. This page serves a dual purpose:
1.  **Add Friend**: A form to add new friends by username.
2.  **View Friends**: A display table showing all current friends with edit/delete options.

**Key Dependencies**:
*   `functions.php` (Logic: `addFriend`, `getFriends`)
*   `db.php` (Database)
*   `header.php` / `footer.php` (UI)

**Key Features**:
1.  **Iterative Display**: Uses a `foreach` loop to dynamically generate the friends list.
2.  **Status Badges**: Visual indicators (Green for Online, Blue for Playing).
3.  **CRUD Actions**: Direct links to Edit and Delete specific friends.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Header & Initialization (Lines 1-32)

```php
require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) { header("Location: login.php"); }

$userId = getUserId();
$friends = getFriends($userId); // FETCH DATA
```

**Logic Explained**:
1.  **Auth Check**: Like other pages, restricts access to logged-in users.
2.  **Data Fetching**:
    *   `getFriends($userId)` is called immediately when the page loads.
    *   This fetches ALL friends for this user from the MySQL database.
    *   The result is stored in the `$friends` array for later display.

---

## SECTION 2: Form Processing (Lines 34-47)

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inputs: friend_username, note, status
    $error = addFriend($userId, $friendUsername, $note, $status);
    
    if (!$error) {
        setMessage('success', 'Friend added!');
        header("Location: add_friend.php"); // Refresh page
        exit;
    }
}
```

**Logic Explained**:
1.  **Self-Redirect Pattern**:
    *   On success, we redirect back to `add_friend.php`.
    *   **Why?** This prevents the "Form Resubmission" warning if the user refreshes the page.
    *   It also immediately updates the list below (since the page reloads).
2.  **`addFriend()` Logic**:
    *   Checks if the friend exists in the `users` table?
    *   Checks if you are already friends?
    *   Checks if you are trying to add yourself?

---

## SECTION 3: The "Add Friend" Form (Lines 69-99)

**Structure**:
1.  **Friend Username Input**:
    *   `required`, `maxlength="50"`.
    *   User enters the Gamertag of the person they want to add.
2.  **Note Input**:
    *   Optional `textarea`.
    *   "Good at Fortnite", "Met at LAN party", etc.
3.  **Status Dropdown**:
    *   Options: `Offline`, `Online`, `Playing`, `Away`.
    *   Default is `Offline`.

---

## SECTION 4: The Friends List Display (Lines 102-143)

This section renders the data fetched in Section 1.

### A. The Table Structure
Uses Bootstrap classes: `table-dark` (theme), `table-bordered`, `table-hover` (interactive).

### B. Validating Empty State (Lines 115-119)
```php
if (empty($friends)):
    // Show "No friends yet!" message
else:
    // Show the list
endif;
```
*   **UX Best Practice**: Always tell the user if the list is empty, rather than showing a broken empty table header.

### C. The Loop (Lines 120-138)
```php
foreach ($friends as $friend):
```
*   Loops through every row returned by `getFriends`.

### D. Security & Output (Lines 122-129)
```php
<td><?php echo safeEcho($friend['username']); ?></td>
```
*   **XSS Protection**: Uses `safeEcho()` everywhere.
*   **Why?** Even though the `username` came from the DB, a malicious user might have registered as `<script>alert('hack')</script>`. Escaping it here renders it safe.

### E. Dynamic Badges (Lines 124-127)
```php
class="badge <?php echo $friend['status'] === 'Online' ? 'bg-success' : ... ?>"
```
*   **Conditional Logic in HTML**:
    *   If Status is 'Online' -> `bg-success` (Green).
    *   If Status is 'Playing' -> `bg-primary` (Blue).
    *   Else -> `bg-secondary` (Grey).

### F. Action Buttons (Lines 131-135)
1.  **Edit**: Links to `edit_friend.php?id=123`.
    *   Passes the specific `friend_id` in the URL (GET parameter).
2.  **Delete**: Links to `delete.php?type=friend&id=123`.
    *   Includes `onclick="return confirm(...)"` for safety.

---

# 3. Security Measures in this File

1.  **Ownership Verification**:
    *   The `getFriends($userId)` function ONLY returns records linked to `user_id = $myId`.
    *   You cannot see other people's friends.
2.  **Output Escaping**:
    *   Every user-controlled variable (`username`, `note`, `status`) is wrapped in `safeEcho()`.
3.  **Delete Protection**:
    *   The Delete button requires a JavaScript confirmation dialog.
    *   Double-checked in `delete.php` (server-side).

---

# 4. Data Flow Description

1.  **User enters "Gamertag123"** in the form.
2.  **PHP (`addFriend`)**:
    *   Queries `users` table: Does "Gamertag123" exist?
    *   INSERT into `friends` table: `(my_id, friend_user_id, note, status)`.
3.  **Page Reloads**:
    *   `getFriends()` runs again.
    *   Fetches the new record.
    *   The `foreach` loop renders the new row in the table.

---

**END OF FILE DOCUMENTATION**
