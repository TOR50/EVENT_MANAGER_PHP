<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $max_participants = $_POST['max_participants'];
    
    $query = "INSERT INTO events (title, description, event_date, location, max_participants, created_by) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssii", $title, $description, $event_date, $location, $max_participants, $_SESSION['user_id']);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Create New Event</h2>
        <form method="POST" id="createEventForm">
            <input type="text" name="title" placeholder="Event Title" required>
            <textarea name="description" placeholder="Event Description" required></textarea>
            <input type="date" name="event_date" required>
            <input type="text" name="location" placeholder="Event Location" required>
            <input type="number" name="max_participants" placeholder="Maximum Participants" required>
            <button type="submit">Create Event</button>
        </form>
    </div>
</body>
</html>