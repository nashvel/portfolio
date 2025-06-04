<?php

if (session_status() == PHP_SESSION_NONE) {
    $cookieParams = [
        'lifetime' => 0, 
        'path' => '/', 
        'domain' => $_SERVER['HTTP_HOST'], 
        'secure' => isset($_SERVER['HTTPS']), 
        'httponly' => true, 
        'samesite' => 'Lax' 
    ];
    session_set_cookie_params($cookieParams);
    session_start();
}

?>
