<?php
// config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'event_management');

// Base URL configuration 
define('BASE_URL', 'http://localhost:8000/');

// Connect to database
try {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        throw new Exception(mysqli_connect_error());
    }
    mysqli_set_charset($conn, "utf8");
} catch (Exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Global functions
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

function clean($string) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($string));
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);