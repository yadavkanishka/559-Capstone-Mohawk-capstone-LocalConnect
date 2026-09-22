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

$sql = "SELECT p.*, u.full_name FROM posts p JOIN users u ON u.id = p.user_id $whereSql $orderSql";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$posts = $stmt->fetchAll();

// Filter option lists
$locations = $pdo->query("SELECT DISTINCT location FROM posts WHERE location <> '' ORDER BY location")->fetchAll(PDO::FETCH_COLUMN);

$allSkills = [];

foreach ($pdo->query("SELECT required_skills FROM posts")->fetchAll(PDO::FETCH_COLUMN) as $rs) {
    foreach (skills_to_array($rs) as $s) {
        $allSkills[$s] = true;
    }
}

$skillOptions = array_keys($allSkills);
sort($skillOptions);

$page_title = 'Browse collaborations';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <div class="container">
        <h1>Browse collaborations</h1>
        <p class="muted">Find local projects that match your skills and interests.</p>
    </div>
</div>