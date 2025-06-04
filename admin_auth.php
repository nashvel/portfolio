<?php
require_once 'init_session.php';



define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'password123'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {

        session_regenerate_id(true);

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        unset($_SESSION['login_error']); 
        

        header('Location: admin_dashboard.php');
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid username or password.';
        header('Location: admin_login.php');
        exit;
    }

} else {

    $_SESSION['login_error'] = 'Invalid request method.';
    header('Location: admin_login.php');
    exit;
}
?>
