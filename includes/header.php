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
    <link rel="stylesheet" href="/559-Capstone-Mohawk-capstone-LocalConnect/css/style.css">
    <title><?= e($page_title) ?> - LocalConnect</title>

</head>

<body>


<nav class="navbar">
    <div class="container">
        <a href="/559-Capstone-Mohawk-capstone-LocalConnect/index.php" class="brand">
            <span class="dot">L</span>
            LocalConnect
        </a>

        <div class="nav-links">
            <a class="nav-link" href="/559-Capstone-Mohawk-capstone-LocalConnect/index.php">Home</a>
            <a class="nav-link" href="/559-Capstone-Mohawk-capstone-LocalConnect/posts.php">Collaborations</a>

            <?php if (is_logged_in()): ?>
                <a class="nav-link" href="/559-Capstone-Mohawk-capstone-LocalConnect/profile.php">My Profile</a>
                <a class="nav-link" href="/559-Capstone-Mohawk-capstone-LocalConnect/post-create.php">Create Post</a>
                <a class="nav-link" href="/559-Capstone-Mohawk-capstone-LocalConnect/dashboard.php">Dashboard</a>
            <?php else: ?>
                <a class="nav-link" href="/559-Capstone-Mohawk-capstone-LocalConnect/login.php">Login</a>
                <a class="nav-link" href="/559-Capstone-Mohawk-capstone-LocalConnect/register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>


