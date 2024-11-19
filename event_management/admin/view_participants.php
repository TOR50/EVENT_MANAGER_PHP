<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$event_id = $_GET['event_id'] ?? 0;

$query = "SELECT e.title, e.event_date FROM events e WHERE e.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$event = mysqli_stmt_get_result($stmt)->fetch_assoc();

$query = "SELECT u.name, u.email, p.registration_date 
          FROM participants p 
          JOIN users u ON p.user_id = u.id 
          WHERE p.event_id = ?
          ORDER BY p.registration_date DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$participants = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Participants</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Participants for: <?php echo htmlspecialchars($event['title']); ?></h2>
        <p>Event Date: <?php echo $event['event_date']; ?></p>
        
        <div class="export-options">
            <button onclick="exportToCSV()">Export to CSV</button>
            <button onclick="printList()">Print List</button>
        </div>

        <table id="participantsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($participant = mysqli_fetch_assoc($participants)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($participant['name']); ?></td>
                        <td><?php echo htmlspecialchars($participant['email']); ?></td>
                        <td><?php echo $participant['registration_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="../assets/js/export.js"></script>
</body>
</html>