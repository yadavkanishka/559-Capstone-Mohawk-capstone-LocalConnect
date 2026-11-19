<?php
require_once __DIR__ . '/config/config.php';
// Only logged-in users can send requests.
require_login();

// Accept POST requests only.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/posts.php');
}

// Verify CSRF token.
verify_csrf();

$user = current_user();

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
$message = trim($_POST['message'] ?? '');

if (!$post_id || $post_id <= 0) {
    flash('error', 'Invalid collaboration post.');
    redirect('/posts.php');
}

if (strlen($message) > 1000) {
    flash('error', 'Your message must be 1000 characters or less.');
    redirect('/posts.php');
}

$pdo = getDBConnection();

try {
    $pdo->beginTransaction();

    // Find the post and lock it during request processing.
    $stmt = $pdo->prepare(
        "SELECT id, user_id
         FROM posts
         WHERE id = ?
         FOR UPDATE"
    );

    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        $pdo->rollBack();
        flash('error', 'Collaboration post not found.');
        redirect('/posts.php');
    }

    // Users cannot request their own posts.
    if ((int) $post['user_id'] === (int) $user['id']) {
        $pdo->rollBack();
        flash('error', 'You cannot send a request to your own post.');
        redirect('/posts.php');
    }

    // Check whether this user has already sent a request.
    $check = $pdo->prepare(
        "SELECT id, status
         FROM requests
         WHERE post_id = ? AND sender_id = ?
         ORDER BY id DESC
         LIMIT 1
         FOR UPDATE"
    );

    $check->execute([$post_id, $user['id']]);
    $existing = $check->fetch(PDO::FETCH_ASSOC);

    if ($existing && $existing['status'] !== 'declined') {
        $pdo->rollBack();

        flash(
            'error',
            'You have already sent a request for this collaboration.'
        );

        redirect('/posts.php');
    }

    // Insert the collaboration request.
    $insert = $pdo->prepare(
        "INSERT INTO requests
         (post_id, sender_id, receiver_id, message, status)
         VALUES (?, ?, ?, ?, 'pending')"
    );

    $insert->execute([
        $post_id,
        $user['id'],
        $post['user_id'],
        $message !== '' ? $message : null
    ]);

    $pdo->commit();

    flash('success', 'Your collaboration request was sent successfully.');

    redirect('/posts.php');

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Send request error: ' . $e->getMessage());

    flash('error', 'Unable to send your request. Please try again.');

    redirect('/posts.php');
}
