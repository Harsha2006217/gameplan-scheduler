# FILE DOCUMENTATION: edit_friend.php (A-Z Deep Dive)
## GamePlan Scheduler - Edit Friend Logic

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `edit_friend.php` | **Total Lines**: 120

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\edit_friend.php`
**Purpose**: Allows updating details about a friend in your friend list.
**Unique Feature**: Unlike typical social media, you can manually set a friend's **Status** (Online/Offline) here. This is because this app is a *simulated* environment (for the exam) or tracks external friends (Xbox/PSN) whose status isn't auto-synced.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Friend Retrieval (Lines 30-38)

```php
$friends = getFriends($userId);
$friend = array_filter($friends, ...);
```
**Mechanism**:
*   Reuses `getFriends` from `functions.php` (Line 30).
*   Does NOT create a new SQL query just for fetching one friend (Efficiency: Reuses existing logic).
*   Using `reset($friend)` grabs the first item from the filtered array.

## SECTION 2: Status Management (Lines 93-106)

```html
<select name="status">
    <option value="Offline">Offline</option>
    <option value="Online">Online</option>
    <option value="Playing">Playing</option>
    <option value="Away">Away</option>
</select>
```
**Dropdown Logic**:
*   The `selected` attribute is dynamically echoed based on `$friend['status']`.
*   This status controls the color of the badge on the `add_friend.php` page (Green=Online, Red=Playing, etc.).

## SECTION 3: Update Execution (Lines 43-55)

```php
$error = updateFriend($userId, $id, $friendUsername, $note, $status);
```
**Fields Updated**:
1.  **Username**: In case you made a typo or they changed their gamertag.
2.  **Note**: "Dave from school" -> "Dave (Pro Player)".
3.  **Status**: "Offline" -> "Playing".

---

# 3. Security Considerations

**Q: Can I edit other people's friends?**
*   **Defense**: The filter `getFriends($userId)` ONLY returns friends belonging to you.
*   If you access `edit_friend.php?id=999` (someone else's friend), the filtering yields 0 results.
*   Line 35 catches this: `if (!$friend)` -> Redirects you away.

---

**END OF FILE DOCUMENTATION**
