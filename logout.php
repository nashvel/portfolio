<?php
// logout.php
require_once 'init_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['logout_csrf_token']) || !isset($_SESSION['logout_csrf_token']) || !hash_equals($_SESSION['logout_csrf_token'], $_POST['logout_csrf_token'])) {
        // CSRF token mismatch - log this attempt or handle as an error
        // For simplicity, redirect to login. A more robust solution might log the attempt.
        $_SESSION['login_error'] = 'Invalid logout request. CSRF token mismatch.';
        header('Location: admin_login.php');
        exit;
    }

    // Valid logout request, proceed to destroy session
    // Unset all of the session variables
    $_SESSION = array();

    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    // Regenerate a new CSRF token for the login page, as the old session is gone
    // This is good practice if the login page is immediately shown.
    // However, admin_login.php already generates its own if empty.

    header('Location: admin_login.php?logged_out=true'); // Redirect to login page
    exit;

} else {
    // If not a POST request, redirect to dashboard or login
    $_SESSION['login_error'] = 'Invalid logout method.'; // Or a more generic error
    header('Location: admin_login.php'); // Or admin_dashboard.php if user might still be logged in
    exit;
}
?>
