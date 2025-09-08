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

$query = "SELECT u.name, u.email, p.registered_at 
          FROM participants p 
          JOIN users u ON p.user_id = u.id 
          WHERE p.event_id = ?
          ORDER BY p.registered_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$participants = mysqli_stmt_get_result($stmt);

 include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8 relative">
    <div class="max-w-4xl mx-auto">
        <a href="dashboard.php" class="inline-flex items-center text-gray-400 hover:text-white mb-6 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>

        <div class="bg-surface/30 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-xl">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-white font-outfit">Participants</h2>
                    <p class="text-primary mt-1"><?php echo htmlspecialchars($event['title']); ?> • <?php echo date('M d, Y', strtotime($event['event_date'])); ?></p>
                </div>
                <!-- Future: Export functionality -->
                <!-- <div class="flex gap-2">
                    <button class="bg-white/10 text-white px-4 py-2 rounded-lg hover:bg-white/20">Export CSV</button>
                    <button class="bg-white/10 text-white px-4 py-2 rounded-lg hover:bg-white/20">Print</button>
                </div> -->
            </div>

            <div class="overflow-hidden rounded-xl border border-white/5 bg-surface/40">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="p-4 text-sm font-semibold text-gray-300">Name</th>
                            <th class="p-4 text-sm font-semibold text-gray-300">Email</th>
                            <th class="p-4 text-sm font-semibold text-gray-300">Registered At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php while ($participant = mysqli_fetch_assoc($participants)): ?>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="p-4 text-white font-medium">
                                    <?php echo htmlspecialchars($participant['name']); ?>
                                </td>
                                <td class="p-4 text-gray-400">
                                    <?php echo htmlspecialchars($participant['email']); ?>
                                </td>
                                <td class="p-4 text-gray-400 text-sm">
                                    <?php echo date('M d, Y H:i', strtotime($participant['registered_at'])); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($participants) == 0): ?>
                            <tr>
                                <td colspan="3" class="p-8 text-center text-gray-500">
                                    No participants registered yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>