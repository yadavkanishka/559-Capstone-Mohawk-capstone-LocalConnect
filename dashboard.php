<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

$user = current_user();

$page_title = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <div class="container">
        <h1>Dashboard</h1>
        <p class="text-muted">Welcome back to LocalConnect.</p>
    </div>
</div>

<div class="container">
    <?php if ($user): ?>
        <section class="card">
            <h2>Welcome, <?= htmlspecialchars($user['full_name']) ?>!</h2>

            <p class="text-muted">
                You are successfully logged in.
            </p>

            <div class="mt-2">
                <a href="/posts.php" class="btn btn-primary">
                    Browse Collaborations
                </a>

                <a href="/logout.php" class="btn btn-outline">
                    Logout
                </a>
            </div>
        </section>
    <?php endif; ?>
</div>