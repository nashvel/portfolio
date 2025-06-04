<?php
// init_session.php

// Set secure session cookie parameters before starting the session
// Ensure this script is included *before* any output to the browser.
if (session_status() == PHP_SESSION_NONE) { // Check if session isn't already active
    $cookieParams = [
        'lifetime' => 0, // 0 = until browser is closed. Or set a specific lifetime in seconds.
        'path' => '/',   // Available to the entire domain
        'domain' => $_SERVER['HTTP_HOST'], // Or your specific domain if different
        'secure' => isset($_SERVER['HTTPS']), // Send only over HTTPS
        'httponly' => true, // Prevent access via JavaScript
        'samesite' => 'Lax' // CSRF protection: Lax or Strict. Lax is a good default.
    ];
    session_set_cookie_params($cookieParams);
    session_start();
}

// Optional: Regenerate session ID periodically for enhanced security,
// but be careful with AJAX requests or complex workflows.
// For now, session_regenerate_id(true) on login is the primary defense against fixation.
?>
