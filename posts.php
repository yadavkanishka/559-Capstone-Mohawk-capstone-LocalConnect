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

<div class="container">
    <form method="GET" action="/posts.php" class="card card-pad toolbar mb-3" data-testid="search-toolbar">
    <div class="form-group">
    <label for="q">Search</label>
    <input type="search" id="q" name="q" placeholder="Title or keyword" value="<?= e($q) ?>" data-testid="search-input">
</div>

<div class="form-group">
    <label for="location">Location</label>
    <select id="location" name="location" data-testid="filter-location-select">
        <option value="">All locations</option>
        <?php foreach ($locations as $loc): ?>
            <option value="<?= e($loc) ?>" <?= $loc === $location ? 'selected' : '' ?>><?= e($loc) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <label for="skill">Skill</label>
    <select id="skill" name="skill" data-testid="filter-skill-select">
        <option value="">All skills</option>
        <?php foreach ($skillOptions as $s): ?>
            <option value="<?= e($s) ?>" <?= $s === $skill ? 'selected' : '' ?>><?= e($s) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <label for="sort">Sort</label>
    <select id="sort" name="sort" data-testid="sort-select">
        <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Newest first</option>
        <option value="date_asc" <?= $sort === 'date_asc' ? 'selected' : '' ?>>Oldest first</option>
        <option value="location" <?= $sort === 'location' ? 'selected' : '' ?>>By location</option>
    </select>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary" data-testid="search-apply-button">Apply</button>
</div>

</form>