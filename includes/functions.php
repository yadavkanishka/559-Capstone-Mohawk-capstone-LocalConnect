<?php
// Shared helper functions

// Escape output -> Cross-Site Scripting protection
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

// ---- Flash messages ----
function flash($type, $msg) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }

    $_SESSION['flash'][] = [
        'type' => $type,
        'msg' => $msg
    ];
}

function get_flashes() {
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}
// ---- CSRF protection ----

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
            http_response_code(419);
                die('Invalid or expired form token. Please go back and try again.');
        }
    }

}

// ---- Error logging (stored for administrative review) ----

function log_error($level, $msg, $context = '') {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO error_logs (level, message, context, created_at)
             VALUES (?, ?, ?, NOW())"
        );

        $stmt->execute([
            $level,
            $msg,
            is_string($context) ? $context : json_encode($context)
        ]);
    } catch (Exception $e) {
         // swallow logging failures
    }

}
// ---- Misc ----
function skills_to_array($s) {
    return array_values(
        array_filter(
            array_map('trim', preg_split('/[,\n]+/', $s ?? ''))
        )
    );
}

function format_date($d) {
    return date('M j, Y', strtotime($d));
}

function initials($name) {
    $parts = preg_split('/\s+/', trim($name));
    $out = '';

    foreach (array_slice($parts, 0, 2) as $p) {
        $out .= strtoupper(substr($p, 0, 1));
    }

    return $out ?: 'U';
}

function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

function set_old($data) {
    $_SESSION['old'] = $data;
}

function clear_old() {
    unset($_SESSION['old']);
}