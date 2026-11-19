<?php
if (!isset($page_title)) {
    $page_title = 'LocalConnect';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title><?= e($page_title) ?> - LocalConnect</title>

</head>

<body>


<nav class="navbar">
    <div class="container">
        <a href="/index.php" class="brand">
            <span class="dot">L</span>
            LocalConnect
        </a>

        <div class="nav-links">
            <a class="nav-link" href="/index.php">Home</a>
            <a class="nav-link" href="/posts.php">Collaborations</a>

            <?php if (is_logged_in()): ?>
                <a class="nav-link" href="/profile.php">My Profile</a>
                <a class="nav-link" href="/post-create.php">Create Post</a>
                <a class="nav-link" href="/dashboard.php">Dashboard</a>
                <a class="nav-link" href="/requests.php">Requests</a>
            <?php else: ?>
                <a class="nav-link" href="/login.php">Login</a>
                <a class="nav-link" href="/register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>


