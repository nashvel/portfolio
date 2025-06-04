<?php
// logout.php
require_once 'init_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['logout_csrf_token']) || !isset($_SESSION['logout_csrf_token']) || !hash_equals($_SESSION['logout_csrf_token'], $_POST['logout_csrf_token'])) {

        $_SESSION['login_error'] = 'Invalid logout request. CSRF token mismatch.';
        header('Location: admin_login.php');
        exit;
    }


    $_SESSION = array();


    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();


    header('Location: admin_login.php?logged_out=true'); 
    exit;

} else {

    $_SESSION['login_error'] = 'Invalid logout method.'; 
    header('Location: admin_login.php');
    exit;
}
?>
