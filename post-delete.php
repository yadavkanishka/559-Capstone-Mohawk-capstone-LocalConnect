<?php

require_once __DIR__ . '/config/config.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    flash('error', 'Invalid request.');
    redirect('/559-Capstone-Mohawk-capstone-LocalConnect/posts.php');
}

verify_csrf();

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

if (!$post_id) {
    flash('error', 'Invalid post.');
    redirect('/559-Capstone-Mohawk-capstone-LocalConnect/posts.php');
}

$pdo = getDBConnection();

/*
 * Delete only if the post belongs to the logged-in user.
 */
$stmt = $pdo->prepare("
    DELETE FROM posts
    WHERE id = ?
      AND user_id = ?
");

$stmt->execute([
    $post_id,
    current_user()['id']
]);

if ($stmt->rowCount() === 0) {
    flash('error', 'Post not found or you do not have permission to delete it.');
    redirect('/559-Capstone-Mohawk-capstone-LocalConnect/posts.php');
}

flash('success', 'Post deleted successfully.');

redirect('/559-Capstone-Mohawk-capstone-LocalConnect/posts.php');