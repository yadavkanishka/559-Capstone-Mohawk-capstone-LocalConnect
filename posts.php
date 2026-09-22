<?php
require_once __DIR__ . '/config/config.php';
$pdo = getDBConnection();

$q = trim($_GET['q'] ?? '');
$location = trim($_GET['location'] ?? '');
$skill = trim($_GET['skill'] ?? '');
$sort = $_GET['sort'] ?? 'date_desc';

$where = [];
$params = [];
if ($q !== '') {
    $where[] = "(p.title LIKE ? OR p.description LIKE ?)";
    $params[] = "%$q%";
    $params[] = "%$q%";
}

if ($location !== '') {
    $where[] = "p.location = ?";
    $params[] = $location;
}

if ($skill !== '') {
    $where[] = "p.required_skills LIKE ?";
    $params[] = "%$skill%";
}

$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

switch ($sort) {
    case 'date_asc':
        $orderSql = "ORDER BY p.created_at ASC";
        break;

    case 'location':
        $orderSql = "ORDER BY p.location ASC, p.created_at DESC";
        break;

    default:
        $orderSql = "ORDER BY p.created_at DESC";
        $sort = 'date_desc';
}