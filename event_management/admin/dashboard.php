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

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-white font-outfit">Admin Dashboard</h2>
            <p class="text-gray-400 mt-1">Manage events and participants</p>
        </div>
        <a href="create_event.php" class="bg-primary text-background font-bold px-6 py-3 rounded-lg hover:bg-secondary transition-all hover:shadow-[0_0_20px_rgba(45,212,191,0.4)] flex items-center gap-2">
            <i class="fas fa-plus"></i> Create New Event
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-surface/30 backdrop-blur-md border border-white/5 rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-primary text-xl"></i>
                </div>
                <div>
                    <div class="text-gray-400 text-sm">Total Events</div>
                    <div class="text-2xl font-bold text-white"><?php echo mysqli_num_rows($result); ?></div>
                </div>
            </div>
        </div>
        <!-- Add more stats if available -->
    </div>

    <!-- Events Table -->
    <div class="bg-surface/40 backdrop-blur-md border border-white/5 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="p-4 text-sm font-semibold text-gray-300">Title</th>
                        <th class="p-4 text-sm font-semibold text-gray-300">Date</th>
                        <th class="p-4 text-sm font-semibold text-gray-300">Location</th>
                        <th class="p-4 text-sm font-semibold text-gray-300">Participants</th>
                        <th class="p-4 text-sm font-semibold text-gray-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php 
                    mysqli_data_seek($result, 0); // Reset pointer
                    while ($event = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="p-4">
                                <div class="font-medium text-white group-hover:text-primary transition-colors">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </div>
                            </td>
                            <td class="p-4 text-gray-400">
                                <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                            </td>
                            <td class="p-4 text-gray-400">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                    <?php echo htmlspecialchars($event['location']); ?>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center rounded-full bg-white/5 px-2 py-1 text-xs font-medium text-gray-300">
                                    <?php echo $event['participant_count']; ?> / <?php echo $event['max_participants']; ?>
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="view_participants.php?event_id=<?php echo $event['id']; ?>" class="text-gray-400 hover:text-white transition-colors" title="View Participants">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteEvent(<?php echo $event['id']; ?>)" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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

<?php include '../includes/footer.php'; ?>