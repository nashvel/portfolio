<?php
require_once 'init_session.php';

// --- IMPORTANT SECURITY NOTE ---
// For demonstration purposes, admin credentials are hardcoded here.
// In a real application, use a database to store hashed passwords and verify them securely.
// Example: password_hash('your_strong_password', PASSWORD_DEFAULT);
// And verify with: password_verify($submitted_password, $hashed_password_from_db);

define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'password123'); // CHANGE THIS!!

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['login_error'] = 'CSRF token validation failed. Please try again.';
        header('Location: admin_login.php');
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Username and password are required.';
        header('Location: admin_login.php');
        exit;
    }

    // --- Authentication Check (Replace with secure database check) ---
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        unset($_SESSION['login_error']); // Clear any previous login errors
        
        // Redirect to admin dashboard (create this page next)
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid username or password.';
        header('Location: admin_login.php');
        exit;
    }

} else {
    // If accessed directly without POST, redirect to login page
    $_SESSION['login_error'] = 'Invalid request method.';
    header('Location: admin_login.php');
    exit;
}
?>
