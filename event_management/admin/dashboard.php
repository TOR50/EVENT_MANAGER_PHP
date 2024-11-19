<?php
require_once '../includes/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch all events for admin view
$query = "SELECT e.*, 
          COUNT(p.id) as participant_count 
          FROM events e 
          LEFT JOIN participants p ON e.id = p.event_id 
          GROUP BY e.id 
          ORDER BY e.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <nav class="admin-nav">
        <div class="logo">Admin Panel</div>
        <div class="nav-links">
            <a href="create_event.php" class="btn btn-success">Create Event</a>
            <a href="view_users.php">Manage Users</a>
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <h2>Event Management Dashboard</h2>
        
        <div class="stats-cards">
            <div class="stat-card">
                <h3>Total Events</h3>
                <p><?php echo mysqli_num_rows($result); ?></p>
            </div>
        </div>

        <div class="events-table">
            <h3>All Events</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Participants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo $event['event_date']; ?></td>
                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                            <td><?php echo $event['participant_count']; ?></td>
                            <td>
                                <a href="view_participants.php?event_id=<?php echo $event['id']; ?>" 
                                   class="btn btn-primary btn-sm">View Participants</a>
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" 
                                   class="btn btn-warning btn-sm">Edit</a>
                                <button onclick="deleteEvent(<?php echo $event['id']; ?>)" 
                                        class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
    .admin-nav {
        background: #333;
        color: white;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .dashboard-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .events-table {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    .btn-sm {
        padding: 5px 10px;
        font-size: 0.9em;
    }
    </style>

    <script>
    function deleteEvent(eventId) {
        if(confirm('Are you sure you want to delete this event?')) {
            fetch('delete_event.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'event_id=' + eventId
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Error deleting event');
                }
            });
        }
    }
    </script>
</body>
</html>