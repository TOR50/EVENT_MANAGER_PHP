<?php
// login.php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Check if already logged in
if (Auth::check()) {
    redirect($_SESSION['user_role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!$email || !$password) {
        $error = "Please enter both email and password";
    } else {
        if (Auth::login($email, $password, $role)) {
            redirect($role === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php');
        } else {
            $error = "Invalid email or password";
            error_log("Failed login attempt for: " . $email);
        }
    }
}

include 'includes/header.php';
?>

<div class="flex-grow flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full mix-blend-screen filter blur-[100px] animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary/10 rounded-full mix-blend-screen filter blur-[100px] animation-delay-2000 animate-pulse"></div>

    <div class="w-full max-w-md bg-surface/30 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-2xl relative z-10 view-transition-[login-card]">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white font-outfit mb-2">Welcome Back</h2>
            <p class="text-gray-400 text-sm">Sign in to manage your events</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-6">
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-300">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-500"></i>
                    </div>
                    <input type="email" name="email" required 
                        class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                        placeholder="you@example.com"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-300">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-500"></i>
                    </div>
                    <input type="password" name="password" required 
                        class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-300">Role</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer relative">
                        <input type="radio" name="role" value="user" class="peer sr-only" checked>
                        <div class="w-full py-2 text-center rounded-lg border border-white/10 bg-white/5 peer-checked:bg-primary/20 peer-checked:border-primary peer-checked:text-primary text-gray-400 transition-all hover:bg-white/10">User</div>
                    </label>
                    <label class="cursor-pointer relative">
                        <input type="radio" name="role" value="admin" class="peer sr-only">
                        <div class="w-full py-2 text-center rounded-lg border border-white/10 bg-white/5 peer-checked:bg-primary/20 peer-checked:border-primary peer-checked:text-primary text-gray-400 transition-all hover:bg-white/10">Admin</div>
                    </label>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-primary text-black font-bold py-3 rounded-lg hover:bg-secondary transition-all transform hover:-translate-y-0.5 shadow-lg shadow-primary/20">
                Sign In
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-400">
            Don't have an account? 
            <a href="register.php" class="text-primary hover:text-secondary font-medium transition-colors">Create one now</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>