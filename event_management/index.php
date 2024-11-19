<?php
require_once 'includes/config.php';

// Fetch featured events
$featured_query = "SELECT e.*, 
    COUNT(p.id) as participant_count 
    FROM events e 
    LEFT JOIN participants p ON e.id = p.event_id 
    WHERE e.event_date >= CURRENT_DATE 
    GROUP BY e.id 
    ORDER BY e.created_at DESC 
    LIMIT 3";
$featured_events = mysqli_query($conn, $featured_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="hero">
        <nav class="navbar">
            <div class="logo">EventMS</div>
            
            <div class="nav-links">
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="admin/dashboard.php">Dashboard</a>
        <?php else: ?>
            <a href="user/dashboard.php">Dashboard</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    <?php else: ?>
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="register.php" class="btn btn-success">Register</a>
    <?php endif; ?>
</div>

        </nav>
        
        <div class="hero-content">
            <h1>Welcome to Event Management System</h1>
            <p>Organize, manage, and participate in events with ease</p>
        </div>
    </header>

    <main>
        <section class="featured-events">
            <h2>Featured Events</h2>
            <div class="events-grid">
                <?php while ($event = mysqli_fetch_assoc($featured_events)): ?>
                    <div class="event-card">
                        <div class="event-date">
                            <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                        </div>
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p><?php echo substr(htmlspecialchars($event['description']), 0, 100); ?>...</p>
                        <div class="event-meta">
                            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?></span>
                            <span><i class="fas fa-users"></i> <?php echo $event['participant_count']; ?> participants</span>
                        </div>
                        <a href="event_details.php?id=<?php echo $event['id']; ?>" class="btn btn-primary">Learn More</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="features">
            <h2>Why Choose Us</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Easy Event Creation</h3>
                    <p>Create and manage events with our user-friendly interface</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h3>Participant Management</h3>
                    <p>Track and manage event participants efficiently</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Analytics & Reports</h3>
                    <p>Get detailed insights about your events</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> Event Management System. All rights reserved.</p>
        </div>
    </footer>

    <style>
        .hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/images/hero-bg.jpg');
            background-size: cover;
            color: white;
            padding: 100px 20px;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            padding: 40px 20px;
        }

        .feature-card {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .feature-card i {
            font-size: 2em;
            color: #007bff;
            margin-bottom: 20px;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
        .btn {
    padding: 8px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    border: 2px solid #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-success {
    background-color: #28a745;
    color: white;
    border: 2px solid #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #218838;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    border: 2px solid #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #c82333;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 10px;
}
    </style>
</body>
</html>