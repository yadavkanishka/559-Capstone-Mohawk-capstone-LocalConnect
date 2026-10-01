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

    <title><?= htmlspecialchars($page_title) ?> | LocalConnect</title>

    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

<header class="site-header">
    <div class="container site-header-inner">

        <a href="/index.php" class="brand" data-testid="navbar-logo">
            LocalConnect
        </a>

        <nav aria-label="Main navigation">

            <a href="/posts.php" data-testid="nav-posts-link">
                Browse Posts
            </a>

            <?php if (is_logged_in()): ?>

                <a href="/dashboard.php">
                    Dashboard
                </a>

                <a href="/logout.php">
                    Logout
                </a>

            <?php else: ?>

                <a href="/login.php" data-testid="nav-login-button">
                    Login
                </a>

                <a href="/register.php" data-testid="nav-register-button">
                    Register
                </a>

            <?php endif; ?>

        </nav>

    </div>
</header>

<main>