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