<?php
// setup_db.php
$servername = "localhost";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS event_management";
if ($conn->query($sql) === TRUE) {
    echo "Database 'event_management' created successfully.\n";
} else {
    die("Error creating database: " . $conn->error);
}

// Select database
$conn->select_db("event_management");

// Import SQL from sql.txt
$sqlFile = 'sql.txt';
if (file_exists($sqlFile)) {
    $sqlContent = file_get_contents($sqlFile);
    
    // Execute multi query
    if ($conn->multi_query($sqlContent)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        echo "Tables imported successfully from sql.txt.\n";
    } else {
        echo "Error importing tables: " . $conn->error . "\n";
    }
} else {
    echo "sql.txt file not found.\n";
}

$conn->close();
?>
