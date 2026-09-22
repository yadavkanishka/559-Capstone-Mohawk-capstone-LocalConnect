<?php
// LocalConnect - Application bootstrap. 

error_reporting(E_ALL);
ini_set('display_errors', '0'); // don't leak errors to users
ini_set('log_errors', '1');
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'path'     => '/',
    ]);
    session_start();
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_init.php';

ensure_schema();

?>
