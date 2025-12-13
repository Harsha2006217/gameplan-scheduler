<?php
// ============================================================================
// DB.PHP - Database Connection Script for GamePlan Scheduler
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This file handles the connection between PHP and the MySQL database.
// It uses PDO (PHP Data Objects) which is the modern, secure way to 
// connect to databases in PHP.
//
// WHY PDO?
// - Supports prepared statements (prevents SQL injection attacks)
// - Works with many database types (MySQL, PostgreSQL, SQLite)
// - Throws exceptions for better error handling
// - More secure than older mysql_* functions
//
// HOW IT WORKS:
// 1. Define database connection settings (host, user, password, database)
// 2. Create a PDO connection object
// 3. Return this connection for other files to use
//
// SECURITY FEATURES:
// - Uses constants to store database credentials
// - Singleton pattern (reuses one connection instead of creating many)
// - Error logging without exposing details to users
// - Prepared statements enabled by default
// ============================================================================


// ============================================================================
// STEP 1: DEFINE DATABASE CONFIGURATION CONSTANTS
// ============================================================================
// Constants are like variables but cannot be changed after being set.
// We use define() to create constants with the database connection info.
// 
// In a production environment, these values should be stored securely
// (e.g., in environment variables or a separate config file outside web root).

// DB_HOST: The server where the database is running
// 'localhost' means the database is on the same computer as the web server
// In production, this might be a different server address
define('DB_HOST', 'localhost');

// DB_USER: Username to connect to the database
// 'root' is the default XAMPP/MySQL admin user
// WARNING: In production, create a specific user with limited permissions!
define('DB_USER', 'root');

// DB_PASS: Password for the database user
// Empty for default XAMPP installation
// WARNING: In production, always use a strong password!
define('DB_PASS', '');

// DB_NAME: The name of the database to connect to
// This should match the database created in database.sql
define('DB_NAME', 'gameplan_db');

// DB_CHARSET: Character encoding for the connection
// 'utf8mb4' supports all Unicode characters including emojis 🎮
// This ensures special characters display correctly
define('DB_CHARSET', 'utf8mb4');


// ============================================================================
// STEP 2: CREATE THE DATABASE CONNECTION FUNCTION
// ============================================================================
// This function creates and returns a PDO database connection.
// It uses the SINGLETON PATTERN - only one connection is created and reused.

/**
 * Get Database Connection
 * 
 * This function creates a secure PDO connection to the MySQL database.
 * Uses singleton pattern: creates connection once, then reuses it.
 * 
 * SINGLETON PATTERN EXPLAINED:
 * - First call: Creates new PDO connection and stores it in $pdo
 * - Second call: Returns the existing $pdo (doesn't create new connection)
 * - This is more efficient than creating a new connection for every query
 * 
 * @return PDO The database connection object for executing queries
 * 
 * USAGE EXAMPLE:
 * $pdo = getDBConnection();
 * $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
 * $stmt->execute(['email' => $email]);
 * $user = $stmt->fetch();
 */
function getDBConnection()
{
    // STATIC VARIABLE: Keeps its value between function calls
    // First call: $pdo = null
    // After connection: $pdo = PDO object
    // Next call: $pdo still has the PDO object (not null)
    static $pdo = null;

    // Check if connection already exists
    // If $pdo is null, we need to create a new connection
    // If $pdo has a value, we skip this and return the existing connection
    if ($pdo === null) {

        // ====================================================================
        // STEP 2A: BUILD THE DSN (DATA SOURCE NAME)
        // ====================================================================
        // DSN is a string that tells PDO how to connect to the database
        // Format: "mysql:host=SERVER;dbname=DATABASE;charset=ENCODING"

        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        // Result example: "mysql:host=localhost;dbname=gameplan_db;charset=utf8mb4"


        // ====================================================================
        // STEP 2B: SET PDO OPTIONS FOR SECURITY AND CONVENIENCE
        // ====================================================================
        // These options configure how PDO behaves

        $options = [
            // ERRMODE_EXCEPTION: Throw exceptions when database errors occur
            // This allows us to catch errors with try-catch blocks
            // Without this, errors might be silently ignored
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // FETCH_ASSOC: Return query results as associative arrays
            // Example: $row['username'] instead of $row[0]
            // Much easier to work with and understand
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // EMULATE_PREPARES = FALSE: Use real prepared statements
            // TRUE = PHP simulates prepared statements (less secure)
            // FALSE = Database handles prepared statements (more secure)
            // Real prepared statements prevent SQL injection attacks
            PDO::ATTR_EMULATE_PREPARES => false,

            // PERSISTENT = TRUE: Keep connection open between requests
            // More efficient for busy websites (less connection overhead)
            // Connection is reused instead of opened/closed for each page
            PDO::ATTR_PERSISTENT => true,
        ];


        // ====================================================================
        // STEP 2C: TRY TO CREATE THE CONNECTION
        // ====================================================================
        // We use try-catch to handle connection errors gracefully
        // If something goes wrong, we log the error and show a user-friendly message

        try {
            // Create new PDO connection with our settings
            // Parameters: DSN, username, password, options
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

            // If we reach here, connection was successful!
            // The $pdo variable now holds our database connection

        } catch (PDOException $e) {
            // ================================================================
            // ERROR HANDLING: Connection Failed
            // ================================================================
            // PDOException is thrown when PDO cannot connect to the database
            // Common causes:
            // - MySQL server not running (start XAMPP)
            // - Wrong credentials (check DB_USER and DB_PASS)
            // - Database doesn't exist (run database.sql first)
            // - Wrong host (check DB_HOST)

            // LOG THE ERROR: Write to server error log for developers
            // error_log() writes to PHP error log file (php_error.log)
            // 0 = Use default PHP error logging
            // This helps developers debug the problem later
            error_log("Database Connection Failed: " . $e->getMessage(), 0);

            // SHOW USER-FRIENDLY MESSAGE: Don't expose technical details!
            // Never show the actual error message to users (security risk)
            // It could reveal database structure or server configuration
            // die() stops the script and shows the message
            die("Sorry, there was an issue connecting to the database. Please try again later.");
        }
    }

    // ========================================================================
    // STEP 3: RETURN THE CONNECTION
    // ========================================================================
    // Return the PDO object so other code can use it to query the database
    // Example: $pdo = getDBConnection();

    return $pdo;
}


// ============================================================================
// IMPORTANT NOTES FOR PRODUCTION USE
// ============================================================================
// 
// 1. NEVER use 'root' with empty password in production!
//    Create a specific database user with limited permissions:
//    - Only SELECT, INSERT, UPDATE, DELETE on gameplan_db
//    - No DROP, CREATE, or admin privileges
//
// 2. Store credentials securely:
//    - Use environment variables ($_ENV or getenv())
//    - Or use a config file outside the web root
//    - Never commit passwords to version control (Git)
//
// 3. Consider connection pooling for high-traffic sites
//
// 4. SSL connection recommended for remote databases:
//    Add to DSN: ";ssl-mode=REQUIRED"
//
// ============================================================================
// © 2025 GamePlan Scheduler by Harsha Kanaparthi
// ============================================================================
?>