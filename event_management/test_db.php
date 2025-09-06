<?php
// test_db.php
$servername = "localhost";
$username = "root";
$password = "root";

echo "<h1>Database Connection Test</h1>";

// 1. Test Raw Connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("<p style='color:red'>Connection failed: " . $conn->connect_error . "</p>");
}
echo "<p style='color:green'>Connected successfully to MySQL server.</p>";

// 2. List Databases
echo "<h2>Available Databases:</h2><ul>";
$result = $conn->query("SHOW DATABASES");
$found = false;
while($row = $result->fetch_row()) {
    echo "<li>" . $row[0] . "</li>";
    if ($row[0] == 'event_management') $found = true;
}
echo "</ul>";

if ($found) {
    echo "<h2 style='color:green'>SUCCESS: 'event_management' database exists!</h2>";
} else {
    echo "<h2 style='color:red'>FAILURE: 'event_management' database NOT found.</h2>";
}

// 3. Select DB
if ($conn->select_db("event_management")) {
    echo "<p>Selected database successfully.</p>";
    $res = $conn->query("SHOW TABLES");
    echo "<h3>Tables:</h3><ul>";
    while($row = $res->fetch_row()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:red'>Could not select database.</p>";
}
?>
