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

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8 relative">
    <!-- Background Blob -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full mix-blend-screen filter blur-[100px] -z-10"></div>

    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-bold text-white font-outfit">My Dashboard</h2>
            <p class="text-gray-400 mt-1">Discover and join upcoming events</p>
        </div>
        <div class="text-sm text-gray-500">
            Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($event = mysqli_fetch_assoc($result)): ?>
            <div class="group bg-surface/40 backdrop-blur-md border border-white/5 rounded-2xl overflow-hidden hover:border-primary/50 transition-all duration-300 hover:shadow-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="inline-flex items-center rounded-md bg-white/5 px-2 py-1 text-xs font-medium text-gray-300 ring-1 ring-inset ring-white/10">
                            <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                        </span>
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-user-friends"></i> 
                            <?php echo $event['current_participants']; ?>/<?php echo $event['max_participants']; ?>
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-2 line-clamp-1 group-hover:text-primary transition-colors">
                        <?php echo htmlspecialchars($event['title']); ?>
                    </h3>
                    
                    <p class="text-gray-400 text-sm line-clamp-2 mb-4">
                        <?php echo htmlspecialchars($event['description']); ?>
                    </p>

                    <div class="flex items-center text-sm text-gray-500 mb-6">
                        <i class="fas fa-map-marker-alt mr-2 text-secondary"></i>
                        <?php echo htmlspecialchars($event['location']); ?>
                    </div>

                    <?php if ($event['is_registered'] > 0): ?>
                        <button disabled class="w-full py-2 bg-green-500/20 text-green-400 border border-green-500/30 rounded-lg cursor-not-allowed font-medium">
                            <i class="fas fa-check mr-2"></i> Registered
                        </button>
                    <?php elseif ($event['current_participants'] >= $event['max_participants']): ?>
                        <button disabled class="w-full py-2 bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg cursor-not-allowed font-medium">
                            <i class="fas fa-ban mr-2"></i> Event Full
                        </button>
                    <?php else: ?>
                        <form method="POST" action="participate.php" class="w-full">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" class="w-full py-2 bg-primary text-background font-bold rounded-lg hover:bg-secondary transition-colors">
                                Join Event
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>