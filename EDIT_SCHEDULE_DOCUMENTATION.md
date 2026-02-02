# FILE DOCUMENTATION: edit_schedule.php (A-Z Deep Dive)
## GamePlan Scheduler - Edit Schedule Logic

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `edit_schedule.php` | **Total Lines**: 120

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\edit_schedule.php`
**Purpose**: Allows users to modify details of a planned gaming session (schedule).
**Key Functionality**: Updates Date, Time, Game, and Friends list.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Secure Retrieval (Lines 29-38)

```php
$schedules = getSchedules($userId);
$schedule = array_filter($schedules, ...);
```
**Mechanism**:
*   Fetches ALL user's schedules via `getSchedules($userId)`.
*   Filters for the one matching `$_GET['id']`.
*   **Why efficient?** `getSchedules` uses an optimized `JOIN` query to get Game Titles along with Schedule data in one go.

## SECTION 2: Form Pre-filling (Lines 81-110)

### Date Field (Lines 88-90)
```html
<input type="date" min="<?php echo date('Y-m-d'); ?>" 
       value="<?php echo safeEcho($schedule['date']); ?>">
```
*   **Validation**: Including `min="..."` prevents users from accidentally moving a schedule to the past while editing it.
*   **Pre-fill**: `value="..."` ensures the user sees the original date.

### Friends Field (Lines 98-100)
```html
<input type="text" value="<?php echo safeEcho($schedule['friends']); ?>">
```
*   **Format**: "Tom, Jerry".
*   User can add "Mickey" simply by typing ", Mickey" and hitting save. The backend `editSchedule` function handles parsing this string.

---

# 3. Validation Consistency

**JavaScript**:
*   Uses `onsubmit="return validateScheduleForm();"` (Line 81).
*   This is the **SAME** validation function used in `add_schedule.php`.
*   **Benefit**: Ensures consistent rules (e.g., spaces-only check #1001) apply whether adding NEW or editing EXISTING data.

---

**END OF FILE DOCUMENTATION**
