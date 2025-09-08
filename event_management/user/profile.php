<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $user = mysqli_stmt_get_result($stmt)->fetch_assoc();

    if (password_verify($current_password, $user['password'])) {
        if (!empty($new_password)) {
             $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
             $update_query = "UPDATE users SET name = ?, password = ? WHERE id = ?";
             $stmt = mysqli_prepare($conn, $update_query);
             mysqli_stmt_bind_param($stmt, "ssi", $name, $new_password_hash, $_SESSION['user_id']);
        } else {
             $update_query = "UPDATE users SET name = ? WHERE id = ?";
             $stmt = mysqli_prepare($conn, $update_query);
             mysqli_stmt_bind_param($stmt, "si", $name, $_SESSION['user_id']);
        }

        if (mysqli_stmt_execute($stmt)) {
            $success = "Profile updated successfully";
            $_SESSION['user_name'] = $name; // Update session name
        } else {
            $error = "Update failed: " . mysqli_error($conn);
        }
    } else {
        $error = "Current password is incorrect";
    }
}

$query = "SELECT name, email FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$user = mysqli_stmt_get_result($stmt)->fetch_assoc();

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8 relative">
    <div class="max-w-xl mx-auto">
        <a href="dashboard.php" class="inline-flex items-center text-gray-400 hover:text-white mb-6 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>

        <div class="bg-surface/30 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-xl">
             <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white font-outfit mb-2">My Profile</h2>
                <p class="text-gray-400 text-sm">Manage your account settings</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg mb-6 text-sm">
                    <i class="fas fa-check-circle mr-2"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>"
                            class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                            placeholder="John Doe">
                    </div>
                </div>

                <!-- Email (Disabled) -->
                <div class="border-t border-white/10 my-2"></div>

                <!-- Current Password -->
                 <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Current Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-500"></i>
                        </div>
                        <input type="password" name="current_password" required
                            class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                            placeholder="Required to save changes">
                    </div>
                </div>

                <!-- New Password -->
                 <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">New Password <span class="text-gray-500 font-normal">(Optional)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-500"></i>
                        </div>
                        <input type="password" name="new_password"
                            class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                            placeholder="Leave blank to keep current">
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-black font-bold py-3 rounded-lg hover:bg-secondary transition-all transform hover:-translate-y-0.5 shadow-lg shadow-primary/20 mt-4">
                    Update Profile
                </button>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>