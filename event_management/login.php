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
    // Validate input
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
            error_log("Failed login attempt for email: " . $email);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Event Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <input type="email" 
                       name="email" 
                       placeholder="Email" 
                       required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <input type="password" 
                       name="password" 
                       placeholder="Password" 
                       required>
            </div>

            <div class="form-group">
                <br>
                <label for="role">Login as:</label>
                <select name="role" id="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                
            </div>
            <br>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <br>
        <div class="links">
            <a href="register.php" class="btn btn-success">Register</a>
                    [|]             
            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const email = this.querySelector('input[name="email"]').value;
            const password = this.querySelector('input[name="password"]').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    });
    </script>
</body>
</html>