<?php
require_once 'includes/config.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$event_id = (int)$_GET['id'];
$query = "SELECT e.*, 
    COUNT(p.id) as participant_count 
    FROM events e 
    LEFT JOIN participants p ON e.id = p.event_id 
    WHERE e.id = ?
    GROUP BY e.id";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

if (!$event) {
    header('Location: index.php');
    exit();
}
?>

<div class="relative min-h-screen">
    <!-- Header Background -->
    <div class="absolute inset-x-0 top-0 h-96 bg-gradient-to-b from-primary/10 to-transparent pointer-events-none"></div>

    <div class="container mx-auto px-4 py-8 relative z-10">
        <a href="javascript:history.back()" class="inline-flex items-center text-gray-400 hover:text-white mb-8 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 font-outfit" style="view-transition-name: event-title-<?php echo $event['id']; ?>">
                        <?php echo htmlspecialchars($event['title']); ?>
                    </h1>
                    <div class="flex flex-wrap gap-4 text-gray-400">
                        <span class="flex items-center gap-2 bg-white/5 px-3 py-1 rounded-full">
                            <i class="fas fa-calendar text-primary"></i>
                            <?php echo date('l, F j, Y', strtotime($event['event_date'])); ?>
                        </span>
                        <span class="flex items-center gap-2 bg-white/5 px-3 py-1 rounded-full">
                            <i class="fas fa-map-marker-alt text-secondary"></i>
                            <?php echo htmlspecialchars($event['location']); ?>
                        </span>
                    </div>
                </div>

                <div class="bg-surface/30 backdrop-blur-md border border-white/5 rounded-2xl p-8">
                    <h2 class="text-xl font-bold text-white mb-4">About the Event</h2>
                    <div class="prose prose-invert max-w-none text-gray-300">
                        <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-surface/50 backdrop-blur-md border border-white/10 rounded-2xl p-6 sticky top-24">
                    <div class="flex justify-between items-center mb-6 pb-6 border-b border-white/5">
                        <span class="text-gray-400">Participants</span>
                        <span class="text-2xl font-bold text-white"><?php echo $event['participant_count']; ?> <span class="text-sm font-normal text-gray-500">/ <?php echo $event['max_participants']; ?></span></span>
                    </div>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="POST" action="user/participate.php">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" class="w-full bg-primary text-background font-bold py-3 rounded-lg hover:bg-secondary transition-all transform hover:-translate-y-0.5 shadow-lg shadow-primary/20">
                                Register Now
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="login.php" class="block w-full text-center bg-primary text-background font-bold py-3 rounded-lg hover:bg-secondary transition-colors">
                            Login to Register
                        </a>
                    <?php endif; ?>

                    <div class="mt-6 text-sm text-center text-gray-500">
                        <i class="fas fa-shield-alt mr-1"></i> Secure Registration
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>