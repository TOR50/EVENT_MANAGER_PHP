<?php
require_once '../includes/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

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
        $query = "INSERT INTO events (title, description, event_date, location, max_participants, created_by) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssii", $title, $description, $event_date, $location, $max_participants, $_SESSION['user_id']);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Event created successfully";
            // redirect('admin/dashboard.php'); // Optional: redirect after success
        } else {
            $error = "Error creating event: " . mysqli_error($conn);
        }
    }
}

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8 relative">
    <div class="max-w-2xl mx-auto">
        <a href="dashboard.php" class="inline-flex items-center text-gray-400 hover:text-white mb-6 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>

        <div class="bg-surface/30 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-xl">
            <h2 class="text-3xl font-bold text-white font-outfit mb-6">Create New Event</h2>

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
                    <label for="title">Event Title</label>
                    <input type="text" name="title" id="title" required placeholder="e.g. Annual Tech Conference 2025">
                </div>

                <div>
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4" required placeholder="Describe your event..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="event_date">Date</label>
                        <input type="date" name="event_date" id="event_date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div>
                        <label for="max_participants">Max Participants</label>
                        <input type="number" name="max_participants" id="max_participants" required min="1" value="100">
                    </div>
                </div>

                <div>
                    <label for="location">Location</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-500"></i>
                        </div>
                        <input type="text" name="location" id="location" required class="pl-10 !pl-12" placeholder="e.g. Grand Hall, NYC">
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-background font-bold py-3 rounded-lg hover:bg-secondary transition-all transform hover:-translate-y-0.5 shadow-lg shadow-primary/20">
                    Create Event
                </button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>