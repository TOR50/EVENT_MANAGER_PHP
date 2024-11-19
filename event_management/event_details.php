<?php
require_once 'includes/config.php';

$event_id = $_GET['id'] ?? 0;

$query = "SELECT e.*, 
          COUNT(p.id) as participant_count 
          FROM events e 
          LEFT JOIN participants p ON e.id = p.event_id 
          WHERE e.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$event = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$event) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($event['title']); ?> - Event Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="hero">
        <nav class="navbar">
            <div class="logo">Event MS PROJECT for PHP</div>
            <br><br>
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="admin/dashboard.php" class="btn btn-primary">Dashboard</a>
                    <?php else: ?>
                        <a href="user/dashboard.php" class="btn btn-primary">Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Login</a>
                    <a href="register.php" class="btn btn-success">Register</a>
                    <a href="index.php" class="btn btn-danger">Home</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <br>
        <section class="event-details">
            <h2><?php echo htmlspecialchars($event['title']); ?></h2>
            <p><?php echo htmlspecialchars($event['description']); ?></p>
            <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($event['event_date'])); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
            <p><strong>Participants:</strong> <?php echo $event['participant_count']; ?></p>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> Event Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>