<?php
require_once __DIR__ . '/config/config.php';

$pdo = getDBConnection();

$recent = $pdo->query(
    "SELECT p.*, u.full_name
     FROM posts p
     JOIN users u ON u.id = p.user_id
     ORDER BY p.created_at DESC
     LIMIT 3"
)->fetchAll();

$userCount = (int)$pdo->query(
    "SELECT COUNT(*) FROM users"
)->fetchColumn();

$postCount = (int)$pdo->query(
    "SELECT COUNT(*) FROM posts"
)->fetchColumn();

$page_title = 'Find your people, locally';
?>