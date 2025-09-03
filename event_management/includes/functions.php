<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the user is already registered for the event
    $check_query = "SELECT * FROM participants WHERE event_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ii", $event_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // User is already registered
        header('Location: dashboard.php?error=already_registered');
        exit();
    }

    // Register the user for the event
    $query = "INSERT INTO participants (event_id, user_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $event_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        // Send confirmation email
        $event_query = "SELECT title, event_date, location FROM events WHERE id = ?";
        $stmt = mysqli_prepare($conn, $event_query);
        mysqli_stmt_bind_param($stmt, "i", $event_id);
        mysqli_stmt_execute($stmt);
        $event = mysqli_stmt_get_result($stmt)->fetch_assoc();

        $user_query = "SELECT email FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $user_query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $user = mysqli_stmt_get_result($stmt)->fetch_assoc();

        require_once '../includes/mailer.php';
        sendEventConfirmation($user['email'], $event);

        header('Location: dashboard.php?success=registered');
        exit();
    } else {
        header('Location: dashboard.php?error=registration_failed');
        exit();
    }
}
?>