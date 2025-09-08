<?php
require_once '../includes/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$event_id = $_GET['id'] ?? 0;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean($_POST['title']);
    $description = clean($_POST['description']);
    $event_date = clean($_POST['event_date']);
    $location = clean($_POST['location']);
    $max_participants = (int)$_POST['max_participants'];

    if (empty($title) || empty($description) || empty($event_date) || empty($location)) {
        $error = "All fields are required";
    } else {
        $query = "UPDATE events SET title = ?, description = ?, event_date = ?, location = ?, max_participants = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssii", $title, $description, $event_date, $location, $max_participants, $event_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Event updated successfully";
            // Refresh event data to show updated values
        } else {
            $error = "Failed to update event: " . mysqli_error($conn);
        }
    }
}

// Always fetch (or re-fetch) event data
$query = "SELECT title, description, event_date, location, max_participants FROM events WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$event = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$event) {
    header('Location: dashboard.php');
    exit();
}

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8 relative">
    <div class="max-w-2xl mx-auto">
        <a href="dashboard.php" class="inline-flex items-center text-gray-400 hover:text-white mb-6 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>

        <div class="bg-surface/30 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-xl">
            <h2 class="text-3xl font-bold text-white font-outfit mb-6">Edit Event</h2>

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="title" class="text-sm font-medium text-gray-300">Event Title</label>
                    <input type="text" name="title" id="title" required 
                        value="<?php echo htmlspecialchars($event['title']); ?>"
                        class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 px-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="description" class="text-sm font-medium text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="4" required 
                        class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 px-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="event_date" class="text-sm font-medium text-gray-300">Date</label>
                        <input type="date" name="event_date" id="event_date" required 
                            value="<?php echo $event['event_date']; ?>"
                            class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 px-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label for="max_participants" class="text-sm font-medium text-gray-300">Max Participants</label>
                        <input type="number" name="max_participants" id="max_participants" required min="1" 
                            value="<?php echo $event['max_participants']; ?>"
                            class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 px-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all">
                    </div>
                </div>

                <div>
                    <label for="location" class="text-sm font-medium text-gray-300">Location</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-500"></i>
                        </div>
                        <input type="text" name="location" id="location" required 
                            value="<?php echo htmlspecialchars($event['location']); ?>"
                            class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                            placeholder="e.g. Grand Hall, NYC">
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-background font-bold py-3 rounded-lg hover:bg-secondary transition-all transform hover:-translate-y-0.5 shadow-lg shadow-primary/20">
                    Update Event
                </button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>