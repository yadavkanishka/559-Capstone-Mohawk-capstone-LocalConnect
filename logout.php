
<?php
require_once __DIR__ . '/config/config.php';

// Clear the session data.
$_SESSION = [];

// Clear the session cookie if cookies are enabled.
if (ini_get('session.use_cookies')) {
    $cookieParams = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookieParams['path'],
        $cookieParams['domain'],
        $cookieParams['secure'],
        $cookieParams['httponly']
    );
}

// Destroy the current session.
session_destroy();

// Redirect to the LocalConnect homepage.
header('Location: /559-Capstone-Mohawk-capstone-LocalConnect/index.php');
exit;
