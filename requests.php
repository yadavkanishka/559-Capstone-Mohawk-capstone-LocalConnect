<?php
require_once __DIR__ . '/config/config.php';

// Only logged-in users can view requests.
require_login();

$user = current_user();
$pdo = getDBConnection();

// Fetch requests received for the current user's posts.
$stmt = $pdo->prepare(
    "SELECT
        r.id,
        r.post_id,
        r.sender_id,
        r.message,
        r.status,
        r.created_at,
        p.title AS post_title,
        u.full_name AS sender_name
     FROM requests r
     INNER JOIN posts p ON p.id = r.post_id
     INNER JOIN users u ON u.id = r.sender_id
     WHERE r.receiver_id = ?
     ORDER BY r.created_at DESC"
);

$stmt->execute([$user['id']]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Fetch requests sent by the current user.
$stmt = $pdo->prepare(
    "SELECT
        r.id,
        r.post_id,
        r.message,
        r.status,
        r.created_at,
        p.title AS post_title,
        u.full_name AS receiver_name
     FROM requests r
     INNER JOIN posts p ON p.id = r.post_id
     INNER JOIN users u ON u.id = r.receiver_id
     WHERE r.sender_id = ?
     ORDER BY r.created_at DESC"
);

$stmt->execute([$user['id']]);
$sentRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="requests-page">
    <div class="requests-header">
        <h1>Collaboration Requests</h1>
        <p>Manage requests from people interested in collaborating with you.</p>
    </div>
    
     <section class="requests-section">
        <h2>Received Requests</h2>
        <p class="muted">Requests from people interested in your collaborations.</p>

    <?php if (empty($requests)): ?>
       <div class="request-empty">
            You haven't received any collaboration requests yet.
        </div>
    <?php else: ?>

        <?php foreach ($requests as $request): ?>
            <div class="card request-card">

                <h3><?= e($request['post_title']) ?></h3>

                <p>
                    <strong>From:</strong>
                    <?= e($request['sender_name']) ?>
                </p>

                <p>
                    <strong>Message:</strong>
                    <?= e($request['message'] ?: 'No message provided.') ?>
                </p>

                <p>
                    <strong>Status:</strong>
                    <span class="request-status">
                        <?= e($request['status']) ?>
                    </span>
                </p>

                <p>
                    <strong>Received:</strong>
                    <?= e(format_date($request['created_at'])) ?>
                </p>

                <?php if ($request['status'] === 'pending'): ?>
                    <div class="request-actions">
                        <form method="POST" action="/request-action.php">
                            <?= csrf_field() ?>

                            <input type="hidden" name="request_id"
                                   value="<?= (int) $request['id'] ?>">

                            <input type="hidden" name="action"
                                   value="accepted">

                            <button type="submit" class="btn btn-primary">
                                Accept
                            </button>
                        </form>

                        <form method="POST" action="/request-action.php">
                            <?= csrf_field() ?>

                            <input type="hidden" name="request_id"
                                   value="<?= (int) $request['id'] ?>">

                            <input type="hidden" name="action"
                                   value="declined">

                            <button type="submit" class="btn">
                                Decline
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>
    </section>
     <!-- SENT REQUESTS -->
        <section class="requests-section">
        <h2>Sent Requests</h2>
        <p class="muted">Track the collaboration requests you've sent to others.</p>

        <?php if (empty($sentRequests)): ?>

            <div class="request-empty">
                You haven't sent any collaboration requests yet.
            </div>

        <?php else: ?>

            <?php foreach ($sentRequests as $request): ?>

                <div class="card request-card">

                    <h3><?= e($request['post_title']) ?></h3>

                    <p>
                        <strong>To:</strong>
                        <?= e($request['receiver_name']) ?>
                    </p>

                    <p>
                        <strong>Your message:</strong>
                        <?= e($request['message'] ?: 'No message provided.') ?>
                    </p>

                    <p>
                        <strong>Status:</strong>
                        <span class="request-status">
                            <?= e($request['status']) ?>
                        </span>
                    </p>

                    <p>
                        <strong>Sent:</strong>
                        <?= e(format_date($request['created_at'])) ?>
                    </p>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
