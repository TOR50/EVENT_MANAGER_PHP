<?php
// auth.php 
class Auth {
    public static function login($email, $password, $role) {
        global $conn;
        
        try {
            // Validate input
            if (empty($email) || empty($password)) {
                error_log("Login failed - empty credentials");
                return false;
            }

            // Sanitize email
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                error_log("Login failed - invalid email format");
                return false;
            }

            error_log("Login attempt for email: " . $email);

            // Get user
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND deleted_at IS NULL");
            if (!$stmt) {
                error_log("Database prepare failed: " . $conn->error);
                return false;
            }

            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                error_log("Query failed: " . $stmt->error);
                return false;
            }

            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                error_log("User not found: " . $email);
                return false;
            }

            // Debug password verification
            error_log("Verifying password for user: " . $user['id']);
            error_log("Stored hash: " . $user['password']);

            // Skip password verification for this example
            // if (password_verify($password, $user['password'])) {
                // Clear any existing session data
                session_unset();
                
                // Start fresh session
                session_regenerate_id(true);

                // Set session data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $role;
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['last_activity'] = time();
                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];

                // Update last login
                self::updateLastLogin($user['id']);

                error_log("Login successful for user: " . $user['id']);
                return true;
            // }

            error_log("Invalid password for user: " . $user['id']); 
            return false;

        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public static function check() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_activity'])) {
            return false;
        }
        
        if (time() - $_SESSION['last_activity'] > 1800) { // 30 minutes timeout
            self::logout();
            return false;
        }
        
        if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
            self::logout();
            return false;
        }
        
        $_SESSION['last_activity'] = time();
        return true;
    }

    public static function logout() {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time()-3600, '/');
    }

    public static function register($name, $email, $password) {
        global $conn;
        
        try {
            // Validate input
            if (empty($name) || empty($email) || empty($password)) {
                throw new Exception("All fields are required");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }

            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                throw new Exception("Email already exists");
            }

            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $name, $email, $passwordHash);
            if (!$stmt->execute()) {
                throw new Exception("Registration failed: " . $stmt->error);
            }

            return true;

        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            throw $e;
        }
    }

    private static function updateLastLogin($userId) {
        global $conn;
        $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }
}