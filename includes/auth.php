<?php
// LocalConnect - Authentication & role-based access helpers

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

function require_admin()
{
    require_login();

    if (!is_admin()) {
        http_response_code(403);
        die('Access denied. Administrator privileges required.');
    }
}

function login_user($user)
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
}

function logout_user()
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $p['path'],
            $p['domain'],
            $p['secure'],
            $p['httponly']
        );
    }

    session_destroy();
}

// Received pending request count for navbar badge
function pending_request_count()
{
    $u = current_user();

    if (!$u) return 0;

    $pdo = getDBConnection();

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM requests
         WHERE receiver_id = ? AND status = 'pending'"
    );

    $stmt->execute([$u['id']]);

    return (int)$stmt->fetchColumn();
}
}