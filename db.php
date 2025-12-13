<?php
// db.php - Database Connection Script (Database Verbindingsscript)
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Version: 1.1 (Legendary Edition)
// Description: 
// This file is responsible for establishing a secure connection to the MySQL database.
// It uses PDO (PHP Data Objects), which is the standard, secure way to connect to databases in PHP.
//
// NL: Dit bestand verzorgt de veilige verbinding met de MySQL database. 
// We gebruiken PDO omdat dit veiliger is en beschermt tegen SQL injecties.

// --- 1. Configuration Constants (Configuratie Constanten) ---
// We define constants for the database credentials.
// Constants are variables that cannot be changed once defined, making them safe for configuration.
// NL: We definieren vaste waarden (constanten) voor de database login gegevens.
define('DB_HOST', 'localhost'); // The server address (usually localhost for XAMPP).
define('DB_USER', 'root');      // The username for the database (default is root).
define('DB_PASS', '');          // The password (default is empty for XAMPP).
define('DB_NAME', 'gameplan_db'); // The name of our specific database.
define('DB_CHARSET', 'utf8mb4'); // Character set supporting all characters (including emojis).

// --- 2. Connection Function (Verbindings Functie) ---
/**
 * Function: getDBConnection
 * Purpose: Creates or returns an active connection to the database.
 * 
 * Explanation for Examiner:
 * 1. Static Variable: We use `static $pdo` to implement the "Singleton Pattern". 
 *    This means we only open ONE connection per page load, which is efficient and fast.
 * 2. DSN (Data Source Name): Describes the database type, host, name, and charset.
 * 3. Options: specific settings for security (Exceptions) and performance (Persistent).
 * 4. Try-Catch: If the connection fails (e.g., wrong password), the code jumps to 'catch' 
 *    to handle the error gracefully without crashing the whole site or showing passwords.
 * 
 * @return PDO The active database connection object.
 */
function getDBConnection()
{
    // Defines a static variable that remembers its value between function calls in the same request.
    static $pdo = null;

    // Check if we already have a connection. If not, make one.
    if ($pdo === null) {
        // Create the DSN string required by PDO.
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        // Options for the connection
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // IMPORTANT: Throw errors so we can catch them.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Get results as associative arrays (key-value pairs).
            PDO::ATTR_EMULATE_PREPARES => false, // Use REAL prepared statements (Security Best Practice).
            PDO::ATTR_PERSISTENT => true, // Keep connection open for performance.
        ];

        try {
            // Attempt to create a new PDO connection instance.
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            // If successful, $pdo now holds the connection line.
        } catch (PDOException $e) {
            // If something goes wrong (e.g. server down), this block runs.
            // Log the error to a system file (invisible to user) for security.
            error_log("Database Connection Failed: " . $e->getMessage(), 0);

            // Show a friendly message to the user.
            die("Sorry, there was an issue connecting to the database. Please try again later.");
        }
    }

    // Return the ready-to-use connection.
    return $pdo;
}
?>