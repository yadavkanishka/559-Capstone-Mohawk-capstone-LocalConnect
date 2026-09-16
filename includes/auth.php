<?php
// LocalConnect - 
// Authentication & role-based access helpers


function current_user()
{
    static $cached = null;
    static $loaded = false;

    if ($loaded) return $cached;

    $loaded = true;

    if (empty($_SESSION['user_id'])) {
        $cached = null;
        return null;
    }

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    $cached = $stmt->fetch() ?: null;

    return $cached;
}

function is_logged_in()
{
    return current_user() !== null;
}

function is_admin()
{
    $u = current_user();
    return $u && $u['role'] === 'admin';
}

function require_login()
{
    if (!is_logged_in()) {
        flash('error', 'Please log in to continue.');
        redirect('/login.php');
    }
}