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

    <title><?= e($page_title) ?> - LocalConnect</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            color: #222;
        }
         
        .logo {
            font-size: 23px;
            font-weight: 700;
            color: #111827;
            text-decoration: none;
            margin-right: 12px;
            white-space: nowrap;
        }

        .logo:hover {
            color: #111827;
        }
        nav {
            display: flex;
            align-items: center;
            gap: 34px;
            flex: 1; 
            background: #ffffff;
            border-bottom: 1px solid #ddd;
            padding: 15px 30px;
        }
        

        nav a {
            margin-right: 40px;
            text-decoration: none;
            color: #333;
        }

        nav a:hover {
            color: #2563eb;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 25px;
        }

        .page-head {
            background: #ffffff;
            border-bottom: 1px solid #ddd;
        }

        .card {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .card-pad {
            padding: 20px;
        }

        .mb-2 {
            margin-bottom: 15px;
        }

        .mb-3 {
            margin-bottom: 25px;
        }

        .mt-2 {
            margin-top: 15px;
        }

        .muted {
            color: #666;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 6px;
            border: 1px solid #ccc;
            text-decoration: none;
            cursor: pointer;
            background: white;
            color: #222;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        .feed-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .post-card {
            padding: 20px;
        }

        .post-card h3 {
            margin-top: 0;
        }

        .empty {
            background: white;
            padding: 40px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        footer {
            margin-top: 40px;
            padding: 25px;
            text-align: center;
            color: #666;
        }
    </style>
</head>

<body>

<nav>
    <a href="/" class="logo">LocalConnect</a>
    <a href="/index.php">Home</a>
    <a href="/posts.php">Collaborations</a>

    <?php if (is_logged_in()): ?>
        <a href="/profile.php">My Profile</a>
        <a href="/post-create.php">Create Post</a>
        <a href="/dashboard.php">Dashboard</a>
    <?php else: ?>
        <a href="/login.php">Login</a>
        <a href="/register.php">Register</a>
    <?php endif; ?>
</nav>