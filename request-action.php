
<?php
require_once __DIR__ . '/config/config.php';

// Only logged-in users can manage requests.
require_login();

// Accept POST requests only.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/requests.php');
}

// Verify CSRF token.
verify_csrf();

$user = current_user();

$request_id = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';

if (!$request_id || $request_id <= 0) {
    flash('error', 'Invalid collaboration request.');
    redirect('/requests.php');
}

if (!in_array($action, ['accepted', 'declined'], true)) {
    flash('error', 'Invalid request action.');
    redirect('/requests.php');
}

$pdo = getDBConnection();

try {
    $pdo->beginTransaction();

    // Find the request and lock it during processing.
    $stmt = $pdo->prepare(
        "SELECT r.id, r.status, r.receiver_id, p.user_id AS post_owner_id
         FROM requests r
         INNER JOIN posts p ON p.id = r.post_id
         WHERE r.id = ?
         FOR UPDATE"
    );

    $stmt->execute([$request_id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        $pdo->rollBack();
        flash('error', 'Collaboration request not found.');
        redirect('/requests.php');
    }

    // Only the post owner can manage the request.
    if (
        (int) $request['receiver_id'] !== (int) $user['id'] ||
        (int) $request['post_owner_id'] !== (int) $user['id']
    ) {
        $pdo->rollBack();
        flash('error', 'You are not authorized to manage this request.');
        redirect('/requests.php');
    }

    // Only pending requests can be updated.
    if ($request['status'] !== 'pending') {
        $pdo->rollBack();
        flash('error', 'This request has already been processed.');
        redirect('/requests.php');
    }

    // Update the request status.
    $update = $pdo->prepare(
        "UPDATE requests
         SET status = ?
         WHERE id = ? AND status = 'pending'"
    );

    $update->execute([$action, $request_id]);

    if ($update->rowCount() !== 1) {
        $pdo->rollBack();
        flash('error', 'Unable to update this request.');
        redirect('/requests.php');
    }

    $pdo->commit();

    $message = $action === 'accepted'
        ? 'Collaboration request accepted.'
        : 'Collaboration request declined.';

    flash('success', $message);

    redirect('/requests.php');

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Request action error: ' . $e->getMessage());

    flash('error', 'Unable to process the request. Please try again.');

    redirect('/requests.php');
}
