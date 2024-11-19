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
    <?php include 'admin_nav.php'; ?>


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
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .logo {
        font-size: 1.5em;
        font-weight: bold;
    }
    
    .nav-links {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .nav-links .btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.9em;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .nav-links .btn i {
        font-size: 1em;
    }

    .nav-links .btn-create {
        background-color: #00d4ff;
        color: #000000;
        border: 2px solid #00d4ff;
        font-weight: 600;
    }

    .nav-links .btn-create:hover {
        background-color: #00bde0;
        border-color: #00bde0;
        transform: translateY(-2px);
    }

    .nav-links .btn-manage {
        background-color: #ff9100;
        color: #000000;
        border: 2px solid #ff9100;
        font-weight: 600;
    }

    .nav-links .btn-manage:hover {
        background-color: #e68200;
        border-color: #e68200;
        transform: translateY(-2px);
    }

    .nav-links .btn-logout {
        background-color: #ff3366;
        color: #ffffff;
        border: 2px solid #ff3366;
        font-weight: 600;
    }

    .nav-links .btn-logout:hover {
        background-color: #ff1a53;
        border-color: #ff1a53;
        transform: translateY(-2px);
    }

    .dashboard-container {
        padding: 2rem;
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