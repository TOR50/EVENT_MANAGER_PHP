<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get event statistics
$stats_query = "SELECT 
    COUNT(*) as total_events,
    COUNT(CASE WHEN event_date >= CURRENT_DATE THEN 1 END) as upcoming_events,
    COUNT(CASE WHEN event_date < CURRENT_DATE THEN 1 END) as past_events,
    (SELECT COUNT(*) FROM participants) as total_registrations
FROM events";
$stats = mysqli_query($conn, $stats_query)->fetch_assoc();

// Get monthly registrations
$monthly_query = "SELECT 
    DATE_FORMAT(registration_date, '%Y-%m') as month,
    COUNT(*) as registrations
FROM participants 
GROUP BY month 
ORDER BY month DESC 
LIMIT 6";
$monthly_data = mysqli_query($conn, $monthly_query);

// Get popular events
$popular_query = "SELECT e.title, COUNT(p.id) as participants
FROM events e
LEFT JOIN participants p ON e.id = p.event_id
GROUP BY e.id
ORDER BY participants DESC
LIMIT 5";
$popular_events = mysqli_query($conn, $popular_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Analytics</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="analytics-dashboard">
        <h2>Event Analytics Dashboard</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Events</h3>
                <p class="stat-number"><?php echo $stats['total_events']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Upcoming Events</h3>
                <p class="stat-number"><?php echo $stats['upcoming_events']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Past Events</h3>
                <p class="stat-number"><?php echo $stats['past_events']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Registrations</h3>
                <p class="stat-number"><?php echo $stats['total_registrations']; ?></p>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-box">
                <h3>Monthly Registrations</h3>
                <canvas id="registrationsChart"></canvas>
            </div>
            
            <div class="chart-box">
                <h3>Popular Events</h3>
                <canvas id="eventsChart"></canvas>
            </div>
        </div>
    </div>

    <script>
    // Monthly registrations chart
    const monthlyData = <?php 
        $labels = [];
        $data = [];
        while($row = mysqli_fetch_assoc($monthly_data)) {
            $labels[] = $row['month'];
            $data[] = $row['registrations'];
        }
        echo json_encode(['labels' => $labels, 'data' => $data]);
    ?>;

    new Chart(document.getElementById('registrationsChart'), {
        type: 'line',
        data: {
            labels: monthlyData.labels,
            datasets: [{
                label: 'Registrations',
                data: monthlyData.data,
                borderColor: '#007bff',
                tension: 0.1
            }]
        }
    });

    // Popular events chart
    const eventsData = <?php 
        $labels = [];
        $data = [];
        while($row = mysqli_fetch_assoc($popular_events)) {
            $labels[] = $row['title'];
            $data[] = $row['participants'];
        }
        echo json_encode(['labels' => $labels, 'data' => $data]);
    ?>;

    new Chart(document.getElementById('eventsChart'), {
        type: 'bar',
        data: {
            labels: eventsData.labels,
            datasets: [{
                label: 'Participants',
                data: eventsData.data,
                backgroundColor: '#28a745'
            }]
        }
    });
    </script>
</body>
</html>