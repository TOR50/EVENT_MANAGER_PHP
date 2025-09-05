<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    
    try {
        if (Auth::register($name, $email, $password)) {
            header('Location: login.php');
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include 'includes/header.php';
?>

<div class="flex-grow flex items-center justify-center p-4 relative overflow-hidden">
     <!-- Background Accents -->
    <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-primary/20 rounded-full mix-blend-screen filter blur-[100px] animate-pulse"></div>
    <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-secondary/10 rounded-full mix-blend-screen filter blur-[100px] animation-delay-2000 animate-pulse"></div>

    <div class="w-full max-w-md bg-surface/30 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-2xl relative z-10 view-transition-[register-card]">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white font-outfit mb-2">Create Account</h2>
            <p class="text-gray-400 text-sm">Join the event revolution today</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" id="registerForm" class="space-y-6">
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-300">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <input type="text" name="name" required 
                        class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                        placeholder="John Doe">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-300">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-500"></i>
                    </div>
                    <input type="email" name="email" required 
                        class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                        placeholder="you@example.com">
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-300">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-500"></i>
                    </div>
                    <input type="password" name="password" required minlength="6"
                        class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                        placeholder="Min. 6 characters">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-300">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-500"></i>
                    </div>
                    <input type="password" name="confirm_password" required minlength="6"
                        class="w-full bg-black/20 border border-white/5 rounded-lg py-2.5 !pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all"
                        placeholder="Repeat password">
                </div>
            </div>
            
            <button type="submit" class="w-full bg-primary text-black font-bold py-3 rounded-lg hover:bg-secondary transition-all transform hover:-translate-y-0.5 shadow-lg shadow-primary/20">
                Sign Up
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-400">
            Already have an account? 
            <a href="login.php" class="text-primary hover:text-secondary font-medium transition-colors">Sign In</a>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = this.querySelector('input[name="password"]').value;
    const confirmPassword = this.querySelector('input[name="confirm_password"]').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
    }
});
</script>

<?php include 'includes/footer.php'; ?>