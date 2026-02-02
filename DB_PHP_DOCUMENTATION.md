# FILE DOCUMENTATION: db.php (A-Z Deep Dive)
## GamePlan Scheduler - Database Connection Layer

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `db.php` | **Total Lines**: 314

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\db.php`
**Purpose**: Responsbile for establishing a secure, efficient connection between the PHP backend and the MySQL database.
**Pattern Used**: Singleton Pattern (Stateless Reusability).
**Technology**: PDO (PHP Data Objects).

**Why PDO?**
*   **Security**: Native support for Prepared Statements (Anti-SQL Injection).
*   **Flexibility**: Works with 12 different database systems (MySQL, PostgreSQL, etc.), though we use MySQL here.
*   **Error Handling**: Supports Exceptions, unlike the older `mysqli`.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Configuration Constants (Lines 46-70)

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gameplan_db');
define('DB_CHARSET', 'utf8mb4');
```

**Explanations**:
1.  **`DB_HOST`**: Why "localhost"? Because XAMPP runs both Apache (Web Server) and MySQL (DB Server) on the same machine.
2.  **`DB_USER`**: 'root' is the default superadmin for XAMPP.
3.  **`DB_CHARSET`**: Using `utf8mb4` instead of `utf8` is critical. It supports 4-byte characters (like Emojis 🎮), whereas standard MySQL `utf8` only supports 3 bytes.

---

## SECTION 2: The Singleton Function (Lines 96-299)

**Function Name**: `getDBConnection()`

### A. The Singleton Pattern (Lines 115)
```php
static $pdo = null;
```
*   **The Magic Keyword**: `static`.
*   **How it works**:
    *   **Call 1**: `getDBConnection()` runs. `$pdo` is null. Connection starts.
    *   **Call 2**: `getDBConnection()` runs. `$pdo` remembers it isn't null. It returns the *existing* connection.
*   **Significance**: Without this, if you called `getDBConnection` 10 times in one script, you'd open 10 separate connections to MySQL. That would crash a busy server.

### B. The DSN (Lines 149)
```php
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
```
*   **Definition**: Data Source Name. It tells PDO specifically which driver and database to use.

### C. PDO Options (Lines 163-208) - CRITICAL FOR EXAM
We set an array of attributes to configure security/performance behavior:

1.  **`PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`**
    *   **Meaning**: "If DB breaks, explode loudly."
    *   **Why**: By default, PDO fails silently. We *need* exceptions so our `try-catch` blocks work.
2.  **`PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`**
    *   **Meaning**: Return `['username' => 'Harsha']` instead of `[0] => 'Harsha'`.
    *   **Why**: Array keys are readable; numbers are not.
3.  **`PDO::ATTR_EMULATE_PREPARES => false`**
    *   **Meaning**: "Use the ACTUAL database engine for prepared statements, not a PHP simulation."
    *   **Why**: **Maximum Security.** Emulated prepares are slightly less secure against complex SQL injection.

### D. The Try-Catch Block (Lines 226-281)

```php
try {
    $pdo = new PDO($dsn, ...);
} catch (PDOException $e) {
    error_log("Failed: " . $e->getMessage());
    die("Use-friendly error message.");
}
```

**Security Analysis**:
*   **Scenario**: The database password is wrong.
*   **Without Try-Catch**: PHP might print: `Fatal error: Access denied for user 'root'@'localhost' (using password: YES) in C:\xampp...`
*   **Problem**: That error reveals your username and folder structure to hackers.
*   **With Try-Catch**:
    1.  We catch the detailed crash.
    2.  We **LOG** the details privately (`error_log`).
    3.  We show the user: "Sorry, connection issue." (Safe).

---

# 3. Connection Flow Visualized

1.  Script files (e.g., `functions.php`) call `getDBConnection()`.
2.  Function checks static variable `$pdo`.
    *   If **NULL**:
        *   Build DSN string.
        *   Set secure Options.
        *   Handshake with MySQL.
        *   Store result in `$pdo`.
    *   If **EXISTS**:
        *   Skip connection logic.
3.  Return `$pdo` object.
4.  Script uses `$pdo->prepare("SELECT...")`.

---

# 4. Exam "Why" Questions

**Q: Why separate db.php?**
A: **Modularity**. If we change the database password, we change it in ONE file, not in every single page.

**Q: Why no ?> at the end?**
A: To prevent accidental whitespace. If there is a space after `?>`, PHP sends that space to the browser. This blocks headers (needed for `header("Location: ...")` redirects) and causes "Headers already sent" errors.

---

**END OF FILE DOCUMENTATION**
