<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$query = "SELECT e.*, 
          (SELECT COUNT(*) FROM participants WHERE event_id = e.id) as current_participants,
          (SELECT COUNT(*) FROM participants WHERE event_id = e.id AND user_id = ?) as is_registered
          FROM events e 
          WHERE e.event_date >= CURRENT_DATE
          ORDER BY e.event_date ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <h2>Available Events</h2>
        <div class="events-grid">
            <?php while ($event = mysqli_fetch_assoc($result)): ?>
                <div class="event-card">
                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                    <p>Date: <?php echo $event['event_date']; ?></p>
                    <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
                    <p>Participants: <?php echo $event['current_participants']; ?>/<?php echo $event['max_participants']; ?></p>
                    
                    <?php if ($event['is_registered'] > 0): ?>
                        <button disabled>Already Registered</button>
                    <?php elseif ($event['current_participants'] >= $event['max_participants']): ?>
                        <button disabled>Event Full</button>
                    <?php else: ?>
                        <form method="POST" action="participate.php">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit">Register</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>