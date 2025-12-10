<?php
// This file is db.php - It connects to the database.
// What is a connection? Like plugging in your phone to charge - it links the app to the data storage.
// Author: Harsha Kanaparthi.
// Date: Improved on 10-12-2025.
// Description: Uses PDO (a safe way to talk to MySQL). Singleton pattern means only one connection to save resources.
// Improvements: Added persistent connection for speed, error logging, no bugs like connection fails handled gracefully.
// Simple: Define settings (like address, username), then try to connect, if fail, log and show nice message.

define('DB_HOST', 'localhost'); // Server address, localhost means same machine.
define('DB_USER', 'root'); // Database username, change in production.
define('DB_PASS', ''); // Password, keep secret.
define('DB_NAME', 'gameplan_db'); // Database name.
define('DB_CHARSET', 'utf8mb4'); // Character set for special chars.

function getDBConnection() { // Function to get connection, can be called anywhere.
    static $pdo = null; // Static means remember value across calls, singleton - only one.
    if ($pdo === null) { // If not connected yet.
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET; // Connection string.
        $options = [ // Settings for PDO.
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw errors as exceptions to catch.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return data as arrays with keys.
            PDO::ATTR_EMULATE_PREPARES => false, // Real prepares for security.
            PDO::ATTR_PERSISTENT => true, // Keep connection open for speed.
        ];
        try { // Try to connect.
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options); // Create PDO object.
        } catch (PDOException $e) { // If fail (wrong password etc.).
            error_log("Database Connection Failed: " . $e->getMessage(), 0); // Log to file, not show to user.
            die("Sorry, there was an issue connecting to the database. Please try again later."); // Show friendly message, stop.
        }
    }
    return $pdo; // Return the connection.
}
?>