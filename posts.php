<?php
require_once __DIR__ . '/config/config.php';
$pdo = getDBConnection();

$q = trim($_GET['q'] ?? '');
$location = trim($_GET['location'] ?? '');
$skill = trim($_GET['skill'] ?? '');
$sort = $_GET['sort'] ?? 'date_desc';

$where = [];
$params = [];